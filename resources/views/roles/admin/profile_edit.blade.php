<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Edit Profil Admin – Azka Laundry SIMALUN</title>
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

#ambient { position:fixed; inset:0; pointer-events:none; z-index:0; overflow:hidden; }
#wave-band { position:fixed; bottom:0; left:-5%; right:-5%; height:40%; pointer-events:none; z-index:0; }
.ws { position:absolute; left:0; width:200%; overflow:visible; }
.ws.w1 { bottom:38%; }
.ws.w2 { bottom:34%; opacity:.55; }
.ws.w3 { bottom:30%; opacity:.28; }

#page { position:relative; z-index:1; min-height:100vh; display:flex; flex-direction:column; align-items:center; padding:2rem 1.2rem max(4rem, env(safe-area-inset-bottom)); }

#header { display:flex; flex-direction:column; align-items:center; gap:.35rem; padding-top:max(2rem, env(safe-area-inset-top)); margin-bottom:1.6rem; opacity:0; }
#drum-wrap { width:72px; height:72px; position:relative; }
#drum-wrap::after { content:''; position:absolute; inset:-8px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.14) 40%, transparent 72%); animation:halo 3.5s ease-in-out infinite; }
@keyframes halo { 0%,100%{transform:scale(1)} 50%{transform:scale(1.09)} }
#drum-spin-ring { position:absolute; inset:-4px; border-radius:50%; border:2px dashed rgba(255,255,255,.28); animation:ring-spin 6s linear infinite; pointer-events:none; }
@keyframes ring-spin { to{transform:rotate(360deg);} }

.wm-name { font-weight:800; font-size:1.8rem; color:#fff; letter-spacing:-.5px; text-shadow:0 3px 18px rgba(0,0,0,.28); line-height:1; }
.wm-sub  { font-size:.6rem; font-weight:800; color:rgba(144,224,239,.9); letter-spacing:5px; text-transform:uppercase; margin-top:.2rem; }

#card { width:100%; max-width:440px; background:rgba(255,255,255,.10); border:1.5px solid rgba(255,255,255,.22); border-radius:24px; backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px); box-shadow:0 16px 48px rgba(0,0,0,.22), inset 0 1px 0 rgba(255,255,255,.2); padding:1.8rem 1.6rem 1.6rem; opacity:0; transform:translateY(32px); margin-bottom: 20px; }
.card-title { font-weight:800; font-size:1.4rem; color:#fff; text-align:center; margin-bottom:.25rem; }
.card-sub { font-size:.8rem; font-weight:700; color:rgba(255,255,255,.7); text-align:center; margin-bottom:1.3rem; }

.form-group { margin-bottom:1.2rem; }
.form-label { display:block; font-size:.72rem; font-weight:800; color:rgba(255,255,255,.85); letter-spacing:.4px; margin-bottom:.45rem; text-transform:uppercase; padding-left:4px; }
.input-wrap { position:relative; }
.input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); font-size:1rem; pointer-events:none; opacity:.7; }
.form-input { width:100%; padding:.82rem .9rem .82rem 2.7rem; background:rgba(255,255,255,.12); border:1.5px solid rgba(255,255,255,.22); border-radius:14px; color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-size:.93rem; font-weight:700; outline:none; transition:all .3s; }
.form-input:focus { border-color:rgba(255,255,255,.6); background:rgba(255,255,255,.18); box-shadow:0 0 0 4px rgba(255,255,255,.1); transform: scale(1.01); }

.btn { width:100%; padding:.9rem; border:none; border-radius:99px; font-family:'Plus Jakarta Sans',sans-serif; font-weight:900; font-size:1rem; letter-spacing:.4px; cursor:pointer; position:relative; overflow:hidden; transition:all .2s; }
.btn-pri { background:linear-gradient(135deg,#FF6B35 0%,#ff8c5a 100%); color:#fff; box-shadow:0 8px 24px rgba(255,107,53,0.4); }
.btn-pri:active { transform:scale(.97); }
.btn-pri:hover { box-shadow:0 12px 32px rgba(255,107,53,0.55); }

.alert { border-radius:14px; padding:.8rem 1rem; margin-bottom:1.2rem; font-size:.85rem; font-weight:700; display:flex; align-items:center; gap:8px; }
.alert-success { background:rgba(0,196,140,.18); border:1px solid rgba(0,196,140,.35); color:#7fffd4; }

#btn-back { position:fixed; top:max(1rem, env(safe-area-inset-top)); left:1rem; z-index:10; width:38px; height:38px; border-radius:50%; border:none; cursor:pointer; background:rgba(255,255,255,.15); backdrop-filter:blur(8px); border:1px solid rgba(255,255,255,.25); display:flex; align-items:center; justify-content:center; color:#fff; font-size:1.1rem; transition:all .2s; text-decoration:none; }
#btn-back:hover { background:rgba(255,255,255,.25); transform:translateX(-3px); }

.avatar-edit-wrap { display:flex; flex-direction:column; align-items:center; margin-bottom:20px; position:relative; }
.avatar-preview { width:90px; height:90px; border-radius:30px; object-fit:cover; border:3px solid #fff; box-shadow:0 8px 20px rgba(0,0,0,0.2); }
.avatar-label { position:absolute; bottom:-5px; right:calc(50% - 50px); background:#FF6B35; width:32px; height:32px; border-radius:10px; display:flex; align-items:center; justify-content:center; color:#fff; border:2.5px solid #fff; cursor:pointer; font-size:0.9rem; }
</style>
</head>
<body>

<div id="ambient" aria-hidden="true"></div>
<div id="wave-band" aria-hidden="true">
  <svg class="ws w1" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.20)" d="M0,32 C200,64 400,0 600,32 C800,64 1000,0 1200,32 C1320,48 1400,20 1440,32 L1440,64 L0,64Z"/></svg>
  <svg class="ws w2" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.14)" d="M0,16 C180,46 360,4 540,24 C720,44 900,8 1080,28 C1260,48 1380,12 1440,22 L1440,64 L0,64Z"/></svg>
  <svg class="ws w3" viewBox="0 0 1440 64" preserveAspectRatio="none"><path fill="rgba(255,255,255,.08)" d="M0,44 C160,12 320,52 480,36 C640,20 800,50 960,32 C1120,14 1300,46 1440,30 L1440,64 L0,64Z"/></svg>
</div>

<a href="{{ route('dashboard.admin') }}" id="btn-back">&#8592;</a>

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
    <div class="wm-sub">ADMIN PROFILE EDIT</div>
  </div>

  @if(session('status') === 'profile-updated')
    <div class="alert alert-success js-in">✅ Profil admin berhasil diperbarui!</div>
  @endif

  <div id="card">
    <div class="card-title">Data Identitas 👤</div>
    <div class="card-sub">Perbarui informasi dasar admin</div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
      @csrf
      @method('patch')

      <div class="avatar-edit-wrap">
        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=fff&color=0077b6' }}" class="avatar-preview" id="preview">
        <label for="avatar-input" class="avatar-label">📸</label>
        <input type="file" id="avatar-input" name="avatar" style="display:none" onchange="previewImage(this)">
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-wrap">
          <span class="input-icon">👤</span>
          <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat Email</label>
        <div class="input-wrap">
          <span class="input-icon">📧</span>
          <input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor WhatsApp</label>
        <div class="input-wrap">
          <span class="input-icon">📱</span>
          <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
        </div>
      </div>

      <button type="submit" class="btn btn-pri">Simpan Perubahan</button>
    </form>
  </div>

  <div id="card">
    <div class="card-title">Ganti Password 🔒</div>
    <div class="card-sub">Amankan akses panel administrator</div>

    <form method="POST" action="{{ route('password.update') }}">
      @csrf
      @method('put')

      <div class="form-group">
        <label class="form-label">Password Saat Ini</label>
        <div class="input-wrap">
          <span class="input-icon">🔑</span>
          <input type="password" name="current_password" class="form-input" placeholder="••••••••">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Password Baru</label>
        <div class="input-wrap">
          <span class="input-icon">🔐</span>
          <input type="password" name="password" class="form-input" placeholder="Min. 8 karakter">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Konfirmasi Password Baru</label>
        <div class="input-wrap">
          <span class="input-icon">🛡️</span>
          <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password">
        </div>
      </div>

      <button type="submit" class="btn btn-pri" style="background:linear-gradient(135deg, #002f5c, #0077b6)">Perbarui Kata Sandi</button>
    </form>
  </div>

  <div style="margin-top:20px; opacity:0.4; font-size:0.7rem; font-weight:800; color:#fff; letter-spacing:1px">SIMALUN ADMIN PANEL</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.ws').forEach((w,i)=>{
    gsap.fromTo(w,{x:'0%'},{x:'-50%',duration:14+i*4,ease:'none',repeat:-1});
    gsap.to(w,{y:i%2?5:-5,duration:3+i*1.5,ease:'sine.inOut',yoyo:true,repeat:-1});
  });

  (function(){
    const amb = document.getElementById('ambient'), VH = window.innerHeight;
    for(let i=0; i<15; i++){
      const sz = 8+Math.random()*10, el = document.createElement('div');
      el.style.cssText=['position:absolute',`width:${sz}px`,`height:${sz}px`,'border-radius:50%','background:rgba(255,255,255,0.15)','border:1.5px solid rgba(255,255,255,0.2)',`left:${Math.random()*100}%`,`top:${VH+20}px`,'pointer-events:none'].join(';');
      amb.appendChild(el);
      gsap.fromTo(el,{y:0},{y:-(VH*1.5), duration:10+Math.random()*10, ease:'none', repeat:-1, delay:Math.random()*5});
    }
  })();

  const tl = gsap.timeline();
  tl.to('#header', {opacity:1, y:0, duration:0.8, ease:'back.out(1.7)'}, 0.2)
    .to('#card', {opacity:1, y:0, duration:0.6, stagger:0.15, ease:'power2.out'}, 0.4);
});

function previewImage(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('preview').src = e.target.result;
      gsap.from('#preview', {scale:0.8, opacity:0.5, duration:0.4, ease:'back.out(2)'});
    }
    reader.readAsDataURL(input.files[0]);
  }
}
</script>

</body>
</html>
