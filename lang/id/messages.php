<?php

/*
|--------------------------------------------------------------------------
| Bahasa: messages
|--------------------------------------------------------------------------
| Pesan flash success/error/warning + pesan validasi custom yang
| muncul di flash session. Bukan validation rule (itu di validation.php),
| tapi pesan hasil aksi.
*/

return [
    'success' => [
        'saved' => 'Data berhasil disimpan.',
        'updated' => 'Data berhasil diperbarui.',
        'deleted' => 'Data berhasil dihapus.',
        'created' => 'Data berhasil dibuat.',
    ],

    'error' => [
        'generic' => 'Terjadi kesalahan. Silakan coba lagi.',
        'unauthorized' => 'Kamu tidak punya akses untuk aksi ini.',
        'not_found' => 'Data tidak ditemukan.',
        'invalid_state' => 'Aksi tidak bisa dilakukan pada status saat ini.',
    ],

    'order' => [
        'created' => 'Pesanan berhasil dibuat.',
        'cancelled' => 'Pesanan berhasil dibatalkan.',
        'cannot_cancel' => 'Pesanan tidak bisa dibatalkan karena sudah dalam proses pencucian.',
        'too_many_active' => 'Kamu masih punya 3 pesanan aktif. Selesaikan dulu salah satunya.',
        'duplicate' => 'Pesanan serupa baru saja kamu buat. Tunggu sebentar sebelum mencoba lagi.',
    ],

    'voucher' => [
        'invalid' => 'Voucher tidak ditemukan atau sudah tidak berlaku.',
        'min_order' => 'Minimum order Rp :amount untuk pakai voucher ini.',
        'used' => 'Voucher sudah pernah dipakai, tidak bisa dihapus. Nonaktifkan saja.',
    ],
];
