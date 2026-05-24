<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Daftar – Azka Laundry SIMALUN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html, body {
  width:100%; min-height:100%; font-family:'Plus Jakarta Sans',sans-serif;
  background:linear-gradient(168deg, #002f5c 0%, #0077b6 45%, #00b4d8 78%, #48cae4 100%);
  overflow-x:hidden;
}

/* ── AMBIENT ── */
#ambient { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }

/* ── WAVES ── */
#wave-band { position:fixed; bottom:0; left:-5%; right:-5%; height:40%; pointer-events:none; z-index:0; }
.ws { position:absolute; left:0; width:200%; overflow:visible; }
.ws.w1 { bottom:38%; }
.ws.w2 { bottom:34%; opacity:.55; }
.ws.w3 { bottom:30%; opacity:.28; }

/* ── PAGE ── */
#page {
  position:relative; z-index:1; min-height:100vh;
  display:flex; flex-direction:column; align-items:center;
  padding:0 1.2rem max(2rem, env(safe-area-inset-bottom));
}

/* ── HEADER ── */
#header {
  display:flex; flex-direction:column; align-items:center;
  gap:.3rem; padding-top:max(2rem, env(safe-area-inset-top));
  margin-bottom:1.2rem; opacity:0;
}
#drum-wrap {
  width:clamp(62px,14vw,78px); height:clamp(62px,14vw,78px); position:relative;
}
#drum-wrap::after {
  content:''; position:absolute; inset:-8px; border-radius:50%;
  background:radial-gradient(circle, rgba(255,255,255,.14) 40%, transparent 72%);
  animation:halo 3.5s ease-in-out infinite;
}
@keyframes halo { 0%,100%{transform:scale(1)} 50%{transform:scale(1.09)} }
#drum-spin-ring {
  position:absolute; inset:-4px; border-radius:50%;
  border:2px dashed rgba(255,255,255,.28);
  animation:ring-spin 6s linear infinite; pointer-events:none;
}
@keyframes ring-spin { to{transform:rotate(360deg);} }
.wm-name { font-weight:800; font-size:clamp(1.4rem,5.5vw,1.9rem); color:#fff; letter-spacing:-.5px; text-shadow:0 3px 18px rgba(0,0,0,.28); line-height:1; }
.wm-sub  { font-size:clamp(.5rem,1.9vw,.62rem); font-weight:800; color:rgba(144,224,239,.9); letter-spacing:5px; text-transform:uppercase; margin-top:.18rem; }
.wm-bar  { width:60px; height:2.5px; background:#FF6B35; border-radius:99px; margin:.3rem auto 0; opacity:.75; }

/* ── CARD ── */
#card {
  width:100%; max-width:420px;
  background:rgba(255,255,255,.10); border:1.5px solid rgba(255,255,255,.22);
  border-radius:24px; backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px);
  box-shadow:0 16px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.2);
  padding:1.6rem 1.5rem 1.5rem; opacity:0; transform:translateY(32px);
}
.card-title {
  font-weight:800; font-size:clamp(1.2rem,4.8vw,1.5rem);
  color:#fff; text-align:center; margin-bottom:.2rem; text-shadow:0 2px 12px rgba(0,0,0,.2);
}
.card-sub {
  font-size:.78rem; font-weight:700; color:rgba(255,255,255,.7);
  text-align:center; margin-bottom:1.2rem; letter-spacing:.2px;
}

/* ── STEP INDICATOR ── */
.step-indicator {
  display:flex; align-items:center; justify-content:center;
  gap:.4rem; margin-bottom:1.3rem;
}
.step-dot {
  width:28px; height:28px; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  font-weight:800; font-size:.75rem;
  border:2px solid rgba(255,255,255,.3);
  color:rgba(255,255,255,.5);
  transition:all .3s ease;
}
.step-dot.active {
  background:#FF6B35; border-color:#FF6B35; color:#fff;
  box-shadow:0 0 14px rgba(255,107,53,.5);
}
.step-dot.done {
  background:rgba(0,196,140,.9); border-color:#00C48C; color:#fff;
}
.step-line {
  flex:1; max-width:40px; height:2px;
  background:rgba(255,255,255,.2); border-radius:99px;
  transition:background .3s;
}
.step-line.done { background:#00C48C; }

/* ── FORM ── */
.form-step { display:none; }
.form-step.active { display:block; }

.form-group { margin-bottom:.9rem; position:relative; }
.form-label {
  display:block; font-size:.73rem; font-weight:800;
  color:rgba(255,255,255,.85); letter-spacing:.3px;
  margin-bottom:.4rem; text-transform:uppercase;
}
.input-wrap { position:relative; }
.input-icon {
  position:absolute; left:14px; top:50%; transform:translateY(-50%);
  font-size:.95rem; pointer-events:none; opacity:.7;
}
.form-input {
  width:100%; padding:.78rem .9rem .78rem 2.65rem;
  background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.22);
  border-radius:12px; color:#fff;
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.91rem; font-weight:600;
  outline:none; transition:border-color .2s, background .2s, box-shadow .2s;
  -webkit-appearance:none;
}
.form-input::placeholder { color:rgba(255,255,255,.45); }
.form-input:focus {
  border-color:rgba(255,255,255,.6); background:rgba(255,255,255,.18);
  box-shadow:0 0 0 3px rgba(255,255,255,.1);
}
.form-input.error { border-color:#ff6b6b; box-shadow:0 0 0 3px rgba(255,107,107,.15); }

.pw-toggle {
  position:absolute; right:12px; top:50%; transform:translateY(-50%);
  background:none; border:none; cursor:pointer; font-size:.95rem;
  color:rgba(255,255,255,.5); padding:.25rem; transition:color .2s; line-height:1;
}
.pw-toggle:hover { color:rgba(255,255,255,.9); }

.form-error {
  font-size:.7rem; font-weight:700; color:#ff9999;
  margin-top:.3rem; display:none; padding-left:.2rem;
}
.form-error.show { display:block; }

/* password strength meter */
.pw-strength {
  margin-top:.4rem; display:none;
}
.pw-strength.show { display:block; }
.pw-strength-bar {
  height:4px; border-radius:99px;
  background:rgba(255,255,255,.15); overflow:hidden; margin-bottom:.3rem;
}
.pw-strength-fill {
  height:100%; border-radius:99px;
  width:0%; transition:width .3s, background .3s;
}
.pw-strength-lbl {
  font-size:.65rem; font-weight:800; color:rgba(255,255,255,.55);
  letter-spacing:.3px;
}

/* password hint checklist */
.pw-hints { list-style:none; margin-top:.4rem; display:flex; flex-wrap:wrap; gap:.3rem; }
.pw-hint {
  font-size:.63rem; font-weight:700;
  color:rgba(255,255,255,.45); padding:.15rem .45rem;
  background:rgba(255,255,255,.08); border-radius:99px;
  border:1px solid rgba(255,255,255,.15);
  transition:all .2s;
}
.pw-hint.ok { color:#00C48C; background:rgba(0,196,140,.12); border-color:rgba(0,196,140,.3); }

/* phone input with country code */
.phone-row { display:flex; gap:.5rem; }
.phone-prefix {
  display:flex; align-items:center; justify-content:center;
  padding:.78rem .8rem; background:rgba(255,255,255,.1);
  border:1.5px solid rgba(255,255,255,.22); border-radius:12px;
  font-size:.85rem; font-weight:800; color:rgba(255,255,255,.8);
  white-space:nowrap; flex-shrink:0; letter-spacing:.2px;
}
.phone-input-wrap { flex:1; position:relative; }

/* gender select */
.gender-row { display:flex; gap:.6rem; }
.gender-opt { display:none; }
.gender-label {
  flex:1; display:flex; flex-direction:column; align-items:center; gap:.3rem;
  padding:.65rem .4rem; background:rgba(255,255,255,.08);
  border:1.5px solid rgba(255,255,255,.2); border-radius:12px;
  cursor:pointer; transition:all .2s; text-align:center;
}
.gender-label span:first-child { font-size:1.3rem; }
.gender-label span:last-child { font-size:.68rem; font-weight:800; color:rgba(255,255,255,.7); }
.gender-opt:checked + .gender-label {
  background:rgba(255,107,53,.2); border-color:#FF6B35;
  box-shadow:0 0 0 2px rgba(255,107,53,.25);
}
.gender-opt:checked + .gender-label span:last-child { color:#fff; }

/* terms checkbox */
.terms-row { display:flex; align-items:flex-start; gap:.7rem; margin-top:.6rem; }
.terms-cb { width:18px; height:18px; flex-shrink:0; cursor:pointer; accent-color:#FF6B35; margin-top:2px; }
.terms-txt { font-size:.75rem; font-weight:700; color:rgba(255,255,255,.75); line-height:1.5; }
.terms-txt a { color:rgba(144,224,239,.9); text-decoration:none; font-weight:900; }

/* ── BUTTONS ── */
.btn-row { display:flex; gap:.6rem; margin-top:1.1rem; }
.btn-back-step {
  padding:.82rem 1.1rem; background:rgba(255,255,255,.12);
  border:1.5px solid rgba(255,255,255,.22); border-radius:99px;
  color:rgba(255,255,255,.8); font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:800; font-size:.88rem; cursor:pointer;
  transition:background .2s; white-space:nowrap;
}
.btn-back-step:hover { background:rgba(255,255,255,.2); }

.btn-next-step, .btn-submit {
  flex:1; padding:.85rem; border:none; border-radius:99px;
  background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%);
  color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:900;
  font-size:.95rem; letter-spacing:.3px; cursor:pointer; position:relative; overflow:hidden;
  box-shadow:0 8px 24px rgba(255,107,53,.45), inset 0 1px 0 rgba(255,255,255,.2);
  transition:transform .15s, box-shadow .15s;
}
.btn-next-step::before, .btn-submit::before {
  content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
  animation:shimmer 2.8s ease-in-out infinite;
}
@keyframes shimmer { 0%{left:-100%} 60%,100%{left:160%} }
.btn-next-step:active, .btn-submit:active { transform:scale(.97); }
.btn-next-step:hover, .btn-submit:hover { box-shadow:0 12px 32px rgba(255,107,53,.6); }
.btn-next-step:disabled, .btn-submit:disabled { opacity:.6; cursor:not-allowed; transform:none; }

.btn-spinner {
  display:none; width:18px; height:18px;
  border:2.5px solid rgba(255,255,255,.35); border-top-color:#fff;
  border-radius:50%; animation:spin .7s linear infinite;
  vertical-align:middle; margin-right:.4rem;
}
@keyframes spin { to{transform:rotate(360deg);} }
.btn-submit.loading .btn-spinner { display:inline-block; }
.btn-submit.loading .btn-text { opacity:.6; }

/* alert */
.alert {
  border-radius:12px; padding:.7rem .9rem; margin-bottom:.9rem;
  font-size:.8rem; font-weight:700; display:flex; align-items:center; gap:.5rem;
}
.alert-error { background:rgba(255,80,80,.18); border:1px solid rgba(255,80,80,.35); color:#ffaaaa; }
.alert-success { background:rgba(0,196,140,.18); border:1px solid rgba(0,196,140,.35); color:#7fffd4; }

/* login link */
.login-row {
  text-align:center; margin-top:.9rem;
  font-size:.8rem; font-weight:700; color:rgba(255,255,255,.65);
}
.login-row a {
  color:rgba(144,224,239,.95); text-decoration:none; font-weight:900; transition:color .2s;
}
.login-row a:hover { color:#fff; }

/* success state */
#success-state {
  display:none; text-align:center; padding:1rem 0;
}
#success-state .success-icon { font-size:3.5rem; margin-bottom:.8rem; }
#success-state .success-title {
  font-weight:800; font-size:1.4rem; color:#fff; margin-bottom:.4rem;
}
#success-state .success-sub {
  font-size:.82rem; font-weight:700; color:rgba(255,255,255,.75);
  line-height:1.6; margin-bottom:1.2rem;
}
.btn-to-login {
  display:inline-flex; align-items:center; gap:.4rem;
  background:linear-gradient(135deg,#00C48C 0%,#00e0a1 100%);
  color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:900;
  font-size:.95rem; border:none; border-radius:99px;
  padding:.82rem 2rem; cursor:pointer; text-decoration:none;
  box-shadow:0 8px 24px rgba(0,196,140,.45);
  transition:transform .15s, box-shadow .15s;
}
.btn-to-login:hover { box-shadow:0 12px 32px rgba(0,196,140,.6); transform:scale(1.02); }

/* footer */
#footer {
  margin-top:1.4rem; text-align:center;
  font-size:.67rem; font-weight:700; color:rgba(255,255,255,.3); letter-spacing:.3px;
}

/* back button fixed */
#btn-back {
  position:fixed; top:max(1rem, env(safe-area-inset-top)); left:1rem; z-index:10;
  width:38px; height:38px; border-radius:50%; border:none; cursor:pointer;
  background:rgba(255,255,255,.15); backdrop-filter:blur(8px);
  border:1px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  color:#fff; font-size:1.1rem; transition:background .2s, transform .15s;
  text-decoration:none;
}
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

<a href="{{ route('login') }}" id="btn-back" title="Kembali ke Login">&#8592;</a>

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
    <div class="card-title">Buat Akun Baru</div>
    <div class="card-sub">Daftar sebagai pelanggan SIMALUN</div>

    @if(session('error'))
      <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif

    <!-- Step Indicator -->
    <div class="step-indicator" id="step-indicator">
      <div class="step-dot active" id="sdot-1">1</div>
      <div class="step-line" id="sline-1"></div>
      <div class="step-dot" id="sdot-2">2</div>
      <div class="step-line" id="sline-2"></div>
      <div class="step-dot" id="sdot-3">3</div>
    </div>

    <form method="POST" action="{{ route('register.post') }}" id="register-form" novalidate>
      @csrf

      <!-- ══ STEP 1: Data Diri ══ -->
      <div class="form-step active" id="step-1">
        <div class="form-group">
          <label class="form-label" for="name">Nama Lengkap</label>
          <div class="input-wrap">
            <span class="input-icon">👤</span>
            <input class="form-input @error('name') error @enderror"
              type="text" id="name" name="name"
              placeholder="Nama sesuai identitas"
              value="{{ old('name') }}" autocomplete="name">
          </div>
          @error('name')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-name"></div>
        </div>

        <div class="form-group">
          <label class="form-label">Jenis Kelamin</label>
          <div class="gender-row">
            <input type="radio" class="gender-opt" name="gender" id="g-male" value="L" {{ old('gender')=='L' ? 'checked' : '' }}>
            <label class="gender-label" for="g-male">
              <span>👨</span><span>Laki-laki</span>
            </label>
            <input type="radio" class="gender-opt" name="gender" id="g-female" value="P" {{ old('gender')=='P' ? 'checked' : '' }}>
            <label class="gender-label" for="g-female">
              <span>👩</span><span>Perempuan</span>
            </label>
          </div>
          <div class="form-error" id="err-gender"></div>
        </div>

        <div class="form-group">
          <label class="form-label" for="phone">Nomor HP (WhatsApp)</label>
          <div class="phone-row">
            <div class="phone-prefix">🇮🇩 +62</div>
            <div class="phone-input-wrap">
              <input class="form-input @error('phone') error @enderror"
                type="tel" id="phone" name="phone"
                placeholder="8xxxxxxxxxx"
                value="{{ old('phone') }}" inputmode="tel" autocomplete="tel"
                pattern="[0-9]{9,15}" title="Masukkan nomor HP valid (9-15 angka)">
            </div>
          </div>
          @error('phone')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-phone"></div>
        </div>

        <div class="btn-row">
          <button type="button" class="btn-next-step" id="btn-step1" onclick="goStep(2)">
            Lanjut &rarr;
          </button>
        </div>
      </div>

      <!-- ══ STEP 2: Akun & Password ══ -->
      <div class="form-step" id="step-2">
        <div class="form-group">
          <label class="form-label" for="email">
            Email <span style="font-weight:700;color:rgba(255,255,255,.55);text-transform:none;letter-spacing:0;">(opsional)</span>
          </label>
          <div class="input-wrap">
            <span class="input-icon">@</span>
            <input class="form-input @error('email') error @enderror"
              type="email" id="email" name="email"
              placeholder="Boleh dikosongkan"
              value="{{ old('email') }}" autocomplete="email" inputmode="email">
          </div>
          @error('email')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-email"></div>
          <div style="font-size:.66rem;font-weight:700;color:rgba(255,255,255,.55);margin-top:.35rem;line-height:1.4;">
            Notifikasi pesanan kami kirim lewat WhatsApp ke nomor HP kamu. Email hanya cadangan.
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">🔒</span>
            <input class="form-input" type="password" id="password" name="password"
              placeholder="Min. 8 karakter" autocomplete="new-password">
            <button type="button" class="pw-toggle" id="pw-toggle-1">👁️</button>
          </div>
          <div class="form-error" id="err-pw"></div>
          <!-- Strength meter -->
          <div class="pw-strength" id="pw-strength">
            <div class="pw-strength-bar">
              <div class="pw-strength-fill" id="pw-strength-fill"></div>
            </div>
            <div class="pw-strength-lbl" id="pw-strength-lbl">Ketik password...</div>
            <ul class="pw-hints">
              <li class="pw-hint" id="hint-len">Min. 8 karakter</li>
              <li class="pw-hint" id="hint-upper">Huruf besar</li>
              <li class="pw-hint" id="hint-num">Angka</li>
              <li class="pw-hint" id="hint-sym">Simbol (!@#...)</li>
            </ul>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
          <div class="input-wrap">
            <span class="input-icon">🔐</span>
            <input class="form-input" type="password" id="password_confirmation" name="password_confirmation"
              placeholder="Ulangi password" autocomplete="new-password">
            <button type="button" class="pw-toggle" id="pw-toggle-2">👁️</button>
          </div>
          <div class="form-error" id="err-pwc"></div>
        </div>

        <div class="btn-row">
          <button type="button" class="btn-back-step" onclick="goStep(1)">&#8592;</button>
          <button type="button" class="btn-next-step" onclick="goStep(3)">Lanjut &rarr;</button>
        </div>
      </div>

      <!-- ══ STEP 3: Alamat & Konfirmasi ══ -->
      <div class="form-step" id="step-3">
        <div class="form-group">
          <label class="form-label" for="address">Alamat Lengkap</label>
          <div class="input-wrap">
            <span class="input-icon" style="top:14px;transform:none;">🏠</span>
            <textarea class="form-input" id="address" name="address"
              placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."
              rows="3" style="padding-left:2.65rem;resize:none;">{{ old('address') }}</textarea>
          </div>
          @error('address')<div class="form-error show">{{ $message }}</div>@enderror
          <div class="form-error" id="err-address"></div>
        </div>

        <div class="form-group">
          <label class="form-label" for="address_note">Catatan Alamat (Opsional)</label>
          <div class="input-wrap">
            <span class="input-icon">📝</span>
            <input class="form-input" type="text" id="address_note" name="address_note"
              placeholder="Warna pagar, patokan, dll."
              value="{{ old('address_note') }}">
          </div>
        </div>

        <div class="terms-row">
          <input type="checkbox" class="terms-cb" id="terms" name="terms">
          <label class="terms-txt" for="terms">
            Saya menyetujui <a href="#" onclick="return false;">Syarat & Ketentuan</a>
            serta <a href="#" onclick="return false;">Kebijakan Privasi</a> Azka Laundry
          </label>
        </div>
        <div class="form-error" id="err-terms"></div>

        <div class="btn-row">
          <button type="button" class="btn-back-step" onclick="goStep(2)">&#8592;</button>
          <button type="submit" class="btn-submit" id="btn-submit">
            <span class="btn-spinner"></span>
            <span class="btn-text">Daftar Sekarang</span>
          </button>
        </div>
      </div>

    </form>

    <!-- Success State (shown after successful registration) -->
    <div id="success-state">
      @if(session('registered'))
      <div class="success-icon">🎉</div>
      <div class="success-title">Akun Berhasil Dibuat!</div>
      <div class="success-sub">
        Selamat datang di SIMALUN!<br>
        Akun kamu sudah aktif. Silakan masuk untuk mulai memesan.
      </div>
      <a href="{{ route('login') }}" class="btn-to-login">Masuk Sekarang &rarr;</a>
      @endif
    </div>

    <div class="login-row">
      Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
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
    const amb=document.getElementById('ambient'), VH=window.innerHeight;
    const cfg=[[8,.7],[14,.5],[6,.8],[20,.42],[10,.65],[5,.85],[16,.5],[9,.72],[24,.38],[7,.78]];
    cfg.forEach(([base,al],i)=>{
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

  /* ── INTRO ANIMATION ── */
  const tl = gsap.timeline();
  tl.to('#header',{opacity:1,y:0,duration:.9,ease:'elastic.out(.75,.65)'},.2)
    .to('#card',{opacity:1,y:0,duration:.6,ease:'back.out(1.6)'},.55);

  /* ── SHOW SUCCESS STATE IF SESSION ── */
  @if(session('registered'))
    document.getElementById('step-indicator').style.display = 'none';
    document.querySelectorAll('.form-step,.login-row').forEach(el => el.style.display='none');
    const ss = document.getElementById('success-state');
    ss.style.display = 'block';
    gsap.fromTo(ss, {opacity:0, scale:.9}, {opacity:1, scale:1, duration:.6, ease:'back.out(1.8)'});
  @endif

  /* ── STEP NAVIGATION ── */
  let currentStep = 1;
  window.goStep = function(next) {
    if (!validateStep(currentStep)) return;
    const prev = currentStep;
    currentStep = next;

    // animate out
    gsap.to(`#step-${prev}`, {opacity:0, x: next>prev ? -30 : 30, duration:.22, ease:'power2.in', onComplete(){
      document.getElementById(`step-${prev}`).classList.remove('active');
      document.getElementById(`step-${next}`).classList.add('active');
      // animate in
      gsap.fromTo(`#step-${next}`,{opacity:0, x: next>prev ? 30 : -30},{opacity:1, x:0, duration:.32, ease:'power2.out'});
    }});

    updateStepDots(next);
    // pulse drum on step change
    gsap.to('#drum-svg',{rotation:'+=90',duration:.25,ease:'power2.out'});
  };

  function updateStepDots(step) {
    for (let i = 1; i <= 3; i++) {
      const dot = document.getElementById(`sdot-${i}`);
      dot.classList.remove('active','done');
      if (i < step) dot.classList.add('done'), dot.textContent = '✓';
      else if (i === step) dot.classList.add('active'), dot.textContent = i;
      else dot.textContent = i;
    }
    for (let i = 1; i <= 2; i++) {
      document.getElementById(`sline-${i}`).classList.toggle('done', i < step);
    }
  }

  /* ── STEP VALIDATION ── */
  function validateStep(step) {
    let ok = true;
    if (step === 1) {
      const name = document.getElementById('name').value.trim();
      const gender = document.querySelector('input[name="gender"]:checked');
      const phone = document.getElementById('phone').value.trim();

      clearErr('err-name'); clearErr('err-gender'); clearErr('err-phone');
      document.getElementById('name').classList.remove('error');
      document.getElementById('phone').classList.remove('error');

      if (!name || name.length < 3) { showErr('err-name','Nama minimal 3 karakter'); document.getElementById('name').classList.add('error'); ok=false; }
      if (!gender) { showErr('err-gender','Pilih jenis kelamin'); ok=false; }
      if (!phone || !/^8[0-9]{7,12}$/.test(phone.replace(/[\s-]/g,''))) {
        showErr('err-phone','Nomor HP tidak valid (contoh: 81234567890)');
        document.getElementById('phone').classList.add('error'); ok=false;
      }
    } else if (step === 2) {
      const email = document.getElementById('email').value.trim();
      const pw = document.getElementById('password').value;
      const pwc = document.getElementById('password_confirmation').value;

      clearErr('err-email'); clearErr('err-pw'); clearErr('err-pwc');
      document.getElementById('email').classList.remove('error');
      document.getElementById('password').classList.remove('error');
      document.getElementById('password_confirmation').classList.remove('error');

      // Email opsional — hanya validasi format jika diisi
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showErr('err-email','Format email tidak valid'); document.getElementById('email').classList.add('error'); ok=false;
      }
      if (!pw || pw.length < 8) {
        showErr('err-pw','Password minimal 8 karakter'); document.getElementById('password').classList.add('error'); ok=false;
      }
      if (pw !== pwc) {
        showErr('err-pwc','Konfirmasi password tidak cocok'); document.getElementById('password_confirmation').classList.add('error'); ok=false;
      }
    }
    if (!ok) {
      gsap.fromTo('#card',{x:-6},{x:0,duration:.4,ease:'elastic.out(1,.4)'});
    }
    return ok;
  }

  function showErr(id, msg) { const el=document.getElementById(id); if(el){el.textContent=msg;el.classList.add('show');} }
  function clearErr(id) { const el=document.getElementById(id); if(el){el.textContent='';el.classList.remove('show');} }

  /* ── STEP 3 SUBMIT VALIDATION ── */
  document.getElementById('register-form').addEventListener('submit', function(e) {
    const addr = document.getElementById('address').value.trim();
    const terms = document.getElementById('terms').checked;
    clearErr('err-address'); clearErr('err-terms');
    let ok = true;
    if (!addr || addr.length < 10) { showErr('err-address','Alamat minimal 10 karakter'); ok=false; }
    if (!terms) { showErr('err-terms','Kamu harus menyetujui syarat & ketentuan'); ok=false; }
    if (!ok) { e.preventDefault(); gsap.fromTo('#card',{x:-6},{x:0,duration:.4,ease:'elastic.out(1,.4)'}); return; }
    document.getElementById('btn-submit').classList.add('loading');
    document.getElementById('btn-submit').disabled = true;
  });

  /* ── PASSWORD TOGGLE ── */
  function togglePw(inputId, btnId) {
    const input = document.getElementById(inputId);
    const btn = document.getElementById(btnId);
    btn.addEventListener('click', function() {
      const show = input.type === 'password';
      input.type = show ? 'text' : 'password';
      this.textContent = show ? '🙈' : '👁️';
    });
  }
  togglePw('password','pw-toggle-1');
  togglePw('password_confirmation','pw-toggle-2');

  /* ── PASSWORD STRENGTH ── */
  const pwInput = document.getElementById('password');
  pwInput.addEventListener('input', function() {
    const v = this.value;
    const strength = document.getElementById('pw-strength');
    const fill = document.getElementById('pw-strength-fill');
    const lbl = document.getElementById('pw-strength-lbl');
    if (!v) { strength.classList.remove('show'); return; }
    strength.classList.add('show');

    const hasLen = v.length >= 8;
    const hasUpper = /[A-Z]/.test(v);
    const hasNum = /[0-9]/.test(v);
    const hasSym = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(v);

    document.getElementById('hint-len').classList.toggle('ok', hasLen);
    document.getElementById('hint-upper').classList.toggle('ok', hasUpper);
    document.getElementById('hint-num').classList.toggle('ok', hasNum);
    document.getElementById('hint-sym').classList.toggle('ok', hasSym);

    const score = [hasLen, hasUpper, hasNum, hasSym].filter(Boolean).length;
    const configs = [
      {w:'15%', bg:'#ef4444', txt:'Sangat Lemah'},
      {w:'35%', bg:'#f97316', txt:'Lemah'},
      {w:'60%', bg:'#eab308', txt:'Cukup'},
      {w:'80%', bg:'#22c55e', txt:'Kuat'},
      {w:'100%',bg:'#00C48C', txt:'Sangat Kuat'},
    ];
    const cfg = configs[score] || configs[0];
    fill.style.width = cfg.w;
    fill.style.background = cfg.bg;
    lbl.textContent = cfg.txt;
    lbl.style.color = cfg.bg;
  });

  /* ── PHONE: strip leading 0 ── */
  document.getElementById('phone').addEventListener('input', function() {
    if (this.value.startsWith('0')) this.value = this.value.slice(1);
  });

  /* ── If Laravel returned errors, show on correct step ── */
  @if($errors->any())
    @if($errors->has('name') || $errors->has('gender') || $errors->has('phone'))
      currentStep = 1;
    @elseif($errors->has('email') || $errors->has('password'))
      goStep(2);
    @else
      goStep(3);
    @endif
  @endif

});
</script>
</body>
</html>