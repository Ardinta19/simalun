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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /* ═══════════════════════════════════════════
       CUSTOMER – Buat Pesanan
    ════════════════════════════════════════════ */

    /**
     * Form buat pesanan
     * GET /order/create
     */
    public function create()
    {
        $services    = Service::where('is_active', true)->orderBy('id')->get();
        $kgServices  = $services->where('pricing_model', 'per_kg')->values();
        $itemServices = $services->where('pricing_model', 'per_item')->values();

        $alamatTersimpan = Auth::user()->customerAddresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('last_used_at')
            ->get();

        return view('order.create', compact('services', 'kgServices', 'itemServices', 'alamatTersimpan'));
    }

    /**
     * Simpan pesanan baru
     * POST /order/store
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id'      => 'required|exists:services,id',
            'address'         => 'required|string|min:10',
            'zone'            => 'required|in:A,B,C',
            'pickup_date'     => 'required|date|after_or_equal:today',
            'pickup_time'     => 'required|in:pagi,siang,sore',
            'weight_estimate' => 'required|numeric|min:1|max:100',
            'notes'           => 'nullable|string|max:500',
            'item_lines'      => 'nullable|array',
            'item_lines.*.service_id' => 'required_with:item_lines|exists:services,id',
            'item_lines.*.qty'        => 'required_with:item_lines|integer|min:0',
        ]);

        $service    = Service::findOrFail($request->service_id);
        $weight     = (float) $request->weight_estimate;
        $zone       = $request->zone;
        $pickupCost = Order::zoneCost($zone);

        // Hitung biaya layanan utama
        $pricePerUnit  = $service->effective_unit_price;
        $serviceCost   = (int) ($pricePerUnit * $weight);

        // Hitung item satuan
        $itemLines    = [];
        $itemTotal    = 0;
        if ($request->filled('item_lines')) {
            foreach ($request->item_lines as $line) {
                $qty = (int) ($line['qty'] ?? 0);
                if ($qty <= 0) continue;
                $itemSvc = Service::find($line['service_id']);
                if (!$itemSvc) continue;
                $lineTotal  = $itemSvc->effective_unit_price * $qty;
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
        }

        $subtotal   = $serviceCost + $itemTotal;
        $totalCost  = $subtotal + $pickupCost;

        DB::transaction(function () use (
            $request, $service, $weight, $zone, $pickupCost,
            $serviceCost, $itemLines, $itemTotal, $totalCost
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

            // Simpan order items
            // Item utama (kiloan)
            OrderItem::create([
                'order_id'         => $order->id,
                'service_id'       => $service->id,
                'item_description' => $service->name,
                'qty'              => 1,
                'weight_kg'        => $weight,
                'unit_price'       => $service->effective_unit_price,
                'line_total'       => $serviceCost,
            ]);

            // Item satuan
            foreach ($itemLines as $line) {
                OrderItem::create(array_merge($line, ['order_id' => $order->id]));
            }

            // Catat history status
            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => 'menunggu',
                'status_note' => 'Pesanan berhasil dibuat oleh customer.',
                'updated_by'  => Auth::id(),
            ]);

            // Update last_used_at pada alamat jika dipakai
            if ($request->customer_address_id) {
                CustomerAddress::where('id', $request->customer_address_id)
                    ->where('customer_id', Auth::id())
                    ->update(['last_used_at' => now()]);
            }

            // Finance entry – order masuk
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

            // Notify admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new OrderStatusUpdated(
                    $order,
                    'Pesanan Baru Masuk',
                    "Pesanan #{$order->order_code} dari {$order->customer->name} menunggu penugasan kurir."
                ));
            }

            return redirect()->route('order.show', $order->order_code);
        });

        // Ambil order yang baru dibuat untuk redirect
        $newOrder = Order::where('customer_id', Auth::id())->latest()->first();
        return redirect()->route('order.show', $newOrder->order_code);
    }

    /**
     * Halaman sukses / detail singkat setelah order dibuat
     * GET /order/{orderCode}
     */
    public function show(string $orderCode)
    {
        $order = Order::with(['service', 'items.service'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        abort_if(
            Auth::user()->role === 'customer' && $order->customer_id !== Auth::id(),
            403
        );

        return view('order.success', compact('order'));
    }

    /* ═══════════════════════════════════════════
       CUSTOMER – Lihat Pesanan
    ════════════════════════════════════════════ */

    /**
     * Daftar pesanan customer
     * GET /customer/orders
     */
    public function customerIndex(Request $request)
    {
        $user   = Auth::user();
        $filter = $request->get('filter', 'semua');

        $query = $user->customerOrders()
            ->with(['service', 'driver'])
            ->latest();

        match ($filter) {
            'aktif'  => $query->whereIn('status', Order::statusAktifSemua()),
            'selesai'=> $query->whereIn('status', Order::statusSelesaiSemua()),
            'batal'  => $query->where('status', 'dibatalkan'),
            default  => null,
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

    /**
     * Detail pesanan customer
     * GET /customer/orders/{order}
     */
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

        return view('roles.customer.orders.orders', compact('order', 'histori'));
    }

    /**
     * Halaman tracking kurir (customer)
     * GET /customer/tracking
     */
    public function tracking()
    {
        $order = Auth::user()->customerOrders()
            ->with('driver')
            ->whereIn('status', ['dijemput', 'dikirim', 'siap'])
            ->latest()
            ->first();

        // Jika tidak ada order aktif, ambil order terbaru
        if (!$order) {
            $order = Auth::user()->customerOrders()
                ->with('driver')
                ->latest()
                ->first();
        }

        abort_if(!$order, 404);

        return view('roles.customer.orders.tracking', compact('order'));
    }

    /* ═══════════════════════════════════════════
       ADMIN – Kelola Pesanan
    ════════════════════════════════════════════ */

    /**
     * Daftar semua pesanan (admin)
     * GET /admin/orders
     */
    public function adminIndex(Request $request)
    {
        $status = $request->get('status');

        $query = Order::with(['customer', 'service', 'driver'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $pesanan      = $query->paginate(15)->withQueryString();
        $jumlahSemua  = Order::count();
        $jumlahAktif  = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahSelesai = Order::whereIn('status', Order::statusSelesaiSemua())->count();
        $daftarDriver  = User::where('role', 'driver')->where('is_active', true)->orderBy('name')->get();

        return view('roles.admin.orders', compact(
            'pesanan', 'jumlahSemua', 'jumlahAktif', 'jumlahSelesai', 'daftarDriver'
        ));
    }

    /**
     * Tugaskan driver ke order
     * POST /admin/orders/{order}/assign-driver
     */
    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id'       => 'required|exists:users,id',
            'assignment_type' => 'required|in:pickup,delivery',
        ]);

        $driver = User::where('id', $request->driver_id)
            ->where('role', 'driver')
            ->firstOrFail();

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

            // Notifikasi customer
            $order->customer->notify(new OrderStatusUpdated(
                $order,
                'Kurir Ditugaskan',
                "Kurir {$driver->name} sedang menuju lokasi kamu."
            ));
        });

        return back()->with('success', "Driver {$driver->name} berhasil ditugaskan.");
    }

    /**
     * Update status order (workshop: dicuci → disetrika → siap)
     * PATCH /admin/orders/{order}/update-status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:dicuci,disetrika,siap,selesai,dibatalkan',
        ]);

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

            // Notifikasi customer
            $order->customer->notify(new OrderStatusUpdated(
                $order,
                "Pesanan {$statusLabel[$request->status]}",
                "Pesanan #{$order->order_code} kamu sekarang: {$statusLabel[$request->status]}."
            ));
        });

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Struk / receipt pesanan
     * GET /orders/{order}/receipt
     */
    public function receipt(Order $order)
    {
        // Admin bisa lihat semua, customer hanya miliknya
        if (Auth::user()->role === 'customer') {
            abort_if($order->customer_id !== Auth::id(), 403);
        }

        $order->load(['customer', 'service', 'items.service', 'driver']);

        return view('order.receipt', compact('order'));
    }

    /**
     * Form walk-in (admin input pesanan tanpa customer account)
     * GET /admin/walkin
     */
    public function walkinForm()
    {
        $daftarLayanan     = Service::where('is_active', true)->where('pricing_model', 'per_kg')->get();
        $daftarLayananItem = Service::where('is_active', true)->where('pricing_model', 'per_item')->get();

        return view('roles.admin.walkin', compact('daftarLayanan', 'daftarLayananItem'));
    }

    /**
     * Simpan pesanan walk-in
     * POST /admin/walkin
     */
    public function walkinStore(Request $request)
    {
        $request->validate([
            'customer_name'   => 'required|string|max:120',
            'customer_phone'  => 'nullable|string|max:20',
            'service_id'      => 'required|exists:services,id',
            'weight_estimate' => 'required|numeric|min:0.5',
            'pickup_time'     => 'required|in:pagi,siang,sore',
        ]);

        // Cari atau buat user customer walk-in
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

        $service    = Service::findOrFail($request->service_id);
        $weight     = (float) $request->weight_estimate;
        $serviceCost = (int) ($service->effective_unit_price * $weight);

        // Hitung item satuan
        $itemLines = [];
        $itemTotal = 0;
        if ($request->filled('item_lines')) {
            foreach ($request->item_lines as $line) {
                $qty = (int) ($line['qty'] ?? 0);
                if ($qty <= 0) continue;
                $itemSvc = Service::find($line['service_id'] ?? null);
                if (!$itemSvc) continue;
                $lineTotal  = $itemSvc->effective_unit_price * $qty;
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
        }

        $totalCost = $serviceCost + $itemTotal; // Walk-in = tidak ada ongkos jemput

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
                'status'          => 'dicuci', // Walk-in langsung masuk proses
                'notes'           => $request->notes,
                'payment_method'  => 'cod',
                'is_paid'         => false,
                'created_by'      => Auth::id(),
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

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => 'dicuci',
                'status_note' => 'Pesanan walk-in dibuat oleh admin. Langsung masuk proses cuci.',
                'updated_by'  => Auth::id(),
            ]);

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

    /* ═══════════════════════════════════════════
       DRIVER – Kelola Tugas
    ════════════════════════════════════════════ */

    /**
     * Daftar tugas driver
     * GET /driver/orders
     */
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

    /**
     * Detail tugas driver
     * GET /driver/orders/{order}
     */
    public function driverDetail(Order $order)
    {
        abort_if($order->driver_id !== Auth::id(), 403);
        $order->load(['customer', 'customerAddress', 'service', 'items.service']);

        return view('roles.driver.orders', compact('order'));
    }

    /**
     * Driver update status order (jemput, antar, selesai + upload foto)
     * POST /driver/orders/{order}/action
     */
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

        // Setelah jemput: update berat aktual
        if ($request->status === 'dicuci' && $request->filled('weight_actual')) {
            $updateData['weight_actual'] = $request->weight_actual;

            // Recalculate harga berdasarkan berat aktual
            $actualCost  = (int) ($order->service->effective_unit_price * $request->weight_actual);
            $updateData['service_cost'] = $actualCost;
            $updateData['total_cost']   = $actualCost + $order->pickup_cost - $order->discount;
            $note = "Berat aktual: {$request->weight_actual} kg.";
        }

        // Setelah antar: upload foto bukti
        if ($request->status === 'selesai' && $request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('proof', 'public');
            $updateData['proof_image'] = $path;
            $updateData['is_paid']     = true;
            $updateData['paid_at']     = now();
            $note = 'Pesanan berhasil diantar. Foto bukti diunggah.';
        }

        DB::transaction(function () use ($order, $updateData, $note, $request) {
            $order->update($updateData);

            OrderStatusHistory::create([
                'order_id'    => $order->id,
                'status_code' => $request->status,
                'status_note' => $note ?: "Status diperbarui oleh driver.",
                'updated_by'  => Auth::id(),
            ]);

            // Notifikasi customer
            $titles = [
                'dicuci'  => 'Cucian Sedang Diproses',
                'dikirim' => 'Kurir Sedang Mengantar',
                'selesai' => 'Pesanan Selesai! 🎉',
            ];
            $messages = [
                'dicuci'  => "Pakaian #{$order->order_code} sudah dijemput dan masuk proses cuci.",
                'dikirim' => "Kurir sedang mengantar pesanan #{$order->order_code} ke rumah kamu.",
                'selesai' => "Pesanan #{$order->order_code} sudah sampai. Terima kasih sudah menggunakan Azka Laundry!",
            ];

            $order->customer->notify(new OrderStatusUpdated(
                $order,
                $titles[$request->status],
                $messages[$request->status]
            ));
        });

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Tracking untuk driver
     * GET /driver/tracking
     */
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