<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

// ── BROADCASTING-READY (template, belum aktif) ──────────────────────
// Untuk aktifkan realtime via Reverb (butuh VPS / process worker):
// 1. Tambah `implements ShouldBroadcast` di class signature di bawah.
// 2. Import `Illuminate\Contracts\Broadcasting\ShouldBroadcast`.
// 3. Tambah method `broadcastOn()` & `broadcastAs()` (lihat block di
//    paling bawah file ini, sudah disiapkan tinggal uncomment).
// 4. Ikuti docs/realtime.md untuk install Reverb + Echo.
//
// Pendekatan ini gak bikin baris yang berjalan saat polling-mode,
// jadi performa & dependency tetap minimal.
// ────────────────────────────────────────────────────────────────────

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    protected $title;

    protected $message;

    /**
     * Dijalankan via queue (`QUEUE_CONNECTION=database`) supaya update status
     * di controller tidak menunggu proses tulis ke tabel notifications.
     * Antrian dipisah ke `notifications` agar mudah dipantau.
     */
    public function __construct(Order $order, string $title, string $message)
    {
        $this->order = $order;
        $this->title = $title;
        $this->message = $message;

        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * Untuk aktifkan realtime broadcasting, tambah 'broadcast' ke array:
     *   return ['database', 'broadcast'];
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_code' => $this->order->order_code,
            'title' => $this->title,
            'message' => $this->message,
            'status' => $this->order->status,
        ];
    }

    // ── BROADCASTING METHODS (uncomment + add ShouldBroadcast) ─────────
    //
    // public function broadcastOn(): array
    // {
    //     return [
    //         new \Illuminate\Broadcasting\PrivateChannel(
    //             'App.Models.User.'.$this->order->driver_id
    //         ),
    //     ];
    // }
    //
    // public function broadcastAs(): string
    // {
    //     return 'order.status-updated';
    // }
    //
    // public function broadcastWith(object $notifiable): array
    // {
    //     return $this->toArray($notifiable);
    // }
}
