@php
    $code = 500; $emoji = '🫧'; $title = 'Server Error'; $accent = '#ef4444';
    $message = 'Ada gangguan di server kami. Tim teknis sudah dikabari, coba lagi sebentar.';
    $primary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    $secondary = ['url' => 'javascript:location.reload()', 'label' => 'Coba Muat Ulang', 'icon' => '🔄'];
    $tip = 'Jika masalah terus terjadi, hubungi admin Azka Laundry.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
