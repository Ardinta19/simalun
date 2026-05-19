@php
    $code = 401;
    $emoji = '🔑';
    $title = 'Belum Masuk';
    $message = 'Kamu perlu login dulu untuk mengakses halaman ini.';
    $accent = '#0077b6';
    $primary = ['url' => route('login'), 'label' => 'Masuk Sekarang', 'icon' => '🔑'];
    $secondary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary'))
