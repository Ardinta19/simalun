<?php

/*
|--------------------------------------------------------------------------
| Bahasa: orders
|--------------------------------------------------------------------------
| String terkait pesanan — status, slot waktu, zona, label umum.
| Dipanggil via __('orders.status.menunggu') dst.
|
| Centralisasi di sini supaya kalau Azka mau ganti istilah (mis.
| 'Sedang Dicuci' -> 'Proses Cuci'), cukup edit satu file.
*/

return [
    'status' => [
        'menunggu' => 'Menunggu',
        'dijemput' => 'Dijemput',
        'dicuci' => 'Sedang Dicuci',
        'disetrika' => 'Disetrika',
        'siap' => 'Siap Dikirim',
        'dikirim' => 'Dalam Pengiriman',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ],

    'pickup_time' => [
        'pagi' => 'Pagi (07.00–11.00)',
        'siang' => 'Siang (11.00–15.00)',
        'sore' => 'Sore (15.00–18.00)',
    ],

    'pickup_time_short' => [
        'pagi' => 'Pagi',
        'siang' => 'Siang',
        'sore' => 'Sore',
    ],

    'zone' => [
        'A' => 'Zona A (0-3 km)',
        'B' => 'Zona B (3-7 km)',
        'C' => 'Zona C (>7 km)',
    ],

    'payment' => [
        'cod' => 'Bayar di Tempat (COD)',
        'cash' => 'Tunai',
        'transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
    ],

    'labels' => [
        'order_code' => 'Kode Pesanan',
        'customer' => 'Pelanggan',
        'driver' => 'Kurir',
        'service' => 'Layanan',
        'pickup_address' => 'Alamat Jemput',
        'pickup_date' => 'Tanggal Jemput',
        'pickup_time' => 'Waktu Jemput',
        'weight' => 'Berat',
        'weight_estimate' => 'Estimasi Berat',
        'weight_actual' => 'Berat Aktual',
        'subtotal' => 'Subtotal',
        'discount' => 'Diskon',
        'pickup_cost' => 'Biaya Jemput',
        'total' => 'Total',
        'voucher_code' => 'Kode Voucher',
        'notes' => 'Catatan',
        'status' => 'Status',
        'paid' => 'Lunas',
        'unpaid' => 'Belum Dibayar',
    ],
];
