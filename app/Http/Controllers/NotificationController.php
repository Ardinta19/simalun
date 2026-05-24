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

    public function markAndRedirect(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        $orderId = $notif->data['order_id'] ?? null;
        $role = Auth::user()->role;

        // Tentukan halaman notifikasi asal (untuk back button di halaman tujuan)
        $backUrl = match ($role) {
            'admin' => route('admin.notifications'),
            'driver' => route('driver.notifications'),
            default => route('customer.notifications'),
        };

        if (! $orderId) {
            return redirect($backUrl);
        }

        // Redirect ke detail order dengan ?back= mengarah ke halaman notifikasi
        return match ($role) {
            'admin' => redirect()->route('admin.orders', ['back' => $backUrl]),
            'driver' => redirect()->route('driver.orders.show', ['order' => $orderId, 'back' => $backUrl]),
            default => redirect()->route('customer.order.detail', ['order' => $orderId, 'back' => $backUrl]),
        };
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
