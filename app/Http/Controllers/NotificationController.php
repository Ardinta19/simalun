<?php
/* ══════════════════════════════════════════════════════════════
   FILE: app/Http/Controllers/NotificationController.php
══════════════════════════════════════════════════════════════ */
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** GET /customer/notifications */
    public function customerIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('roles.customer.notifications', compact('notifications'));
    }

    /** POST /customer/notifications/read-all */
    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }

    /** PATCH /customer/notifications/{id}/read */
    public function markRead(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();
        return back();
    }

    /** GET /admin/notifications */
    public function adminIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        // Pesanan operasional terbaru untuk admin
        $notifikasiOperasional = Order::with(['customer', 'service'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->take(10)
            ->get();

        Auth::user()->unreadNotifications->markAsRead();

        return view('roles.admin.notifications', compact('notifications', 'notifikasiOperasional'));
    }

    /** GET /driver/notifications */
    public function driverIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        Auth::user()->unreadNotifications->markAsRead();

        return view('roles.driver.notifications', compact('notifications'));
    }
}