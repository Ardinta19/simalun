<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin'  => redirect()->route('dashboard.admin'),
            'driver' => redirect()->route('driver.dashboard'),
            default  => redirect()->route('customer.dashboard'),
        };
    }

    public function customer()
    {
        $user = Auth::user();

        $pesananAktif = $user->customerOrders()
            ->with(['service', 'driver'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->first();

        $riwayat = $user->customerOrders()
            ->with('service')
            ->whereIn('status', Order::statusSelesaiSemua())
            ->latest()
            ->take(5)
            ->get();

        $totalPesanan = $user->customerOrders()->count();
        $totalSelesai = $user->customerOrders()->where('status', 'selesai')->count();
        $totalKg      = (float) $user->customerOrders()
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
        $jumlahDiproses    = Order::whereIn('status', Order::STATUS_AKTIF)->count();
        $jumlahPrioritas   = Order::where('status', 'menunggu')->count();
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

        return view('roles.admin.dashboard', compact(
            'jumlahDiproses',
            'jumlahPrioritas',
            'jumlahSelesaiHari',
            'orderPrioritas',
            'daftarDriver',
            'pemasukanHari',
            'pemasukanBulan',
            'pesananSelesaiHariIni',
            'adminUnread'
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
}
