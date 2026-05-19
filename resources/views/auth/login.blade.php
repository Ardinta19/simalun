<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Login – Azka Laundry SIMALUN</title>
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
.ws.w1 { bottom:38%; }
.ws.w2 { bottom:34%; opacity:.55; }
.ws.w3 { bottom:30%; opacity:.28; }
#page { position:relative; z-index:1; min-height:100vh; display:flex; flex-direction:column; align-items:center; padding:2rem 1.2rem max(2rem, env(safe-area-inset-bottom)); }
#header { display:flex; flex-direction:column; align-items:center; gap:.35rem; padding-top:max(2.5rem, env(safe-area-inset-top)); margin-bottom:1.6rem; opacity:0; }
#drum-wrap { width:clamp(72px,16vw,92px); height:clamp(72px,16vw,92px); position:relative; }
#drum-wrap::after { content:''; position:absolute; inset:-10px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.14) 40%, transparent 72%); animation:halo 3.5s ease-in-out infinite; }
@keyframes halo { 0%,100%{transform:scale(1)} 50%{transform:scale(1.09)} }
#drum-spin-ring { position:absolute; inset:-5px; border-radius:50%; border:2px dashed rgba(255,255,255,.28); animation:ring-spin 6s linear infinite; pointer-events:none; }
@keyframes ring-spin { to{transform:rotate(360deg);} }
.wm-name { font-family:'Fredoka One',cursive; font-size:clamp(1.6rem,6vw,2.2rem); color:#fff; letter-spacing:-.5px; text-shadow:0 3px 18px rgba(0,0,0,.28); line-height:1; }
.wm-sub  { font-size:clamp(.52rem,2vw,.65rem); font-weight:800; color:rgba(144,224,239,.9); letter-spacing:5px; text-transform:uppercase; margin-top:.2rem; }
.wm-bar  { width:70px; height:2.5px; background:#FF6B35; border-radius:99px; margin:.35rem auto 0; opacity:.75; }
#card { width:100%; max-width:420px; background:rgba(255,255,255,.10); border:1.5px solid rgba(255,255,255,.22); border-radius:24px; backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px); box-shadow:0 16px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.2); padding:1.8rem 1.6rem 1.6rem; opacity:0; transform:translateY(32px); }
.card-title { font-family:'Fredoka One',cursive; font-size:clamp(1.3rem,5vw,1.6rem); color:#fff; text-align:center; margin-bottom:.25rem; text-shadow:0 2px 12px rgba(0,0,0,.2); }
.card-sub { font-size:.8rem; font-weight:700; color:rgba(255,255,255,.7); text-align:center; margin-bottom:1.3rem; letter-spacing:.2px; }
.form-group { margin-bottom:1rem; position:relative; }
.form-label { display:block; font-size:.75rem; font-weight:800; color:rgba(255,255,255,.85); letter-spacing:.3px; margin-bottom:.45rem; text-transform:uppercase; }
.input-wrap { position:relative; }
.input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; opacity:.7; }
.form-input { width:100%; padding:.82rem .9rem .82rem 2.7rem; background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.22); border-radius:12px; color:#fff; font-family:'Nunito',sans-serif; font-size:.93rem; font-weight:600; outline:none; transition:border-color .2s, background .2s, box-shadow .2s; -webkit-appearance:none; }
.form-input::placeholder { color:rgba(255,255,255,.45); }
.form-input:focus { border-color:rgba(255,255,255,.6); background:rgba(255,255,255,.18); box-shadow:0 0 0 3px rgba(255,255,255,.1); }
.pw-toggle { position:absolute; right:13px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; font-size:1rem; color:rgba(255,255,255,.5); padding:.25rem; transition:color .2s; line-height:1; }
.pw-toggle:hover { color:rgba(255,255,255,.9); }
.form-input.error { border-color:#ff6b6b; box-shadow:0 0 0 3px rgba(255,107,107,.15); }
.form-error { font-size:.72rem; font-weight:700; color:#ff9999; margin-top:.35rem; display:none; padding-left:.2rem; }
.form-error.show { display:block; }
.input-error { border-color:#ff6b6b !important; }
.error-msg { font-size:.72rem; font-weight:700; color:#ff9999; margin-top:.35rem; padding-left:.2rem; }
.form-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.2rem; gap:.5rem; }
.remember-label { display:flex; align-items:center; gap:.5rem; cursor:pointer; font-size:.78rem; font-weight:700; color:rgba(255,255,255,.75); }
.remember-label input[type=checkbox] { width:16px; height:16px; border-radius:4px; cursor:pointer; accent-color:#FF6B35; }
.forgot-link { font-size:.78rem; font-weight:800; color:rgba(144,224,239,.9); text-decoration:none; letter-spacing:.2px; white-space:nowrap; transition:color .2s; }
.forgot-link:hover { color:#fff; }
#btn-login { width:100%; padding:.9rem; border:none; border-radius:99px; background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%); color:#fff; font-family:'Nunito',sans-serif; font-weight:900; font-size:1rem; letter-spacing:.4px; cursor:pointer; position:relative; overflow:hidden; box-shadow:0 8px 24px rgba(255,107,53,.45), inset 0 1px 0 rgba(255,255,255,.2); transition:transform .15s, box-shadow .15s; }
#btn-login::before { content:''; position:absolute; top:0; left:-100%; width:60%; height:100%; background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent); animation:shimmer 2.8s ease-in-out infinite; }
@keyframes shimmer { 0%{left:-100%} 60%,100%{left:160%} }
#btn-login:active { transform:scale(.97); }
#btn-login:hover  { box-shadow:0 12px 32px rgba(255,107,53,.6); }
#btn-login:disabled { opacity:.6; cursor:not-allowed; transform:none; }
.btn-spinner { display:inline-block; width:18px; height:18px; border:2.5px solid rgba(255,255,255,.35); border-top-color:#fff; border-radius:50%; animation:spin .7s linear infinite; vertical-align:middle; margin-right:.4rem; display:none; }
@keyframes spin { to{transform:rotate(360deg);} }
#btn-login.loading .btn-spinner { display:inline-block; }
#btn-login.loading .btn-text { opacity:.6; }
.divider { display:flex; align-items:center; gap:.8rem; margin:1.1rem 0; }
.divider::before, .divider::after { content:''; flex:1; height:1px; background:rgba(255,255,255,.2); }
.divider span { font-size:.72rem; font-weight:800; color:rgba(255,255,255,.45); letter-spacing:.5px; text-transform:uppercase; white-space:nowrap; }
.register-row { text-align:center; margin-top:.8rem; font-size:.82rem; font-weight:700; color:rgba(255,255,255,.7); }
.register-row a { color:rgba(144,224,239,.95); text-decoration:none; font-weight:900; transition:color .2s; }
.register-row a:hover { color:#fff; }
.alert { border-radius:12px; padding:.75rem 1rem; margin-bottom:1rem; font-size:.82rem; font-weight:700; display:flex; align-items:center; gap:.5rem; }
.alert-error { background:rgba(255,80,80,.18); border:1px solid rgba(255,80,80,.35); color:#ffaaaa; }
.alert-success { background:rgba(0,196,140,.18); border:1px solid rgba(0,196,140,.35); color:#7fffd4; }
#footer { margin-top:1.6rem; text-align:center; font-size:.68rem; font-weight:700; color:rgba(255,255,255,.35); letter-spacing:.3px; }
#btn-back { position:fixed; top:max(1rem, env(safe-area-inset-top)); left:1rem; z-index:10; width:38px; height:38px; border-radius:50%; border:none; cursor:pointer; background:rgba(255,255,255,.15); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; transition:background .2s, transform .15s; text-decoration:none; }
#btn-back:hover { background:rgba(255,255,255,.25); }
#btn-back:active { transform:scale(.92); }
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

<a href="{{ url('/') }}" id="btn-back" title="Kembali">&#8592;</a>

<div id="page">

  <div id="header">
    <div id="drum-wrap">
      <div id="drum-spin-ring"></div>
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
      </svg>
    </div>
    <div class="wm-name">Azka Laundry</div>
    <div class="wm-sub">LAUNDRY&nbsp;&nbsp;•&nbsp;&nbsp;SIMALUN</div>
    <div class="wm-bar"></div>
  </div>

  <div id="card">

    <div class="card-title">Selamat Datang 👋</div>
    <div class="card-sub">Masuk ke akun SIMALUN kamu</div>

    @if(session('error'))
      <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif
    
    @if(session('success'))
      <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" id="login-form" novalidate>
      @csrf
      
      {{-- Identifier: Email or Phone --}}
      <div class="form-group">
        <label class="form-label" for="identifier">Email / No. HP</label>
        <div class="input-wrap">
          <span class="input-icon" id="identifier-icon">📧</span>
          <input
            class="form-input @error('identifier') input-error @enderror"
            type="text"
            id="identifier"
            name="identifier"
            placeholder="email@contoh.com atau 08xxxxxxxxxx"
            value="{{ old('identifier') }}"
            autocomplete="username"
            inputmode="email"
          >
        </div>
        @error('identifier')
          <div class="error-msg">{{ $message }}</div>
        @enderror
        <div class="form-error" id="err-identifier"></div>
      </div>

      {{-- Password Field --}}
      <div class="form-group">
        <label class="form-label" for="password">Password</label>
        <div class="input-wrap">
          <span class="input-icon">🔒</span>
          <input
            class="form-input @error('password') input-error @enderror"
            type="password"
            id="password"
            name="password"
            placeholder="Masukkan password"
            autocomplete="current-password"
          >
          <button type="button" class="pw-toggle" id="pw-toggle" title="Tampilkan password">👁️</button>
        </div>
        @error('password')
          <div class="error-msg">{{ $message }}</div>
        @enderror
        <div class="form-error" id="err-password"></div>
      </div>

      {{-- Remember + Forgot --}}
      <div class="form-row">
        <label class="remember-label">
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
          Ingat saya
        </label>
        <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
      </div>

      {{-- Submit Button --}}
      <button type="submit" id="btn-login">
        <span class="btn-spinner"></span>
        <span class="btn-text">Masuk Sekarang</span>
      </button>
    </form>

    <div class="divider"><span>Belum punya akun?</span></div>

    <div class="register-row">
      Daftar sebagai pelanggan &rarr;
      <a href="{{ route('register') }}">Buat Akun</a>
    </div>

  </div>

  <div id="footer">
    &copy; {{ date('Y') }} Azka Laundry · SIMALUN · v1.0
  </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

  /* ── WAVES ── */
  document.querySelectorAll('.ws').forEach((w,i)=>{
    gsap.fromTo(w,{x:'0%'},{x:'-50%',duration:14+i*4,ease:'none',repeat:-1});
    gsap.to(w,{y:i%2?5:-5,duration:3+i*1.5,ease:'sine.inOut',yoyo:true,repeat:-1});
  });

  /* ── DRUM SPIN ── */
  gsap.to('#drum-svg',{rotation:'+=360',duration:18,ease:'none',repeat:-1,transformOrigin:'50% 50%'});

  /* ── AMBIENT BUBBLES ── */
  (function(){
    const amb = document.getElementById('ambient');
    const VH  = window.innerHeight;
    const cfg = [[8,.7],[14,.5],[6,.8],[20,.42],[10,.65],[5,.85],[16,.5],[9,.72],[24,.38],[7,.78],[12,.6],[18,.46],[5,.88],[11,.68],[22,.4],[6,.82]];
    cfg.forEach(([base,al],i)=>{
      const sz = base+Math.random()*6;
      const el = document.createElement('div');
      el.style.cssText=['position:absolute',`width:${sz}px`,`height:${sz}px`,'border-radius:50%','background:radial-gradient(circle at 30% 28%,rgba(255,255,255,.88),rgba(255,255,255,.1))','border:1.5px solid rgba(255,255,255,.4)',`left:${3+Math.random()*93}%`,`top:${VH+sz+10}px`,'opacity:0','pointer-events:none'].join(';');
      amb.appendChild(el);
      const rise=10+Math.random()*14, delay=.5+i*.1+Math.random()*3;
      gsap.to(el,{opacity:al,duration:.5,delay});
      gsap.fromTo(el,{y:0},{y:-(VH*1.3),x:(Math.random()-.5)*50,duration:rise,ease:'none',repeat:-1,delay,onRepeat(){gsap.set(el,{y:0,x:0,left:(3+Math.random()*93)+'%'});}});
    });
  })();

  /* ── INTRO ANIMATION ── */
  const tl = gsap.timeline();
  tl.to('#header', {opacity:1, y:0, duration:.9, ease:'elastic.out(.75,.65)'}, .2)
    .to('#card',   {opacity:1, y:0, duration:.6, ease:'back.out(1.6)'}, .55);

  /* ── IDENTIFIER TYPE DETECTION (auto switch inputmode) ── */
  const identEl = document.getElementById('identifier');
  const identIcon = document.getElementById('identifier-icon');
  identEl.addEventListener('input', function(){
    const v = this.value.trim();
    if(/^[0-9+]/.test(v)){
      this.inputMode = 'tel';
      identIcon.textContent = '📱';
    } else {
      this.inputMode = 'email';
      identIcon.textContent = '📧';
    }
  });

  /* ── PASSWORD TOGGLE ── */
  const pwInput = document.getElementById('password');
  const pwToggle = document.getElementById('pw-toggle');
  pwToggle.addEventListener('click', function(){
    const show = pwInput.type === 'password';
    pwInput.type = show ? 'text' : 'password';
    this.textContent = show ? '🙈' : '👁️';
  });

  /* ── CLIENT-SIDE VALIDATION ── */
  const form = document.getElementById('login-form');
  const btnLogin = document.getElementById('btn-login');
  const errIdent = document.getElementById('err-identifier');
  const errPw = document.getElementById('err-password');

  function showErr(el, msg){ el.textContent=msg; el.classList.add('show'); }
  function clearErr(el) { el.textContent=''; el.classList.remove('show'); }
  function isValidEmail(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
  function isValidPhone(v){ return /^(\+62|62|0)8[0-9]{7,12}$/.test(v.replace(/[\s-]/g,'')); }

  form.addEventListener('submit', function(e){
    let valid = true;
    const ident = identEl.value.trim();
    const pw = pwInput.value;

    clearErr(errIdent); clearErr(errPw);
    identEl.classList.remove('error');
    pwInput.classList.remove('error');

    if(!ident){
      showErr(errIdent, 'Email atau No. HP wajib diisi'); identEl.classList.add('error'); valid=false;
    } else if(!isValidEmail(ident) && !isValidPhone(ident)){
      showErr(errIdent, 'Format email atau No. HP tidak valid'); identEl.classList.add('error'); valid=false;
    }

    if(!pw){
      showErr(errPw, 'Password wajib diisi'); pwInput.classList.add('error'); valid=false;
    } else if(pw.length < 6){
      showErr(errPw, 'Password minimal 6 karakter'); pwInput.classList.add('error'); valid=false;
    }

    if(!valid){ e.preventDefault(); gsap.fromTo(form,{x:-6},{x:0,duration:.4,ease:'elastic.out(1,.4)'}); return; }

    /* show loading state */
    btnLogin.classList.add('loading');
    btnLogin.disabled = true;
  });

  /* remove error on input */
  identEl.addEventListener('input', ()=>{ clearErr(errIdent); identEl.classList.remove('error'); });
  pwInput.addEventListener('input', ()=>{ clearErr(errPw); pwInput.classList.remove('error'); });

});
</script>
</body>
</html>