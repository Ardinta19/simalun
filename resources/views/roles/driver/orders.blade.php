<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Daftar Tugas – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--surface:#f4f8fc;--card:#ffffff;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--border:#ddeeff;--radius:20px;--radius-sm:12px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

.page-header{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);padding:max(env(safe-area-inset-top,0px),16px) 20px 24px;position:sticky;top:0;z-index:100;}
.header-row{display:flex;align-items:center;gap:12px;max-width:520px;margin:0 auto;}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;flex-shrink:0;}
.btn-back svg{width:18px;height:18px;}
.header-title{flex:1;font-family:'Fredoka One',cursive;font-size:1.3rem;color:white;}

.page-body{max-width:520px;margin:0 auto;padding:16px;}

.task-card{background:white;border-radius:var(--radius);border:1.5px solid var(--border);margin-bottom:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,47,92,.05);opacity:0;transform:translateY(16px;}
.task-strip{height:4px;width:100%;}
.task-body{padding:14px 16px;}
.task-row1{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;}
.task-code{font-family:'Fredoka One',cursive;font-size:.9rem;color:var(--blue-mid);}
.task-customer{font-weight:900;font-size:.95rem;color:var(--ink);margin-top:2px;}
.task-badge{font-size:.62rem;font-weight:900;padding:4px 10px;border-radius:99px;text-transform:uppercase;white-space:nowrap;flex-shrink:0;}
.badge-pickup{background:#fff3ee;color:#9a3412;}
.badge-delivery{background:#e6fff6;color:#065f46;}
.task-addr{font-size:.76rem;font-weight:700;color:var(--ink-lt);line-height:1.5;margin-bottom:10px;display:flex;gap:6px;align-items:flex-start;}
.task-meta{display:flex;gap:8px;align-items:center;margin-bottom:12px;flex-wrap:wrap;}
.meta-chip{display:inline-flex;align-items:center;gap:4px;background:var(--blue-sky);color:var(--blue-mid);border-radius:99px;padding:3px 9px;font-size:.68rem;font-weight:900;}
.task-actions{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.task-btn{padding:10px;border-radius:var(--radius-sm);border:none;font-family:'Nunito',sans-serif;font-weight:900;font-size:.75rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:5px;text-decoration:none;transition:transform .15s;}
.task-btn:active{transform:scale(.96);}
.task-btn.blue{background:var(--blue-mid);color:white;}
.task-btn.green{background:var(--green);color:white;}
.task-btn.outline{background:white;color:var(--ink-mid);border:1.5px solid var(--border);}

/* Status update form */
.status-form{background:#f8fafc;border-top:1px solid var(--border);padding:12px 16px;display:grid;grid-template-columns:1fr auto;gap:8px;align-items:center;}
.status-note{font-size:.72rem;font-weight:700;color:var(--ink-lt);}
.status-btn{padding:9px 16px;border:none;border-radius:var(--radius-sm);font-family:'Nunito',sans-serif;font-weight:900;font-size:.75rem;cursor:pointer;white-space:nowrap;transition:all .15s;}

.empty-state{text-align:center;padding:50px 20px;}
.empty-icon{font-size:3rem;margin-bottom:12px;display:block;opacity:.5;}
.empty-title{font-family:'Fredoka One',cursive;font-size:1.1rem;color:var(--ink-mid);margin-bottom:6px;}
.empty-sub{font-size:.8rem;font-weight:700;color:var(--ink-lt);}

/* DRIVER NAV (simplified inline) */
.driver-nav{position:fixed;bottom:0;left:0;right:0;z-index:999;background:rgba(255,255,255,.97);backdrop-filter:blur(20px);border-top:1px solid var(--border);box-shadow:0 -4px 24px rgba(0,47,92,.08);padding-bottom:env(safe-area-inset-bottom,0px);}
.driver-nav__inner{max-width:520px;margin:0 auto;display:flex;align-items:center;height:64px;padding:0 16px;}
.driver-nav__item{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;text-decoration:none;color:#94a3b8;padding:6px 0;transition:color .2s;}
.driver-nav__item.is-active{color:var(--blue-mid);}
.driver-nav__icon svg{width:22px;height:22px;}
.driver-nav__label{font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.4px;font-family:'Nunito',sans-serif;}
</style>
</head>
<body>

<header class="page-header">
    <div class="header-row">
        <a href="{{ route('driver.dashboard') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div class="header-title">Daftar Tugas</div>
    </div>
</header>

<div class="page-body">

    @php
    $stripColors = ['dijemput'=>'#3B82F6','dikirim'=>'#FF6B35','siap'=>'#00C48C','menunggu'=>'#f59e0b'];
    @endphp

    @forelse($pesanan as $order)
    @php $strip = $stripColors[$order->status] ?? '#0077b6'; @endphp
    <div class="task-card js-card">
        <div class="task-strip" style="background:{{ $strip }}"></div>
        <div class="task-body">
            <div class="task-row1">
                <div>
                    <div class="task-code">#{{ strtoupper($order->order_code) }}</div>
                    <div class="task-customer">{{ $order->customer->name ?? 'Customer' }}</div>
                </div>
                <span class="task-badge {{ $order->status === 'dijemput' ? 'badge-pickup' : 'badge-delivery' }}">
                    {{ $order->status === 'dijemput' ? '🛵 Jemput' : '📦 Antar' }}
                </span>
            </div>

            <div class="task-addr">
                📍 {{ Str::limit($order->customerAddress?->full_address ?? $order->address ?? '-', 70) }}
            </div>

            <div class="task-meta">
                <span class="meta-chip">⚖️ {{ $order->weight_estimate }} kg</span>
                <span class="meta-chip">🕐 {{ ucfirst($order->pickup_time ?? '-') }}</span>
                @if($order->pickup_date)
                <span class="meta-chip">📅 {{ $order->pickup_date->format('d/m') }}</span>
                @endif
            </div>

            <div class="task-actions">
                @if($order->customer?->phone)
                <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}"
                   target="_blank" class="task-btn outline">💬 WA Customer</a>
                @else
                <div class="task-btn outline" style="cursor:default;opacity:.5;">Tidak ada WA</div>
                @endif
                <a href="{{ route('driver.orders.show', $order) }}" class="task-btn blue">
                    Detail & Aksi →
                </a>
            </div>
        </div>

        {{-- Quick action berdasarkan status --}}
        @if($order->status === 'dijemput')
        <form method="POST" action="{{ route('driver.orders.action', $order) }}" class="status-form">
            @csrf
            <input type="hidden" name="status" value="dicuci">
            <span class="status-note">Setelah pakaian dijemput:</span>
            <button type="submit" class="status-btn" style="background:var(--green);color:white;">
                ✅ Konfirmasi Jemput
            </button>
        </form>
        @elseif($order->status === 'dikirim')
        <div class="status-form">
            <span class="status-note">Upload bukti pengiriman di halaman detail</span>
            <a href="{{ route('driver.orders.show', $order) }}" class="status-btn" style="background:var(--orange);color:white;text-decoration:none;">
                📸 Upload Bukti
            </a>
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <span class="empty-icon">✅</span>
        <div class="empty-title">Tidak Ada Tugas</div>
        <div class="empty-sub">Semua tugas sudah diselesaikan.<br>Tunggu penugasan dari admin.</div>
    </div>
    @endforelse

    {{ $pesanan->links() }}

</div>

<nav class="driver-nav">
    <div class="driver-nav__inner">
        <a href="{{ route('driver.dashboard') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
            <span class="driver-nav__label">Beranda</span>
        </a>
        <a href="{{ route('driver.orders') }}" class="driver-nav__item is-active">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg></span>
            <span class="driver-nav__label">Tugas</span>
        </a>
        <a href="{{ route('driver.notifications') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg></span>
            <span class="driver-nav__label">Notif</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
            <span class="driver-nav__label">Profil</span>
        </a>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    gsap.to('.js-card', { opacity: 1, y: 0, duration: 0.45, stagger: 0.08, ease: 'power2.out', delay: 0.1 });
    gsap.from('.page-header', { opacity: 0, y: -16, duration: 0.4, ease: 'power2.out' });
});
</script>
</body>
</html>