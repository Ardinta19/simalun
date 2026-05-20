<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Service;
use App\Models\User;
use App\Models\CustomerAddress;
use App\Models\FinanceEntry;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function create()
    {
        $services     = Service::where('is_active', true)->orderBy('id')->get();
        $kgServices   = $services->where('pricing_model', 'per_kg')->values();
        $itemServices = $services->where('pricing_model', 'per_item')->values();

        $alamatTersimpan = Auth::user()->customerAddresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('last_used_at')
            ->get();

        return view('order.create', compact('services', 'kgServices', 'itemServices', 'alamatTersimpan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'              => ['required', 'exists:services,id'],
            'address'                 => ['required', 'string', 'min:10', 'max:300'],
            'address_note'            => ['nullable', 'string', 'max:200'],
            'zone'                    => ['required', 'in:A,B,C'],
            'pickup_date'             => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(14)->toDateString()],
            'pickup_time'             => ['required', 'in:pagi,siang,sore'],
            'weight_estimate'         => ['required', 'numeric', 'min:1', 'max:50'],
            'notes'                   => ['nullable', 'string', 'max:500'],
            'customer_address_id'     => ['nullable', 'integer', 'exists:customer_addresses,id'],
            'item_lines'              => ['nullable', 'array', 'max:30'],
            'item_lines.*.service_id' => ['required_with:item_lines', 'exists:services,id'],
            'item_lines.*.qty'        => ['required_with:item_lines', 'integer', 'min:0', 'max:200'],
            'item_lines.*.notes'      => ['nullable', 'string', 'max:200'],
        ], [
            'pickup_date.before_or_equal' => 'Tanggal jemput maksimal 14 hari ke depan.',
            'weight_estimate.max'         => 'Estimasi berat melebihi batas (maks 50 kg).',
            'item_lines.max'              => 'Item satuan terlalu banyak (maks 30 baris).',
        ]);

        // Ownership check for customer_address_id
        if (!empty($validated['customer_address_id'])) {
            $owns = CustomerAddress::where('id', $validated['customer_address_id'])
                ->where('customer_id', Auth::id())
                ->exists();
            abort_if(!$owns, 403, 'Alamat tersimpan tidak ditemukan.');
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

        $service    = Service::findOrFail($request->service_id);
        $weight     = (float) $request->weight_estimate;
        $zone       = $request->zone;
        $pickupCost = Order::zoneCost($zone);

        $serviceCost = (int) ($service->effective_unit_price * $weight);

        $itemLines = [];
        $itemTotal = 0;

        foreach ((array) $request->input('item_lines', []) as $line) {
            $qty = (int) ($line['qty'] ?? 0);
            if ($qty <= 0) continue;

            $itemSvc = Service::find($line['service_id'] ?? null);
            if (!$itemSvc) continue;

            $lineTotal = $itemSvc->effective_unit_price * $qty;
            $itemTotal += $lineTotal;

            $itemLines[] = [
                'service_id'       => $itemSvc->id,
                'item_description' => $itemSvc->name,
                'qty'              => $qty,
                'weight_kg'        => null,
                'unit_price'       => $itemSvc->effective_unit_price,
                'line_total'       => $lineTotal,
                'notes'            => $line['notes'] ?? null,
            ];
        }

        $totalCost = $serviceCost + $itemTotal + $pickupCost;

        try {
            $newOrder = DB::transaction(function () use (
            $request, $service, $weight, $zone, $pickupCost,
            $serviceCost, $itemLines, $totalCost
        ) {
            $order = Order::create([
                'order_code'          => Order::generateCode(),
                'customer_id'         => Auth::id(),
                'service_id'          => $service->id,
                'address'             => $request->address,
                'address_note'        => $request->address_note,
                'zone'                => $zone,
                'pickup_cost'         => $pickupCost,
                'pickup_date'         => $request->pickup_date,
                'pickup_time'         => $request->pickup_time,
                'weight_estimate'     => $weight,
                'service_cost'        => $serviceCost,
                'discount'            => 0,
                'total_cost'          => $totalCost,
                'status'              => 'menunggu',
                'notes'               => $request->notes,
                'payment_method'      => 'cod',
                'is_paid'             => false,
                'customer_address_id' => $request->customer_address_id ?: null,
            ]);

            OrderItem::create([
                'order_id'         => $order->id,
                'service_id'       => $service->id,
                'item_description' => $service->name,
                'qty'              => 1,
                'weight_kg'        => $weight,
                'unit_price'       => $service->effective_unit_price,
                'line_total'       => $serviceCost,
            ]);

            foreach ($itemLines as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            if ($request->customer_address_id) {
                CustomerAddress::where('id', $request->customer_address_id)
                    ->where('customer_id', Auth::id())
                    ->update(['last_used_at' => now()]);
            }

            FinanceEntry::create([
                'entry_date'  => today(),
                'period_key'  => now()->format('Y-m'),
                'entry_type'  => 'income',
                'amount'      => $totalCost,
                'source_type' => 'order',
                'source_id'   => $order->id,
                'order_id'    => $order->id,
                'notes'       => "Order {$order->order_code}",
                'created_by'  => Auth::id(),
            ]);

            User::where('role', 'admin')->each(function ($admin) use ($order) {
                $admin->notify(new OrderStatusUpdated(
                    $order,
                    'Pesanan Baru Masuk',
                    "Pesanan #{$order->order_code} dari {$order->customer->name} menunggu penugasan kurir."
                ));
            });

            return $order;
        });
        } catch (\Throwable $e) {
            report($e);
            return back()->withInput()
                ->withErrors(['service_id' => 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.']);
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
        abort_if(
            $user && $user->role === 'customer' && $order->customer_id !== $user->id,
            403
        );

        // Hanya tampilkan layar success ketika order baru saja dibuat
        // (status masih "menunggu"). Selebihnya arahkan ke detail real.
        if ($order->status !== 'menunggu') {
            return match ($user->role ?? 'customer') {
                'admin'  => redirect()->route('admin.orders.receipt', $order),
                'driver' => redirect()->route('driver.orders.show', ['order' => $order->id, 'from' => 'orders']),
                default  => redirect()->route('customer.order.detail', ['order' => $order->id, 'from' => 'success']),
            };
        }

        return view('order.success', compact('order'));
    }

    public function customerIndex(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->get('filter', 'semua');

        $query = $user->customerOrders()
            ->with(['service', 'driver'])
            ->latest();

        match ($filter) {
            'aktif'   => $query->whereIn('status', Order::statusAktifSemua()),
            'selesai' => $query->whereIn('status', Order::statusSelesaiSemua()),
            'batal'   => $query->where('status', 'dibatalkan'),
            default   => null,
        };

        $pesananAktif = $user->customerOrders()
            ->with(['service', 'driver'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        return view('roles.customer.orders.index', [
            'pesanan'      => $query->paginate(10)->withQueryString(),
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
        ]);

        $histori = $order->statusHistories()
            ->latest('updated_at')
            ->get();

        return view('roles.customer.orders.order', compact('order', 'histori'));
    }

    public function tracking()
    {
        $order = Auth::user()->customerOrders()
            ->with('driver')
            ->whereIn('status', ['dijemput', 'dikirim', 'siap'])
            ->latest()
            ->first();

        if (!$order) {
            $order = Auth::user()->customerOrders()
                ->with('driver')
                ->latest()
                ->first();
        }

        abort_if(!$order, 404);

        return view('roles.customer.orders.tracking', compact('order'));
    }

    public function adminIndex(Request $request)
    {
        $status = $request->get('status');

        $query = Order::with(['customer', 'service', 'driver'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pesanan       = $query->paginate(15)->withQueryString();
        $jumlahSemua   = Order::count();
        $jumlahAktif   = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahSelesai = Order::whereIn('status', Order::statusSelesaiSemua())->count();
        $daftarDriver  = User::where('role', 'driver')->where('is_active', true)->orderBy('name')->get();

        return view('roles.admin.orders', compact(
            'pesanan', 'jumlahSemua', 'jumlahAktif', 'jumlahSelesai', 'daftarDriver'
        ));
    }

    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id'       => ['required', 'exists:users,id'],
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

        if (!$driver) {
            return back()->with('error', 'Driver tidak ditemukan atau sedang nonaktif.');
        }

        $newStatus = $request->assignment_type === 'pickup' ? 'dijemput' : 'dikirim';

        DB::transaction(function () use ($order, $driver, $newStatus, $request) {
            $order->update([
                'driver_id' => $driver->id,
                'status'    => $newStatus,
            ]);

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => $newStatus,
                'status_note' => "Driver {$driver->name} ditugaskan untuk {$request->assignment_type}.",
                'updated_by'  => Auth::id(),
            ]);

            $order->customer->notify(new OrderStatusUpdated(
                $order,
                'Kurir Ditugaskan',
                "Kurir {$driver->name} sedang menuju lokasi kamu."
            ));

            // Notifikasi ke driver yang ditugaskan
            $taskLabel = $request->assignment_type === 'pickup' ? 'penjemputan' : 'pengantaran';
            $driver->notify(new OrderStatusUpdated(
                $order,
                'Tugas Baru',
                "Anda ditugaskan untuk {$taskLabel} pesanan #{$order->order_code} dari {$order->customer->name}."
            ));
        });

        return back()->with('success', "Driver {$driver->name} berhasil ditugaskan.");
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:dicuci,disetrika,siap,selesai,dibatalkan'],
        ]);

        // Block updates on final orders
        if (in_array($order->status, ['selesai', 'dibatalkan'], true)) {
            return back()->with('error', 'Pesanan sudah final, tidak bisa diubah lagi.');
        }

        $statusLabel = [
            'dicuci'     => 'Sedang Dicuci',
            'disetrika'  => 'Sedang Disetrika',
            'siap'       => 'Siap Dikirim',
            'selesai'    => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        DB::transaction(function () use ($order, $request, $statusLabel) {
            $order->update(['status' => $request->status]);

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => $request->status,
                'status_note' => "Status diperbarui menjadi: {$statusLabel[$request->status]}.",
                'updated_by'  => Auth::id(),
            ]);

            $order->customer->notify(new OrderStatusUpdated(
                $order,
                "Pesanan {$statusLabel[$request->status]}",
                "Pesanan #{$order->order_code} kamu sekarang: {$statusLabel[$request->status]}."
            ));

            // Notifikasi ke driver jika status siap (perlu antar)
            if ($request->status === 'siap' && $order->driver_id) {
                $order->driver->notify(new OrderStatusUpdated(
                    $order,
                    'Cucian Siap Diantar',
                    "Pesanan #{$order->order_code} sudah siap. Menunggu penugasan pengantaran."
                ));
            }
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function receipt(Order $order)
    {
        if (Auth::user()->role === 'customer') {
            abort_if($order->customer_id !== Auth::id(), 403);
        }

        $order->load(['customer', 'service', 'items.service', 'driver']);

        return view('order.receipt', compact('order'));
    }

    public function walkinForm()
    {
        $daftarLayanan     = Service::where('is_active', true)->where('pricing_model', 'per_kg')->get();
        $daftarLayananItem = Service::where('is_active', true)->where('pricing_model', 'per_item')->get();

        return view('roles.admin.walkin', compact('daftarLayanan', 'daftarLayananItem'));
    }

    public function walkinStore(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:120',
            'customer_phone'  => 'nullable|string|max:20',
            'service_id'      => 'required|exists:services,id',
            'weight_estimate' => 'required|numeric|min:0.5',
            'pickup_time'     => 'required|in:pagi,siang,sore',
        ]);

        $customer = User::firstOrCreate(
            ['phone' => $request->customer_phone ?: null],
            [
                'name'     => $request->customer_name,
                'email'    => null,
                'password' => bcrypt(Str::random(12)),
                'role'     => 'customer',
                'phone'    => $request->customer_phone,
            ]
        );

        $service     = Service::findOrFail($request->service_id);
        $weight      = (float) $request->weight_estimate;
        $serviceCost = (int) ($service->effective_unit_price * $weight);

        $itemLines = [];
        $itemTotal = 0;

        foreach ((array) $request->input('item_lines', []) as $line) {
            $qty = (int) ($line['qty'] ?? 0);
            if ($qty <= 0) continue;

            $itemSvc = Service::find($line['service_id'] ?? null);
            if (!$itemSvc) continue;

            $lineTotal = $itemSvc->effective_unit_price * $qty;
            $itemTotal += $lineTotal;

            $itemLines[] = [
                'service_id'       => $itemSvc->id,
                'item_description' => $itemSvc->name,
                'qty'              => $qty,
                'weight_kg'        => null,
                'unit_price'       => $itemSvc->effective_unit_price,
                'line_total'       => $lineTotal,
            ];
        }

        $totalCost = $serviceCost + $itemTotal;

        DB::transaction(function () use (
            $request, $customer, $service, $weight, $serviceCost, $itemLines, $totalCost
        ) {
            $order = Order::create([
                'order_code'      => Order::generateCode(),
                'customer_id'     => $customer->id,
                'service_id'      => $service->id,
                'address'         => 'Walk-in (Datang Langsung)',
                'zone'            => 'A',
                'pickup_cost'     => 0,
                'pickup_date'     => today(),
                'pickup_time'     => $request->pickup_time,
                'weight_estimate' => $weight,
                'service_cost'    => $serviceCost,
                'discount'        => 0,
                'total_cost'      => $totalCost,
                'status'          => 'dicuci',
                'notes'           => $request->notes,
                'payment_method'  => 'cod',
                'is_paid'         => false,
            ]);

            OrderItem::create([
                'order_id'         => $order->id,
                'service_id'       => $service->id,
                'item_description' => $service->name,
                'qty'              => 1,
                'weight_kg'        => $weight,
                'unit_price'       => $service->effective_unit_price,
                'line_total'       => $serviceCost,
            ]);

            foreach ($itemLines as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            FinanceEntry::create([
                'entry_date'  => today(),
                'period_key'  => now()->format('Y-m'),
                'entry_type'  => 'income',
                'amount'      => $totalCost,
                'source_type' => 'order',
                'source_id'   => $order->id,
                'order_id'    => $order->id,
                'notes'       => "Walk-in {$order->order_code} – {$customer->name}",
                'created_by'  => Auth::id(),
            ]);
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
            'status'        => 'required|in:dicuci,dikirim,selesai',
            'weight_actual' => 'nullable|numeric|min:0.1',
            'proof_image'   => 'nullable|image|max:5120',
        ]);

        $updateData = ['status' => $request->status];
        $note       = '';

        if ($request->status === 'dicuci' && $request->filled('weight_actual')) {
            $actualWeight = (float) $request->weight_actual;
            $actualCost   = (int) ($order->service->effective_unit_price * $actualWeight);

            $updateData['weight_actual'] = $actualWeight;
            $updateData['service_cost']  = $actualCost;
            $updateData['total_cost']    = $actualCost + $order->pickup_cost - $order->discount;
            $note = "Berat aktual: {$actualWeight} kg.";
        }

        if ($request->status === 'selesai' && $request->hasFile('proof_image')) {
            $updateData['proof_image'] = $request->file('proof_image')->store('proof', 'public');
            $updateData['is_paid']     = true;
            $updateData['paid_at']     = now();
            $note = 'Pesanan berhasil diantar. Foto bukti diunggah.';
        }

        DB::transaction(function () use ($order, $updateData, $note, $request) {
            $order->update($updateData);

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => $request->status,
                'status_note' => $note ?: 'Status diperbarui oleh driver.',
                'updated_by'  => Auth::id(),
            ]);

            $titles = [
                'dicuci'  => 'Cucian Sedang Diproses',
                'dikirim' => 'Kurir Sedang Mengantar',
                'selesai' => 'Pesanan Selesai',
            ];
            $messages = [
                'dicuci'  => "Pakaian #{$order->order_code} sudah dijemput dan masuk proses cuci.",
                'dikirim' => "Kurir sedang mengantar pesanan #{$order->order_code} ke rumah kamu.",
                'selesai' => "Pesanan #{$order->order_code} sudah sampai. Terima kasih sudah menggunakan Azka Laundry.",
            ];

            $order->customer->notify(new OrderStatusUpdated(
                $order,
                $titles[$request->status],
                $messages[$request->status]
            ));
        });

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
