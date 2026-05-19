@php
    $code = 503; $emoji = '🧺'; $title = 'Sedang Pemeliharaan'; $accent = '#0EA5E9';
    $message = $exception?->getMessage() ?: 'Aplikasi sedang diperbarui. Mohon kembali beberapa saat lagi.';
    $primary = ['url' => 'javascript:location.reload()', 'label' => 'Cek Lagi', 'icon' => '🔄'];
    $tip = 'Biasanya hanya beberapa menit.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','tip'))
