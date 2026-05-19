@php
    $code = 403;
    $emoji = '🔒';
    $title = 'Akses Ditolak';
    $message = $exception?->getMessage() ?: 'Halaman ini bukan untuk akun kamu. Silakan kembali ke area yang sesuai dengan rolemu.';
    $accent = '#FF6B35';
    if (auth()->check()) {
        $primary = ['url' => route('dashboard'), 'label' => 'Ke Dashboard Saya', 'icon' => '🏠'];
        $secondary = ['url' => 'javascript:history.back()', 'label' => 'Kembali ke Halaman Sebelumnya', 'icon' => '↩️'];
    } else {
        $primary = ['url' => route('login'), 'label' => 'Masuk Dulu', 'icon' => '🔑'];
        $secondary = ['url' => url('/'), 'label' => 'Beranda', 'icon' => '🏠'];
    }
    $tip = 'Jika kamu yakin seharusnya punya akses, hubungi admin Azka Laundry.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
