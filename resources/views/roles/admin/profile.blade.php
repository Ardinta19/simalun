<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Admin – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#f59e0b;--green:#00C48C;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--surface:#f4f8fc;--card:#fff;--border:#ddeeff;--radius:18px;--nav-h:72px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(var(--nav-h) + env(safe-area-inset-bottom,0px) + 12px);}
.wrap{max-width:520px;margin:0 auto;padding:max(env(safe-area-inset-top,0px),16px) 16px 16px;}

/* Header */
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding:0 4px;}
.page-header-left{display:flex;align-items:center;gap:10px;}
.page-header-left img{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--border);}
.page-title{font-family:'Fredoka One',cursive;font-size:1.1rem;color:var(--ink);}
.notif-btn{width:40px;height:40px;border-radius:50%;background:var(--card);border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;text-decoration:none;position:relative;font-size:1rem;box-shadow:0 2px 6px rgba(0,47,92,.06);}
.notif-badge{position:absolute;top:-2px;right:-2px;min-width:16px;height:16px;background:var(--orange);color:#fff;border-radius:99px;font-size:.55rem;font-weight:900;display:flex;align-items:center;justify-content:center;border:2px solid var(--surface);padding:0 3px;}

/* Profile Card */
.profile-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:24px 20px;margin-bottom:16px;box-shadow:0 4px 16px rgba(0,47,92,.05);}
.profile-card-inner{display:flex;flex-direction:column;align-items:flex-start;}
.profile-name{font-family:'Fredoka One',cursive;font-size:1.4rem;color:var(--ink);line-height:1.2;}
.profile-desc{font-size:.8rem;font-weight:700;color:var(--ink-lt);margin-top:4px;}
.profile-badge{display:inline-flex;align-items:center;gap:5px;margin-top:10px;padding:5px 12px;border-radius:99px;background:#e6fff6;border:1px solid #a7f3d0;font-size:.72rem;font-weight:800;color:#065f46;}
.profile-badge-dot{width:8px;height:8px;border-radius:50%;background:var(--green);}

/* Stats row */
.stats-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.stat-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:16px;box-shadow:0 2px 8px rgba(0,47,92,.04);display:flex;align-items:center;gap:12px;}
.stat-ico{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.stat-ico.blue{background:var(--blue-sky);color:var(--blue-mid);}
.stat-ico.orange{background:#fff3e0;color:#d97706;}
.stat-body{}
.stat-num{font-family:'Fredoka One',cursive;font-size:1.4rem;line-height:1;color:var(--ink);}
.stat-lbl{font-size:.68rem;font-weight:800;color:var(--ink-lt);margin-top:2px;text-transform:uppercase;letter-spacing:.3px;}

/* Menu section */
.section-label{font-size:.72rem;font-weight:900;color:var(--ink-lt);text-transform:uppercase;letter-spacing:.8px;margin:20px 0 10px;padding-left:4px;}
.menu-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.04);}
.menu-item{display:flex;align-items:center;gap:14px;padding:15px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--ink);transition:background .15s;}
.menu-item:last-child{border-bottom:none;}
.menu-item:active{background:var(--blue-sky);}
.menu-ico{width:38px;height:38px;border-radius:12px;background:var(--blue-sky);display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0;}
.menu-body{flex:1;}
.menu-label{font-weight:800;font-size:.88rem;color:var(--ink);}
.menu-sub{font-size:.7rem;font-weight:700;color:var(--ink-lt);margin-top:2px;}
.menu-arrow{color:var(--ink-lt);font-size:1rem;}

/* Banner */
.ops-banner{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);border-radius:var(--radius);padding:20px;margin-bottom:16px;position:relative;overflow:hidden;box-shadow:0 4px 16px rgba(0,47,92,.15);}
.ops-banner::after{content:'';position:absolute;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06);top:-40px;right:-30px;}
.ops-banner-title{font-family:'Fredoka One',cursive;font-size:1.1rem;color:#fff;line-height:1.2;margin-bottom:4px;}
.ops-banner-sub{font-size:.75rem;font-weight:700;color:rgba(255,255,255,.7);}

/* Logout */
.logout-section{margin-top:8px;}
.btn-logout{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;border-radius:99px;border:1.5px solid #fecaca;background:#fff;color:#dc2626;font-family:'Nunito',sans-serif;font-weight:900;font-size:.88rem;cursor:pointer;transition:all .15s;}
.btn-logout:active{background:#fef2f2;border-color:#fca5a5;}
.version{text-align:center;margin-top:12px;font-size:.7rem;font-weight:700;color:var(--ink-lt);letter-spacing:.3px;}

/* Bottom Nav */
.bottom-nav{position:fixed;left:0;right:0;bottom:0;height:var(--nav-h);background:rgba(255,255,255,.97);backdrop-filter:blur(16px);border-top:1.5px solid var(--border);padding-bottom:env(safe-area-inset-bottom);z-index:100;}
.nav-in{max-width:520px;margin:0 auto;height:100%;display:flex;align-items:center;}
.nav-item{flex:1;text-decoration:none;color:#94a3b8;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;font-weight:800;font-size:.65rem;text-transform:uppercase;letter-spacing:.4px;}
.nav-item.active{color:var(--blue-mid);}
.nav-item svg{width:22px;height:22px;margin-bottom:1px;}
</style>
</head>
<body>
<div class="wrap">

  <!-- Header -->
  <header class="page-header">
    <div class="page-header-left">
      <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0077b6&color=fff&size=72" alt="{{ $user->name }}">
      <span class="page-title">Profil Admin</span>
    </div>
    <a href="{{ route('admin.notifications') }}" class="notif-btn">
      &#128276;
      @php $adminUnread = auth()->user()->unreadNotifications->count(); @endphp
      @if($adminUnread > 0)
        <span class="notif-badge">{{ $adminUnread }}</span>
      @endif
    </a>
  </header>

  @if(session('status') === 'profile-updated')
  <div style="background:#e6fff6;border:1.5px solid #a7f3d0;border-radius:12px;padding:12px 16px;margin-bottom:14px;font-size:.82rem;font-weight:700;color:#065f46;">Profil berhasil diperbarui.</div>
  @endif

  <!-- Profile Card -->
  <div class="profile-card">
    <div class="profile-card-inner">
      <div class="profile-name">{{ $user->name }}</div>
      <div class="profile-desc">Administrator Utama</div>
      <div class="profile-badge">
        <span class="profile-badge-dot"></span>
        Sistem Aktif
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-ico blue">&#128101;</div>
      <div class="stat-body">
        <div class="stat-num">{{ \App\Models\User::where('role','driver')->where('is_active',true)->count() }}</div>
        <div class="stat-lbl">Kurir Aktif</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-ico orange">&#128230;</div>
      <div class="stat-body">
        <div class="stat-num">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</div>
        <div class="stat-lbl">Pesanan Hari Ini</div>
      </div>
    </div>
  </div>

  <!-- Pengaturan Operasional -->
  <div class="section-label">Pengaturan Operasional</div>
  <div class="menu-card">
    <a href="{{ route('admin.orders') }}" class="menu-item">
      <div class="menu-ico">&#9881;</div>
      <div class="menu-body">
        <div class="menu-label">Manajemen Layanan</div>
        <div class="menu-sub">Kelola pesanan dan status order</div>
      </div>
      <span class="menu-arrow">&#8250;</span>
    </a>
    <a href="{{ route('admin.orders') }}" class="menu-item">
      <div class="menu-ico">&#128101;</div>
      <div class="menu-body">
        <div class="menu-label">Daftar Kurir</div>
        <div class="menu-sub">Lihat dan kelola kurir aktif</div>
      </div>
      <span class="menu-arrow">&#8250;</span>
    </a>
    <a href="{{ route('admin.finance.index') }}" class="menu-item">
      <div class="menu-ico">&#128200;</div>
      <div class="menu-body">
        <div class="menu-label">Laporan Keuangan</div>
        <div class="menu-sub">Rekap pemasukan dan pengeluaran</div>
      </div>
      <span class="menu-arrow">&#8250;</span>
    </a>
  </div>

  <!-- Banner -->
  <div class="ops-banner">
    <div class="ops-banner-title">Optimalkan Operasi</div>
    <div class="ops-banner-sub">Cek performa outlet hari ini</div>
  </div>

  <!-- Logout -->
  <div class="logout-section">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>
  <div class="version">Azka Laundry v2.4.0 &bull; Admin Panel</div>

</div>

<!-- Bottom Nav -->
<nav class="bottom-nav">
  <div class="nav-in">
    <a href="{{ route('dashboard.admin') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('admin.orders') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="{{ route('admin.profile') }}" class="nav-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <span>Profil</span>
    </a>
  </div>
</nav>

</body>
</html>
