@php
    $code = 404;
    $emoji = '🧦';
    $title = 'Halaman Hilang Saat Diputar';
    $message = 'Sepertinya halaman yang kamu cari ikut tercuci. Yuk balik lagi sebelum kebawa arus.';
    $accent = '#0077b6';
    $primary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    if (auth()->check()) {
        $primary = ['url' => route('dashboard'), 'label' => 'Ke Dashboard Saya', 'icon' => '🏠'];
        $secondary = ['url' => url('/'), 'label' => 'Halaman Utama', 'icon' => '↩️'];
    }
    $tip = 'Salah ketik URL? Coba periksa kembali alamatnya, atau pakai tombol di atas.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
