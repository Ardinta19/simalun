@php
    $code = 404; $emoji = '🧦'; $title = 'Halaman Tidak Ditemukan'; $accent = '#0077b6';
    $message = 'Halaman yang kamu cari tidak ada. Mungkin URL-nya salah atau sudah dipindahkan.';
    $primary = auth()->check() ? ['url' => route('dashboard'), 'label' => 'Ke Dashboard', 'icon' => '🏠'] : ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    $secondary = ['url' => 'javascript:history.back()', 'label' => 'Halaman Sebelumnya', 'icon' => '↩️'];
    $tip = 'Coba periksa kembali alamat URL yang kamu ketik.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
