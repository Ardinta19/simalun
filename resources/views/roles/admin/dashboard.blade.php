<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Dashboard Admin – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue:#0077b6;--blue-dark:#002f5c;--cyan:#00b4d8;--orange:#f59e0b;--green:#10b981;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#7b8b9a;--line:#ddeeff;--r:24px;--nav-h:74px}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
body{font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;padding-bottom:calc(var(--nav-h) + env(safe-area-inset-bottom));}
.wrap{max-width:520px;margin:0 auto;padding:14px}

/* Header Profil */
.profile-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
  padding: 0 4px;
}
.profile-info {
  display: flex;
  align-items: center;
  gap: 12px;
}
.profile-img {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  object-fit: cover;
  border: 2.5px solid #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.profile-text .name {
  font-family: 'Fredoka One', cursive;
  font-size: 1.1rem;
  font-weight: 400;
  color: var(--ink);
}
.profile-text .role {
  font-size: .75rem;
  font-weight: 800;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .5px;
}
.notif-btn {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1.5px solid var(--line);
  color: var(--blue-mid);
  font-size: 1.1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.hero{background:#fff;border:1.5px solid var(--line);border-radius:var(--r);padding:20px;margin-bottom:12px;box-shadow: 0 4px 20px rgba(0,47,92,0.04)}
.hello{font-family: 'Fredoka One', cursive; font-size:1.6rem; line-height:1.1}.sub{font-size:.85rem;color:#4b5563;margin-top:6px;font-weight:700}

.kpi{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:20px}
.k{border-radius:26px;padding:18px;min-height:130px;position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:space-between}
.k.blue{background:#58a8e8;color:#fff}.k.orange{background:#f6b443;color:#fff}
.k-ico { position: absolute; right: -12px; bottom: -12px; font-size: 4.5rem; opacity: 0.18; transform: rotate(-15deg); }
.k .n{font-size:2.2rem;font-family:'Fredoka One',cursive;line-height:1}.k .l{font-size:.9rem;font-weight:900;margin-top:4px;letter-spacing: .2px}

.sec{margin-top:20px}
.sec-h{display:flex;align-items:center;justify-content:space-between;margin:0 4px 12px}
.sec-t{font-family:'Fredoka One',cursive;font-size:1.05rem;color:var(--ink)}.sec-a{font-size:.82rem;font-weight:900;color:var(--blue);text-decoration:none}

.orders{display:flex;gap:14px;overflow-x:auto;padding-bottom:12px;scrollbar-width:none}.orders::-webkit-scrollbar{display:none}
.o{min-width:280px;background:#fff;border:1.5px solid var(--line);border-radius:26px;padding:18px;box-shadow: 0 6px 16px rgba(0,47,92,0.05)}
.o-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
.o-avatar { width: 50px; height: 50px; border-radius: 50%; background: #f0f7ff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; border: 1.5px solid var(--line); }
.o-name{font-family: 'Fredoka One', cursive; font-size:1.1rem; color: var(--ink)}
.o-badge{font-size:.68rem;font-weight:900;background:#dbeafe;color:#0369a1;border-radius:99px;padding:.2rem .6rem;text-transform:uppercase}
.o-meta{color:#4b5563;font-size:.82rem;font-weight:700;line-height:1.7}
.o-meta div { display: flex; align-items: center; gap: 8px; margin-top: 5px; }
.o-btn{display:flex;align-items:center;justify-content:center;margin-top:16px;background:#0d6fb8;color:#fff;text-decoration:none;border-radius:99px;padding:.85rem;font-weight:900;font-size:.85rem;gap:8px;box-shadow: 0 4px 12px rgba(13,111,184,0.3)}

.quick{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.q{display:flex;align-items:center;gap:14px;background:#fff;border:1.5px solid var(--line);border-radius:26px;padding:18px;text-decoration:none;color:inherit;box-shadow: 0 4px 16px rgba(0,47,92,0.04)}
.q-ico{width:44px;height:44px;border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0}
.q.ic-g .q-ico { background:#10b981; color:#fff }
.q.ic-y .q-ico { background:#854d0e; color:#fff }
.q-t{font-family: 'Fredoka One', cursive; font-size:.9rem;line-height:1.2;color:var(--ink)}

.bottom-nav{position:fixed;left:0;right:0;bottom:0;height:var(--nav-h);background:rgba(255,255,255,.98);backdrop-filter:blur(16px);border-top:1.5px solid var(--line);padding-bottom:env(safe-area-inset-bottom);z-index:100}
.nav-in{max-width:520px;margin:0 auto;height:100%;display:flex;align-items:center}
.nav-item{flex:1;text-decoration:none;color:#94a3b8;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;font-weight:800;font-size:.68rem;text-transform:uppercase;letter-spacing:.6px}.nav-item.active{color:#0d6fb8}
.nav-item svg { width: 24px; height: 24px; margin-bottom: 2px; }

.logout{margin:24px 0 12px;display:flex;justify-content:center}
.logout button{width:100%;height:50px;border-radius:99px;border:1.5px solid #fecaca;background:#fff1f1;color:#b91c1c;font-family:'Nunito',sans-serif;font-weight:900;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;font-size:.9rem}
.version{text-align:center;margin-bottom:12px;color:#94a3b8;font-size:.75rem;font-weight:700;letter-spacing:.4px}
</style>
</head>
<body>
<main class="wrap">
  <!-- Header Profil sesuai Mockup -->
  <header class="profile-header js-in">
    <div class="profile-info">
      <img src="https://ui-avatars.com/api/?name=Admin&background=0077b6&color=fff" class="profile-img" alt="Admin">
      <div class="profile-text">
        <div class="name">Profil Admin</div>
        <div class="role">Administrator Utama</div>
      </div>
    </div>
    <a href="{{ route('admin.notifications') }}" class="notif-btn" style="text-decoration:none; position:relative">
      🔔
      @php $adminUnread = auth()->user()->unreadNotifications->count(); @endphp
      @if($adminUnread > 0)
        <span style="position:absolute; top:-2px; right:-2px; background:var(--orange); color:#fff; font-size:0.6rem; font-weight:900; min-width:18px; height:18px; border-radius:10px; display:flex; align-items:center; justify-content:center; border:2.5px solid #fff">{{ $adminUnread }}</span>
      @endif
    </a>
  </header>

  <section class="hero js-in">
    <div class="hello">Halo, Admin! 👋</div>
    <div class="sub">Berikut ringkasan operasional hari ini.</div>

    <div class="kpi">
      <div class="k blue">
        <div class="k-ico">🧺</div>
        <div class="n">{{ $jumlahDiproses ?? 0 }}</div>
        <div class="l">Pesanan Masuk</div>
      </div>
      <div class="k orange">
        <div class="k-ico">🚚</div>
        <div class="n">{{ $jumlahPrioritas ?? 0 }}</div>
        <div class="l">Belum Dijemput</div>
      </div>
    </div>
  </section>

  <section class="sec js-in">
    <div class="sec-h">
      <div class="sec-t">Daftar Pesanan</div>
      <a href="{{ route('admin.orders') }}" class="sec-a">Lihat Semua</a>
    </div>
    <div class="orders">
      @forelse(($orderPrioritas ?? collect()) as $order)
      <article class="o">
        <div class="o-top">
          <div style="display:flex; align-items:center; gap:12px">
            <div class="o-avatar">👤</div>
            <div>
              <div class="o-name">{{ Str::limit($order->customer->name ?? 'Customer', 12) }}</div>
              <div style="font-size:.7rem; font-weight:900; color:var(--blue)">#{{ strtoupper($order->order_code) }}</div>
            </div>
          </div>
          <span class="o-badge">Baru</span>
        </div>
        <div class="o-meta">
          <div>📍 {{ Str::limit($order->address ?? '-', 28) }}</div>
          <div>⏰ Dijemput jam {{ $order->pickup_time ?? '-' }}</div>
        </div>
        <a href="{{ route('admin.orders') }}" class="o-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
          Tugaskan Kurir
        </a>
      </article>
      @empty
      <div style="text-align:center; padding:30px; width:100%; color:var(--muted); font-weight:800; background:#fff; border-radius:26px; border:1.5px dashed var(--line)">Belum ada pesanan masuk.</div>
      @endforelse
    </div>
  </section>

  <section class="sec js-in">
    <div class="sec-h"><div class="sec-t">Aksi Cepat</div></div>
    <div class="quick">
      <a href="{{ route('admin.walkin.form') }}" class="q ic-g">
        <div class="q-ico">👤</div>
        <span class="q-t">Tambah<br>Pelanggan</span>
      </a>
      <a href="{{ route('admin.finance.index') }}" class="q ic-y">
        <div class="q-ico">🧾</div>
        <span class="q-t">Laporan<br>Harian</span>
      </a>
    </div>
  </section>

  <div class="logout js-in">
    <form action="{{ route('logout') }}" method="POST" style="width:100%;">
      @csrf
      <button type="submit">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>
  <div class="version js-in">Azka Laundry v2.4.0 &bull; Admin Panel</div>
</main>

<nav class="bottom-nav">
  <div class="nav-in">
    <a href="{{ route('dashboard.admin') }}" class="nav-item active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span>Beranda</span>
    </a>
    <a href="{{ route('admin.orders') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
      <span>Pesanan</span>
    </a>
    <a href="{{ route('admin.profile') }}" class="nav-item">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      <span>Profil</span>
    </a>
  </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function(){
  gsap.from('.js-in',{y:40, opacity:0, duration:0.7, stagger:0.12, ease:'power4.out'});

  document.querySelectorAll('.k, .o, .q').forEach(el => {
    el.addEventListener('mouseenter', () => gsap.to(el, {y:-5, scale:1.02, duration:0.3, ease:'power2.out'}));
    el.addEventListener('mouseleave', () => gsap.to(el, {y:0, scale:1, duration:0.3, ease:'power2.out'}));
  });
});
</script>
</body>
</html>