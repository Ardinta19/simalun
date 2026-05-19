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

    /**
     * GET /customer/notifications/{id}/click
     * Mark as read AND redirect to associated resource (order detail).
     * Used for clickable notification rows so user gets one-tap UX.
     */
    public function click(string $id)
    {
        $notif = Auth::user()->notifications()->find($id);
        if (!$notif) {
            return redirect()->route('customer.notifications');
        }

        if (is_null($notif->read_at)) {
            $notif->markAsRead();
        }

        $orderId = $notif->data['order_id'] ?? null;
        if ($orderId) {
            return redirect()->route('customer.order.detail', $orderId);
        }

        return redirect()->route('customer.notifications');
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