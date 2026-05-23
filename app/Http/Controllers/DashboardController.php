<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use App\Models\OrderRating;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;

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
        $sejak = now()->subDays(30)->startOfDay();

        $topServices = Order::query()
            ->selectRaw('service_id, COUNT(*) as total_order')
            ->whereNotNull('service_id')
            ->where('created_at', '>=', $sejak)
            ->groupBy('service_id')
            ->orderByDesc('total_order')
            ->take(5)
            ->with('service:id,name')
            ->get();

        // Distribusi jam pickup (pagi/siang/sore) — bantu admin atur jadwal kurir.
        $pickupBuckets = Order::query()
            ->selectRaw('pickup_time, COUNT(*) as total')
            ->whereNotNull('pickup_time')
            ->where('created_at', '>=', $sejak)
            ->groupBy('pickup_time')
            ->pluck('total', 'pickup_time');

        $pickupBuckets = collect(['pagi', 'siang', 'sore'])
            ->mapWithKeys(fn ($slot) => [$slot => (int) ($pickupBuckets[$slot] ?? 0)])
            ->toArray();

        $topCustomers = Order::query()
            ->selectRaw('customer_id, COUNT(*) as total_order, SUM(total_cost) as total_spent')
            ->whereNotNull('customer_id')
            ->where('created_at', '>=', $sejak)
            ->groupBy('customer_id')
            ->orderByDesc('total_order')
            ->take(5)
            ->with('customer:id,name,phone')
            ->get();

        $ratingStats = OrderRating::query()
            ->where('created_at', '>=', $sejak)
            ->selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_rating')
            ->first();

        $ulasanTerbaru = OrderRating::with(['customer:id,name', 'order:id,order_code'])
            ->latest()
            ->take(3)
            ->get();

        $voucherAktif = Voucher::where('is_active', true)->count();

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
