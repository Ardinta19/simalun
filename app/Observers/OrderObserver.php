<?php

namespace App\Observers;

use App\Models\Order;
use App\Http\Controllers\FinanceController;
use App\Models\OrderStatusHistory;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Support\Facades\Auth;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status_code' => $order->status,
            'status_note' => 'Pesanan baru berhasil dibuat',
            'updated_by' => Auth::id() ?? $order->customer_id,
        ]);

        // Notifikasi ke Customer
        $order->customer->notify(new OrderStatusUpdated(
            $order,
            'Pesanan Berhasil!',
            'Pesanan #' . $order->order_code . ' telah kami terima dan menunggu penugasan kurir.'
        ));
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Otomasi Pencatatan Keuangan jika status berubah jadi SELESAI
        if ($order->isDirty('status') && $order->status === 'selesai') {
            FinanceController::recordIncomeFromOrder($order);
        }

        // Otomasi Riwayat Status jika status berubah
        if ($order->isDirty('status')) {
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status_code' => $order->status,
                'status_note' => 'Status diperbarui otomatis: ' . $order->status_label,
                'updated_by' => Auth::id() ?? 1,
            ]);

            // Kirim Notifikasi Database sesuai status
            $this->sendNotification($order);
        }
    }

    protected function sendNotification(Order $order): void
    {
        $title = 'Update Pesanan #' . $order->order_code;
        $msg = '';

        switch ($order->status) {
            case 'dijemput':
                $msg = 'Kurir sedang menuju lokasi Anda untuk menjemput pakaian.';
                break;
            case 'dicuci':
                $msg = 'Pakaian Anda sudah masuk ke workshop dan mulai dicuci.';
                break;
            case 'disetrika':
                $msg = 'Proses mencuci selesai, sekarang sedang dalam tahap setrika.';
                break;
            case 'siap':
                $msg = 'Cucian Anda sudah rapi dan siap untuk dikirim kembali.';
                break;
            case 'dikirim':
                $msg = 'Kurir sedang dalam perjalanan mengirim pakaian Anda.';
                break;
            case 'selesai':
                $msg = 'Alhamdulillah! Pesanan telah selesai. Terima kasih telah menggunakan jasa kami.';
                break;
        }

        if ($msg && $order->customer) {
            $order->customer->notify(new OrderStatusUpdated($order, $title, $msg));
        }

        // Notifikasi khusus Driver jika ditugaskan
        if ($order->isDirty('driver_id') && $order->driver) {
            $order->driver->notify(new OrderStatusUpdated(
                $order,
                'Tugas Baru!',
                'Anda mendapat tugas untuk pesanan #' . $order->order_code
            ));
        }
    }
}
