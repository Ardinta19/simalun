<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Edit Profil – Azka Laundry SIMALUN</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
:root {
  --c-ocean-deep: #002f5c;
  --c-ocean-mid: #0077b6;
  --c-ocean-light: #00b4d8;
  --c-ocean-sky: #48cae4;
  --c-fire: #FF6B35;
  --c-fire-soft: #ff8c5a;
  --c-mint: #00C48C;
  --c-surface: #f2f8fd;
  --c-ink: #0d2137;
  --c-ink-mid: #3a546b;
  --c-ink-soft: #7a9ab0;
  --c-line: #daedf8;
}

*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html, body {
  width:100%; min-height:100%; font-family:'Nunito',sans-serif;
  background:linear-gradient(168deg, var(--c-ocean-deep) 0%, var(--c-ocean-mid) 45%, var(--c-ocean-light) 78%, var(--c-ocean-sky) 100%);
  overflow-x:hidden;
}

/* Ambient Background */
#ambient { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
#wave-band { position:fixed; bottom:0; left:-5%; right:-5%; height:40%; pointer-events:none; z-index:0; }
.ws { position:absolute; left:0; width:200%; overflow:visible; }
.ws.w1 { bottom:38%; }
.ws.w2 { bottom:34%; opacity:.55; }
.ws.w3 { bottom:30%; opacity:.28; }

/* Page Container */
#page {
  position:relative; z-index:1; min-height:100vh;
  display:flex; flex-direction:column; align-items:center;
  padding: 1rem 1.2rem calc(80px + env(safe-area-inset-bottom, 0px) + 1rem);
  width:100%;
  max-width: 100%;
}

/* Header */
#header {
  display:flex; flex-direction:column; align-items:center;
  gap:.35rem; padding-top:max(2rem, env(safe-area-inset-top));
  margin-bottom:1.6rem; opacity:0;
  width:100%;
}
#drum-wrap { width:72px; height:72px; position:relative; }
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

.wm-name { font-family:'Fredoka One',cursive; font-size:1.8rem; color:#fff; letter-spacing:-.5px; text-shadow:0 3px 18px rgba(0,0,0,.28); line-height:1; }
.wm-sub  { font-size:.6rem; font-weight:800; color:rgba(144,224,239,.9); letter-spacing:5px; text-transform:uppercase; margin-top:.2rem; }

/* Card */
.profile-card {
  width:100%; max-width:440px;
  background:rgba(255,255,255,.10);
  border:1.5px solid rgba(255,255,255,.22);
  border-radius:24px;
  backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px);
  box-shadow:0 16px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.2);
  padding:1.8rem 1.6rem 1.6rem;
  opacity:0; transform:translateY(32px);
  margin-bottom: 20px;
}
.card-title { font-family:'Fredoka One',cursive; font-size:1.4rem; color:#fff; text-align:center; margin-bottom:.25rem; }
.card-sub { font-size:.8rem; font-weight:700; color:rgba(255,255,255,.7); text-align:center; margin-bottom:1.3rem; }

/* Form */
.form-group { margin-bottom:1.2rem; }
.form-label { display:block; font-size:.72rem; font-weight:800; color:rgba(255,255,255,.85); letter-spacing:.4px; margin-bottom:.45rem; text-transform:uppercase; padding-left:4px; }
.input-wrap { position:relative; }
.input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; opacity:.7; }
.form-input {
  width:100%; padding:.82rem .9rem .82rem 2.7rem;
  background:rgba(255,255,255,.12);
  border:1.5px solid rgba(255,255,255,.22);
  border-radius:14px; color:#fff;
  font-family:'Nunito',sans-serif; font-size:.93rem; font-weight:700;
  outline:none; transition:all .3s;
}
.form-input:focus {
  border-color:rgba(255,255,255,.6);
  background:rgba(255,255,255,.18);
  box-shadow:0 0 0 4px rgba(255,255,255,.1);
  transform: scale(1.01);
}
.form-input::placeholder { color:rgba(255,255,255,.45); }

/* Buttons */
.btn { width:100%; padding:.9rem; border:none; border-radius:99px; font-family:'Nunito',sans-serif; font-weight:900; font-size:1rem; letter-spacing:.4px; cursor:pointer; position:relative; overflow:hidden; transition:all .2s; }
.btn-pri { background:linear-gradient(135deg, var(--c-fire) 0%, var(--c-fire-soft) 100%); color:#fff; box-shadow:0 8px 24px rgba(255,107,53,0.4); }
.btn-pri:active { transform:scale(.97); }
.btn-pri:hover { box-shadow:0 12px 32px rgba(255,107,53,0.55); }
.btn-sec { background:rgba(255,255,255,.1); border:1.5px solid rgba(255,255,255,.2); color:#fff; margin-top:10px; font-size:0.85rem; }

/* Alert */
.alert { border-radius:14px; padding:.8rem 1rem; margin-bottom:1.2rem; font-size:.85rem; font-weight:700; display:flex; align-items:center; gap:8px; max-width:440px; width:100%; }
.alert-success { background:rgba(0,196,140,.18); border:1px solid rgba(0,196,140,.35); color:#7fffd4; }
.alert-error { background:rgba(239,68,68,.18); border:1px solid rgba(239,68,68,.35); color:#fca5a5; }

/* Back Button */
#btn-back {
  position:fixed; top:max(1rem, env(safe-area-inset-top)); left:1rem;
  z-index:10; width:42px; height:42px; border-radius:50%;
  cursor:pointer; background:rgba(255,255,255,.15);
  backdrop-filter:blur(8px); border:1.5px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  color:#fff; text-decoration:none;
  transition:all .25s cubic-bezier(.34,1.56,.64,1);
}
#btn-back:hover { background:rgba(255,255,255,.25); transform:translateX(-3px) scale(1.05); }
#btn-back svg { width:20px; height:20px; }

/* Avatar */
.avatar-edit-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:20px; position:relative; }
.avatar-preview { width:90px; height:90px; border-radius:30px; object-fit:cover; border:3px solid #fff; box-shadow:0 8px 20px rgba(0,0,0,0.2); }
.avatar-label {
  position:absolute; bottom:-5px; right:calc(50% - 50px);
  background:var(--c-fire); width:32px; height:32px;
  border-radius:10px; display:flex; align-items:center; justify-content:center;
  color:#fff; border:2.5px solid #fff; cursor:pointer; font-size:0.9rem;
  transition: transform .2s;
}
.avatar-label:hover { transform: scale(1.1); }

/* Validation errors */
.field-error { font-size:.73rem; font-weight:700; color:#fca5a5; margin-top:5px; padding-left:4px; }

/* ═══════ RESPONSIVE ═══════ */
@media (min-width: 768px) {
  #page { padding: 2rem 2rem calc(80px + env(safe-area-inset-bottom, 0px) + 2rem); }
  .profile-card { max-width: 500px; padding: 2.2rem 2rem 2rem; }
  .card-title { font-size: 1.6rem; }
  #drum-wrap { width: 80px; height: 80px; }
  .wm-name { font-size: 2rem; }
}

@media (min-width: 1024px) {
  #page { padding: 3rem 2rem calc(80px + env(safe-area-inset-bottom, 0px) + 2rem); }
  .profile-card { max-width: 540px; padding: 2.5rem 2.2rem; border-radius: 28px; }
  .form-input { font-size: 1rem; padding: .9rem 1rem .9rem 2.8rem; }
}

@media (min-width: 1280px) {
  .profile-card { max-width: 580px; }
}
</style>
</head>
<body>

<div id="ambient" aria-hidden="true"></div>
<div id="wave-band" aria-hidden="true">
  <svg class="ws w1" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.20)" d="M0,32 C200,64 400,0 600,32 C800,64 1000,0 1200,32 C1320,48 1400,20 1440,32 L1440,64 L0,64Z"/></svg>
  <svg class="ws w2" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.14)" d="M0,16 C180,46 360,4 540,24 C720,44 900,8 1080,28 C1260,48 1380,12 1440,22 L1440,64 L0,64Z"/></svg>
  <svg class="ws w3" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.08)" d="M0,44 C160,12 320,52 480,36 C640,20 800,50 960,32 C1120,14 1300,46 1440,30 L1440,64 L0,64Z"/></svg>
</div>

{{-- BACK BUTTON — Fixed route to customer profile --}}
<a href="{{ route('customer.profile') }}" id="btn-back" aria-label="Kembali ke profil">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
    <path d="M19 12H5M12 5l-7 7 7 7"/>
  </svg>
</a>

<div id="page">
  <div id="header">
    <div id="drum-wrap">
      <div id="drum-spin-ring"></div>
      <svg width="100%" viewBox="0 0 148 148" fill="none">
        <circle cx="74" cy="74" r="70" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.18)" stroke-width="1.5"/>
        <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.14)" stroke="rgba(255,255,255,.35)" stroke-width="1.8"/>
        <circle cx="36" cy="41" r="7" fill="#FF6B35"/><circle cx="56" cy="41" r="7" fill="#00C48C"/>
        <circle cx="74" cy="90" r="38" fill="rgba(0,100,160,.55)" stroke="rgba(255,255,255,.45)" stroke-width="2.5"/>
      </svg>
    </div>
    <div class="wm-name">SIMALUN</div>
    <div class="wm-sub">PENGATURAN PROFIL</div>
  </div>

  {{-- Success Alert --}}
  @if(session('status') === 'profile-updated')
    <div class="alert alert-success js-in">Profil berhasil diperbarui!</div>
  @endif

  {{-- Validation Errors --}}
  @if($errors->any())
    <div class="alert alert-error js-in">
      <span>Terdapat kesalahan pada data yang kamu isi. Periksa kembali form di bawah.</span>
    </div>
  @endif

  {{-- CARD 1: Informasi Akun --}}
  <div class="profile-card" id="card-info">
    <div class="card-title">Informasi Akun</div>
    <div class="card-sub">Perbarui data diri & foto profil</div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method('patch')

      {{-- Avatar Upload --}}
      <div class="avatar-edit-wrap">
        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=fff&color=0077b6&size=180' }}" class="avatar-preview" id="preview" alt="Foto profil">
        <label for="avatar-input" class="avatar-label" title="Ganti foto profil">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
        </label>
        <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display:none">
      </div>
      @error('avatar') <div class="field-error" style="text-align:center;margin-top:-12px;margin-bottom:12px;">{{ $message }}</div> @enderror

      {{-- Name --}}
      <div class="form-group">
        <label class="form-label" for="input-name">Nama Lengkap</label>
        <div class="input-wrap">
          <span class="input-icon">👤</span>
          <input type="text" name="name" id="input-name" class="form-input" value="{{ old('name', $user->name) }}" required autocomplete="name">
        </div>
        @error('name') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      {{-- Email --}}
      <div class="form-group">
        <label class="form-label" for="input-email">Alamat Email</label>
        <div class="input-wrap">
          <span class="input-icon">📧</span>
          <input type="email" name="email" id="input-email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="email">
        </div>
        @error('email') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      {{-- Phone --}}
      <div class="form-group">
        <label class="form-label" for="input-phone">Nomor WhatsApp</label>
        <div class="input-wrap">
          <span class="input-icon">📱</span>
          <input type="text" name="phone" id="input-phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" autocomplete="tel">
        </div>
        @error('phone') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn btn-pri" id="btn-save-profile">Simpan Perubahan</button>
    </form>
  </div>

  {{-- CARD 2: Keamanan Akun --}}
  <div class="profile-card" id="card-security">
    <div class="card-title">Keamanan Akun</div>
    <div class="card-sub">Ganti password secara berkala</div>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      @method('put')

      <div class="form-group">
        <label class="form-label" for="current-password">Password Saat Ini</label>
        <div class="input-wrap">
          <span class="input-icon">🔑</span>
          <input type="password" name="current_password" id="current-password" class="form-input" placeholder="Masukkan password saat ini" autocomplete="current-password">
        </div>
        @error('current_password') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="new-password">Password Baru</label>
        <div class="input-wrap">
          <span class="input-icon">🔐</span>
          <input type="password" name="password" id="new-password" class="form-input" placeholder="Min. 8 karakter" autocomplete="new-password">
        </div>
        @error('password') <div class="field-error">{{ $message }}</div> @enderror
      </div>

      <div class="form-group">
        <label class="form-label" for="confirm-password">Konfirmasi Password Baru</label>
        <div class="input-wrap">
          <span class="input-icon">🛡️</span>
          <input type="password" name="password_confirmation" id="confirm-password" class="form-input" placeholder="Ulangi password baru" autocomplete="new-password">
        </div>
      </div>

      <button type="submit" class="btn btn-pri" style="background:linear-gradient(135deg, var(--c-ocean-deep), var(--c-ocean-mid))">Perbarui Password</button>
    </form>
  </div>

  {{-- Delete Account --}}
  @if($user->role !== 'admin')
  <div style="max-width:440px;width:100%;">
    <button type="button" class="btn btn-sec" onclick="confirm('Apakah kamu yakin ingin menghapus akun secara permanen? Tindakan ini tidak bisa dibatalkan.') && document.getElementById('del-form').submit()" style="color:#ff6b6b; border-color:rgba(255,107,107,0.3)">Hapus Akun</button>
    <form id="del-form" method="POST" action="{{ route('profile.destroy') }}" style="display:none">@csrf @method('delete')</form>
  </div>
  @endif

  <div style="margin-top:20px; opacity:0.4; font-size:0.7rem; font-weight:800; color:#fff; letter-spacing:1px">SIMALUN v1.0</div>
</div>

{{-- SHARED BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', function(){

  /* ── WAVE ANIMATIONS ── */
  document.querySelectorAll('.ws').forEach((w,i)=>{
    gsap.fromTo(w,{x:'0%'},{x:'-50%',duration:14+i*4,ease:'none',repeat:-1});
    gsap.to(w,{y:i%2?5:-5,duration:3+i*1.5,ease:'sine.inOut',yoyo:true,repeat:-1});
  });

  /* ── AMBIENT BUBBLES ── */
  (function(){
    const amb = document.getElementById('ambient'), VH = window.innerHeight;
    for(let i=0; i<12; i++){
      const sz = 8+Math.random()*10, el = document.createElement('div');
      el.style.cssText=['position:absolute',`width:${sz}px`,`height:${sz}px`,'border-radius:50%','background:rgba(255,255,255,0.15)','border:1px solid rgba(255,255,255,0.2)',`left:${Math.random()*100}%`,`top:${VH+20}px`,'pointer-events:none'].join(';');
      amb.appendChild(el);
      gsap.fromTo(el,{y:0},{y:-(VH*1.5), duration:10+Math.random()*10, ease:'none', repeat:-1, delay:Math.random()*5});
    }
  })();

  /* ── ENTRANCE ANIMATIONS ── */
  const tl = gsap.timeline();
  tl.to('#header', {opacity:1, y:0, duration:0.8, ease:'back.out(1.7)'}, 0.2)
    .to('.profile-card', {opacity:1, y:0, duration:0.6, stagger:0.15, ease:'power2.out'}, 0.4);

  /* ── BACK BUTTON ENTRANCE ── */
  gsap.from('#btn-back', {opacity:0, x:-20, duration:0.5, delay:0.3, ease:'power2.out'});

  /* ── AVATAR PREVIEW ANIMATION ── */
  const avatarInput = document.getElementById('avatar-input');
  if (avatarInput) {
    avatarInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        const file = this.files[0];
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
          alert('Ukuran foto maksimal 2MB. Silakan pilih foto yang lebih kecil.');
          this.value = '';
          return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
          const preview = document.getElementById('preview');
          preview.src = e.target.result;
          gsap.from(preview, {scale:0.8, opacity:0.5, duration:0.4, ease:'back.out(2)'});
        }
        reader.readAsDataURL(file);
      }
    });
  }

  /* ── FORM SUBMIT FEEDBACK ── */
  document.getElementById('btn-save-profile').closest('form').addEventListener('submit', function(e) {
    const btn = document.getElementById('btn-save-profile');
    btn.textContent = 'Menyimpan...';
    btn.style.opacity = '0.7';
    btn.style.pointerEvents = 'none';
  });

  /* ── NAVBAR TOUCH FEEDBACK ── */
  document.querySelectorAll('.customer-nav__item, .customer-nav__fab').forEach(el => {
    el.addEventListener('touchstart', function() {
      gsap.to(this, { scale:.92, duration:.09, ease:'power2.out' });
    }, { passive: true });
    el.addEventListener('touchend', function() {
      gsap.to(this, { scale:1, duration:.22, ease:'back.out(2.5)' });
    }, { passive: true });
  });

});
</script>

</body>
</html>
