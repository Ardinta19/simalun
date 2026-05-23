<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Notifications\OrderStatusUpdated;
use App\Support\Audit;
use App\Support\DriverAssigner;
use App\Support\Laundry;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        $services = Service::where('is_active', true)->orderBy('id')->get();
        $kgServices = $services->where('pricing_model', 'per_kg')->values();
        $itemServices = $services->where('pricing_model', 'per_item')->values();

        // Group per kategori biar form pemesanan bisa nampilin section dinamis
        // (mis. admin nambah kategori "Karpet" otomatis muncul di section item).
        // Kategori dengan service kosong di-skip — gak ada gunanya tampil.
        $kgCategories = ServiceCategory::where('is_active', true)
            ->where('pricing_model', 'per_kg')
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('id')])
            ->orderBy('name')
            ->get()
            ->filter(fn ($cat) => $cat->services->isNotEmpty())
            ->values();

        $itemCategories = ServiceCategory::where('is_active', true)
            ->where('pricing_model', 'per_item')
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('id')])
            ->orderBy('name')
            ->get()
            ->filter(fn ($cat) => $cat->services->isNotEmpty())
            ->values();

        $alamatTersimpan = Auth::user()->customerAddresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('last_used_at')
            ->get();

        return view('order.create', compact(
            'services', 'kgServices', 'itemServices',
            'kgCategories', 'itemCategories',
            'alamatTersimpan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'address' => ['required', 'string', 'min:10', 'max:300'],
            'address_note' => ['nullable', 'string', 'max:200'],
            'zone' => ['required', 'in:A,B,C'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addDays(14)->toDateString()],
            'pickup_time' => ['required', 'in:pagi,siang,sore'],
            'weight_estimate' => ['required', 'numeric', 'min:1', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
            'customer_address_id' => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'voucher_code' => ['nullable', 'string', 'max:30'],
            'item_lines' => ['nullable', 'array', 'max:30'],
            'item_lines.*.service_id' => ['required_with:item_lines', 'exists:services,id'],
            'item_lines.*.qty' => ['required_with:item_lines', 'integer', 'min:0', 'max:200'],
            'item_lines.*.notes' => ['nullable', 'string', 'max:200'],
        ], [
            'pickup_date.before_or_equal' => 'Tanggal jemput maksimal 14 hari ke depan.',
            'weight_estimate.max' => 'Estimasi berat melebihi batas (maks 50 kg).',
            'item_lines.max' => 'Item satuan terlalu banyak (maks 30 baris).',
        ]);

        // Ownership check for customer_address_id
        if (! empty($validated['customer_address_id'])) {
            $owns = CustomerAddress::where('id', $validated['customer_address_id'])
                ->where('customer_id', Auth::id())
                ->exists();
            abort_if(! $owns, 403, 'Alamat tersimpan tidak ditemukan.');
        }

        // Duplicate-order prevention: same service+date+time within 30s
        $recentDuplicate = Order::where('customer_id', Auth::id())
            ->where('service_id', $validated['service_id'])
            ->where('pickup_date', $validated['pickup_date'])
            ->where('pickup_time', $validated['pickup_time'])
            ->where('created_at', '>=', now()->subSeconds(30))
            ->exists();

        if ($recentDuplicate) {
            return back()->withInput()
                ->withErrors(['service_id' => 'Pesanan serupa baru saja kamu buat. Tunggu sebentar sebelum mencoba lagi.']);
        }

        // Max 3 active orders per customer
        $activeOrders = Order::where('customer_id', Auth::id())
            ->whereIn('status', Order::statusAktifSemua())
            ->count();

        if ($activeOrders >= 3) {
            return back()->withInput()
                ->withErrors(['service_id' => 'Kamu masih punya 3 pesanan aktif. Selesaikan dulu salah satunya.']);
        }

        $request->merge($validated);

        $service = Service::findOrFail($request->service_id);
        $weight = (float) $request->weight_estimate;
        $zone = $request->zone;
        $pickupCost = Order::zoneCost($zone);

        $serviceCost = (int) ($service->effective_unit_price * $weight);

        $itemLines = [];
        $itemTotal = 0;

        foreach ((array) $request->input('item_lines', []) as $line) {
            $qty = (int) ($line['qty'] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $itemSvc = Service::find($line['service_id'] ?? null);
            if (! $itemSvc) {
                continue;
            }

            $lineTotal = $itemSvc->effective_unit_price * $qty;
            $itemTotal += $lineTotal;

            $itemLines[] = [
                'service_id' => $itemSvc->id,
                'item_description' => $itemSvc->name,
                'qty' => $qty,
                'weight_kg' => null,
                'unit_price' => $itemSvc->effective_unit_price,
                'line_total' => $lineTotal,
                'notes' => $line['notes'] ?? null,
            ];
        }

        $totalCost = $serviceCost + $itemTotal + $pickupCost;

        // Voucher: divalidasi & dihitung di luar transaksi (read-only). Pemakaian
        // (increment used_count + insert ke voucher_usages) dilakukan di dalam
        // transaksi supaya konsisten dengan order. Subtotal yang dihitung untuk
        // diskon adalah service_cost + item_total + pickup_cost.
        $voucher = null;
        $discount = 0;
        if (! empty($validated['voucher_code'])) {
            $voucher = Voucher::where('code', Str::upper($validated['voucher_code']))->first();

            if (! $voucher || ! $voucher->isCurrentlyValid()) {
                return back()->withInput()
                    ->withErrors(['voucher_code' => 'Voucher tidak berlaku.']);
            }

            if ($totalCost < $voucher->min_order) {
                return back()->withInput()
                    ->withErrors(['voucher_code' => 'Minimum order Rp '.number_format($voucher->min_order, 0, ',', '.').' untuk pakai voucher ini.']);
            }

            $discount = $voucher->calculateDiscount($totalCost);
        }

        $totalCost -= $discount;

        try {
            $result = DB::transaction(function () use (
                $request, $service, $weight, $zone, $pickupCost,
                $serviceCost, $itemLines, $totalCost, $voucher, $discount
            ) {
                $order = Order::create([
                    'order_code' => Order::generateCode(),
                    'customer_id' => Auth::id(),
                    'service_id' => $service->id,
                    'address' => $request->address,
                    'address_note' => $request->address_note,
                    'zone' => $zone,
                    'pickup_cost' => $pickupCost,
                    'pickup_date' => $request->pickup_date,
                    'pickup_time' => $request->pickup_time,
                    'weight_estimate' => $weight,
                    'service_cost' => $serviceCost,
                    'discount' => $discount,
                    'voucher_code' => $voucher?->code,
                    'total_cost' => $totalCost,
                    'status' => 'menunggu',
                    'notes' => $request->notes,
                    'payment_method' => 'cod',
                    'is_paid' => false,
                    'customer_address_id' => $request->customer_address_id ?: null,
                ]);

                if ($voucher) {
                    // Audit + idempotency: catat pemakaian per (voucher, order).
                    VoucherUsage::create([
                        'voucher_id' => $voucher->id,
                        'order_id' => $order->id,
                        'customer_id' => Auth::id(),
                        'discount_amount' => $discount,
                    ]);
                    // Increment counter pemakaian. Pakai increment supaya
                    // aman dari race kalau beberapa order pakai voucher
                    // yang sama bersamaan.
                    $voucher->increment('used_count');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'service_id' => $service->id,
                    'item_description' => $service->name,
                    'qty' => 1,
                    'weight_kg' => $weight,
                    'unit_price' => $service->effective_unit_price,
                    'line_total' => $serviceCost,
                ]);

                foreach ($itemLines as $line) {
                    OrderItem::create(array_merge($line, ['order_id' => $order->id]));
                }

                if ($request->customer_address_id) {
                    CustomerAddress::where('id', $request->customer_address_id)
                        ->where('customer_id', Auth::id())
                        ->update(['last_used_at' => now()]);
                } else {
                    // Order pertama / pesanan tanpa alamat tersimpan: simpan alamat
                    // manual ini ke buku alamat customer biar bisa dipakai ulang.
                    $customer = Auth::user();
                    $isFirst = ! $customer->customerAddresses()->exists();

                    $newAddress = CustomerAddress::create([
                        'customer_id' => $customer->id,
                        'label' => $isFirst ? 'Alamat Utama' : 'Alamat Pesanan',
                        'recipient_name' => $customer->name,
                        'phone' => $customer->phone,
                        'full_address' => $request->address,
                        'notes' => $request->address_note,
                        'zone' => $zone,
                        'is_primary' => $isFirst,
                        'last_used_at' => now(),
                    ]);

                    $order->update(['customer_address_id' => $newAddress->id]);
                }

                // Income TIDAK dicatat di sini — dicatat saat order selesai (COD model).
                // Lihat FinanceController::recordIncomeFromOrder()

                // Auto-assign ke driver aktif. DriverAssigner pilih driver
                // pakai strategi yang diset di config (round_robin / load_based)
                // — lebih adil daripada selalu ambil driver pertama.
                $driver = DriverAssigner::pick();

                if ($driver) {
                    // Driver tersedia — langsung assign
                    Order::withoutEvents(function () use ($order, $driver) {
                        $order->update([
                            'driver_id' => $driver->id,
                            'status' => 'dijemput',
                        ]);
                    });

                    DriverAssigner::markAssigned($driver);

                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status_code' => 'dijemput',
                        'status_note' => "Kurir {$driver->name} otomatis ditugaskan untuk penjemputan.",
                        'updated_by' => $order->customer_id,
                    ]);
                } else {
                    // Tidak ada driver aktif — order tetap 'menunggu', notif admin untuk assign manual
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status_code' => 'menunggu',
                        'status_note' => 'Pesanan masuk. Belum ada kurir aktif — menunggu penugasan manual.',
                        'updated_by' => $order->customer_id,
                    ]);
                }

                return ['order' => $order, 'driver' => $driver];
            });
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()
                ->withErrors(['service_id' => 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.']);
        }

        // Notifikasi dikirim SETELAH transaksi commit berhasil
        $newOrder = $result['order'];
        $assignedDriver = $result['driver'];

        if ($assignedDriver) {
            $newOrder->customer->notify(new OrderStatusUpdated(
                $newOrder,
                'Kurir Sedang Menuju',
                "Kurir {$assignedDriver->name} sedang menuju lokasi kamu untuk jemput cucian."
            ));

            $assignedDriver->notify(new OrderStatusUpdated(
                $newOrder,
                'Tugas Baru',
                "Pesanan #{$newOrder->order_code} dari {$newOrder->customer->name} siap dijemput."
            ));

            User::where('role', 'admin')->each(function ($admin) use ($newOrder, $assignedDriver) {
                $admin->notify(new OrderStatusUpdated(
                    $newOrder,
                    'Pesanan Baru Masuk',
                    "Pesanan #{$newOrder->order_code} dari {$newOrder->customer->name} — kurir {$assignedDriver->name} sudah ditugaskan otomatis."
                ));
            });
        } else {
            User::where('role', 'admin')->each(function ($admin) use ($newOrder) {
                $admin->notify(new OrderStatusUpdated(
                    $newOrder,
                    'Perlu Assign Kurir',
                    "Pesanan #{$newOrder->order_code} dari {$newOrder->customer->name} butuh penugasan manual. Tidak ada kurir aktif saat ini."
                ));
            });
        }

        return redirect()->route('order.show', $newOrder->order_code);
    }

    /**
     * Halaman pasca-checkout (success). Untuk akses berikutnya
     * (refresh, share link, admin click "Detail"), kita arahkan
     * ke halaman detail sesuai role agar tidak terjebak di state
     * "success" yang sudah tidak relevan.
     */
    public function show(string $orderCode)
    {
        $order = Order::with(['service', 'items.service'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        $user = Auth::user();

        // Customer hanya boleh lihat ordernya sendiri.
        if ($user && $user->role === 'customer' && $order->customer_id !== $user->id) {
            abort(403);
        }

        // Tampilkan layar success hanya saat order baru dibuat (menunggu/dijemput).
        // Kalau sudah masuk proses lebih lanjut, arahkan ke detail.
        $showSuccess = in_array($order->status, ['menunggu', 'dijemput']);
        if (! $showSuccess) {
            return match ($user->role ?? 'customer') {
                'admin' => redirect()->route('admin.orders.receipt', $order),
                'driver' => redirect()->route('driver.orders.show', ['order' => $order->id]),
                default => redirect()->route('customer.order.detail', ['order' => $order->id]),
            };
        }

        return view('order.success', compact('order'));
    }

    public function customerIndex(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'semua');

        $query = $user->customerOrders()
            ->with(['service', 'driver', 'items'])
            ->latest();

        match ($filter) {
            'aktif' => $query->whereIn('status', Order::statusAktifSemua()),
            'selesai' => $query->whereIn('status', Order::statusSelesaiSemua()),
            'batal' => $query->where('status', 'dibatalkan'),
            default => null,
        };

        $pesananAktif = $user->customerOrders()
            ->with(['service', 'driver', 'items'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        return view('roles.customer.orders.index', [
            'pesanan' => $query->paginate(10)->withQueryString(),
            'pesananAktif' => $pesananAktif,
        ]);
    }

    public function customerDetail(Order $order)
    {
        abort_if($order->customer_id !== Auth::id(), 403);

        $order->load([
            'service',
            'driver',
            'items.service',
            'customerAddress',
            'statusHistories.updater',
            'rating',
        ]);

        $histori = $order->statusHistories()
            ->latest('updated_at')
            ->get();

        return view('roles.customer.orders.order', compact('order', 'histori'));
    }

    /**
     * Customer batal pesanan — hanya bisa selama status masih 'menunggu' atau 'dijemput'.
     * Setelah masuk workshop (dicuci dst), tidak bisa dibatalkan.
     */
    public function customerCancel(Request $request, Order $order)
    {
        abort_if($order->customer_id !== Auth::id(), 403);

        // Pakai canTransitionTo supaya aturan transisi terpusat di model.
        // Dibatalkan hanya boleh dari 'menunggu' & 'dijemput' (sebelum cucian
        // masuk workshop) — definisinya ada di Order::TRANSITIONS.
        if (! $order->canTransitionTo('dibatalkan')) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah dalam proses pencucian.');
        }

        $request->validate([
            'cancel_reason' => ['nullable', 'string', 'max:300'],
        ]);

        $reason = $request->input('cancel_reason');
        $reasonText = $reason ? "Alasan: {$reason}" : 'Tidak menyertakan alasan.';

        DB::transaction(function () use ($order, $reasonText) {
            $order->update(['status' => 'dibatalkan']);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_code' => 'dibatalkan',
                'status_note' => "Dibatalkan oleh customer. {$reasonText}",
                'updated_by' => Auth::id(),
            ]);
        });

        // Notifikasi dikirim SETELAH transaksi commit
        User::where('role', 'admin')->each(function ($admin) use ($order, $reasonText) {
            $admin->notify(new OrderStatusUpdated(
                $order,
                'Pesanan Dibatalkan',
                "Pesanan #{$order->order_code} dibatalkan oleh {$order->customer->name}. {$reasonText}"
            ));
        });

        if ($order->driver) {
            $order->driver->notify(new OrderStatusUpdated(
                $order,
                'Pesanan Dibatalkan',
                "Pesanan #{$order->order_code} dibatalkan oleh customer. Tidak perlu dijemput."
            ));
        }

        return redirect()->route('customer.orders')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function tracking()
    {
        $order = Auth::user()->customerOrders()
            ->with('driver')
            ->whereIn('status', ['dijemput', 'dikirim', 'siap'])
            ->latest()
            ->first();

        if (! $order) {
            $order = Auth::user()->customerOrders()
                ->with('driver')
                ->latest()
                ->first();
        }

        abort_if(! $order, 404);

        return view('roles.customer.orders.tracking', compact('order'));
    }

    public function adminIndex(Request $request)
    {
        $status = $request->get('status');

        $query = Order::with(['customer', 'service', 'driver', 'items'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pesanan = $query->paginate(15)->withQueryString();
        $jumlahSemua = Order::count();
        $jumlahAktif = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahSelesai = Order::whereIn('status', Order::statusSelesaiSemua())->count();
        $daftarDriver = User::where('role', 'driver')->where('is_active', true)->orderBy('name')->get();

        return view('roles.admin.orders', compact(
            'pesanan', 'jumlahSemua', 'jumlahAktif', 'jumlahSelesai', 'daftarDriver'
        ));
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => ['required', 'exists:users,id'],
            'assignment_type' => ['required', 'in:pickup,delivery'],
        ]);

        // Status flow: pickup only from 'menunggu', delivery only from 'siap'
        $expectedStatus = $request->assignment_type === 'pickup' ? 'menunggu' : 'siap';
        if ($order->status !== $expectedStatus) {
            return back()->with('error', "Tidak bisa menugaskan driver: status pesanan saat ini '{$order->status_label}'.");
        }

        $driver = User::where('id', $request->driver_id)
            ->where('role', 'driver')
            ->where('is_active', true)
            ->first();

        if (! $driver) {
            return back()->with('error', 'Driver tidak ditemukan atau sedang nonaktif.');
        }

        $newStatus = $request->assignment_type === 'pickup' ? 'dijemput' : 'dikirim';

        DB::transaction(function () use ($order, $driver, $newStatus, $request) {
            $order->update([
                'driver_id' => $driver->id,
                'status' => $newStatus,
            ]);

            DriverAssigner::markAssigned($driver);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_code' => $newStatus,
                'status_note' => "Driver {$driver->name} ditugaskan untuk {$request->assignment_type}.",
                'updated_by' => Auth::id(),
            ]);
        });

        Audit::log('order.assign-driver', $order,
            after: ['driver_id' => $driver->id, 'status' => $newStatus, 'assignment_type' => $request->assignment_type],
            summary: "Tugaskan {$driver->name} ke pesanan #{$order->order_code} ({$request->assignment_type})");

        // Notifikasi dikirim SETELAH transaksi commit — agar tidak rollback order jika notif gagal
        $order->customer->notify(new OrderStatusUpdated(
            $order,
            'Kurir Ditugaskan',
            "Kurir {$driver->name} sedang menuju lokasi kamu."
        ));

        $taskLabel = $request->assignment_type === 'pickup' ? 'penjemputan' : 'pengantaran';
        $driver->notify(new OrderStatusUpdated(
            $order,
            'Tugas Baru',
            "Anda ditugaskan untuk {$taskLabel} pesanan #{$order->order_code} dari {$order->customer->name}."
        ));

        return back()->with('success', "Driver {$driver->name} berhasil ditugaskan.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:dicuci,disetrika,siap,selesai,dibatalkan'],
        ]);

        // Order final tidak bisa diubah lagi
        if ($order->isFinal()) {
            return back()->with('error', 'Pesanan sudah final, tidak bisa diubah lagi.');
        }

        // Guard transisi: cegah lompat status. Mis. dari 'menunggu' loncat
        // ke 'selesai' bakal nge-trigger income recording padahal cucian
        // bahkan belum dijemput.
        if (! $order->canTransitionTo($request->status)) {
            return back()->with(
                'error',
                "Tidak bisa ubah status dari '{$order->status_label}' ke '{$request->status}'. "
                .'Ikuti alur: dijemput → dicuci → disetrika → siap → dikirim → selesai.'
            );
        }

        $statusLabel = [
            'dicuci' => 'Sedang Dicuci',
            'disetrika' => 'Sedang Disetrika',
            'siap' => 'Siap Dikirim',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        $oldStatus = $order->status;

        DB::transaction(function () use ($order, $request, $statusLabel) {
            $order->update(['status' => $request->status]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_code' => $request->status,
                'status_note' => "Status diperbarui menjadi: {$statusLabel[$request->status]}.",
                'updated_by' => Auth::id(),
            ]);

            // Record income saat order selesai (COD)
            if ($request->status === 'selesai') {
                $order->update(['is_paid' => true, 'paid_at' => now()]);
                FinanceController::recordIncomeFromOrder($order->fresh());
            }
        });

        Audit::log('order.status', $order,
            before: ['status' => $oldStatus],
            after: ['status' => $request->status],
            summary: "Update status #{$order->order_code}: {$oldStatus} → {$request->status}");

        // Notifikasi dikirim SETELAH transaksi commit
        $order->refresh();
        $order->customer->notify(new OrderStatusUpdated(
            $order,
            "Pesanan {$statusLabel[$request->status]}",
            "Pesanan #{$order->order_code} kamu sekarang: {$statusLabel[$request->status]}."
        ));

        if ($request->status === 'siap' && $order->driver_id) {
            $order->driver->notify(new OrderStatusUpdated(
                $order,
                'Cucian Siap Diantar',
                "Pesanan #{$order->order_code} sudah siap. Menunggu penugasan pengantaran."
            ));
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function receipt(Order $order)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort_if($order->customer_id !== $user->id, 403);
        } elseif ($user->role === 'driver') {
            abort_if($order->driver_id !== $user->id, 403);
        }
        // Admin dapat melihat semua receipt tanpa batasan

        $order->load(['customer', 'service', 'items.service', 'driver']);

        $format = request('format', 'a5');
        $laundry = Laundry::receiptHeader();

        return view('order.receipt', compact('order', 'format', 'laundry'));
    }

    public function receiptPdf(Order $order)
    {
        $user = Auth::user();

        if ($user->role === 'customer') {
            abort_if($order->customer_id !== $user->id, 403);
        } elseif ($user->role === 'driver') {
            abort_if($order->driver_id !== $user->id, 403);
        }

        $order->load(['customer', 'service', 'items.service', 'driver']);

        $format = request('format', 'a5');
        $laundry = Laundry::receiptHeader();

        $pdf = Pdf::loadView('order.receipt-pdf', compact('order', 'format', 'laundry'));

        $paperSize = match ($format) {
            '58mm' => [0, 0, 164, 600],
            '80mm' => [0, 0, 226, 600],
            'thermal' => [0, 0, 226, 600],
            default => 'a5',
        };

        if (is_array($paperSize)) {
            $pdf->setPaper($paperSize);
        } else {
            $pdf->setPaper($paperSize, 'portrait');
        }

        $filename = "nota-{$order->order_code}.pdf";

        return $pdf->download($filename);
    }

    public function walkinForm()
    {
        $daftarLayanan = Service::where('is_active', true)->where('pricing_model', 'per_kg')->get();
        $daftarLayananItem = Service::where('is_active', true)->where('pricing_model', 'per_item')->get();

        // Kategori dipakai di view sebagai optgroup di select layanan, biar
        // admin gampang lihat layanan dikelompokkan per kategori.
        $kgCategories = ServiceCategory::where('is_active', true)
            ->where('pricing_model', 'per_kg')
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('id')])
            ->orderBy('name')
            ->get()
            ->filter(fn ($cat) => $cat->services->isNotEmpty())
            ->values();

        $itemCategories = ServiceCategory::where('is_active', true)
            ->where('pricing_model', 'per_item')
            ->with(['services' => fn ($q) => $q->where('is_active', true)->orderBy('id')])
            ->orderBy('name')
            ->get()
            ->filter(fn ($cat) => $cat->services->isNotEmpty())
            ->values();

        return view('roles.admin.walkin', compact(
            'daftarLayanan', 'daftarLayananItem',
            'kgCategories', 'itemCategories'
        ));
    }

    public function walkinStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:120',
            'customer_phone' => 'nullable|string|max:20',
            'service_category_id' => 'nullable|exists:service_categories,id',
            'service_id' => 'required|exists:services,id',
            'weight_estimate' => 'required|numeric|min:0.5',
            'pickup_time' => 'required|in:pagi,siang,sore',
        ]);

        // Validasi konsistensi: kategori yang dipilih harus sama dengan kategori
        // service utama. Mencegah admin tidak sengaja salah pilih (mis. layanan
        // kiloan dengan kategori "Karpet").
        if ($request->filled('service_category_id')) {
            $selectedService = Service::find($request->service_id);
            if ($selectedService && $selectedService->service_category_id !== null
                && (int) $selectedService->service_category_id !== (int) $request->service_category_id) {
                return back()->withInput()
                    ->withErrors(['service_category_id' => 'Kategori yang dipilih tidak sesuai dengan layanan utama.']);
            }
        }

        $customer = User::firstOrCreate(
            ['phone' => $request->customer_phone ?: null],
            [
                'name' => $request->customer_name,
                'email' => null,
                'password' => bcrypt(Str::random(12)),
                'role' => 'customer',
                'phone' => $request->customer_phone,
            ]
        );

        $service = Service::findOrFail($request->service_id);
        $weight = (float) $request->weight_estimate;
        $serviceCost = (int) ($service->effective_unit_price * $weight);

        $itemLines = [];
        $itemTotal = 0;

        foreach ((array) $request->input('item_lines', []) as $line) {
            $qty = (int) ($line['qty'] ?? 0);
            if ($qty <= 0) {
                continue;
            }

            $itemSvc = Service::find($line['service_id'] ?? null);
            if (! $itemSvc) {
                continue;
            }

            $lineTotal = $itemSvc->effective_unit_price * $qty;
            $itemTotal += $lineTotal;

            $itemLines[] = [
                'service_id' => $itemSvc->id,
                'item_description' => $itemSvc->name,
                'qty' => $qty,
                'weight_kg' => null,
                'unit_price' => $itemSvc->effective_unit_price,
                'line_total' => $lineTotal,
            ];
        }

        $totalCost = $serviceCost + $itemTotal;

        DB::transaction(function () use (
            $request, $customer, $service, $weight, $serviceCost, $itemLines, $totalCost
        ) {
            $order = Order::create([
                'order_code' => Order::generateCode(),
                'customer_id' => $customer->id,
                'service_id' => $service->id,
                'address' => 'Walk-in (Datang Langsung)',
                'zone' => 'A',
                'pickup_cost' => 0,
                'pickup_date' => today(),
                'pickup_time' => $request->pickup_time,
                'weight_estimate' => $weight,
                'service_cost' => $serviceCost,
                'discount' => 0,
                'total_cost' => $totalCost,
                'status' => 'dicuci',
                'notes' => $request->notes,
                'payment_method' => 'cod',
                'is_paid' => false,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'item_description' => $service->name,
                'qty' => 1,
                'weight_kg' => $weight,
                'unit_price' => $service->effective_unit_price,
                'line_total' => $serviceCost,
            ]);

            foreach ($itemLines as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            // Income dicatat saat order selesai (COD model)
        });

        return back()->with('status', "Pesanan walk-in untuk {$request->customer_name} berhasil dibuat.");
    }

    public function driverIndex()
    {
        $driver = Auth::user();

        $pesanan = Order::with(['customer', 'customerAddress', 'service'])
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['dijemput', 'dikirim', 'siap'])
            ->latest()
            ->paginate(15);

        return view('roles.driver.orders', compact('pesanan'));
    }

    public function driverDetail(Order $order)
    {
        abort_if($order->driver_id !== Auth::id(), 403);
        $order->load(['customer', 'customerAddress', 'service', 'items.service', 'statusHistories.updater']);

        $histori = $order->statusHistories()->latest('updated_at')->get();

        return view('roles.driver.order_detail', compact('order', 'histori'));
    }

    public function driverAction(Request $request, Order $order)
    {
        abort_if($order->driver_id !== Auth::id(), 403);

        $request->validate([
            'status' => 'required|in:dicuci,dikirim,selesai',
            'weight_actual' => 'nullable|numeric|min:0.1',
            'proof_image' => 'nullable|image|max:5120',
            'payment_channel' => 'nullable|in:cash,transfer,qris',
        ]);

        // Guard transisi sama seperti updateStatus admin. Driver punya 3
        // titik aksi: dijemput→dicuci, siap→dikirim, dikirim→selesai.
        // Selain itu ditolak — termasuk loncat dari dijemput langsung
        // selesai (yang akan nge-trigger income recording prematur).
        if ($order->isFinal()) {
            return back()->with('error', 'Pesanan sudah final, tidak bisa diubah lagi.');
        }

        if (! $order->canTransitionTo($request->status)) {
            return back()->with(
                'error',
                "Tidak bisa ubah status dari '{$order->status_label}' ke '{$request->status}'."
            );
        }

        // Aturan tambahan khusus driver: driver hanya boleh aksi pada
        // pesanan yang sedang di tangannya (status 'dijemput' atau 'dikirim').
        // Status 'dicuci'/'disetrika'/'siap' adalah domain workshop & admin
        // — driver tidak boleh ambil alih.
        $allowedDriverStartStatuses = ['dijemput', 'dikirim'];
        if (! in_array($order->status, $allowedDriverStartStatuses, true)) {
            return back()->with('error', 'Status pesanan saat ini di luar kendali driver.');
        }

        $updateData = ['status' => $request->status];
        $note = '';

        if ($request->status === 'dicuci' && $request->filled('weight_actual')) {
            $actualWeight = (float) $request->weight_actual;
            $actualCost = (int) ($order->service->effective_unit_price * $actualWeight);

            // Hitung item satuan (service selain layanan utama)
            $itemTotal = $order->items()
                ->where('service_id', '!=', $order->service_id)
                ->sum('line_total');

            $updateData['weight_actual'] = $actualWeight;
            $updateData['service_cost'] = $actualCost;
            $updateData['total_cost'] = $actualCost + (int) $itemTotal + $order->pickup_cost - $order->discount;
            $note = "Berat aktual: {$actualWeight} kg.";
        }

        if ($request->status === 'selesai' && $request->hasFile('proof_image')) {
            $updateData['proof_image'] = $request->file('proof_image')->store('proof', 'public');
            $updateData['is_paid'] = true;
            $updateData['paid_at'] = now();
            $updateData['payment_channel'] = $request->input('payment_channel', 'cash');
            $note = 'Pesanan berhasil diantar. Foto bukti diunggah. Bayar via '.strtoupper($updateData['payment_channel']).'.';
        } elseif ($request->status === 'selesai') {
            $updateData['is_paid'] = true;
            $updateData['paid_at'] = now();
            $updateData['payment_channel'] = $request->input('payment_channel', 'cash');
            $note = $note ?: 'Pesanan selesai. Bayar via '.strtoupper($updateData['payment_channel']).'.';
        }

        DB::transaction(function () use ($order, $updateData, $note, $request) {
            $order->update($updateData);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_code' => $request->status,
                'status_note' => $note ?: 'Status diperbarui oleh driver.',
                'updated_by' => Auth::id(),
            ]);

            // Record income saat order selesai (COD — bayar di tempat)
            if ($request->status === 'selesai') {
                FinanceController::recordIncomeFromOrder($order->fresh());
            }
        });

        // Notifikasi dikirim SETELAH transaksi commit
        $titles = [
            'dicuci' => 'Cucian Sedang Diproses',
            'dikirim' => 'Kurir Sedang Mengantar',
            'selesai' => 'Pesanan Selesai',
        ];
        $messages = [
            'dicuci' => "Pakaian #{$order->order_code} sudah dijemput dan masuk proses cuci.",
            'dikirim' => "Kurir sedang mengantar pesanan #{$order->order_code} ke rumah kamu.",
            'selesai' => "Pesanan #{$order->order_code} sudah sampai. Terima kasih sudah menggunakan Azka Laundry.",
        ];

        $order->customer->notify(new OrderStatusUpdated(
            $order,
            $titles[$request->status],
            $messages[$request->status]
        ));

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    public function driverTracking()
    {
        $order = Auth::user()->driverOrders()
            ->with(['customer', 'customerAddress'])
            ->whereIn('status', ['dijemput', 'dikirim'])
            ->latest()
            ->first();

        return view('roles.driver.tracking', compact('order'));
    }
}
