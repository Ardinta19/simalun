<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use App\Models\OrderRating;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('dashboard.admin'),
            'driver' => redirect()->route('driver.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }

    public function customer()
    {
        $user = Auth::user();

        $pesananAktif = $user->customerOrders()
            ->with(['service', 'driver', 'items'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        $riwayat = $user->customerOrders()
            ->with(['service', 'items'])
            ->whereIn('status', Order::statusSelesaiSemua())
            ->latest()
            ->take(5)
            ->get();

        $totalPesanan = $user->customerOrders()->count();
        $totalSelesai = $user->customerOrders()->where('status', 'selesai')->count();
        $totalKg = (float) $user->customerOrders()
            ->where('status', 'selesai')
            ->sum('weight_actual');

        $alamatUtama = $user->customerAddresses()
            ->where('is_primary', true)
            ->first();

        $unreadNotif = $user->unreadNotifications->count();

        return view('roles.customer.dashboard', compact(
            'user',
            'pesananAktif',
            'riwayat',
            'totalPesanan',
            'totalSelesai',
            'totalKg',
            'alamatUtama',
            'unreadNotif'
        ));
    }

    public function admin()
    {
        $jumlahDiproses = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahPrioritas = Order::where('status', 'menunggu')->count();
        $jumlahSelesaiHari = Order::where('status', 'selesai')
            ->whereDate('updated_at', today())
            ->count();

        $orderPrioritas = Order::with(['customer', 'service'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        $daftarDriver = User::where('role', 'driver')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $pemasukanHari = FinanceEntry::where('entry_type', 'income')
            ->whereDate('entry_date', today())
            ->sum('amount');

        $pemasukanBulan = FinanceEntry::where('entry_type', 'income')
            ->where('period_key', now()->format('Y-m'))
            ->sum('amount');

        $pesananSelesaiHariIni = Order::where('status', 'selesai')
            ->whereDate('updated_at', today())
            ->count();

        $adminUnread = Auth::user()->unreadNotifications->count();

        // ── Analitik 30 hari terakhir ─────────────────────────────────
        // Sengaja pakai window 30 hari supaya tetap relevan saat usaha tumbuh.
        // Window lifetime cenderung condong ke layanan/customer lama saja.
        //
        // Cache 60 detik per segment supaya dashboard admin tetap snappy
        // walau aggregate query berat. Trade-off: data bisa basi maksimal
        // 1 menit — acceptable untuk analitik 30-hari (top services /
        // customer / rating distribusi gak berubah signifikan dalam
        // window 60 detik). Operasional counter (jumlahDiproses,
        // pemasukanHari, dll) di atas SENGAJA gak di-cache karena admin
        // butuh data live untuk dispatching.
        $sejak = now()->subDays(30)->startOfDay();
        $cacheTtl = 60; // detik

        $topServices = Cache::remember('dashboard.admin.top-services', $cacheTtl, function () use ($sejak) {
            return Order::query()
                ->selectRaw('service_id, COUNT(*) as total_order')
                ->whereNotNull('service_id')
                ->where('created_at', '>=', $sejak)
                ->groupBy('service_id')
                ->orderByDesc('total_order')
                ->take(5)
                ->with('service:id,name')
                ->get();
        });

        // Distribusi jam pickup (pagi/siang/sore) — bantu admin atur jadwal kurir.
        $pickupBuckets = Cache::remember('dashboard.admin.pickup-buckets', $cacheTtl, function () use ($sejak) {
            $raw = Order::query()
                ->selectRaw('pickup_time, COUNT(*) as total')
                ->whereNotNull('pickup_time')
                ->where('created_at', '>=', $sejak)
                ->groupBy('pickup_time')
                ->pluck('total', 'pickup_time');

            // Normalisasi: pastikan 3 slot selalu ada walau count = 0,
            // supaya view gak butuh fallback `?? 0` di tiap render.
            return collect(['pagi', 'siang', 'sore'])
                ->mapWithKeys(fn ($slot) => [$slot => (int) ($raw[$slot] ?? 0)])
                ->toArray();
        });

        $topCustomers = Cache::remember('dashboard.admin.top-customers', $cacheTtl, function () use ($sejak) {
            return Order::query()
                ->selectRaw('customer_id, COUNT(*) as total_order, SUM(total_cost) as total_spent')
                ->whereNotNull('customer_id')
                ->where('created_at', '>=', $sejak)
                ->groupBy('customer_id')
                ->orderByDesc('total_order')
                ->take(5)
                ->with('customer:id,name,phone')
                ->get();
        });

        $ratingStats = Cache::remember('dashboard.admin.rating-stats', $cacheTtl, function () use ($sejak) {
            return OrderRating::query()
                ->where('created_at', '>=', $sejak)
                ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_rating')
                ->first();
        });

        $ulasanTerbaru = Cache::remember('dashboard.admin.latest-reviews', $cacheTtl, function () {
            return OrderRating::with(['customer:id,name', 'order:id,order_code'])
                ->latest()
                ->take(3)
                ->get();
        });

        $voucherAktif = Cache::remember('dashboard.admin.voucher-aktif-count', $cacheTtl, function () {
            return Voucher::where('is_active', true)->count();
        });

        return view('roles.admin.dashboard', compact(
            'jumlahDiproses',
            'jumlahPrioritas',
            'jumlahSelesaiHari',
            'orderPrioritas',
            'daftarDriver',
            'pemasukanHari',
            'pemasukanBulan',
            'pesananSelesaiHariIni',
            'adminUnread',
            'topServices',
            'pickupBuckets',
            'topCustomers',
            'ratingStats',
            'ulasanTerbaru',
            'voucherAktif',
        ));
    }

    public function driver()
    {
        $driver = Auth::user();

        $tugasAktif = Order::with(['customer', 'customerAddress', 'service'])
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['dijemput', 'dikirim'])
            ->latest()
            ->get();

        $tugasMenunggu = Order::with(['customer', 'customerAddress', 'service'])
            ->where('driver_id', $driver->id)
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        $totalAntarBulanIni = Order::where('driver_id', $driver->id)
            ->where('status', 'selesai')
            ->whereMonth('updated_at', now()->month)
            ->count();

        $unreadNotif = $driver->unreadNotifications->count();

        return view('roles.driver.dashboard', compact(
            'driver',
            'tugasAktif',
            'tugasMenunggu',
            'totalAntarBulanIni',
            'unreadNotif'
        ));
    }

    /**
     * Endpoint polling ringan untuk driver dashboard. Dipanggil setiap
     * 30 detik dari JS supaya driver gak harus refresh manual saat
     * admin assign order baru. Format JSON minimal — payload <500 byte.
     *
     * Catatan: ini fallback shared-hosting friendly. Untuk
     * pengalaman realtime sungguhan, switch ke broadcasting Reverb —
     * lihat docs/realtime.md.
     */
    public function driverPoll()
    {
        $driver = Auth::user();
        abort_if($driver->role !== 'driver', 403);

        $aktif = Order::where('driver_id', $driver->id)
            ->whereIn('status', ['dijemput', 'dikirim'])
            ->count();

        $unread = $driver->unreadNotifications()->count();

        // signature_hash dipakai client untuk deteksi 'ada perubahan'.
        // Hash dari id terakhir + status — kalau ada order baru di-assign
        // atau status berubah, hash beda → client refresh.
        $latest = Order::where('driver_id', $driver->id)
            ->whereIn('status', ['menunggu', 'dijemput', 'dikirim'])
            ->latest('updated_at')
            ->first();

        return response()->json([
            'tugas_aktif' => $aktif,
            'unread_notif' => $unread,
            'signature' => $latest ? hash('crc32', $latest->id.'|'.$latest->status.'|'.$latest->updated_at) : null,
            'polled_at' => now()->toIso8601String(),
        ]);
    }
}
