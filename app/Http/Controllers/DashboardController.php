<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\FinanceEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Router utama – redirect berdasarkan role dari database
     * GET /dashboard
     */
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'  => redirect()->route('dashboard.admin'),
            'driver' => redirect()->route('driver.dashboard'),
            default  => redirect()->route('customer.dashboard'),
        };
    }

    /* ───────────────────────────────────────────
       CUSTOMER DASHBOARD
       GET /customer/dashboard
    ___________________________________________ */
    public function customer()
    {
        $user = Auth::user();

        // Pesanan aktif (yang sedang berjalan) — view pakai $activeOrder
        $activeOrder = $user->customerOrders()
            ->with(['service', 'driver'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        // Riwayat pesanan (5 terbaru selesai) — view pakai $recentOrders
        $recentOrders = $user->customerOrders()
            ->with('service')
            ->whereIn('status', Order::statusSelesaiSemua())
            ->latest()
            ->take(5)
            ->get();

        // Stats — view pakai $totalOrders & $completedOrders
        $totalOrders     = $user->customerOrders()->count();
        $completedOrders = $user->customerOrders()
            ->whereIn('status', Order::statusSelesaiSemua())
            ->count();

        // Alamat utama
        $alamatUtama = $user->customerAddresses()
            ->where('is_primary', true)
            ->first();

        // Notifikasi belum dibaca
        $unreadNotif = $user->unreadNotifications->count();

        return view('roles.customer.dashboard', compact(
            'user',
            'activeOrder',
            'recentOrders',
            'totalOrders',
            'completedOrders',
            'alamatUtama',
            'unreadNotif'
        ));
    }

    /* ───────────────────────────────────────────
       ADMIN DASHBOARD
       GET /admin/dashboard
    ___________________________________________ */
    public function admin()
    {
        // KPI cards
        $jumlahDiproses = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahPrioritas = Order::where('status', 'menunggu')->count();
        $jumlahSelesaiHari = Order::where('status', 'selesai')
            ->whereDate('updated_at', today())
            ->count();

        // Pesanan menunggu penugasan (untuk cards)
        $orderPrioritas = Order::with(['customer', 'service'])
            ->where('status', 'menunggu')
            ->latest()
            ->take(5)
            ->get();

        // Daftar driver aktif
        $daftarDriver = User::where('role', 'driver')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Pemasukan hari ini
        $pemasukanHari = FinanceEntry::where('entry_type', 'income')
            ->whereDate('entry_date', today())
            ->sum('amount');

        // Notifikasi admin
        $adminUnread = auth()->user()->unreadNotifications->count();

        return view('roles.admin.dashboard', compact(
            'jumlahDiproses',
            'jumlahPrioritas',
            'jumlahSelesaiHari',
            'orderPrioritas',
            'daftarDriver',
            'pemasukanHari',
            'adminUnread'
        ));
    }

    /* ───────────────────────────────────────────
       DRIVER DASHBOARD
       GET /driver/dashboard
    ___________________________________________ */
    public function driver()
    {
        $driver = Auth::user();

        // Tugas aktif hari ini
        $tugasAktif = Order::with(['customer', 'customerAddress', 'service'])
            ->where('driver_id', $driver->id)
            ->whereIn('status', ['dijemput', 'dikirim'])
            ->latest()
            ->get();

        // Tugas menunggu (menunggu dijemput)
        $tugasMenunggu = Order::with(['customer', 'customerAddress', 'service'])
            ->where('driver_id', $driver->id)
            ->where('status', 'menunggu')
            ->orWhere(function ($q) use ($driver) {
                $q->whereHas('assignments', fn($a) =>
                    $a->where('driver_id', $driver->id)
                      ->where('assignment_status', 'assigned')
                );
            })
            ->latest()
            ->get();

        // Stats driver (bulan ini)
        $totalAntarBulanIni = Order::where('driver_id', $driver->id)
            ->where('status', 'selesai')
            ->whereMonth('updated_at', now()->month)
            ->count();

        // Notifikasi belum dibaca
        $unreadNotif = $driver->unreadNotifications->count();

        return view('roles.driver.dashboard', compact(
            'driver',
            'tugasAktif',
            'tugasMenunggu',
            'totalAntarBulanIni',
            'unreadNotif'
        ));
    }
}