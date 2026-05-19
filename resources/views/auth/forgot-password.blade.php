<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Lupa Password – Azka Laundry SIMALUN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html, body {
  width:100%; min-height:100%; font-family:'Nunito',sans-serif;
  background:linear-gradient(168deg, #002f5c 0%, #0077b6 45%, #00b4d8 78%, #48cae4 100%);
  overflow-x:hidden;
}
#ambient { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
#wave-band { position:fixed; bottom:0; left:-5%; right:-5%; height:40%; pointer-events:none; z-index:0; }
.ws { position:absolute; left:0; width:200%; overflow:visible; }
.ws.w1{bottom:38%;} .ws.w2{bottom:34%;opacity:.55;} .ws.w3{bottom:30%;opacity:.28;}

#page {
  position:relative; z-index:1; min-height:100vh;
  display:flex; flex-direction:column; align-items:center;
  padding:0 1.2rem max(2rem, env(safe-area-inset-bottom));
}
#header {
  display:flex; flex-direction:column; align-items:center;
  gap:.3rem; padding-top:max(2.5rem, env(safe-area-inset-top));
  margin-bottom:1.5rem; opacity:0;
}
#drum-wrap { width:clamp(68px,15vw,86px); height:clamp(68px,15vw,86px); position:relative; }
#drum-wrap::after {
  content:''; position:absolute; inset:-9px; border-radius:50%;
  background:radial-gradient(circle, rgba(255,255,255,.14) 40%, transparent 72%);
  animation:halo 3.5s ease-in-out infinite;
}
@keyframes halo { 0%,100%{transform:scale(1)} 50%{transform:scale(1.09)} }
#drum-spin-ring {
  position:absolute; inset:-5px; border-radius:50%;
  border:2px dashed rgba(255,255,255,.28); animation:ring-spin 6s linear infinite;
}
@keyframes ring-spin{to{transform:rotate(360deg);}}
.wm-name{font-family:'Fredoka One',cursive;font-size:clamp(1.5rem,5.8vw,2rem);color:#fff;letter-spacing:-.5px;text-shadow:0 3px 18px rgba(0,0,0,.28);line-height:1;}
.wm-sub{font-size:clamp(.52rem,2vw,.63rem);font-weight:800;color:rgba(144,224,239,.9);letter-spacing:5px;text-transform:uppercase;margin-top:.2rem;}
.wm-bar{width:65px;height:2.5px;background:#FF6B35;border-radius:99px;margin:.32rem auto 0;opacity:.75;}

#card {
  width:100%; max-width:420px;
  background:rgba(255,255,255,.10); border:1.5px solid rgba(255,255,255,.22);
  border-radius:24px; backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px);
  box-shadow:0 16px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.2);
  padding:1.8rem 1.6rem 1.6rem; opacity:0; transform:translateY(32px);
}

/* ── STATES ── */
.view { display:none; }
.view.active { display:block; }

/* icon area */
.icon-area {
  text-align:center; margin-bottom:1.2rem;
}
.icon-circle {
  width:72px; height:72px; border-radius:50%;
  background:rgba(255,255,255,.12); border:2px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  font-size:2rem; margin:0 auto .8rem;
  box-shadow:0 8px 24px rgba(0,0,0,.15);
}
.card-title {
  font-family:'Fredoka One',cursive; font-size:clamp(1.25rem,5vw,1.5rem);
  color:#fff; text-align:center; margin-bottom:.25rem; text-shadow:0 2px 12px rgba(0,0,0,.2);
}
.card-sub {
  font-size:.8rem; font-weight:700; color:rgba(255,255,255,.7);
  text-align:center; margin-bottom:1.3rem; letter-spacing:.2px; line-height:1.5;
}

.form-group { margin-bottom:1rem; }
.form-label { display:block; font-size:.73rem; font-weight:800; color:rgba(255,255,255,.85); letter-spacing:.3px; margin-bottom:.42rem; text-transform:uppercase; }
.input-wrap { position:relative; }
.input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:.95rem; pointer-events:none; opacity:.7; }
.form-input {
  width:100%; padding:.8rem .9rem .8rem 2.65rem;
  background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.22);
  border-radius:12px; color:#fff; font-family:'Nunito',sans-serif; font-size:.92rem; font-weight:600;
  outline:none; transition:border-color .2s, background .2s, box-shadow .2s; -webkit-appearance:none;
}
.form-input::placeholder { color:rgba(255,255,255,.45); }
.form-input:focus { border-color:rgba(255,255,255,.6); background:rgba(255,255,255,.18); box-shadow:0 0 0 3px rgba(255,255,255,.1); }
.form-input.error { border-color:#ff6b6b; }
.form-error { font-size:.7rem; font-weight:700; color:#ff9999; margin-top:.3rem; display:none; }
.form-error.show { display:block; }

.alert { border-radius:12px; padding:.75rem 1rem; margin-bottom:1rem; font-size:.82rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
.alert-error { background:rgba(255,80,80,.18); border:1px solid rgba(255,80,80,.35); color:#ffaaaa; }
.alert-success { background:rgba(0,196,140,.18); border:1px solid rgba(0,196,140,.35); color:#7fffd4; }
.alert-info { background:rgba(0,164,218,.18); border:1px solid rgba(0,164,218,.35); color:#a0d8ef; }

#btn-send {
  width:100%; padding:.88rem; border:none; border-radius:99px;
  background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%);
  color:#fff; font-family:'Nunito',sans-serif; font-weight:900;
  font-size:.97rem; letter-spacing:.3px; cursor:pointer; position:relative; overflow:hidden;
  box-shadow:0 8px 24px rgba(255,107,53,.45), inset 0 1px 0 rgba(255,255,255,.2);
  transition:transform .15s, box-shadow .15s;
}
#btn-send::before {
  content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
  animation:shimmer 2.8s ease-in-out infinite;
}
@keyframes shimmer{0%{left:-100%}60%,100%{left:160%}}
#btn-send:active{transform:scale(.97);} #btn-send:hover{box-shadow:0 12px 32px rgba(255,107,53,.6);}
#btn-send:disabled{opacity:.6;cursor:not-allowed;transform:none;}
.btn-spinner{display:none;width:18px;height:18px;border:2.5px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;vertical-align:middle;margin-right:.4rem;}
@keyframes spin{to{transform:rotate(360deg);}}
#btn-send.loading .btn-spinner{display:inline-block;}
#btn-send.loading .btn-text{opacity:.6;}

/* sent state */
.sent-icon { font-size:3.5rem; margin-bottom:.8rem; display:block; text-align:center; }
.sent-email-display {
  background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.22);
  border-radius:12px; padding:.65rem 1rem; text-align:center;
  font-weight:800; color:#fff; font-size:.9rem; margin-bottom:1.1rem;
  letter-spacing:.3px;
}
.resend-row { text-align:center; margin-top:.8rem; }
.resend-lbl { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.6); }
#resend-timer { font-weight:900; color:#FF6B35; }
#btn-resend {
  background:none; border:none; cursor:pointer;
  font-family:'Nunito',sans-serif; font-weight:900; font-size:.78rem;
  color:rgba(144,224,239,.9); display:none; padding:.1rem;
  transition:color .2s;
}
#btn-resend:hover { color:#fff; }
#btn-resend.visible { display:inline-block; }

/* reset form */
.new-pw-label { font-size:.73rem; font-weight:800; color:rgba(255,255,255,.85); letter-spacing:.3px; margin-bottom:.42rem; text-transform:uppercase; display:block; }
.pw-toggle { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:.92rem; color:rgba(255,255,255,.5); padding:.25rem; transition:color .2s; line-height:1; }
.pw-toggle:hover{color:rgba(255,255,255,.9);}

.back-link-row {
  text-align:center; margin-top:.9rem;
  font-size:.8rem; font-weight:700; color:rgba(255,255,255,.6);
}
.back-link-row a { color:rgba(144,224,239,.9); text-decoration:none; font-weight:900; }
.back-link-row a:hover { color:#fff; }

#btn-back {
  position:fixed; top:max(1rem, env(safe-area-inset-top)); left:1rem; z-index:10;
  width:38px; height:38px; border-radius:50%;
  background:rgba(255,255,255,.15); backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:1.1rem; text-decoration:none;
  transition:background .2s, transform .15s;
}
#btn-back:hover{background:rgba(255,255,255,.25);}
#btn-back:active{transform:scale(.92);}
#footer{margin-top:1.4rem;text-align:center;font-size:.67rem;font-weight:700;color:rgba(255,255,255,.3);letter-spacing:.3px;}
</style>
</head>
<body>

<div id="ambient" aria-hidden="true"></div>
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

<a href="{{ route('login') }}" id="btn-back" title="Kembali">&#8592;</a>

<div id="page">
  <div id="header">
    <div id="drum-wrap">
      <div id="drum-spin-ring"></div>
      <svg id="drum-svg" width="100%" viewBox="0 0 148 148" fill="none">
        <circle cx="74" cy="74" r="70" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.18)" stroke-width="1.5"/>
        <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.14)" stroke="rgba(255,255,255,.35)" stroke-width="1.8"/>
        <rect x="16" y="26" width="116" height="30" rx="16" fill="rgba(255,255,255,.2)"/>
        <circle cx="36" cy="41" r="7" fill="#FF6B35"/>
        <circle cx="56" cy="41" r="7" fill="#00C48C"/>
        <circle cx="112" cy="41" r="5" fill="rgba(255,255,255,.45)"/>
        <circle cx="124" cy="41" r="5" fill="rgba(255,255,255,.28)"/>
        <circle cx="74" cy="90" r="38" fill="rgba(0,100,160,.55)" stroke="rgba(255,255,255,.45)" stroke-width="2.5"/>
        <circle cx="74" cy="90" r="22" fill="rgba(255,255,255,.16)" stroke="rgba(255,255,255,.6)" stroke-width="2.5"/>
        <path d="M63 88 Q74 78 85 88 L82 100 H66Z" fill="white" opacity=".9"/>
        <path d="M63 88 L57 80 L66 75 L71 82" fill="white" opacity=".9"/>
        <path d="M85 88 L91 80 L82 75 L77 82" fill="white" opacity=".9"/>
        <circle cx="90" cy="70" r="6" fill="#FF6B35"/>
        <circle cx="80" cy="62" r="4.5" fill="#00A8E8" opacity=".85"/>
      </svg>
    </div>
    <div class="wm-name">Azka Laundry</div>
    <div class="wm-sub">LAUNDRY&nbsp;&nbsp;•&nbsp;&nbsp;SIMALUN</div>
    <div class="wm-bar"></div>
  </div>

  <div id="card">

    <!-- ══ VIEW 1: REQUEST EMAIL ══ -->
    <div class="view active" id="view-request">
      <div class="icon-area">
        <div class="icon-circle">🔑</div>
        <div class="card-title">Lupa Password?</div>
        <div class="card-sub">
          Tenang! Masukkan emailmu dan kami akan kirimkan link untuk reset password.
        </div>
      </div>

      @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
      @endif
      @if(session('info'))
        <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
      @endif

      <form method="POST" action="{{ route('password.email') }}" id="forgot-form" novalidate>
        @csrf
        <div class="form-group">
          <label class="form-label" for="email">Email yang Terdaftar</label>
          <div class="input-wrap">
            <span class="input-icon">📧</span>
            <input class="form-input @error('email') error @enderror"
              type="email" id="email" name="email"
              placeholder="email@contoh.com"
              value="{{ old('email') }}" autocomplete="email" inputmode="email">
          </div>
          @error('email')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-email"></div>
        </div>

        <button type="submit" id="btn-send">
          <span class="btn-spinner"></span>
          <span class="btn-text">Kirim Link Reset 📨</span>
        </button>
      </form>

      <div class="back-link-row">
        Ingat password? <a href="{{ route('login') }}">Kembali Login</a>
      </div>
    </div>

    <!-- ══ VIEW 2: EMAIL SENT ══ -->
    <div class="view" id="view-sent">
      <span class="sent-icon">📨</span>
      <div class="card-title">Email Terkirim!</div>
      <div class="card-sub">
        Link reset password telah dikirim ke:
      </div>
      <div class="sent-email-display" id="sent-email-display">
        {{ session('reset_email') ?? old('email') ?? '—' }}
      </div>
      <div class="card-sub" style="margin-bottom:.4rem;">
        Cek inbox (dan folder Spam) emailmu. Link berlaku selama <strong style="color:#fff;">60 menit</strong>.
      </div>

      <div class="resend-row">
        <div class="resend-lbl">
          Tidak menerima email?
          <button id="btn-resend" onclick="resendEmail()">Kirim Ulang</button>
          <span id="resend-timer-wrap" style="display:none;"> Tunggu <span id="resend-timer">60</span>d</span>
        </div>
      </div>

      <div class="back-link-row" style="margin-top:1.1rem;">
        <a href="{{ route('login') }}">← Kembali ke Login</a>
      </div>
    </div>

    <!-- ══ VIEW 3: RESET PASSWORD FORM (via token) ══ -->
    {{-- This view is shown on the password.reset route --}}
    @if(isset($token))
    <div class="view" id="view-reset" style="display:block;">
      <div class="icon-area">
        <div class="icon-circle">🔐</div>
        <div class="card-title">Buat Password Baru</div>
        <div class="card-sub">Masukkan password baru untuk akunmu</div>
      </div>

      @if($errors->any())
        <div class="alert alert-error">⚠️ {{ $errors->first() }}</div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" id="reset-form" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group">
          <label class="new-pw-label" for="reset-email">Email</label>
          <div class="input-wrap">
            <span class="input-icon">📧</span>
            <input class="form-input" type="email" name="email" id="reset-email"
              value="{{ $email ?? old('email') }}" readonly style="opacity:.7;cursor:not-allowed;">
          </div>
        </div>

        <div class="form-group">
          <label class="new-pw-label" for="new-pw">Password Baru</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input class="form-input @error('password') error @enderror"
              type="password" id="new-pw" name="password"
              placeholder="Min. 8 karakter" autocomplete="new-password">
            <button type="button" class="pw-toggle" id="rpw-toggle-1">👁️</button>
          </div>
          @error('password')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-rpw"></div>
        </div>

        <div class="form-group">
          <label class="new-pw-label" for="new-pw-confirm">Konfirmasi Password Baru</label>
          <div class="input-wrap">
            <span class="input-icon">🔐</span>
            <input class="form-input"
              type="password" id="new-pw-confirm" name="password_confirmation"
              placeholder="Ulangi password baru" autocomplete="new-password">
            <button type="button" class="pw-toggle" id="rpw-toggle-2">👁️</button>
          </div>
          <div class="form-error" id="err-rpwc"></div>
        </div>

        <button type="submit" id="btn-send" style="margin-top:.4rem;">
          <span class="btn-spinner"></span>
          <span class="btn-text">Simpan Password Baru 🔐</span>
        </button>
      </form>
    </div>
    @endif

    <!-- ══ VIEW 4: RESET SUCCESS ══ -->
    @if(session('password_reset_success'))
    <div class="view" id="view-reset-success" style="display:block;">
      <div style="text-align:center;padding:.5rem 0;">
        <div class="sent-icon">🎉</div>
        <div class="card-title">Password Berhasil Diubah!</div>
        <div class="card-sub" style="margin-bottom:1.2rem;">
          Password baru kamu sudah aktif. Silakan login dengan password yang baru.
        </div>
        <a href="{{ route('login') }}" style="display:inline-flex;align-items:center;gap:.4rem;background:linear-gradient(135deg,#00C48C 0%,#00e0a1 100%);color:#fff;font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;border:none;border-radius:99px;padding:.82rem 2rem;text-decoration:none;box-shadow:0 8px 24px rgba(0,196,140,.45);transition:transform .15s;">
          Masuk Sekarang →
        </a>
      </div>
    </div>
    @endif

  </div><!-- #card -->

  <div id="footer">
    &copy; {{ date('Y') }} Azka Laundry · SIMALUN · v1.0
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

  /* ── WAVES ── */
  document.querySelectorAll('.ws').forEach((w,i)=>{
    gsap.fromTo(w,{x:'0%'},{x:'-50%',duration:14+i*4,ease:'none',repeat:-1});
    gsap.to(w,{y:i%2?5:-5,duration:3+i*1.5,ease:'sine.inOut',yoyo:true,repeat:-1});
  });

  /* ── DRUM ── */
  gsap.to('#drum-svg',{rotation:'+=360',duration:18,ease:'none',repeat:-1,transformOrigin:'50% 50%'});

  /* ── AMBIENT BUBBLES ── */
  (function(){
    const amb=document.getElementById('ambient'), VH=window.innerHeight;
    [[8,.7],[14,.5],[6,.8],[20,.42],[10,.65],[5,.85],[16,.5],[9,.72]].forEach(([base,al],i)=>{
      const sz=base+Math.random()*6, el=document.createElement('div');
      el.style.cssText=['position:absolute',`width:${sz}px`,`height:${sz}px`,'border-radius:50%',
        'background:radial-gradient(circle at 30% 28%,rgba(255,255,255,.88),rgba(255,255,255,.1))',
        'border:1.5px solid rgba(255,255,255,.4)',`left:${3+Math.random()*93}%`,
        `top:${VH+sz+10}px`,'opacity:0','pointer-events:none'].join(';');
      amb.appendChild(el);
      const delay=.5+i*.1+Math.random()*3;
      gsap.to(el,{opacity:al,duration:.5,delay});
      gsap.fromTo(el,{y:0},{y:-(VH*1.3),x:(Math.random()-.5)*50,duration:10+Math.random()*14,
        ease:'none',repeat:-1,delay,onRepeat(){gsap.set(el,{y:0,x:0,left:(3+Math.random()*93)+'%'});}});
    });
  })();

  /* ── INTRO ── */
  const tl = gsap.timeline();
  tl.to('#header',{opacity:1,duration:.9,ease:'elastic.out(.75,.65)'},.2)
    .to('#card',{opacity:1,y:0,duration:.6,ease:'back.out(1.6)'},.55);

  /* ── SHOW SENT STATE if session says so ── */
  @if(session('status') == 'passwords.sent' || session('reset_sent'))
    switchView('view-sent');
  @endif

  /* ── FORM SUBMIT ── */
  const forgotForm = document.getElementById('forgot-form');
  if (forgotForm) {
    forgotForm.addEventListener('submit', function(e) {
      const email = document.getElementById('email').value.trim();
      const errEl = document.getElementById('err-email');
      errEl.textContent=''; errEl.classList.remove('show');
      document.getElementById('email').classList.remove('error');
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        e.preventDefault();
        errEl.textContent = 'Masukkan alamat email yang valid';
        errEl.classList.add('show');
        document.getElementById('email').classList.add('error');
        gsap.fromTo('#card',{x:-6},{x:0,duration:.4,ease:'elastic.out(1,.4)'});
        return;
      }
      document.getElementById('btn-send').classList.add('loading');
      document.getElementById('btn-send').disabled = true;
    });
  }

  /* ── RESET FORM SUBMIT ── */
  const resetForm = document.getElementById('reset-form');
  if (resetForm) {
    document.getElementById('rpw-toggle-1')?.addEventListener('click', function(){
      const inp = document.getElementById('new-pw');
      const show = inp.type==='password';
      inp.type = show?'text':'password';
      this.textContent = show?'🙈':'👁️';
    });
    document.getElementById('rpw-toggle-2')?.addEventListener('click', function(){
      const inp = document.getElementById('new-pw-confirm');
      const show = inp.type==='password';
      inp.type = show?'text':'password';
      this.textContent = show?'🙈':'👁️';
    });
    resetForm.addEventListener('submit', function(e) {
      const pw = document.getElementById('new-pw').value;
      const pwc = document.getElementById('new-pw-confirm').value;
      let ok = true;
      document.getElementById('err-rpw').classList.remove('show');
      document.getElementById('err-rpwc').classList.remove('show');
      if (!pw || pw.length < 8) {
        document.getElementById('err-rpw').textContent='Password minimal 8 karakter';
        document.getElementById('err-rpw').classList.add('show');
        document.getElementById('new-pw').classList.add('error'); ok=false;
      }
      if (pw !== pwc) {
        document.getElementById('err-rpwc').textContent='Konfirmasi password tidak cocok';
        document.getElementById('err-rpwc').classList.add('show');
        document.getElementById('new-pw-confirm').classList.add('error'); ok=false;
      }
      if (!ok) { e.preventDefault(); gsap.fromTo('#card',{x:-6},{x:0,duration:.4,ease:'elastic.out(1,.4)'}); return; }
      document.getElementById('btn-send').classList.add('loading');
      document.getElementById('btn-send').disabled = true;
    });
  }

  /* ── RESEND TIMER ── */
  let resendSeconds = 60;
  let resendInterval = null;
  function startResendTimer() {
    const timerWrap = document.getElementById('resend-timer-wrap');
    const timerEl = document.getElementById('resend-timer');
    const resendBtn = document.getElementById('btn-resend');
    if (!timerWrap) return;
    timerWrap.style.display = 'inline';
    resendSeconds = 60;
    timerEl.textContent = resendSeconds;
    resendInterval = setInterval(function() {
      resendSeconds--;
      timerEl.textContent = resendSeconds;
      if (resendSeconds <= 0) {
        clearInterval(resendInterval);
        timerWrap.style.display = 'none';
        resendBtn.classList.add('visible');
      }
    }, 1000);
  }

  window.resendEmail = function() {
    document.getElementById('btn-resend').classList.remove('visible');
    startResendTimer();
    // submit the forgot form again
    if (forgotForm) forgotForm.submit();
  };

  @if(session('status') == 'passwords.sent' || session('reset_sent'))
    startResendTimer();
  @endif

});

function switchView(id) {
  document.querySelectorAll('.view').forEach(v=>v.classList.remove('active'));
  const target = document.getElementById(id);
  if (!target) return;
  target.classList.add('active');
  gsap.fromTo(target,{opacity:0,y:20},{opacity:1,y:0,duration:.4,ease:'power2.out'});
}
</script>
</body>
</html>