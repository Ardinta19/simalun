<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Kurir – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--surface:#f4f8fc;--card:#fff;--border:#ddeeff;--radius:18px;--nav-h:72px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(var(--nav-h) + env(safe-area-inset-bottom,0px) + 12px);}
.wrap{max-width:520px;margin:0 auto;padding:max(env(safe-area-inset-top,0px),16px) 16px 16px;}

/* Header */
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding:0 4px;}
.page-header-left{display:flex;align-items:center;gap:10px;}
.page-header-left img{width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--border);}
.page-title{font-family:'Fredoka One',cursive;font-size:1.1rem;color:var(--blue-mid);}
.notif-btn{width:40px;height:40px;border-radius:50%;background:var(--card);border:1.5px solid var(--border);display:flex;align-items:center;justify-content:center;text-decoration:none;position:relative;font-size:1rem;box-shadow:0 2px 6px rgba(0,47,92,.06);}
.notif-badge{position:absolute;top:-2px;right:-2px;min-width:16px;height:16px;background:var(--orange);color:#fff;border-radius:99px;font-size:.55rem;font-weight:900;display:flex;align-items:center;justify-content:center;border:2px solid var(--surface);padding:0 3px;}

/* Profile Card */
.profile-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:28px 20px;text-align:center;margin-bottom:16px;box-shadow:0 4px 16px rgba(0,47,92,.05);}
.avatar-wrap{width:88px;height:88px;border-radius:50%;border:3px solid var(--blue-mid);margin:0 auto 14px;display:flex;align-items:center;justify-content:center;position:relative;overflow:visible;}
.avatar-wrap img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.avatar-badge{position:absolute;bottom:-2px;right:-2px;background:var(--green);color:#fff;font-size:.55rem;font-weight:900;padding:3px 8px;border-radius:99px;border:2.5px solid #fff;text-transform:uppercase;letter-spacing:.5px;}
.profile-name{font-family:'Fredoka One',cursive;font-size:1.3rem;color:var(--ink);margin-bottom:2px;}
.profile-id{font-size:.78rem;font-weight:700;color:var(--ink-lt);}

/* Stats */
.stats-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.stat-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:16px;text-align:center;box-shadow:0 2px 8px rgba(0,47,92,.04);}
.stat-ico{font-size:1.2rem;margin-bottom:6px;display:block;}
.stat-num{font-family:'Fredoka One',cursive;font-size:1.5rem;color:var(--blue-mid);}
.stat-lbl{font-size:.65rem;font-weight:900;color:var(--ink-lt);text-transform:uppercase;letter-spacing:.4px;margin-top:2px;}
.stat-sub{font-size:.7rem;font-weight:800;color:var(--ink-mid);margin-top:4px;}

/* Menu */
.menu-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.04);}
.menu-item{display:flex;align-items:center;gap:14px;padding:15px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--ink);transition:background .15s;}
.menu-item:last-child{border-bottom:none;}
.menu-item:active{background:var(--blue-sky);}
.menu-ico{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.menu-ico.blue{background:var(--blue-sky);}
.menu-ico.green{background:#ecfdf5;}
.menu-ico.amber{background:#fff9e6;}
.menu-body{flex:1;}
.menu-label{font-weight:800;font-size:.88rem;color:var(--ink);}
.menu-sub{font-size:.7rem;font-weight:700;color:var(--ink-lt);margin-top:2px;}
.menu-arrow{color:var(--ink-lt);font-size:1.1rem;}

/* Performance */
.perf-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:16px 20px;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.04);}
.perf-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.perf-title{font-family:'Fredoka One',cursive;font-size:.92rem;color:var(--ink);}
.perf-badge{font-size:.68rem;font-weight:900;padding:4px 10px;border-radius:99px;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;}
.perf-chart{display:flex;align-items:flex-end;gap:8px;height:100px;padding-top:8px;}
.perf-bar{flex:1;border-radius:6px 6px 0 0;background:var(--blue-sky);position:relative;min-height:12px;transition:height .3s;}
.perf-bar.highlight{background:var(--blue-mid);}
.perf-labels{display:flex;gap:8px;margin-top:6px;}
.perf-lbl{flex:1;text-align:center;font-size:.6rem;font-weight:800;color:var(--ink-lt);text-transform:uppercase;}

/* Logout */
.btn-logout{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:14px;border-radius:99px;border:1.5px solid #fecaca;background:#fff;color:#dc2626;font-family:'Nunito',sans-serif;font-weight:900;font-size:.88rem;cursor:pointer;margin-top:4px;transition:all .15s;}
.btn-logout:active{background:#fef2f2;}

/* Bottom Nav */
.bottom-nav{position:fixed;left:0;right:0;bottom:0;height:var(--nav-h);background:rgba(255,255,255,.97);backdrop-filter:blur(16px);border-top:1.5px solid var(--border);padding-bottom:env(safe-area-inset-bottom);z-index:100;}
.nav-in{max-width:520px;margin:0 auto;height:100%;display:flex;align-items:center;}
.nav-item{flex:1;text-decoration:none;color:#94a3b8;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;font-weight:800;font-size:.65rem;text-transform:uppercase;letter-spacing:.4px;}
.nav-item.active{color:var(--blue-mid);}
.nav-item svg{width:22px;height:22px;margin-bottom:1px;}
</style>
</head>
<body>
@php
  $driver = auth()->user();
  $totalAntar = \App\Models\Order::where('driver_id', $driver->id)->where('status','selesai')->count();
  $antarBulanIni = \App\Models\Order::where('driver_id', $driver->id)->where('status','selesai')->whereMonth('updated_at', now()->month)->count();
  $unreadNotif = $driver->unreadNotifications->count();
@endphp
<div class="wrap">

  <!-- Header -->
  <header class="page-header">
    <div class="page-header-left">
      <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0077b6&color=fff&size=72" alt="{{ $driver->name }}">
      <span class="page-title">Profil Kurir</span>
    </div>
    <a href="{{ route('driver.notifications') }}" class="notif-btn">
      &#128276;
      @if($unreadNotif > 0)
        <span class="notif-badge">{{ $unreadNotif }}</span>
      @endif
    </a>
  </header>

  <!-- Profile Card -->
  <div class="profile-card">
    <div class="avatar-wrap">
      @if($driver->avatar)
        <img src="{{ asset('storage/'.$driver->avatar) }}" alt="{{ $driver->name }}">
      @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0077b6&color=fff&size=176" alt="{{ $driver->name }}">
      @endif
      <span class="avatar-badge">Aktif</span>
    </div>
    <div class="profile-name">{{ $driver->name }}</div>
    <div class="profile-id">ID Kurir: LK-{{ str_pad($driver->id, 5, '0', STR_PAD_LEFT) }}</div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <span class="stat-ico">&#128666;</span>
      <div class="stat-num">{{ number_format($totalAntar) }}</div>
      <div class="stat-lbl">Total Antaran</div>
    </div>
    <div class="stat-card">
      <span class="stat-ico">&#11088;</span>
      <div class="stat-num">4.9</div>
      <div class="stat-lbl">Rating</div>
      <div class="stat-sub">Unggul</div>
    </div>
  </div>

  <!-- Menu -->
  <div class="menu-card">
    <a href="{{ route('driver.orders') }}" class="menu-item">
      <div class="menu-ico blue">&#128203;</div>
      <div class="menu-body">
        <div class="menu-label">Riwayat Tugas</div>
        <div class="menu-sub">Lihat semua tugas jemput dan antar</div>
      </div>
      <span class="menu-arrow">&#8250;</span>
    </a>
    <a href="{{ route('driver.help') }}" class="menu-item">
      <div class="menu-ico amber">&#128161;</div>
      <div class="menu-body">
        <div class="menu-label">Bantuan</div>
        <div class="menu-sub">Pusat bantuan dan lapor kendala</div>
      </div>
      <span class="menu-arrow">&#8250;</span>
    </a>
  </div>

  <!-- Performance -->
  <div class="perf-card">
    <div class="perf-header">
      <span class="perf-title">Performa Minggu Ini</span>
      @if($antarBulanIni > 0)
        <span class="perf-badge">Aktif</span>
      @endif
    </div>
    @php
      // Simple weekly performance data
      $days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
      $weekData = [];
      for($i=6; $i>=0; $i--) {
        $date = now()->subDays($i);
        $count = \App\Models\Order::where('driver_id', $driver->id)
          ->where('status','selesai')
          ->whereDate('updated_at', $date)
          ->count();
        $weekData[] = $count;
      }
      $maxVal = max(max($weekData), 1);
      $todayIdx = 6; // today is last index
    @endphp
    <div class="perf-chart">
      @foreach($weekData as $idx => $val)
        <div class="perf-bar {{ $idx === $todayIdx ? 'highlight' : '' }}" style="height:{{ max(($val / $maxVal) * 100, 8) }}%;"></div>
      @endforeach
    </div>
    <div class="perf-labels">
      @foreach($days as $d)
        <span class="perf-lbl">{{ $d }}</span>
      @endforeach
    </div>
  </div>

  <!-- Logout -->
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn-logout">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Keluar Akun
    </button>
  </form>

</div>

<!-- Bottom Nav -->
<nav class="bottom-nav">
  <div class="nav-in">
    <a href="{{ route('driver.dashboard') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('driver.orders') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
      <span>Tugas</span>
    </a>
    <a href="{{ route('driver.profile') }}" class="nav-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <span>Profil</span>
    </a>
  </div>
</nav>

</body>
</html>
