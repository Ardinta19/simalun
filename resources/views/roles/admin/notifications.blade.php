<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Notifikasi Admin – Azka Laundry</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--ink:#1a2332;--ink-lt:#8899aa;--surface:#f4f8fc;--card:#fff;--border:#ddeeff;--radius:16px;}
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--surface);color:var(--ink)}
.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue) 60%,var(--sky) 100%)}
.header-inner{padding:20px;max-width:760px;margin:0 auto}.header-top{display:flex;align-items:center;gap:12px}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem}
.header-title{font-weight:800;font-size:1.2rem;color:#fff}
.page-body{max-width:760px;margin:0 auto;padding:16px;display:grid;gap:12px}
.card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--radius);padding:14px}
.card-title{font-weight:800;font-size:1rem;margin-bottom:10px}
.row{display:flex;justify-content:space-between;gap:10px;padding:10px;border:1px solid var(--border);border-radius:10px;margin-bottom:8px}
.meta{font-size:.74rem;color:var(--ink-lt);font-weight:700;margin-top:3px}
.badge{font-size:.68rem;font-weight:800;padding:.2rem .45rem;border-radius:99px;background:#fff3e8;color:#b45309;align-self:flex-start}
</style>
</head>
<body>
<div class="top-header" id="top"><div class="header-inner"><div class="header-top"><a href="{{ route('dashboard.admin') }}" class="back-btn">‹</a><div class="header-title">Notifikasi Admin</div></div></div></div>
<div class="page-body">
  <section class="card js-in">
    <div class="card-title">Antrian Operasional Terbaru</div>
    @forelse($notifikasiOperasional as $order)
      <div class="row"><div><div style="font-weight:800;">{{ $order->order_code }} · {{ $order->service->name ?? 'Layanan' }}</div><div class="meta">{{ $order->customer->name ?? 'Customer' }} · {{ $order->created_at->translatedFormat('d M Y H:i') }}</div></div><div class="badge">{{ $order->status_label }}</div></div>
    @empty
      <div class="meta">Belum ada aktivitas order.</div>
    @endforelse
  </section>
  <section class="card js-in">
    <div class="card-title">Notifikasi Sistem</div>
    @forelse($notifications as $notif)
      <a href="{{ isset($notif->data['order_id']) ? route('admin.orders') : '#' }}" class="row" style="text-decoration:none; color:inherit; display:flex; {{ is_null($notif->read_at) ? 'background: #f0f9ff; border-left: 4px solid var(--blue);' : '' }}">
        <div style="flex:1">
          <div style="font-weight:800;">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
          <div class="meta">{{ $notif->data['message'] ?? '-' }}</div>
        </div>
        <div class="meta" style="text-align:right">
          {{ $notif->created_at->diffForHumans() }}
        </div>
      </a>
    @empty
      <div class="meta">Belum ada notifikasi sistem.</div>
    @endforelse
    <div style="margin-top:10px">
      {{ $notifications->links() }}
    </div>
  </section>
</div>
<script>document.addEventListener('DOMContentLoaded',()=>{gsap.from('#top',{y:-16,opacity:0,duration:.45,ease:'power2.out'});gsap.from('.js-in',{y:18,opacity:0,duration:.42,stagger:.08,ease:'power2.out'});});</script>

@include('layouts.component.admin._navbar_admin', ['active' => ''])

</body>
</html>