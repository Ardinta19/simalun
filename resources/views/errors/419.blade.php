@php
    $code = 419; $emoji = '⏳'; $title = 'Sesi Kedaluwarsa'; $accent = '#f59e0b';
    $message = 'Halaman ini terlalu lama dibuka tanpa aktivitas. Muat ulang dan coba lagi.';
    $primary = ['url' => 'javascript:location.reload()', 'label' => 'Muat Ulang', 'icon' => '🔄'];
    $secondary = ['url' => url('/'), 'label' => 'Ke Beranda', 'icon' => '🏠'];
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary'))
