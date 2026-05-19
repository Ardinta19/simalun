<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root {
  --blue-dark:#002f5c; --blue-mid:#0077b6; --blue-light:#00b4d8;
  --blue-sky:#e0f4ff; --orange:#FF6B35; --green:#00C48C;
  --white:#ffffff; --ink:#1a2332; --ink-mid:#3d5066; --ink-lt:#8899aa;
  --surface:#f4f8fc; --border:#ddeeff; --radius:16px; --nav-h:72px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:var(--nav-h);}

.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);padding:0;position:relative;overflow:hidden;}
.top-header::before{content:'';position:absolute;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,.06);top:-80px;right:-60px;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 20px;max-width:520px;margin:0 auto;}
.header-top{display:flex;align-items:center;gap:12px;margin-bottom:0;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;flex:1;}
.header-wave{display:block;width:100%;margin-bottom:-2px;}

.page-body{max-width:520px;margin:0 auto;padding:16px 16px 8px;}

/* Avatar card */
.avatar-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:24px 20px;text-align:center;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.avatar-wrap{width:80px;height:80px;border-radius:50%;background:var(--blue-sky);border:3px solid var(--border);margin:0 auto 12px;display:flex;align-items:center;justify-content:center;font-size:2rem;overflow:hidden;}
.avatar-wrap img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.profile-name{font-family:'Fredoka One',cursive;font-size:1.3rem;color:var(--ink);}
.profile-role{font-size:.75rem;font-weight:800;color:var(--ink-lt);letter-spacing:.5px;text-transform:uppercase;margin-top:4px;}
.edit-btn{display:inline-flex;align-items:center;gap:.4rem;background:var(--blue-mid);color:#fff;font-family:'Nunito',sans-serif;font-weight:800;font-size:.82rem;border:none;border-radius:99px;padding:.5rem 1.2rem;cursor:pointer;text-decoration:none;margin-top:14px;transition:background .2s;}
.edit-btn:hover{background:var(--blue-dark);}

/* Info list */
.info-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.info-row{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--border);}
.info-row:last-child{border-bottom:none;}
.info-icon{width:36px;height:36px;border-radius:10px;background:var(--blue-sky);display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;}
.info-body{flex:1;min-width:0;}
.info-label{font-size:.68rem;font-weight:800;color:var(--ink-lt);text-transform:uppercase;letter-spacing:.3px;}
.info-value{font-size:.92rem;font-weight:700;color:var(--ink);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}

/* Danger zone */
.danger-card{background:#fff;border:1.5px solid #fee2e2;border-radius:var(--radius);padding:14px 16px;margin-bottom:16px;}
.danger-title{font-size:.82rem;font-weight:800;color:#ef4444;margin-bottom:10px;}
.btn-logout{display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.65rem;border-radius:99px;border:1.5px solid #ef4444;background:transparent;color:#ef4444;font-family:'Nunito',sans-serif;font-weight:800;font-size:.88rem;cursor:pointer;text-decoration:none;transition:all .2s;}
.btn-logout:hover{background:#ef4444;color:#fff;}

/* Alert */
.alert-success{background:#e6fff6;border:1.5px solid var(--green);border-radius:var(--radius);padding:12px 16px;margin-bottom:14px;font-size:.85rem;font-weight:700;color:var(--green);}

/* Responsive */
@media(min-width:768px){
  .header-inner{max-width:680px;}
  .page-body{max-width:680px;padding:20px 32px;}
  .avatar-card{border-radius:20px;padding:28px 24px;}
  .avatar-wrap{width:90px;height:90px;}
  .profile-name{font-size:1.5rem;}
  .info-card{border-radius:20px;}
  .danger-card{border-radius:20px;}
}
@media(min-width:1024px){
  .header-inner{max-width:720px;}
  .page-body{max-width:720px;padding:24px 40px;}
  .avatar-card{border-radius:24px;padding:32px 28px;}
  .avatar-wrap{width:100px;height:100px;}
  .info-card{border-radius:24px;}
  .info-row{padding:16px 20px;}
}
@media(min-width:1280px){
  .header-inner{max-width:800px;}
  .page-body{max-width:800px;}
}
</style>
</head>
<body>

<div class="top-header">
  <div class="header-inner">
    <div class="header-top">
      @php
  $role = auth()->user()->role;
  $dashboardRoute = $role === 'admin' ? 'dashboard.admin' : ($role === 'driver' ? 'dashboard.driver' : 'customer.dashboard');
@endphp
<a href="{{ route($dashboardRoute) }}" class="back-btn">‹</a>
      <div class="header-title">Profil Saya</div>
    </div>
  </div>
  <svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;">
    <path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/>
  </svg>
</div>

<div class="page-body">

  @if(session('status') === 'profile-updated')
  <div class="alert-success">✅ Profil berhasil diperbarui.</div>
  @endif

  <!-- Avatar Card -->
  <div class="avatar-card">
    <div class="avatar-wrap">
      @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}" alt="Avatar">
      @else
        👤
      @endif
    </div>
    <div class="profile-name">{{ $user->name }}</div>
    <div class="profile-role">{{ ucfirst($user->role) }}</div>
    <a href="{{ route('profile.edit') }}" class="edit-btn">
      ✏️ Edit Profil
    </a>
  </div>

  <!-- Info -->
  <div class="info-card">
    <div class="info-row">
      <div class="info-icon">📧</div>
      <div class="info-body">
        <div class="info-label">Email</div>
        <div class="info-value">{{ $user->email }}</div>
      </div>
    </div>
    <div class="info-row">
      <div class="info-icon">📱</div>
      <div class="info-body">
        <div class="info-label">Nomor HP</div>
        <div class="info-value">{{ $user->phone ?? '-' }}</div>
      </div>
    </div>
    <div class="info-row">
      <div class="info-icon">📅</div>
      <div class="info-body">
        <div class="info-label">Bergabung Sejak</div>
        <div class="info-value">{{ $user->created_at->format('d M Y') }}</div>
      </div>
    </div>
  </div>

  <!-- Danger -->
  <div class="danger-card">
    <div class="danger-title">⚠️ Akun</div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
        </svg>
        Keluar
      </button>
    </form>
  </div>

</div>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Header entrance
  gsap.from('.top-header', { opacity:0, y:-20, duration:0.4, ease:'power2.out' });

  // Avatar card
  gsap.from('.avatar-card', { opacity:0, y:25, scale:0.97, duration:0.5, delay:0.15, ease:'back.out(1.5)' });

  // Info card
  gsap.from('.info-card', { opacity:0, y:20, duration:0.4, delay:0.3, ease:'power2.out' });

  // Info rows stagger
  gsap.from('.info-row', { opacity:0, x:-15, duration:0.35, stagger:0.08, delay:0.4, ease:'power2.out' });

  // Danger card
  gsap.from('.danger-card', { opacity:0, y:15, duration:0.4, delay:0.55, ease:'power2.out' });

  // Success alert animation
  const alert = document.querySelector('.alert-success');
  if (alert) {
    gsap.from(alert, { opacity:0, y:-10, scale:0.95, duration:0.4, ease:'back.out(1.5)' });
  }

  // Nav touch feedback
  document.querySelectorAll('.customer-nav__item, .customer-nav__fab').forEach(el => {
    el.addEventListener('touchstart', function() { gsap.to(this, {scale:.92, duration:.09, ease:'power2.out'}); }, {passive:true});
    el.addEventListener('touchend', function() { gsap.to(this, {scale:1, duration:.22, ease:'back.out(2.5)'}); }, {passive:true});
  });
});
</script>

</body>
</html>