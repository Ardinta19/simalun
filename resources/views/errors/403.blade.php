@php
    $code = 403; $emoji = '🔒'; $title = 'Akses Ditolak'; $accent = '#FF6B35';
    $message = $exception?->getMessage() ?: 'Halaman ini bukan untuk akun kamu. Silakan kembali ke area yang sesuai.';
    $primary = auth()->check() ? ['url' => route('dashboard'), 'label' => 'Ke Dashboard', 'icon' => '🏠'] : ['url' => route('login'), 'label' => 'Masuk Dulu', 'icon' => '🔑'];
    $secondary = ['url' => 'javascript:history.back()', 'label' => 'Kembali', 'icon' => '↩️'];
    $tip = 'Jika kamu yakin seharusnya punya akses, hubungi admin.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
