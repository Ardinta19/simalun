@php
    $code = 419;
    $emoji = '⏳';
    $title = 'Sesi Kamu Sudah Kedaluwarsa';
    $message = 'Halaman ini terlalu lama dibuka tanpa aktivitas. Demi keamanan, coba muat ulang dan kirim ulang.';
    $accent = '#f59e0b';
    $primary = ['url' => 'javascript:location.reload()', 'label' => 'Muat Ulang Halaman', 'icon' => '🔄'];
    $secondary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    $tip = 'Token CSRF kedaluwarsa adalah pelindung dari serangan. Aman, kok.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
