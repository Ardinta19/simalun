<?php

namespace App\Observers;

use App\Http\Controllers\FinanceController;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    public function created(Order $order): void
    {
        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'status_code' => $order->status,
            'status_note' => 'Pesanan baru berhasil dibuat.',
            'updated_by'  => Auth::id() ?? $order->customer_id,
        ]);

        if ($order->customer) {
            $order->customer->notify(new OrderStatusUpdated(
                $order,
                'Pesanan Berhasil Dibuat',
                'Pesanan #' . $order->order_code . ' telah kami terima dan menunggu penugasan kurir.'
            ));
        }
    }

    public function updated(Order $order): void
    {
        if (!$order->isDirty('status')) {
            $this->notifyDriverIfAssigned($order);
            return;
        }

        // Income recording dihapus dari sini — sudah ditangani di controller
        // (driverAction dan updateStatus) agar timing-nya tepat setelah recalculation.

        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'status_code' => $order->status,
            'status_note' => 'Status diperbarui menjadi: ' . $order->status_label,
            'updated_by'  => Auth::id() ?? $order->customer_id,
        ]);

        $this->notifyCustomerStatus($order);
        $this->notifyDriverIfAssigned($order);
    }

    protected function notifyCustomerStatus(Order $order): void
    {
        $messages = [
            'dijemput'  => 'Kurir sedang menuju lokasi Anda untuk menjemput pakaian.',
            'dicuci'    => 'Pakaian Anda sudah masuk workshop dan mulai dicuci.',
            'disetrika' => 'Cucian selesai, sekarang dalam tahap setrika.',
            'siap'      => 'Cucian Anda sudah rapi dan siap dikirim kembali.',
            'dikirim'   => 'Kurir sedang dalam perjalanan mengirim pakaian Anda.',
            'selesai'   => 'Pesanan telah selesai. Terima kasih sudah menggunakan jasa kami.',
        ];

        $message = $messages[$order->status] ?? null;
        if (!$message || !$order->customer) {
            return;
        }

        $order->customer->notify(new OrderStatusUpdated(
            $order,
            'Update Pesanan #' . $order->order_code,
            $message
        ));
    }

    protected function notifyDriverIfAssigned(Order $order): void
    {
        if (!$order->isDirty('driver_id') || !$order->driver) {
            return;
        }

        $order->driver->notify(new OrderStatusUpdated(
            $order,
            'Tugas Baru',
            'Anda mendapat tugas untuk pesanan #' . $order->order_code
        ));
    }
}
