<?php

namespace App\Support;

use App\Models\Order;

/**
 * Helper WhatsApp untuk skenario "klik tombol → buka chat".
 *
 * Pendekatan ini sengaja tidak pakai API berbayar (Fonnte/Wablas)
 * karena target operasionalnya usaha laundry rumahan — admin / kurir
 * pegang HP sendiri dan tetap mau approve setiap chat secara manual.
 * Tinggal generate URL `wa.me` dengan teks pre-filled saja.
 *
 * Untuk WA toko (customer → admin), gunakan {@see Laundry::waLink()}.
 * Helper ini fokus ke chat ke nomor user spesifik (driver / customer).
 */
class WhatsApp
{
    /**
     * Bangun URL `wa.me` untuk satu nomor + pesan opsional.
     * Mengembalikan null kalau nomor kosong / tidak valid supaya view
     * bisa menyembunyikan tombol tanpa error.
     */
    public static function link(?string $phone, string $message = ''): ?string
    {
        $normalized = static::normalize($phone);
        if (! $normalized) {
            return null;
        }

        $url = "https://wa.me/{$normalized}";

        if ($message !== '') {
            $url .= '?text='.rawurlencode($message);
        }

        return $url;
    }

    /**
     * Normalisasi nomor HP Indonesia ke format internasional tanpa simbol:
     *   "0823-7888-6053" → "62823788860 53" → "6282378886053"
     *   "+62 823 7888 6053" → "6282378886053"
     *   "8237888..." (tanpa awalan) → tetap pakai 62
     *
     * Kalau panjangnya tidak masuk akal (kurang dari 9 atau lebih dari 15 digit)
     * dianggap tidak valid dan dikembalikan null.
     */
    public static function normalize(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if ($digits === '') {
            return null;
        }

        // 0xxxx → 62xxxx
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        // 8xxxx (tanpa awalan) → 628xxxx
        if (str_starts_with($digits, '8')) {
            $digits = '62'.$digits;
        }

        if (strlen($digits) < 9 || strlen($digits) > 15) {
            return null;
        }

        return $digits;
    }

    /**
     * Template pesan dari admin/driver ke customer berdasarkan status order.
     * Bahasanya sengaja casual ramah karena ini chat informal antar manusia,
     * bukan email transaksional formal.
     */
    public static function customerMessage(Order $order, string $context = 'general'): string
    {
        $name = $order->customer->name ?? 'Kak';
        $code = $order->order_code;
        $brand = Laundry::name();

        return match ($context) {
            'pickup' => "Halo Kak {$name}, ini dari {$brand} ya. Saya sedang menuju lokasi untuk jemput cucian pesanan #{$code}. Mohon disiapkan. Terima kasih.",
            'delivery' => "Halo Kak {$name}, pesanan #{$code} sudah selesai dan saya antar sekarang. Mohon ditunggu di lokasi ya.",
            'ready' => "Halo Kak {$name}, kabar baik — pesanan #{$code} sudah siap. Boleh konfirmasi alamat pengantarannya?",
            'confirm' => "Halo Kak {$name}, saya admin {$brand}. Mau konfirmasi pesanan #{$code} ya, ada yang ingin ditanyakan?",
            default => "Halo Kak {$name}, ini dari {$brand} terkait pesanan #{$code}.",
        };
    }

    /**
     * Template pesan customer ke driver (mis. dari halaman tracking).
     */
    public static function driverMessage(Order $order): string
    {
        $code = $order->order_code;

        return "Halo Pak/Bu kurir, saya customer pesanan #{$code}. Mau tanya posisi sekarang dimana ya?";
    }
}
