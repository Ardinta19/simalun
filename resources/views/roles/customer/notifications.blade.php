<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Notifikasi – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--ink:#1a2332;--ink-lt:#8899aa;--surface:#f4f8fc;--border:#ddeeff;--radius:16px;--nav-h:72px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:var(--nav-h);}

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
.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);position:relative;overflow:hidden;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 20px;max-width:520px;margin:0 auto;}
.header-top{display:flex;align-items:center;gap:12px;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;flex:1;}
.header-wave{display:block;width:100%;margin-bottom:-2px;}
.page-body{max-width:520px;margin:0 auto;padding:16px;}
.notif-list{display:flex;flex-direction:column;gap:10px;}
.notif-item{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:14px 16px;display:flex;gap:12px;box-shadow:0 2px 8px rgba(0,47,92,.05);}
.notif-item.unread{border-left:4px solid var(--blue-mid);background:var(--blue-sky);}
.notif-icon{width:40px;height:40px;border-radius:10px;background:var(--blue-sky);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.notif-body{flex:1;min-width:0;}
.notif-title{font-weight:800;font-size:.9rem;color:var(--ink);}
.notif-msg{font-size:.78rem;color:var(--ink-lt);margin-top:2px;}
.notif-time{font-size:.68rem;font-weight:700;color:var(--ink-lt);margin-top:4px;}
.empty-state{text-align:center;padding:3rem 1rem;background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);}
.empty-icon{font-size:2.5rem;display:block;margin-bottom:.5rem;}
.empty-title{font-family:'Fredoka One',cursive;font-size:1rem;color:var(--ink);}
.empty-sub{font-size:.8rem;font-weight:700;color:var(--ink-lt);margin-top:.25rem;}
</style>
</head>
<body>
<div class="top-header"><div class="header-inner"><div class="header-top"><a href="{{ route('customer.dashboard') }}" class="back-btn">‹</a><div class="header-title">Notifikasi</div></div></div><svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg></div>
<div class="page-body">
  @if($notifications->count() > 0)
  <div class="notif-list">
    @foreach($notifications as $notif)
    <a href="{{ isset($notif->data['order_id']) ? route('customer.order.detail', $notif->data['order_id']) : '#' }}" class="notif-item {{ is_null($notif->read_at) ? 'unread' : '' }}" style="text-decoration:none">
      <div class="notif-icon">
        @php
          $status = $notif->data['status'] ?? '';
          $icon = '🔔';
          if($status == 'dijemput') $icon = '🛵';
          if($status == 'dicuci') $icon = '🧺';
          if($status == 'siap') $icon = '✨';
          if($status == 'selesai') $icon = '✅';
        @endphp
        {{ $icon }}
      </div>
      <div class="notif-body">
        <div class="notif-title">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
        <div class="notif-msg">{{ $notif->data['message'] ?? '' }}</div>
        <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
      </div>
    </a>
    @endforeach
  </div>
  {{ $notifications->links() }}
  @else
  <div class="empty-state"><span class="empty-icon">🔔</span><div class="empty-title">Belum ada notifikasi</div><div class="empty-sub">Notifikasi pesananmu akan muncul di sini</div></div>
  @endif
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
    <a href="{{ route('customer.notifications') }}" class="nav-item active">
      <span class="nav-ico">🔔</span><span class="nav-label">Notif</span>
    </a>
    <a href="{{ route('customer.profile') }}" class="nav-item">
      <span class="nav-ico">👤</span><span class="nav-label">Profil</span>
    </a>
  </div>
</nav>
</body>
</html>