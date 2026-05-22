<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

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
}
