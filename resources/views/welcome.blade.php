<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Azka Laundry – SIMALUN</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
*, *::before, *::after {
  margin:0; padding:0; box-sizing:border-box;
  -webkit-tap-highlight-color:transparent; user-select:none;
}
html, body { width:100%; height:100%; overflow:hidden; font-family:'Plus Jakarta Sans',sans-serif; background:#001f3f; }

/* STAGE */
#stage {
  position:fixed; inset:0; overflow:hidden;
  background:linear-gradient(168deg, #002f5c 0%, #0077b6 45%, #00b4d8 78%, #48cae4 100%);
  cursor:pointer;
}

/* AMBIENT */
#ambient { position:absolute; inset:0; pointer-events:none; z-index:1; }
#particles { position:absolute; inset:0; pointer-events:none; z-index:3; }

/* WAVES */
#wave-band { position:absolute; bottom:0; left:-5%; right:-5%; height:46%; pointer-events:none; z-index:2; }
.ws { position:absolute; left:0; width:200%; overflow:visible; }
.ws.w1 { bottom:42%; }
.ws.w2 { bottom:38%; opacity:.55; }
.ws.w3 { bottom:34%; opacity:.28; }

/* LOGO ZONE */
#logo-zone {
  position:absolute; top:0; left:0; right:0; height:34%;
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  gap:.4rem; z-index:5; opacity:0; transform:translateY(-24px);
}
#drum-wrap {
  width:clamp(90px,18vw,120px); height:clamp(90px,18vw,120px); position:relative;
}
#drum-wrap::after {
  content:''; position:absolute; inset:-12px; border-radius:50%;
  background:radial-gradient(circle, rgba(255,255,255,.14) 40%, transparent 72%);
  animation:halo 3.5s ease-in-out infinite;
}
@keyframes halo { 0%,100%{transform:scale(1)} 50%{transform:scale(1.09)} }

#drum-spin-ring {
  position:absolute; inset:-6px; border-radius:50%;
  border:2px dashed rgba(255,255,255,.28);
  animation:ring-spin 6s linear infinite; pointer-events:none;
}
@keyframes ring-spin { to { transform:rotate(360deg); } }

#drum-ripple {
  position:absolute; inset:-18px; border-radius:50%;
  border:2px solid rgba(0,180,230,.45); opacity:0; pointer-events:none;
}

#wordmark { text-align:center; }
.wm-name { font-weight:800; font-size:clamp(1.7rem,6.5vw,2.3rem); color:#fff; letter-spacing:-.5px; text-shadow:0 3px 18px rgba(0,0,0,.28); line-height:1; }
.wm-sub  { font-size:clamp(.55rem,2vw,.68rem); font-weight:800; color:rgba(144,224,239,.9); letter-spacing:5px; text-transform:uppercase; margin-top:.25rem; }
.wm-bar  { width:80px; height:2.5px; background:#FF6B35; border-radius:99px; margin:.4rem auto 0; opacity:.75; }

/* CAROUSEL */
#carousel-viewport {
  position:absolute; top:34%; bottom:0; left:0; right:0;
  overflow:hidden; z-index:4;
}
#tape {
  display:flex; flex-direction:row; flex-wrap:nowrap;
  width:300vw; height:100%; will-change:transform;
}

/*   PANEL — flex column, konten dari atas
   padding-bottom dibuat cukup agar tidak
   tertutup oleh #controls (tinggi ~160px)
 */
.panel {
  flex:0 0 100vw; height:100%;
  display:flex; flex-direction:column; align-items:center;
  justify-content:flex-start;
  padding:0.6rem 1.4rem 0;
  padding-bottom:max(158px, 21vh);
  gap:.45rem; position:relative;
  overflow:hidden;
}

/* soft glow */
.panel::before {
  content:''; position:absolute;
  width:280px; height:280px; border-radius:50%;
  background:radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
  top:40%; left:50%; transform:translate(-50%,-50%); pointer-events:none;
}

/*
   PANEL IMAGE WRAPPER — lebar penuh, sudut
   rounded, BUKAN lingkaran
*/
.panel-icon-wrap {
  position:relative;
  width:min(310px,88vw);
  height:clamp(130px, calc(58vh - 210px), 220px); /* ← hitung dari sisa ruang */
  min-height:110px;
  border-radius:16px;
  background:rgba(255,255,255,.08);
  border:1.5px solid rgba(255,255,255,.18);
  box-shadow:0 6px 24px rgba(0,0,0,.2);
  backdrop-filter:blur(4px);
  flex-shrink:0;
  overflow:hidden;
}
.panel-icon-wrap::after { display:none !important; }
/* Hapus ::after sepenuhnya — jangan tambah lagi */

/* gambar mengisi wrapper */
.panel-img {
  width:100%; height:100%;
  object-fit:cover;
  object-position:center center; /* ← kembali ke center */
  display:block;
}

/* nomor badge di pojok kanan atas wrapper */
.panel-num {
  position:absolute; top:8px; right:8px;
  width:22px; height:22px; border-radius:50%;
  background:#FF6B35; color:#fff;
  font-weight:800; font-size:.8rem;
  display:flex; align-items:center; justify-content:center;
  box-shadow:0 2px 8px rgba(255,107,53,.5);
  z-index:2;
}

/* emoji fallback */
.panel-icon {
  font-size:clamp(2.4rem,9vw,3rem); line-height:1; display:block;
  filter:drop-shadow(0 2px 8px rgba(0,0,0,.2));
}

/* ─────────────────────────────────────────────
   BADGE STICKER — letakkan di luar wrapper,
   di atas gambar (absolute relatif ke .panel)
───────────────────────────────────────────── */
.panel-sticker {
  /* relative to .panel */
  position:absolute;
  /* tempatkan tepat di pojok kanan atas gambar */
  top:calc(0.5rem - 10px);   /* sejajar top panel + offset */
  right:calc( (100% - min(310px,88vw)) / 2 - 10px );
  background:rgba(255,107,53,.92); color:#fff;
  font-weight:800; font-size:.65rem;
  padding:.22rem .6rem; border-radius:6px; letter-spacing:.5px;
  box-shadow:0 3px 10px rgba(255,107,53,.4);
  transform:rotate(3deg);
  z-index:6; pointer-events:none;
}

.panel h2 {
  font-weight:800; font-size:clamp(1.35rem,5.5vw,1.75rem);
  color:#fff; text-align:center; line-height:1.2;
  text-shadow:0 3px 14px rgba(0,0,0,.22);
  margin-top:.1rem;
}
.panel p {
  font-size:clamp(.82rem,3.2vw,.94rem); font-weight:600;
  color:rgba(255,255,255,.88); text-align:center; line-height:1.6;
  max-width:min(280px,80vw); text-shadow:0 2px 8px rgba(0,0,0,.18);
}
.panel-tags {
  display:flex; gap:.5rem; flex-wrap:wrap; justify-content:center;
  margin-top:.15rem;
}
.tag {
  background:rgba(255,255,255,.13); border:1px solid rgba(255,255,255,.22);
  color:rgba(255,255,255,.9); font-size:.67rem; font-weight:800;
  padding:.25rem .65rem; border-radius:99px; letter-spacing:.3px;
  backdrop-filter:blur(4px);
}

/* progress bar */
#progress-bar-wrap {
  width:min(200px,55vw); height:4px;
  background:rgba(255,255,255,.2); border-radius:99px; overflow:hidden;
}
#progress-bar-fill {
  height:100%; border-radius:99px;
  background:linear-gradient(90deg,#FF6B35 0%,#ffaa80 100%);
  box-shadow:0 0 8px rgba(255,107,53,.6); width:33.33%;
  transition:width .45s cubic-bezier(.4,0,.2,1);
}

/* ── CONTROLS ── */
#controls {
  position:absolute; bottom:0; left:0; right:0;
  display:flex; flex-direction:column; align-items:center;
  gap:.45rem; padding-top:.7rem;
  padding-bottom:max(env(safe-area-inset-bottom,0px),16px);
  background:linear-gradient(0deg,rgba(0,40,90,.75) 0%,transparent 100%);
  z-index:10; opacity:0;
}

/* slogan pill */
#slogan-bubble {
  background:rgba(255,255,255,.10); border:1px solid rgba(255,255,255,.22);
  backdrop-filter:blur(8px); border-radius:99px; padding:.35rem 1rem;
  display:flex; align-items:center; gap:.5rem;
  box-shadow:0 4px 16px rgba(0,0,0,.12), inset 0 1px 0 rgba(255,255,255,.15);
}
#slogan-bubble::before { content:'💧'; font-size:.82rem; animation:drip 2.4s ease-in-out infinite; }
#slogan-bubble::after  { content:'💧'; font-size:.72rem; animation:drip 2.4s ease-in-out infinite .6s; }
@keyframes drip { 0%,100%{transform:translateY(0) scale(1);opacity:.8} 50%{transform:translateY(3px) scale(.9);opacity:1} }
#slogan {
  font-size:clamp(.58rem,1.9vw,.68rem); font-style:italic; font-weight:800;
  color:rgba(255,255,255,.9); letter-spacing:.3px; text-align:center; white-space:nowrap;
  text-shadow:0 1px 4px rgba(0,0,0,.2);
}
#dots { display:flex; gap:7px; align-items:center; }
.dot {
  width:7px; height:7px; border-radius:50%; background:rgba(255,255,255,.4); cursor:pointer;
  transition:width .35s cubic-bezier(.4,0,.2,1), background .35s;
}
.dot.on { width:26px; border-radius:99px; background:#fff; box-shadow:0 0 10px rgba(255,255,255,.55); }

#btn-next {
  display:inline-flex; align-items:center; gap:.5rem; padding:.8rem 2.1rem;
  background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%);
  color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
  font-size:clamp(.88rem,3.4vw,.98rem); border:none; border-radius:99px; cursor:pointer;
  box-shadow:0 8px 22px rgba(255,107,53,.45),0 2px 6px rgba(0,0,0,.15),inset 0 1px 0 rgba(255,255,255,.2);
  transition:transform .15s, box-shadow .15s; letter-spacing:.3px; position:relative; overflow:hidden;
}
#btn-next::before {
  content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
  animation:btn-shimmer 2.8s ease-in-out infinite;
}
@keyframes btn-shimmer { 0%{left:-100%} 60%,100%{left:160%} }
#btn-next:active { transform:scale(.95); }
#btn-next:hover  { box-shadow:0 12px 30px rgba(255,107,53,.6); }
.btn-arr { transition:transform .2s; }
#btn-next:hover .btn-arr { transform:translateX(4px); }

#btn-skip {
  background:none; border:none; color:rgba(255,255,255,.5);
  font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.78rem;
  letter-spacing:.5px; cursor:pointer; padding:.25rem .9rem; transition:color .2s;
}
#btn-skip:hover { color:rgba(255,255,255,.88); }

/* soap word bubbles */
.soap-bubble {
  position:absolute; display:flex; align-items:center; justify-content:center;
  border-radius:50%; font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
  text-align:center; pointer-events:none; line-height:1.2;
  border:1.5px solid rgba(255,255,255,.4);
  background:radial-gradient(circle at 30% 28%,rgba(255,255,255,.7),rgba(255,255,255,.08));
  box-shadow:inset -4px -4px 12px rgba(0,120,180,.25),inset 2px 2px 8px rgba(255,255,255,.4),0 4px 16px rgba(0,0,0,.1);
}

/* touch pulse ring */
#touch-pulse {
  position:absolute; inset:-22px; border-radius:50%;
  border:3px solid rgba(255,255,255,.7);
  pointer-events:none; opacity:0;
}

/* ═══════════════════════════════════════
   CINEMATIC EXIT OVERLAY
═══════════════════════════════════════ */
#exit-overlay {
  position:fixed; inset:0; z-index:999;
  pointer-events:none; opacity:0; overflow:hidden;
}
#exit-bg {
  position:absolute; inset:0;
  background:radial-gradient(ellipse at 50% 60%, #0096c7 0%, #023e8a 50%, #03045e 100%);
  opacity:0;
}
#exit-water {
  position:absolute; left:0; right:0; bottom:0; height:0;
  background:linear-gradient(0deg,#023e8a 0%,#0077b6 40%,#00b4d8 80%,rgba(0,180,216,.6) 100%);
}
#exit-wave { position:absolute; left:-5%; right:-5%; bottom:0; opacity:0; pointer-events:none; }
#exit-drum-wrap {
  position:absolute; top:50%; left:50%;
  transform:translate(-50%,-50%) scale(0); opacity:0;
}
#exit-drum-svg { filter:drop-shadow(0 0 32px rgba(0,200,255,.8)); }
.vortex-ring {
  position:absolute; border-radius:50%; border:2px solid rgba(255,255,255,.3);
  top:50%; left:50%; transform:translate(-50%,-50%) scale(0);
  opacity:0; pointer-events:none;
}
#exit-particles { position:absolute; inset:0; pointer-events:none; }
#exit-text {
  position:absolute; bottom:22%; left:0; right:0;
  text-align:center; opacity:0;
  font-weight:800; font-size:1.3rem; color:rgba(255,255,255,.9);
  letter-spacing:3px;
  text-shadow:0 0 20px rgba(72,202,228,.9),0 2px 8px rgba(0,0,0,.4);
}
#exit-flash { position:absolute; inset:0; background:#fff; opacity:0; pointer-events:none; }
.exit-drop {
  position:absolute;
  border-radius:50% 50% 60% 60% / 40% 40% 60% 60%;
  background:rgba(255,255,255,.7); pointer-events:none;
}
</style>
</head>
<body>
<div id="stage">

  <div id="ambient"   aria-hidden="true"></div>
  <div id="particles" aria-hidden="true"></div>

  <!-- Waves -->
  <div id="wave-band" aria-hidden="true">
    <svg class="ws w1" viewBox="0 0 1440 64" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,.20)" d="M0,32 C200,64 400,0 600,32 C800,64 1000,0 1200,32 C1320,48 1400,20 1440,32 L1440,64 L0,64Z"/>
    </svg>
    <svg class="ws w2" viewBox="0 0 1440 64" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,.14)" d="M0,16 C180,46 360,4 540,24 C720,44 900,8 1080,28 C1260,48 1380,12 1440,22 L1440,64 L0,64Z"/>
    </svg>
    <svg class="ws w3" viewBox="0 0 1440 64" preserveAspectRatio="none">
      <path fill="rgba(255,255,255,.08)" d="M0,44 C160,12 320,52 480,36 C640,20 800,50 960,32 C1120,14 1300,46 1440,30 L1440,64 L0,64Z"/>
    </svg>
  </div>

  <!-- Logo -->
  <div id="logo-zone">
    <div id="drum-wrap">
      <div id="drum-spin-ring"></div>
      <div id="drum-ripple"></div>
      <div id="touch-pulse"></div>
      <svg id="drum-svg" width="100%" viewBox="0 0 148 148" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="74" cy="74" r="70" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.18)" stroke-width="1.5"/>
        <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.14)" stroke="rgba(255,255,255,.35)" stroke-width="1.8"/>
        <rect x="16" y="26" width="116" height="30" rx="16" fill="rgba(255,255,255,.2)"/>
        <circle cx="36" cy="41" r="7" fill="#FF6B35"/>
        <circle cx="56" cy="41" r="7" fill="#00C48C"/>
        <circle cx="112" cy="41" r="5" fill="rgba(255,255,255,.45)"/>
        <circle cx="124" cy="41" r="5" fill="rgba(255,255,255,.28)"/>
        <circle cx="74" cy="90" r="38" fill="rgba(0,100,160,.55)" stroke="rgba(255,255,255,.45)" stroke-width="2.5"/>
        <circle cx="74" cy="90" r="31" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="2" stroke-dasharray="6 5"/>
        <circle cx="74" cy="90" r="22" fill="rgba(255,255,255,.16)" stroke="rgba(255,255,255,.6)" stroke-width="2.5"/>
        <path d="M63 88 Q74 78 85 88 L82 100 H66Z" fill="white" opacity=".9"/>
        <path d="M63 88 L57 80 L66 75 L71 82" fill="white" opacity=".9"/>
        <path d="M85 88 L91 80 L82 75 L77 82" fill="white" opacity=".9"/>
        <circle cx="90" cy="70" r="6" fill="#FF6B35"/>
        <circle cx="80" cy="62" r="4.5" fill="#00A8E8" opacity=".85"/>
        <circle cx="95" cy="60" r="3.5" fill="#FF6B35" opacity=".6"/>
        <circle cx="85" cy="56" r="2.5" fill="#00C48C" opacity=".75"/>
        <circle cx="89" cy="69" r="2" fill="white" opacity=".55"/>
      </svg>
    </div>
    <div id="wordmark">
      <div class="wm-name">Azka Laundry</div>
      <div class="wm-sub">LAUNDRY&nbsp;&nbsp;•&nbsp;&nbsp;SIMALUN</div>
      <div class="wm-bar"></div>
    </div>
  </div>

  <!-- Carousel -->
  <div id="carousel-viewport">
    <div id="tape">

      <!-- SLIDE 1 -->
      <div class="panel">
        <span class="panel-sticker">NEW ✦</span>
        <div class="panel-icon-wrap">
          <img class="panel-img" src="{{ asset('images/slide1.png') }}" alt="Pesan dari Rumah"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <span class="panel-icon" style="display:none">🧺</span>
          <span class="panel-num">1</span>
        </div>
        <h2>Pesan dari Rumah</h2>
        <p>Tinggal pesan, driver kami langsung jemput cucianmu sampai depan pintu.</p>
        <div class="panel-tags">
          <span class="tag">📍 Jemput Lokasi</span>
          <span class="tag">⚡ Cepat &amp; Mudah</span>
        </div>
      </div>

      <!-- SLIDE 2 -->
      <div class="panel">
        <span class="panel-sticker">PREMIUM ✦</span>
        <div class="panel-icon-wrap">
          <img class="panel-img" src="{{ asset('images/slide2.png') }}" alt="Cuci Bersih"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <span class="panel-icon" style="display:none">✨</span>
          <span class="panel-num">2</span>
        </div>
        <h2>Cuci Bersih &amp; Wangi</h2>
        <p>Deterjen premium &amp; pewangi tahan lama — pakaian kembali segar seperti baru keluar toko.</p>
        <div class="panel-tags">
          <span class="tag">🌸 Wangi Tahan Lama</span>
          <span class="tag">💎 Deterjen Premium</span>
        </div>
      </div>

      <!-- SLIDE 3 -->
      <div class="panel">
        <span class="panel-sticker">LIVE ✦</span>
        <div class="panel-icon-wrap">
          <img class="panel-img" src="{{ asset('images/slide3.png') }}" alt="Pantau Real-Time"
               onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
          <span class="panel-icon" style="display:none">📍</span>
          <span class="panel-num">3</span>
        </div>
        <h2>Pantau Status Real-Time</h2>
        <p>Lacak tahapan cucian kapan saja. Driver antar langsung, bayar di tempat — semudah itu.</p>
        <div class="panel-tags">
          <span class="tag">💬 Notifikasi WhatsApp</span>
          <span class="tag">💵 Bayar di Tempat</span>
        </div>
      </div>

    </div>
  </div>

  <!-- Controls -->
  <div id="controls">
    <div id="slogan-bubble">
      <span id="slogan">Budayakan Malas Nyuci, Karena Itu Pekerjaan Kami</span>
    </div>
    <div id="dots">
      <span class="dot on" data-i="0"></span>
      <span class="dot"    data-i="1"></span>
      <span class="dot"    data-i="2"></span>
    </div>
    <div id="progress-bar-wrap">
      <div id="progress-bar-fill"></div>
    </div>
    <button id="btn-next">
      <span id="btn-label">Mulai Sekarang</span>
      <svg class="btn-arr" width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path id="btn-path" d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </button>
    <button id="btn-skip">Lewati</button>
  </div>

  <!-- slide-change flash -->
  <div id="flash-div" style="position:fixed;inset:0;background:#fff;opacity:0;pointer-events:none;z-index:50;"></div>

  <!-- EXIT OVERLAY -->
  <div id="exit-overlay">
    <div id="exit-bg"></div>
    <div id="exit-water"></div>
    <svg id="exit-wave" viewBox="0 0 420 60" preserveAspectRatio="none" style="height:60px;">
      <path id="exit-wave-path" fill="rgba(255,255,255,.25)"
        d="M0,30 C60,10 120,50 180,30 C240,10 300,50 360,30 C390,15 410,22 420,30 L420,60 L0,60Z"/>
    </svg>
    <div class="vortex-ring" id="vr1" style="width:80px;height:80px;"></div>
    <div class="vortex-ring" id="vr2" style="width:140px;height:140px;border-color:rgba(255,255,255,.2);"></div>
    <div class="vortex-ring" id="vr3" style="width:220px;height:220px;border-color:rgba(255,255,255,.12);"></div>
    <div class="vortex-ring" id="vr4" style="width:320px;height:320px;border-color:rgba(255,255,255,.07);"></div>
    <div id="exit-drum-wrap">
      <svg id="exit-drum-svg" width="160" height="160" viewBox="0 0 148 148" fill="none">
        <circle cx="74" cy="74" r="70" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.3)" stroke-width="2"/>
        <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.18)" stroke="rgba(255,255,255,.4)" stroke-width="2"/>
        <rect x="16" y="26" width="116" height="30" rx="16" fill="rgba(255,255,255,.28)"/>
        <circle cx="36" cy="41" r="7" fill="#FF6B35"/>
        <circle cx="56" cy="41" r="7" fill="#00C48C"/>
        <circle cx="74" cy="90" r="38" fill="rgba(0,100,160,.6)" stroke="rgba(255,255,255,.5)" stroke-width="2.5"/>
        <circle cx="74" cy="90" r="31" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="2" stroke-dasharray="6 5"/>
        <circle cx="74" cy="90" r="22" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.7)" stroke-width="2.5"/>
        <path d="M63 88 Q74 78 85 88 L82 100 H66Z" fill="white" opacity=".95"/>
        <path d="M63 88 L57 80 L66 75 L71 82" fill="white" opacity=".95"/>
        <path d="M85 88 L91 80 L82 75 L77 82" fill="white" opacity=".95"/>
      </svg>
    </div>
    <div id="exit-particles"></div>
    <div id="exit-text">Memuat Dashboard...</div>
    <div id="exit-flash"></div>
  </div>

</div><!-- #stage -->

<script>
document.addEventListener('DOMContentLoaded', function () {

/* ═══════════════════════════════════════════════════════════
   DRUM ROTATION SYSTEM
═══════════════════════════════════════════════════════════ */
const DRUM_BASE = 18;
let drumTween  = null;
let isTouching = false;
let touchTimer = null;
let pulseTween = null;

function startDrum() {
  if (drumTween) drumTween.kill();
  drumTween = gsap.to('#drum-svg', {
    rotation: '+=360', duration: DRUM_BASE,
    ease: 'none', repeat: -1, transformOrigin: '50% 50%',
  });
}

function drumAccelerate() {
  const t = drumTween; if (!t) return;
  if (pulseTween) pulseTween.kill();
  pulseTween = gsap.to(t, { timeScale: 6, duration: .3, ease: 'power2.out' });
}

function drumDecelerate() {
  const t = drumTween; if (!t) return;
  if (pulseTween) pulseTween.kill();
  pulseTween = gsap.to(t, { timeScale: 1, duration: 1.4, ease: 'power2.inOut' });
}

function pulseDrumFast() {
  drumAccelerate();
  gsap.killTweensOf('#drum-ripple');
  gsap.fromTo('#drum-ripple',
    { opacity: 0 },
    { opacity: .85, duration: .18, ease: 'power2.out', yoyo: true, repeat: 1,
      onComplete() { gsap.set('#drum-ripple', { opacity: 0 }); }
    }
  );
  gsap.killTweensOf('#touch-pulse');
  gsap.fromTo('#touch-pulse',
    { scale: 0.6, opacity: 0.9 },
    { scale: 2.2, opacity: 0, duration: .65, ease: 'power2.out' }
  );
  clearTimeout(touchTimer);
  touchTimer = setTimeout(() => { if (!isTouching) drumDecelerate(); }, 650);
}

startDrum();

document.getElementById('stage').addEventListener('pointerdown', function () {
  isTouching = true; pulseDrumFast();
}, { passive: true });

document.getElementById('stage').addEventListener('pointerup', function () {
  isTouching = false;
  clearTimeout(touchTimer);
  touchTimer = setTimeout(() => drumDecelerate(), 300);
}, { passive: true });

/* ═══════════════════════════════════════════════════════════
   AMBIENT WATER BUBBLES
═══════════════════════════════════════════════════════════ */
(function spawnBubbles() {
  const amb = document.getElementById('ambient');
  const VH  = window.innerHeight;
  const cfg = [
    [8,.7],[14,.5],[6,.8],[20,.42],[10,.65],[5,.85],
    [16,.5],[9,.72],[24,.38],[7,.78],[12,.6],[18,.46],
    [5,.88],[11,.68],[22,.4],[6,.82],[15,.55],[8,.74],
  ];
  cfg.forEach(([base, al], i) => {
    const sz = base + Math.random() * 6;
    const el = document.createElement('div');
    el.style.cssText = [
      'position:absolute',`width:${sz}px`,`height:${sz}px`,'border-radius:50%',
      'background:radial-gradient(circle at 30% 28%,rgba(255,255,255,.88),rgba(255,255,255,.1))',
      'border:1.5px solid rgba(255,255,255,.4)',
      `left:${3+Math.random()*93}%`,`top:${VH+sz+10}px`,
      'opacity:0','pointer-events:none',
    ].join(';');
    amb.appendChild(el);
    const rise = 10 + Math.random()*14;
    const wobX = (Math.random()-.5)*50;
    const delay = .8 + i*.1 + Math.random()*3;
    gsap.to(el, { opacity:al, duration:.5, delay:.8+i*.1 });
    gsap.fromTo(el, { y:0 }, {
      y:-(VH*1.3), x:wobX, duration:rise, ease:'none', repeat:-1, delay,
      onRepeat() { gsap.set(el, { y:0, x:0, left:(3+Math.random()*93)+'%' }); }
    });
    gsap.to(el, {
      x:`+=${(Math.random()-.5)*28}`, duration:4+Math.random()*3,
      yoyo:true, repeat:-1, ease:'sine.inOut', delay:Math.random()*4
    });
  });
})();

/* ═══════════════════════════════════════════════════════════
   SOAP WORD BUBBLES
═══════════════════════════════════════════════════════════ */
(function spawnSoapBubbles() {
  const container = document.getElementById('ambient');
  const VH = window.innerHeight;
  const words  = ['Malas','Nyuci','Kami','Bersih','Wangi','Siap!','Antar','Cuci'];
  const colors = ['rgba(0,164,218,.18)','rgba(255,107,53,.13)','rgba(0,196,140,.13)','rgba(255,255,255,.08)'];
  for (let i = 0; i < 6; i++) {
    const sz = 48 + Math.random()*36;
    const el = document.createElement('div');
    el.className = 'soap-bubble';
    el.textContent = words[i % words.length];
    el.style.cssText = [
      `width:${sz}px`,`height:${sz}px`,
      `font-size:${Math.max(8, sz*.19)}px`,
      `color:rgba(255,255,255,${.55+Math.random()*.25})`,
      `left:${5+Math.random()*85}%`,`top:${VH+sz}px`,
      'opacity:0',
      `background:radial-gradient(circle at 28% 28%, rgba(255,255,255,.55), ${colors[i%colors.length]})`,
    ].join(';');
    container.appendChild(el);
    const rise = 18 + Math.random()*16;
    const delay = 3 + i*1.8 + Math.random()*4;
    gsap.to(el, { opacity:.75, duration:1, delay });
    gsap.fromTo(el, { y:0 }, {
      y:-(VH*1.4), duration:rise, ease:'none', repeat:-1, delay,
      onRepeat() {
        el.textContent = words[Math.floor(Math.random()*words.length)];
        gsap.set(el, { y:0, left:(5+Math.random()*85)+'%' });
      }
    });
    gsap.to(el, { x:(Math.random()-.5)*60, duration:5+Math.random()*4, yoyo:true, repeat:-1, ease:'sine.inOut', delay:Math.random()*5 });
    gsap.to(el, { scale:1.08, duration:2+Math.random()*1.5, yoyo:true, repeat:-1, ease:'sine.inOut', delay:Math.random()*3 });
  }
})();

/* ── SPARKLE BURST ── */
function burstParticles(x, y) {
  const pCont = document.getElementById('particles');
  const colors = ['#FF6B35','#00C48C','#00A8E8','#fff','#FFD166'];
  for (let i = 0; i < 14; i++) {
    const el = document.createElement('div');
    const sz = 4 + Math.random()*6;
    el.style.cssText = [
      `width:${sz}px`,`height:${sz}px`,
      `background:${colors[Math.floor(Math.random()*colors.length)]}`,
      `left:${x}px`,`top:${y}px`,
      'position:absolute','border-radius:50%','opacity:1','pointer-events:none',
    ].join(';');
    pCont.appendChild(el);
    const angle = (Math.random()*360)*(Math.PI/180);
    const dist  = 40 + Math.random()*80;
    gsap.to(el, {
      x:Math.cos(angle)*dist, y:Math.sin(angle)*dist,
      opacity:0, scale:0, duration:.55+Math.random()*.35, ease:'power2.out',
      onComplete() { el.remove(); }
    });
  }
}

/* ── WAVES ── */
document.querySelectorAll('.ws').forEach((w,i)=>{
  gsap.fromTo(w,{x:'0%'},{x:'-50%',duration:14+i*4,ease:'none',repeat:-1});
  gsap.to(w,{y:i%2?5:-5,duration:3+i*1.5,ease:'sine.inOut',yoyo:true,repeat:-1});
});

/* ═══════════════════════════════════════════════════════════
   INTRO TIMELINE
═══════════════════════════════════════════════════════════ */
document.querySelectorAll('.panel-icon-wrap,.panel h2,.panel p,.panel-tags,.panel-sticker')
  .forEach(el => gsap.set(el, { opacity:0, y:20 }));

const intro = gsap.timeline({ onComplete: afterIntro });
intro
  .to('#logo-zone',    { opacity:1, y:0, duration:1, ease:'elastic.out(.75,.65)' }, .25)
  .fromTo('#drum-wrap',{scale:.65,opacity:0},{scale:1,opacity:1,duration:.75,ease:'back.out(2.2)'},.25)
  .fromTo('#wordmark', {y:20,opacity:0},{y:0,opacity:1,duration:.55,ease:'power3.out'},.88)
  .fromTo('.wm-bar',   {scaleX:0},{scaleX:1,duration:.5,ease:'power2.out',transformOrigin:'left'},1.06)
  .to('#controls',     {opacity:1,duration:.5,ease:'power2.out'},1.2);

function afterIntro() { revealPanel(0, 1); }

/* ── PANEL REVEAL / HIDE ── */
function revealPanel(idx, dir) {
  const p = document.querySelectorAll('.panel')[idx];
  const els = [
    p.querySelector('.panel-icon-wrap'),
    p.querySelector('h2'),
    p.querySelector('p'),
    p.querySelector('.panel-tags'),
    p.querySelector('.panel-sticker')
  ].filter(Boolean);
  gsap.set(els, { opacity:0, y:28, x:dir*24, scale:.92 });
  gsap.to(els,  { opacity:1, y:0, x:0, scale:1, duration:.5, stagger:.07, ease:'back.out(1.4)', delay:.04 });
}
function hidePanel(idx, dir) {
  const p = document.querySelectorAll('.panel')[idx];
  const els = [
    p.querySelector('.panel-icon-wrap'),
    p.querySelector('h2'),
    p.querySelector('p'),
    p.querySelector('.panel-tags'),
    p.querySelector('.panel-sticker')
  ].filter(Boolean);
  gsap.to(els, { opacity:0, y:-20, x:-dir*24, scale:.94, duration:.28, stagger:.04, ease:'power2.in' });
}

/* ═══════════════════════════════════════════════════════════
   CAROUSEL ENGINE
═══════════════════════════════════════════════════════════ */
const TOTAL   = 3;
const getVW   = () => window.innerWidth;
const tape    = document.getElementById('tape');
const dots    = document.querySelectorAll('.dot');
const btnNext = document.getElementById('btn-next');
const btnSkip = document.getElementById('btn-skip');
const btnLabel= document.getElementById('btn-label');
const btnPath = document.getElementById('btn-path');

let cur = 0;
let busy = false;
let exitTriggered = false;

function slideX(i) { return -(i * getVW()); }

function snapTo(i, onDone) {
  busy = true;
  gsap.to(tape, {
    x: slideX(i), duration:.5, ease:'power3.inOut',
    onComplete() { busy = false; if (onDone) onDone(); }
  });
}

function flashTransition() {
  const fl = document.getElementById('flash-div');
  gsap.fromTo(fl, { opacity: 0 }, { opacity: .18, duration: .1, yoyo: true, repeat: 1, ease: 'none' });
}

function goTo(next) {
  if (busy || next === cur || next < 0 || next >= TOTAL) return;
  const dir = next > cur ? 1 : -1;
  const prev = cur;
  cur = next;
  hidePanel(prev, dir);
  flashTransition();
  burstParticles(window.innerWidth/2, window.innerHeight*.72);
  drumAccelerate();
  setTimeout(() => drumDecelerate(), 500);
  snapTo(cur, () => { updateDots(); updateBtn(); revealPanel(cur, dir); });
}

function updateDots() {
  dots.forEach((d,i) => d.classList.toggle('on', i===cur));
  document.getElementById('progress-bar-fill').style.width = ((cur+1)/TOTAL*100)+'%';
}
function updateBtn() {
  if (cur === TOTAL-1) {
    btnLabel.textContent = 'Masuk Sekarang';
    btnPath.setAttribute('d','M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M15 12H3');
    btnSkip.style.visibility = 'hidden';
  } else {
    btnLabel.textContent = cur===0 ? 'Mulai Sekarang' : 'Lanjut';
    btnPath.setAttribute('d','M5 12h14M12 5l7 7-7 7');
    btnSkip.style.visibility = 'visible';
  }
}

gsap.set(tape, { x:slideX(0) });
dots.forEach((d,i) => d.addEventListener('click',()=>goTo(i)));
btnNext.addEventListener('click', () => { if (cur < TOTAL-1) goTo(cur+1); else exitToLogin(); });
btnSkip.addEventListener('click', exitToLogin);

/* ── SWIPE / DRAG ── */
let pStart=null, tapePx=0, dragging=false, moved=false;

function dragStart(cx) {
  if (busy||exitTriggered) return;
  pStart=cx; tapePx=parseFloat(gsap.getProperty(tape,'x'))||slideX(cur);
  dragging=true; moved=false;
}
function dragMove(cx) {
  if (!dragging||busy) return;
  const dx=cx-pStart;
  if (Math.abs(dx)>4) moved=true;
  const raw=tapePx+dx;
  const minX=slideX(TOTAL-1), maxX=slideX(0);
  let newX=raw;
  if (raw>maxX) newX=maxX+(raw-maxX)*.22;
  if (raw<minX) newX=minX+(raw-minX)*.22;
  gsap.set(tape, { x:newX });
}
function dragEnd(cx) {
  if (!dragging) return;
  dragging=false;
  if (!moved) return;
  const dx=cx-pStart;
  const thr=getVW()*.18;
  if (dx<-thr) {
    if (cur<TOTAL-1) {
      const prev=cur; cur++;
      hidePanel(prev,1); flashTransition();
      burstParticles(window.innerWidth/2,window.innerHeight*.72);
      drumAccelerate(); setTimeout(()=>drumDecelerate(),500);
      snapTo(cur,()=>{updateDots();updateBtn();revealPanel(cur,1);});
    } else {
      if (exitTriggered) return;
      snapTo(cur,()=>exitToLogin());
    }
  } else if (dx>thr&&cur>0) {
    const prev=cur; cur--;
    hidePanel(prev,-1); flashTransition();
    burstParticles(window.innerWidth/2,window.innerHeight*.72);
    drumAccelerate(); setTimeout(()=>drumDecelerate(),500);
    snapTo(cur,()=>{updateDots();updateBtn();revealPanel(cur,-1);});
  } else {
    snapTo(cur);
  }
}

const stage=document.getElementById('stage');
stage.addEventListener('touchstart',  e=>dragStart(e.touches[0].clientX),         {passive:true});
stage.addEventListener('touchmove',   e=>dragMove(e.touches[0].clientX),           {passive:true});
stage.addEventListener('touchend',    e=>dragEnd(e.changedTouches[0].clientX),     {passive:true});
stage.addEventListener('touchcancel', ()=>{if(dragging){snapTo(cur);dragging=false;}},{passive:true});
stage.addEventListener('mousedown',   e=>{dragStart(e.clientX);},                  {passive:true});
window.addEventListener('mousemove',  e=>dragMove(e.clientX));
window.addEventListener('mouseup',    e=>dragEnd(e.clientX));
window.addEventListener('resize',     ()=>gsap.set(tape,{x:slideX(cur)}));

/* ═══════════════════════════════════════════════════════════
   CINEMATIC EXIT
═══════════════════════════════════════════════════════════ */
function exitToLogin() {
  if (exitTriggered) return;
  exitTriggered = true;
  busy = true;

  if (drumTween) { drumTween.kill(); drumTween = null; }

  const overlay  = document.getElementById('exit-overlay');
  const exitBg   = document.getElementById('exit-bg');
  const exitWtr  = document.getElementById('exit-water');
  const exitWave = document.getElementById('exit-wave');
  const wavePath = document.getElementById('exit-wave-path');
  const drumWrap = document.getElementById('exit-drum-wrap');
  const exitDrum = document.getElementById('exit-drum-svg');
  const exitTxt  = document.getElementById('exit-text');
  const exitFlsh = document.getElementById('exit-flash');
  const rings    = document.querySelectorAll('.vortex-ring');

  gsap.set(overlay, { pointerEvents:'all' });

  const tl = gsap.timeline();

  tl.to(['#logo-zone','#carousel-viewport','#controls'], {
    opacity:0, scale:.95, y:-14, duration:.35, stagger:.06, ease:'power2.in'
  }, 0)
  .to(overlay, { opacity:1, duration:.28, ease:'power2.out' }, .2)
  .to(exitBg,  { opacity:1, duration:.5,  ease:'power2.out' }, .2)
  .to(exitWtr, { height:'115%', duration:1.1, ease:'power2.inOut', borderRadius:'55% 55% 0 0 / 35px 35px 0 0' }, .3)
  .to(exitWave, { opacity:1, bottom:'112%', duration:1.1, ease:'power2.inOut' }, .3)
  .to(wavePath, {
    attr:{ d:'M0,18 C60,52 120,8 180,28 C240,52 300,8 360,28 C390,44 410,36 420,18 L420,60 L0,60Z' },
    duration:.4, ease:'sine.inOut', yoyo:true, repeat:3
  }, .3)
  .call(()=>spawnExitDrops(10), [], .55)
  .to(drumWrap, { opacity:1, scale:1, duration:.5, ease:'back.out(1.8)' }, 1.0)
  .to(exitDrum, { rotation:1800, duration:1.9, ease:'power2.in', transformOrigin:'50% 50%' }, 1.0)
  .to([...rings].reverse(), { scale:1, opacity:1, duration:.38, stagger:.07, ease:'back.out(1.5)' }, 1.15)
  .to(rings, { scale:1.12, opacity:.45, duration:.45, stagger:.05, yoyo:true, repeat:2, ease:'sine.inOut' }, 1.55)
  .to(exitTxt, { opacity:1, duration:.35, ease:'power2.out' }, 1.65)
  .to(exitTxt, { textShadow:'0 0 40px rgba(72,202,228,1),0 0 80px rgba(0,180,255,.6)', duration:.4, yoyo:true, repeat:1, ease:'sine.inOut' }, 1.85)
  .to(exitDrum, { rotation:'+=2520', scale:9, opacity:0, duration:.65, ease:'power4.in', transformOrigin:'50% 50%' }, 2.2)
  .to(rings, { scale:0, opacity:0, duration:.38, stagger:.04, ease:'power3.in' }, 2.2)
  .call(()=>spawnCenterBurst(18), [], 2.42)
  .to(exitFlsh, { opacity:1, duration:.22, ease:'power3.in' }, 2.72)
  .to(exitTxt,  { opacity:0, duration:.18 }, 2.72)
      /* PRODUCTION: window.location.href = '/login'; */
    .call(()=>{
  window.location.href = '{{ auth()->check()
    ? (auth()->user()->role === "admin"
        ? route("dashboard.admin")
        : (auth()->user()->role === "driver"
            ? route("dashboard.driver")
            : route("customer.dashboard")))
    : route("login") }}';
}, [], 2.95);
}

function spawnExitDrops(count) {
  const cont = document.getElementById('exit-particles');
  for (let i = 0; i < count; i++) {
    const d = document.createElement('div');
    d.className = 'exit-drop';
    const sz = 6+Math.random()*12, x=20+Math.random()*80;
    gsap.set(d,{width:sz,height:sz*1.4,left:x+'%',bottom:'80%',opacity:0,scale:0,position:'absolute'});
    cont.appendChild(d);
    gsap.timeline({delay:Math.random()*.4})
      .to(d,{opacity:.8,scale:1,y:-(60+Math.random()*100),x:(Math.random()-.5)*60,duration:.4,ease:'power2.out'})
      .to(d,{y:'+=80',opacity:0,scaleX:.3,duration:.5,ease:'power2.in'})
      .call(()=>d.remove());
  }
}

function spawnCenterBurst(count) {
  const cont = document.getElementById('exit-particles');
  const cx = window.innerWidth/2, cy = window.innerHeight/2;
  const colors = ['#00C48C','#00A8E8','#fff','#FFD166','#FF6B35'];
  for (let i = 0; i < count; i++) {
    const el = document.createElement('div');
    const sz = 5+Math.random()*10;
    el.style.cssText = [
      `width:${sz}px`,`height:${sz}px`,
      `background:${colors[Math.floor(Math.random()*colors.length)]}`,
      `left:${cx}px`,`top:${cy}px`,
      'position:absolute','border-radius:50%','opacity:1','pointer-events:none'
    ].join(';');
    cont.appendChild(el);
    const angle = (i/count)*Math.PI*2+Math.random()*.5;
    const dist  = 60+Math.random()*140;
    gsap.to(el,{
      x:Math.cos(angle)*dist, y:Math.sin(angle)*dist,
      opacity:0, scale:0, duration:.6+Math.random()*.4, ease:'power2.out',
      onComplete(){el.remove();}
    });
  }
}

/* ── DEMO RESET ── */
function resetForDemo() {
  exitTriggered=false; busy=false; cur=0;
  gsap.to('#exit-flash',{opacity:0,duration:.1});
  gsap.set('#exit-overlay',{opacity:0,pointerEvents:'none'});
  gsap.set('#exit-bg',{opacity:0});
  gsap.set('#exit-water',{height:0,borderRadius:0});
  gsap.set('#exit-wave',{opacity:0,bottom:0});
  gsap.set('#exit-drum-wrap',{opacity:0,scale:0});
  gsap.set('#exit-drum-svg',{rotation:0,scale:1,opacity:1});
  gsap.set('#exit-text',{opacity:0});
  document.querySelectorAll('.vortex-ring').forEach(r=>gsap.set(r,{scale:0,opacity:0}));
  document.getElementById('exit-particles').innerHTML='';
  gsap.set(['#logo-zone','#carousel-viewport','#controls'],{opacity:1,scale:1,y:0});
  gsap.set(tape,{x:slideX(0)});
  updateDots(); updateBtn();
  document.querySelectorAll('.panel-icon-wrap,.panel h2,.panel p,.panel-tags,.panel-sticker')
    .forEach(el=>gsap.set(el,{opacity:0,y:20,x:0,scale:1}));
  revealPanel(0,1);
  startDrum(); /* ← FIX: was setDrumSpeed(DRUM_BASE) — fungsi itu tidak ada */
}

}); /* end DOMContentLoaded */
</script>
</body>
</html>