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

/* Bottom nav */
.nav{position:fixed;left:0;right:0;bottom:0;height:var(--nav-h);background:rgba(255,255,255,0.95);backdrop-filter:blur(16px);border-top:1.5px solid var(--border);z-index:100;padding-bottom:env(safe-area-inset-bottom,0px)}
.nav-in{max-width:520px;height:100%;margin:0 auto;display:flex;align-items:center}
.nav-item{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;text-decoration:none;color:#94a3b8;transition:color 0.2s}
.nav-item.active{color:var(--blue-mid)}
.nav-ico{font-size:1.4rem;line-height:1}
.nav-label{font-size:0.65rem;font-weight:800;text-transform:uppercase;letter-spacing:0.3px}

.nav-fab{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;text-decoration:none;cursor:pointer}
.nav-fab-btn{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,var(--orange) 0%,#ff8c5a 100%);display:flex;align-items:center;justify-content:center;box-shadow:0 6px 20px rgba(255,107,53,0.45);margin-top:-25px;transition:transform 0.15s;color:#fff;font-size:2rem;font-family:'Fredoka One',cursive}
.nav-fab:active .nav-fab-btn{transform:scale(0.95)}
.nav-fab-lbl{font-size:0.62rem;font-weight:800;color:var(--orange);text-transform:uppercase;letter-spacing:0.3px;margin-top:2px}
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
<a href="{{ route($dashboardRoute) }}" class="back-btn" data-smart-back aria-label="Kembali">‹</a>
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

<nav class="nav">
  <div class="nav-in">
    <a href="{{ route('customer.dashboard') }}" class="nav-item">
      <span class="nav-ico">🏠</span><span class="nav-label">Beranda</span>
    </a>
    <a href="{{ route('customer.orders') }}" class="nav-item">
      <span class="nav-ico">📋</span><span class="nav-label">Pesanan</span>
    </a>
    <a href="{{ route('order.create') }}" class="nav-fab">
      <div class="nav-fab-btn"><span>+</span></div>
      <span class="nav-fab-lbl">Pesan</span>
    </a>
    <a href="{{ route('customer.notifications') }}" class="nav-item">
      <span class="nav-ico">🔔</span><span class="nav-label">Notif</span>
    </a>
    <a href="{{ route('customer.profile') }}" class="nav-item active">
      <span class="nav-ico">👤</span><span class="nav-label">Profil</span>
    </a>
  </div>
</nav>

</body>
</html>