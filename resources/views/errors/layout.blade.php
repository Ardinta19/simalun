{{--
    Shared layout for SIMALUN custom error pages.
    Slot variables:
      - $code     : HTTP code displayed (e.g. "404")
      - $emoji    : Hero emoji (e.g. "🧺")
      - $title    : Short headline
      - $message  : Body paragraph
      - $primary  : ['url' => ..., 'label' => ...] (optional)
      - $secondary: ['url' => ..., 'label' => ...] (optional)
      - $accent   : Hex accent for the badge (default brand blue)
--}}
@php
    $accent = $accent ?? '#0077b6';
    $primary = $primary ?? ['url' => url('/'), 'label' => 'Kembali ke Beranda'];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $code }} – {{ $title }} | Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
html,body{min-height:100%;}
body{
    font-family:'Nunito',sans-serif;
    color:#1a2332;
    background:linear-gradient(168deg,#002f5c 0%,#0077b6 50%,#00b4d8 100%);
    min-height:100vh;
    display:flex;align-items:center;justify-content:center;
    padding:24px;position:relative;overflow:hidden;
}
body::before,body::after{content:'';position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none;}
body::before{width:280px;height:280px;top:-80px;right:-80px;}
body::after{width:200px;height:200px;bottom:-60px;left:-60px;}

.bubble{position:absolute;border-radius:50%;background:radial-gradient(circle at 30% 30%,rgba(255,255,255,.55),rgba(255,255,255,.05));border:1.5px solid rgba(255,255,255,.3);pointer-events:none;animation:float 9s ease-in-out infinite;}
.b1{width:60px;height:60px;top:18%;left:14%;animation-delay:0s;}
.b2{width:32px;height:32px;top:32%;left:78%;animation-delay:1.2s;}
.b3{width:48px;height:48px;bottom:22%;right:18%;animation-delay:2.4s;}
.b4{width:24px;height:24px;bottom:30%;left:22%;animation-delay:3.6s;}
@keyframes float{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-22px) scale(1.05);}}

.card{
    position:relative;z-index:2;
    background:rgba(255,255,255,.97);
    border-radius:28px;
    padding:40px 28px 32px;
    width:100%;max-width:440px;
    box-shadow:0 24px 60px rgba(0,29,60,.35);
    text-align:center;
    backdrop-filter:blur(8px);
    border:1.5px solid rgba(255,255,255,.6);
}

.code-pill{
    display:inline-block;
    background:{{ $accent }}1a;
    color:{{ $accent }};
    font-family:'Nunito',sans-serif;
    font-weight:900;font-size:.78rem;
    letter-spacing:1.4px;
    padding:6px 14px;border-radius:999px;
    text-transform:uppercase;
    margin-bottom:16px;
}

.hero-emoji{
    font-size:4.6rem;line-height:1;
    margin:6px 0 14px;
    filter:drop-shadow(0 10px 18px rgba(0,47,92,.18));
    display:inline-block;
    animation:wobble 4.5s ease-in-out infinite;
}
@keyframes wobble{
    0%,100%{transform:rotate(-4deg) translateY(0);}
    50%{transform:rotate(4deg) translateY(-6px);}
}

.code-big{
    font-family:'Fredoka One',cursive;
    font-size:clamp(3.4rem,12vw,4.6rem);
    line-height:1;
    color:{{ $accent }};
    letter-spacing:-1px;
    margin-bottom:6px;
}

h1{
    font-family:'Fredoka One',cursive;
    font-size:clamp(1.4rem,5vw,1.7rem);
    color:#002f5c;
    line-height:1.2;
    margin-bottom:10px;
}
p.lead{
    font-size:.95rem;
    font-weight:600;
    color:#3d5066;
    line-height:1.55;
    margin-bottom:24px;
    max-width:34ch;
    margin-left:auto;margin-right:auto;
}

.actions{display:flex;flex-direction:column;gap:10px;}
.btn{
    display:inline-flex;align-items:center;justify-content:center;gap:8px;
    padding:14px 22px;
    border-radius:999px;
    font-weight:900;font-size:.92rem;
    font-family:'Nunito',sans-serif;
    text-decoration:none;
    border:none;cursor:pointer;
    transition:transform .15s,box-shadow .15s;
}
.btn:active{transform:scale(.97);}
.btn-primary{
    background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%);
    color:#fff;
    box-shadow:0 8px 22px rgba(255,107,53,.45);
}
.btn-primary:hover{box-shadow:0 12px 30px rgba(255,107,53,.6);}
.btn-secondary{
    background:#f4f8fc;
    color:#002f5c;
    border:1.5px solid #ddeeff;
}
.btn-secondary:hover{background:#e0f4ff;}

.tip{
    margin-top:22px;padding-top:18px;
    border-top:1.5px dashed #e0e8f0;
    font-size:.78rem;font-weight:700;
    color:#8899aa;line-height:1.5;
}
.tip code{
    background:#f4f8fc;color:#0077b6;
    padding:2px 8px;border-radius:6px;
    font-family:ui-monospace,'SF Mono',Menlo,monospace;
    font-size:.78rem;font-weight:800;
}

.brand{
    margin-top:18px;
    font-size:.62rem;font-weight:900;
    color:rgba(255,255,255,.85);
    letter-spacing:3px;text-transform:uppercase;
    text-shadow:0 1px 4px rgba(0,0,0,.25);
    text-align:center;
}
</style>
</head>
<body>

<div class="bubble b1"></div>
<div class="bubble b2"></div>
<div class="bubble b3"></div>
<div class="bubble b4"></div>

<main>
    <div class="card" role="alert" aria-live="polite">
        <span class="code-pill">Error {{ $code }}</span>
        <div class="hero-emoji" aria-hidden="true">{{ $emoji }}</div>
        <div class="code-big">{{ $code }}</div>
        <h1>{{ $title }}</h1>
        <p class="lead">{{ $message }}</p>

        <div class="actions">
            <a href="{{ $primary['url'] }}" class="btn btn-primary">
                {!! $primary['icon'] ?? '🏠' !!} {{ $primary['label'] }}
            </a>
            @isset($secondary)
                <a href="{{ $secondary['url'] }}" class="btn btn-secondary">
                    {!! $secondary['icon'] ?? '↩️' !!} {{ $secondary['label'] }}
                </a>
            @endisset
        </div>

        @isset($tip)
            <div class="tip">{!! $tip !!}</div>
        @endisset
    </div>
    <div class="brand">AZKA LAUNDRY • SIMALUN</div>
</main>

</body>
</html>
