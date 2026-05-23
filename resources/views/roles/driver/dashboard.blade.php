<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Dashboard Kurir – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Mode realtime: 'polling' (default, hemat) atau 'broadcasting' (Reverb,
     butuh VPS — lihat docs/realtime.md). Ganti via env DRIVER_REALTIME_MODE. --}}
<meta name="realtime-mode" content="{{ env('DRIVER_REALTIME_MODE', 'polling') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark: #002f5c;
    --blue: #0077b6;
    --blue-lt: #e8f4fd;
    --teal: #0d9488;
    --teal-lt: #ccfbf1;
    --orange: #FF6B35;
    --orange-lt: #fff3ee;
    --green: #059669;
    --green-lt: #ecfdf5;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-mid: #4a5568;
    --ink-lt: #8896a6;
    --border: #e2e8f0;
    --radius: 18px;
    --radius-sm: 12px;
    --shadow: 0 2px 12px rgba(0,47,92,0.04);
    --font: 'Plus Jakarta Sans', -apple-system, sans-serif;
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
    font-family: var(--font);
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(72px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* HEADER */
.hero {
    background: linear-gradient(145deg, var(--blue-dark) 0%, var(--blue) 65%, #00b4d8 100%);
    padding: max(env(safe-area-inset-top, 0px), 24px) 20px 32px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    top: -50px; right: -30px;
}
.hero__inner {
    position: relative; z-index: 2;
    max-width: 520px; margin: 0 auto;
}
.hero__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}
.hero__avatar {
    width: 44px; height: 44px;
    border-radius: 50%;
    border: 2.5px solid rgba(255,255,255,0.5);
    object-fit: cover;
}
.notif-btn {
    width: 38px; height: 38px;
    border-radius: 10px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    position: relative;
}
.notif-btn svg { width: 18px; height: 18px; color: white; stroke: white; }
.notif-badge {
    position: absolute; top: -4px; right: -4px;
    background: var(--orange); color: white;
    font-size: 0.55rem; font-weight: 800;
    min-width: 16px; height: 16px;
    border-radius: 99px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--blue-dark);
    padding: 0 3px;
}
.hero__greeting {
    font-size: 1.4rem;
    font-weight: 800;
    color: white;
    line-height: 1.2;
    margin-bottom: 4px;
}
.hero__sub {
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255,255,255,0.7);
}

/* KPI in hero */
.kpi-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 16px;
}
.kpi-card {
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: var(--radius-sm);
    padding: 14px;
}
.kpi-card__value {
    font-size: 1.6rem;
    font-weight: 800;
    color: white;
    line-height: 1;
}
.kpi-card__label {
    font-size: 0.65rem;
    font-weight: 700;
    color: rgba(255,255,255,0.7);
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

/* CONTENT */
.content {
    max-width: 520px;
    margin: -16px auto 0;
    padding: 0 16px;
    position: relative;
    z-index: 10;
}

/* SECTION HEADER */
.section-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 18px 0 10px;
}
.section-title {
    font-size: 0.88rem;
    font-weight: 800;
    color: var(--ink);
}
.section-link {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--blue);
    text-decoration: none;
}

/* TASK CARD */
.task-card {
    background: var(--card);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    margin-bottom: 10px;
    overflow: hidden;
    box-shadow: var(--shadow);
}
.task-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-bottom: 1px solid #f8fafc;
}
.task-card__code {
    font-size: 0.82rem;
    font-weight: 800;
    color: var(--blue);
}
.task-card__status {
    font-size: 0.6rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 99px;
    text-transform: uppercase;
}
.task-card__status--pickup { background: var(--orange-lt); color: #9a3412; }
.task-card__status--delivery { background: var(--green-lt); color: #065f46; }

.task-card__body { padding: 12px 14px; }
.task-card__customer {
    font-size: 0.88rem;
    font-weight: 800;
    color: var(--ink);
    margin-bottom: 6px;
}
.task-card__address {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ink-lt);
    line-height: 1.5;
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin-bottom: 8px;
}
.task-card__weight {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--blue-lt);
    color: var(--blue);
    border-radius: 99px;
    padding: 4px 10px;
    font-size: 0.68rem;
    font-weight: 700;
}
.task-card__actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 12px;
}
.task-btn {
    padding: 10px;
    border-radius: var(--radius-sm);
    border: none;
    font-family: var(--font);
    font-weight: 700;
    font-size: 0.75rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
    transition: transform 0.12s;
}
.task-btn:active { transform: scale(0.97); }
.task-btn--primary { background: var(--blue); color: white; }
.task-btn--success { background: var(--green); color: white; }
.task-btn--muted { background: #f8fafc; color: var(--ink-lt); cursor: default; }
.task-btn svg { width: 14px; height: 14px; }

/* EMPTY STATE */
.empty-card {
    background: var(--card);
    border-radius: var(--radius);
    border: 1.5px dashed var(--border);
    padding: 30px 20px;
    text-align: center;
}
.empty-card__icon { font-size: 2rem; margin-bottom: 8px; }
.empty-card__text { font-size: 0.78rem; font-weight: 700; color: var(--ink-lt); line-height: 1.5; }

/* LOGOUT */
.logout-section { margin-top: 20px; margin-bottom: 10px; }
.logout-btn {
    width: 100%;
    padding: 12px;
    border-radius: var(--radius-sm);
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #b91c1c;
    font-family: var(--font);
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.version-tag {
    text-align: center;
    font-size: 0.6rem;
    font-weight: 700;
    color: var(--ink-lt);
    letter-spacing: 0.3px;
    margin-bottom: 8px;
}
</style>
</head>
<body>

{{-- HERO --}}
<div class="hero" id="js-hero">
    <div class="hero__inner">
        <div class="hero__top">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0077b6&color=fff&size=128"
                 class="hero__avatar" alt="{{ $driver->name }}">
            <a href="{{ route('driver.notifications') }}" class="notif-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                @if($unreadNotif > 0)
                <span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
                @endif
            </a>
        </div>
        <div class="hero__greeting">Halo, {{ explode(' ', $driver->name)[0] }}! 👋</div>
        <div class="hero__sub">Berikut tugas jemput & antar hari ini.</div>
        <div class="kpi-row">
            <div class="kpi-card">
                <div class="kpi-card__value">{{ $tugasAktif->count() }}</div>
                <div class="kpi-card__label">Tugas Aktif</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-card__value">{{ $totalAntarBulanIni }}</div>
                <div class="kpi-card__label">Antar Bulan Ini</div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="content">

    {{-- Tugas Aktif --}}
    <div class="section-hd">
        <span class="section-title">Tugas Aktif</span>
        <a href="{{ route('driver.orders') }}" class="section-link">Lihat Semua</a>
    </div>

    @forelse($tugasAktif as $order)
    <div class="task-card js-card">
        <div class="task-card__head">
            <div class="task-card__code">#{{ strtoupper($order->order_code) }}</div>
            <span class="task-card__status {{ $order->status === 'dijemput' ? 'task-card__status--pickup' : 'task-card__status--delivery' }}">
                {{ $order->status === 'dijemput' ? 'Jemput' : 'Antar' }}
            </span>
        </div>
        <div class="task-card__body">
            <div class="task-card__customer">{{ $order->customer->name ?? 'Customer' }}</div>
            <div class="task-card__address">
                <span>📍</span>
                {{ Str::limit($order->customerAddress?->full_address ?? $order->address ?? '-', 55) }}
            </div>
            <span class="task-card__weight">⚖️ {{ $order->weight_estimate }} kg est.</span>
            <div class="task-card__actions">
                @if($order->customer?->phone)
                <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}"
                   target="_blank" class="task-btn task-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.08 1.18A2 2 0 012 .18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.09 6.09l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                    Hubungi
                </a>
                @else
                <div class="task-btn task-btn--muted">Tidak ada HP</div>
                @endif
                <a href="{{ route('driver.orders.show', $order) }}" class="task-btn task-btn--success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    Detail
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-card">
        <div class="empty-card__icon">✅</div>
        <div class="empty-card__text">Tidak ada tugas aktif saat ini.<br>Semua sudah diselesaikan!</div>
    </div>
    @endforelse

    {{-- Tugas Menunggu --}}
    @if(isset($tugasMenunggu) && $tugasMenunggu->count() > 0)
    <div class="section-hd">
        <span class="section-title">Menunggu Konfirmasi</span>
    </div>
    @foreach($tugasMenunggu->take(3) as $order)
    <div class="task-card js-card" style="opacity:0.7;">
        <div class="task-card__head">
            <div class="task-card__code">#{{ strtoupper($order->order_code) }}</div>
            <span class="task-card__status task-card__status--pickup">Menunggu</span>
        </div>
        <div class="task-card__body">
            <div class="task-card__customer">{{ $order->customer->name ?? '-' }}</div>
            <div class="task-card__address"><span>📍</span> {{ Str::limit($order->address ?? '-', 50) }}</div>
        </div>
    </div>
    @endforeach
    @endif

    {{-- Logout --}}
    <div class="logout-section">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
    <div class="version-tag">AZKA LAUNDRY &bull; Kurir v{{ \App\Support\Laundry::version() }}</div>

</div>

{{-- DRIVER NAV --}}
@include('layouts.component.driver._navbar_driver', ['active' => 'beranda'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    gsap.from('#js-hero', { opacity: 0, y: -15, duration: 0.5, ease: 'power2.out' });
    gsap.from('.kpi-card', { opacity: 0, scale: 0.92, duration: 0.4, stagger: 0.08, ease: 'back.out(1.4)', delay: 0.2 });
    gsap.from('.js-card', { opacity: 0, y: 16, duration: 0.4, stagger: 0.06, ease: 'power2.out', delay: 0.15 });
});

/* ───────────────── Realtime Driver Dashboard ────────────────────
 * Mode dibaca dari <meta name="realtime-mode">.
 *  - polling (default): cek endpoint setiap 30 detik. Hemat & jalan
 *    di shared hosting.
 *  - broadcasting: pakai Echo + Reverb. Belum diaktifkan di repo —
 *    lihat docs/realtime.md untuk panduan switch.
 *
 * Untuk hindari nge-spam server kalau tab gak aktif, polling auto-pause
 * saat document.hidden = true.
 */
(function () {
    const mode = document.querySelector('meta[name="realtime-mode"]')?.content || 'polling';

    if (mode !== 'polling') {
        // ── BROADCASTING MODE (template, tidak aktif) ─────────────
        // Saat pindah ke VPS, ikuti docs/realtime.md sampai langkah
        // bikin echo.js, lalu uncomment block di bawah & hapus
        // polling-init di bawah.
        //
        // import './echo.js';
        // window.Echo.private(`App.Models.User.${currentUserId}`)
        //     .notification((notification) => {
        //         if (notification.type === 'App\\Notifications\\OrderStatusUpdated') {
        //             window.location.reload();
        //         }
        //     });
        return;
    }

    let lastSignature = null;
    const POLL_INTERVAL_MS = 30000;

    async function poll() {
        if (document.hidden) return; // hemat resource saat tab background

        try {
            const res = await fetch('{{ route('driver.dashboard.poll') }}', {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });
            if (! res.ok) return;
            const data = await res.json();

            if (lastSignature === null) {
                lastSignature = data.signature;
                return;
            }

            // Signature berubah = ada update di backend (order baru
            // di-assign / status berubah). Reload halaman supaya kartu
            // sinkron tanpa kompleksitas patch DOM manual.
            if (data.signature !== lastSignature) {
                lastSignature = data.signature;
                showNewTaskBanner();
            }
        } catch (e) {
            // Diam — bisa jadi user offline sebentar. Polling berikutnya
            // akan retry otomatis.
        }
    }

    function showNewTaskBanner() {
        if (document.getElementById('rt-banner')) return; // udah ada

        const banner = document.createElement('div');
        banner.id = 'rt-banner';
        banner.style.cssText = 'position:fixed;left:50%;top:14px;transform:translateX(-50%);background:#0077b6;color:#fff;padding:10px 18px;border-radius:99px;font-weight:800;font-size:.78rem;z-index:1000;box-shadow:0 4px 16px rgba(0,47,92,.3);cursor:pointer;font-family:inherit;';
        banner.textContent = '🔔 Ada update tugas — tap untuk muat ulang';
        banner.addEventListener('click', () => window.location.reload());
        document.body.appendChild(banner);
    }

    // First poll cepat, biar sig awal di-cache. Setelah itu interval normal.
    setTimeout(poll, 1500);
    setInterval(poll, POLL_INTERVAL_MS);
})();
</script>
</body>
</html>
