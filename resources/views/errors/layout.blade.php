@php $accent = $accent ?? '#0077b6'; $primary = $primary ?? ['url' => url('/'), 'label' => 'Kembali ke Beranda', 'icon' => '🏠']; @endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $code }} – {{ $title }} | Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Nunito',sans-serif;color:#1a2332;background:linear-gradient(168deg,#002f5c 0%,#0077b6 50%,#00b4d8 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;position:relative;overflow:hidden}
body::before,body::after{content:'';position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none}
body::before{width:280px;height:280px;top:-80px;right:-80px}
body::after{width:200px;height:200px;bottom:-60px;left:-60px}
.card{position:relative;z-index:2;background:rgba(255,255,255,.97);border-radius:28px;padding:40px 28px 32px;width:100%;max-width:440px;box-shadow:0 24px 60px rgba(0,29,60,.35);text-align:center;border:1.5px solid rgba(255,255,255,.6)}
.code-pill{display:inline-block;background:{{ $accent }}1a;color:{{ $accent }};font-weight:900;font-size:.78rem;letter-spacing:1.4px;padding:6px 14px;border-radius:999px;text-transform:uppercase;margin-bottom:16px}
.hero-emoji{font-size:4.6rem;margin:6px 0 14px;display:inline-block;animation:wobble 4.5s ease-in-out infinite}
@keyframes wobble{0%,100%{transform:rotate(-4deg) translateY(0)}50%{transform:rotate(4deg) translateY(-6px)}}
.code-big{font-family:'Fredoka One',cursive;font-size:clamp(3.4rem,12vw,4.6rem);color:{{ $accent }};letter-spacing:-1px;margin-bottom:6px}
h1{font-family:'Fredoka One',cursive;font-size:clamp(1.4rem,5vw,1.7rem);color:#002f5c;margin-bottom:10px}
p.lead{font-size:.95rem;font-weight:600;color:#3d5066;line-height:1.55;margin-bottom:24px;max-width:34ch;margin-left:auto;margin-right:auto}
.actions{display:flex;flex-direction:column;gap:10px}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:14px 22px;border-radius:999px;font-weight:900;font-size:.92rem;text-decoration:none;border:none;cursor:pointer;transition:transform .15s}
.btn:active{transform:scale(.97)}
.btn-primary{background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%);color:#fff;box-shadow:0 8px 22px rgba(255,107,53,.45)}
.btn-secondary{background:#f4f8fc;color:#002f5c;border:1.5px solid #ddeeff}
.tip{margin-top:22px;padding-top:18px;border-top:1.5px dashed #e0e8f0;font-size:.78rem;font-weight:700;color:#8899aa}
.brand{margin-top:18px;font-size:.62rem;font-weight:900;color:rgba(255,255,255,.85);letter-spacing:3px;text-transform:uppercase;text-align:center}
</style>
</head>
<body>
<main>
    <div class="card">
        <span class="code-pill">Error {{ $code }}</span>
        <div class="hero-emoji">{{ $emoji }}</div>
        <div class="code-big">{{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p class="lead">{{ $message }}</p>
        <div class="actions">
            <a href="{{ $primary['url'] }}" class="btn btn-primary">{!! $primary['icon'] ?? '🏠' !!} {{ $primary['label'] }}</a>
            @isset($secondary)<a href="{{ $secondary['url'] }}" class="btn btn-secondary">{!! $secondary['icon'] ?? '↩️' !!} {{ $secondary['label'] }}</a>@endisset
        </div>
        @isset($tip)<div class="tip">{!! $tip !!}</div>@endisset
    </div>
    <div class="brand">AZKA LAUNDRY &bull; SIMALUN</div>
</main>
</body>
</html>
