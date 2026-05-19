@php
    $code = 429;
    $emoji = '🚦';
    $title = 'Pelan-pelan Dulu, Ya!';
    $message = 'Kamu mengirim permintaan terlalu sering. Tunggu sebentar, lalu coba lagi setelah beberapa detik.';
    $accent = '#8B5CF6';
    $primary = ['url' => 'javascript:location.reload()', 'label' => 'Coba Lagi', 'icon' => '🔄'];
    $secondary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    $tip = 'Pembatasan ini menjaga server tetap stabil untuk semua pengguna.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
