<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function customerIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('roles.customer.notifications', compact('notifications'));
    }

    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai telah dibaca.');
    }

    public function markRead(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        return back();
    }

    public function adminIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        $notifikasiOperasional = Order::with(['customer', 'service'])
            ->whereIn('status', Order::STATUS_AKTIF)
            ->latest()
            ->take(10)
            ->get();

        Auth::user()->unreadNotifications->markAsRead();

        return view('roles.admin.notifications', compact('notifications', 'notifikasiOperasional'));
    }

    public function driverIndex()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        Auth::user()->unreadNotifications->markAsRead();

        return view('roles.driver.notifications', compact('notifications'));
    }
}
