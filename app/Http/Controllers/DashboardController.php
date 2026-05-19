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

        // Pesanan aktif (yang sedang berjalan)
        $pesananAktif = $user->customerOrders()
            ->with(['service', 'driver'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        // Riwayat pesanan (5 terbaru selesai)
        $riwayat = $user->customerOrders()
            ->with('service')
            ->whereIn('status', Order::statusSelesaiSemua())
            ->latest()
            ->take(5)
            ->get();

        // Stats
        $totalPesanan  = $user->customerOrders()->count();
        $totalSelesai  = $user->customerOrders()->where('status', 'selesai')->count();
        $totalKg       = $user->customerOrders()
            ->where('status', 'selesai')
            ->sum('weight_actual');

        // Alamat utama
        $alamatUtama = $user->customerAddresses()
            ->where('is_primary', true)
            ->first();

        // Notifikasi belum dibaca
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

        // Tugas menunggu konfirmasi (status menunggu yang sudah di-assign ke driver ini,
        // atau yang punya assignment "assigned" di tabel driver_assignments).
        $tugasMenunggu = Order::with(['customer', 'customerAddress', 'service'])
            ->where(function ($q) use ($driver) {
                $q->where(function ($qq) use ($driver) {
                    $qq->where('driver_id', $driver->id)
                       ->where('status', 'menunggu');
                })->orWhereHas('assignments', function ($a) use ($driver) {
                    $a->where('driver_id', $driver->id)
                      ->where('assignment_status', 'assigned');
                });
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