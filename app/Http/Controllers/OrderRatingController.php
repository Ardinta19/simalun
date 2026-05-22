<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderRatingController extends Controller
{
    /**
     * Customer mengirim rating + review setelah order selesai.
     *
     * Aturan:
     *  - Hanya pemilik order yang bisa rating.
     *  - Order harus berstatus 'selesai'.
     *  - Satu order maksimal 1 rating (unique constraint di tabel order_ratings
     *    sudah jadi pengaman, di sini kita cek dulu untuk pesan error yang lebih
     *    ramah daripada exception SQL).
     */
    public function store(Request $request, Order $order)
    {
        abort_if($order->customer_id !== Auth::id(), 403);

        if ($order->status !== 'selesai') {
            return back()->with('error', 'Pesanan ini belum selesai, jadi belum bisa diberi rating.');
        }

        if ($order->rating()->exists()) {
            return back()->with('error', 'Pesanan ini sudah pernah kamu rating.');
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ], [
            'rating.required' => 'Pilih dulu jumlah bintangnya ya.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'comment.max' => 'Komentar terlalu panjang (maks 500 karakter).',
        ]);

        OrderRating::create([
            'order_id' => $order->id,
            'customer_id' => Auth::id(),
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih atas rating dan ulasannya.');
    }
}
