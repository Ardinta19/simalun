<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Dashboard Kurir – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--surface:#f4f8fc;--card:#ffffff;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--border:#ddeeff;--radius:20px;--radius-sm:12px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

/* HERO */
.hero{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 65%,var(--blue-light) 100%);padding:max(env(safe-area-inset-top,0px),24px) 20px 36px;position:relative;overflow:hidden;}
.hero::before,.hero::after{content:'';position:absolute;border-radius:50%;background:rgba(255,255,255,.06);}
.hero::before{width:200px;height:200px;top:-60px;right:-40px;}
.hero::after{width:100px;height:100px;bottom:-20px;left:10px;}
.hero-inner{position:relative;z-index:2;max-width:520px;margin:0 auto;}
.hero-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.hero-avatar{width:44px;height:44px;border-radius:50%;border:2.5px solid rgba(255,255,255,.5);object-fit:cover;}
.notif-btn{width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;text-decoration:none;position:relative;}
.notif-badge{position:absolute;top:-3px;right:-3px;background:var(--orange);color:white;border-radius:99px;font-size:.55rem;font-weight:900;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center;border:2px solid white;padding:0 3px;font-family:'Nunito',sans-serif;}
.hero-greeting{font-family:'Fredoka One',cursive;font-size:1.5rem;color:white;line-height:1.2;margin-bottom:4px;}
.hero-sub{font-size:.8rem;font-weight:700;color:rgba(255,255,255,.7);}

/* KPI */
.kpi-row{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:18px;}
.kpi-card{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:14px;padding:14px;}
.kpi-val{font-family:'Fredoka One',cursive;font-size:1.8rem;color:white;line-height:1;}
.kpi-lbl{font-size:.7rem;font-weight:800;color:rgba(255,255,255,.7);margin-top:3px;text-transform:uppercase;letter-spacing:.5px;}

/* CONTENT */
.content{max-width:520px;margin:-20px auto 0;padding:0 16px;position:relative;z-index:10;}

/* TASK CARD */
.task-card{background:white;border-radius:var(--radius);border:1.5px solid var(--border);margin-bottom:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,47,92,.06);}
.task-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid var(--border);}
.task-order-code{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--blue-mid);}
.task-status{font-size:.65rem;font-weight:900;padding:4px 10px;border-radius:99px;text-transform:uppercase;}
.task-status.pickup{background:#fff3ee;color:#9a3412;}
.task-status.delivery{background:#e6fff6;color:#065f46;}
.task-body{padding:14px 16px;}
.task-customer{font-weight:900;font-size:.95rem;color:var(--ink);margin-bottom:6px;}
.task-addr{font-size:.78rem;font-weight:700;color:var(--ink-lt);line-height:1.5;display:flex;align-items:flex-start;gap:6px;margin-bottom:10px;}
.task-weight{display:inline-flex;align-items:center;gap:5px;background:var(--blue-sky);color:var(--blue-mid);border-radius:99px;padding:4px 10px;font-size:.72rem;font-weight:900;}
.task-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px;}
.task-btn{padding:11px;border-radius:var(--radius-sm);border:none;font-family:'Nunito',sans-serif;font-weight:900;font-size:.78rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;transition:transform .15s;}
.task-btn:active{transform:scale(.97);}
.task-btn.primary{background:var(--blue-mid);color:white;box-shadow:0 4px 12px rgba(0,119,182,.25);}
.task-btn.success{background:var(--green);color:white;box-shadow:0 4px 12px rgba(0,196,140,.25);}
.task-btn svg{width:14px;height:14px;}

/* SECTION HEADER */
.section-hd{display:flex;align-items:center;justify-content:space-between;margin:20px 0 12px;}
.section-title{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--ink);}
.section-link{font-size:.75rem;font-weight:900;color:var(--blue-mid);text-decoration:none;}

/* EMPTY */
.empty-card{background:white;border-radius:var(--radius);border:1.5px dashed var(--border);padding:30px;text-align:center;}
.empty-icon{font-size:2.5rem;margin-bottom:10px;}
.empty-txt{font-size:.82rem;font-weight:700;color:var(--ink-lt);}

/* NAV */
.driver-nav{position:fixed;bottom:0;left:0;right:0;z-index:999;background:rgba(255,255,255,.97);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-top:1px solid var(--border);box-shadow:0 -4px 24px rgba(0,47,92,.08);padding-bottom:env(safe-area-inset-bottom,0px);}
.driver-nav__inner{max-width:520px;margin:0 auto;display:flex;align-items:center;height:64px;padding:0 16px;}
.driver-nav__item{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;text-decoration:none;color:#94a3b8;padding:6px 0;transition:color .2s;position:relative;}
.driver-nav__item.is-active{color:var(--blue-mid);}
.driver-nav__icon svg{width:22px;height:22px;}
.driver-nav__label{font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.4px;font-family:'Nunito',sans-serif;}
.driver-nav__badge{position:absolute;top:2px;right:calc(50% - 18px);background:var(--orange);color:white;border-radius:99px;font-size:.55rem;font-weight:900;min-width:16px;height:16px;display:flex;align-items:center;justify-content:center;border:2px solid white;padding:0 3px;font-family:'Nunito',sans-serif;}
</style>
</head>
<body>

{{-- HERO --}}
<div class="hero" id="js-hero">
    <div class="hero-inner">
        <div class="hero-top">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0077b6&color=fff&size=128"
                 class="hero-avatar" alt="{{ $driver->name }}">
            <a href="{{ route('driver.notifications') }}" class="notif-btn">
                🔔
                @if($unreadNotif > 0)
                <span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
                @endif
            </a>
        </div>
        <div class="hero-greeting">Halo, {{ explode(' ', $driver->name)[0] }}! 👋</div>
        <div class="hero-sub">Berikut tugas jemput & antar hari ini.</div>
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-val">{{ $tugasAktif->count() }}</div>
                <div class="kpi-lbl">Tugas Aktif</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-val">{{ $totalAntarBulanIni }}</div>
                <div class="kpi-lbl">Antar Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="content">

    {{-- Tugas Aktif --}}
    <div class="section-hd">
        <div class="section-title">⚡ Tugas Aktif</div>
        <a href="{{ route('driver.orders') }}" class="section-link">Lihat Semua</a>
    </div>

    @forelse($tugasAktif as $order)
    <div class="task-card js-card">
        <div class="task-head">
            <div class="task-order-code">#{{ strtoupper($order->order_code) }}</div>
            <span class="task-status {{ $order->status === 'dijemput' ? 'pickup' : 'delivery' }}">
                {{ $order->status === 'dijemput' ? '🛵 Jemput' : '📦 Antar' }}
            </span>
        </div>
        <div class="task-body">
            <div class="task-customer">{{ $order->customer->name ?? 'Customer' }}</div>
            <div class="task-addr">
                📍
                {{ Str::limit($order->customerAddress?->full_address ?? $order->address ?? '-', 60) }}
            </div>
            <span class="task-weight">⚖️ {{ $order->weight_estimate }} kg est.</span>
            <div class="task-actions">
                @if($order->customer?->phone)
                <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}"
                   target="_blank" class="task-btn primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.72A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7a2 2 0 011.72 2.04z"/></svg>
                    Hubungi
                </a>
                @else
                <div class="task-btn" style="background:#f8fafc;color:var(--ink-lt);cursor:default;">
                    Tidak ada HP
                </div>
                @endif
                <a href="{{ route('driver.orders.show', $order) }}" class="task-btn success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-card">
        <div class="empty-icon">✅</div>
        <div class="empty-txt">Tidak ada tugas aktif saat ini.<br>Semua sudah diselesaikan!</div>
    </div>
    @endforelse

    {{-- Tugas Menunggu --}}
    @if(isset($tugasMenunggu) && $tugasMenunggu->count() > 0)
    <div class="section-hd" style="margin-top:8px;">
        <div class="section-title">⏳ Menunggu Konfirmasi</div>
    </div>
    @foreach($tugasMenunggu->take(3) as $order)
    <div class="task-card js-card" style="opacity:.7;">
        <div class="task-head">
            <div class="task-order-code">#{{ strtoupper($order->order_code) }}</div>
            <span class="task-status pickup">Menunggu</span>
        </div>
        <div class="task-body">
            <div class="task-customer">{{ $order->customer->name ?? '-' }}</div>
            <div class="task-addr">📍 {{ Str::limit($order->address ?? '-', 55) }}</div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Logout --}}
    <div style="margin-top:20px;margin-bottom:12px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="width:100%;padding:13px;background:white;border:1.5px solid #fecaca;border-radius:var(--radius-sm);color:#ef4444;font-family:'Nunito',sans-serif;font-weight:900;font-size:.85rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
    <div style="text-align:center;font-size:.65rem;font-weight:800;color:var(--ink-lt);letter-spacing:.5px;margin-bottom:8px;">
        AZKA LAUNDRY SIMALUN • KURIR v1.0
    </div>

</div>

{{-- DRIVER NAV --}}
@include('layouts.component.driver._navbar_driver', ['active' => 'beranda'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    gsap.from('#js-hero', { opacity: 0, y: -20, duration: 0.6, ease: 'power2.out' });
    gsap.from('.kpi-card', { opacity: 0, scale: 0.9, duration: 0.4, stagger: 0.1, ease: 'back.out(1.5)', delay: 0.3 });
    gsap.from('.js-card', { opacity: 0, y: 20, duration: 0.45, stagger: 0.08, ease: 'power2.out', delay: 0.2 });
    gsap.from('.section-hd', { opacity: 0, x: -15, duration: 0.4, stagger: 0.1, ease: 'power2.out', delay: 0.25 });
});
</script>

</body>
</html>