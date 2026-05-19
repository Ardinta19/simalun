@php
    $code = 503;
    $emoji = '🧺';
    $title = 'Sedang Pemeliharaan';
    $message = $exception?->getMessage() ?: 'Aplikasi sedang diperbarui supaya makin keren. Mohon kembali beberapa saat lagi.';
    $accent = '#0EA5E9';
    $primary = ['url' => 'javascript:location.reload()', 'label' => 'Cek Lagi Sekarang', 'icon' => '🔄'];
    $tip = 'Status update biasanya hanya beberapa menit.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','tip'))
