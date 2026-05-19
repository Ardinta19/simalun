<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Notifikasi – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--ink:#1a2332;--ink-lt:#8899aa;--surface:#f4f8fc;--border:#ddeeff;--radius:16px;--nav-h:72px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:80px;}
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
<div class="top-header"><div class="header-inner"><div class="header-top"><a href="{{ route('customer.dashboard') }}" class="back-btn">‹</a><div class="header-title">Notifikasi</div>@if($notifications->count() > 0)<form method="POST" action="{{ route('customer.notifications.read-all') }}" style="margin-left:auto;"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" style="background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);color:#fff;border-radius:20px;padding:5px 12px;font-size:.7rem;font-weight:800;cursor:pointer;font-family:'Nunito',sans-serif;">Tandai Semua</button></form>@endif</div></div><svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg></div>
<div class="page-body">
  @if($notifications->count() > 0)
  <div class="notif-list">
    @foreach($notifications as $notif)
    <a href="{{ isset($notif->data['order_id']) ? route('customer.notifications.open', $notif->id) : '#' }}" class="notif-item {{ is_null($notif->read_at) ? 'unread' : '' }}" style="text-decoration:none">
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
  {{ $notifications->links('vendor.pagination.customer-simple') }}
  @else
  <div class="empty-state"><span class="empty-icon">🔔</span><div class="empty-title">Belum ada notifikasi</div><div class="empty-sub">Notifikasi pesananmu akan muncul di sini</div></div>
  @endif
</div>
@include('layouts.component.customer._navbar_customer', ['active' => 'notif'])
</body>
</html>