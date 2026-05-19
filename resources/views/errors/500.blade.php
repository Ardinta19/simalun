@php
    $code = 500;
    $emoji = '🫧';
    $title = 'Mesin Kami Sedang Bermasalah';
    $message = 'Lagi ada gangguan di dapur cuci kami. Tim teknis sudah dikabari, coba lagi sebentar lagi ya.';
    $accent = '#ef4444';
    $primary = ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠'];
    $secondary = ['url' => 'javascript:location.reload()', 'label' => 'Coba Muat Ulang', 'icon' => '🔄'];
    $tip = 'Jika masalah terus terjadi, hubungi <code>support@azkalaundry.id</code>.';
@endphp
@include('errors.layout', compact('code','emoji','title','message','accent','primary','secondary','tip'))
