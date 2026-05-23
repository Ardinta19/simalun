<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Dashboard Admin – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue: #0077b6;
    --blue-dark: #002f5c;
    --blue-lt: #e8f4fd;
    --teal: #0d9488;
    --teal-lt: #ccfbf1;
    --orange: #f59e0b;
    --orange-lt: #fef3c7;
    --green: #059669;
    --green-lt: #ecfdf5;
    --red: #dc2626;
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
.wrap { max-width: 520px; margin: 0 auto; padding: 16px; padding-top: max(env(safe-area-inset-top, 0px), 16px); }

/* ── HEADER ──────────────────────── */
.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.brand {
    display: flex;
    align-items: center;
    gap: 8px;
}
.brand__icon {
    width: 32px; height: 32px;
    background: var(--blue);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 0.8rem; font-weight: 800;
}
.brand__name {
    font-size: 1rem;
    font-weight: 800;
    color: var(--blue-dark);
    letter-spacing: -0.3px;
}
.notif-btn {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: var(--card);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    position: relative;
    box-shadow: var(--shadow);
}
.notif-btn svg { width: 20px; height: 20px; color: var(--ink-mid); }
.notif-badge {
    position: absolute; top: -3px; right: -3px;
    background: var(--orange); color: white;
    font-size: 0.55rem; font-weight: 800;
    min-width: 16px; height: 16px;
    border-radius: 99px;
    display: flex; align-items: center; justify-content: center;
    border: 2px solid var(--surface);
    padding: 0 3px;
}

/* ── GREETING HERO ───────────────── */
.hero {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 14px;
    box-shadow: var(--shadow);
}
.hero__greeting {
    font-size: 1.4rem;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.2;
}
.hero__sub {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--ink-mid);
    margin-top: 4px;
}

/* ── KPI CARDS ───────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 16px;
}
.kpi-card {
    border-radius: var(--radius-sm);
    padding: 18px 16px;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 110px;
}
.kpi-card--teal { background: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%); color: white; }
.kpi-card--orange { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); color: white; }
.kpi-card__icon {
    position: absolute;
    right: -8px; bottom: -8px;
    font-size: 3.5rem;
    opacity: 0.15;
}
.kpi-card__value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    position: relative;
    z-index: 1;
}
.kpi-card__label {
    font-size: 0.75rem;
    font-weight: 700;
    margin-top: auto;
    opacity: 0.9;
    position: relative;
    z-index: 1;
}

/* ── SECTION ─────────────────────── */
.section { margin-top: 20px; }
.section__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    padding: 0 2px;
}
.section__title {
    font-size: 0.92rem;
    font-weight: 800;
    color: var(--ink);
}
.section__link {
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--blue);
    text-decoration: none;
}

/* ── ORDER CARDS (horizontal scroll) ── */
.order-scroll {
    display: flex;
    gap: 12px;
    overflow-x: auto;
    padding-bottom: 8px;
    scrollbar-width: none;
}
.order-scroll::-webkit-scrollbar { display: none; }

.order-card {
    min-width: 270px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    box-shadow: var(--shadow);
}
.order-card__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.order-card__user {
    display: flex;
    align-items: center;
    gap: 10px;
}
.order-card__avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: var(--blue-lt);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    border: 1px solid var(--border);
}
.order-card__name {
    font-size: 0.88rem;
    font-weight: 800;
    color: var(--ink);
}
.order-card__code {
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--blue);
    margin-top: 1px;
}
.order-card__badge {
    font-size: 0.6rem;
    font-weight: 800;
    background: var(--blue-lt);
    color: var(--blue);
    border-radius: 99px;
    padding: 3px 8px;
    text-transform: uppercase;
}
.order-card__meta {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ink-mid);
    line-height: 1.8;
}
.order-card__meta span {
    display: flex;
    align-items: center;
    gap: 6px;
}
.order-card__btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 14px;
    padding: 11px;
    background: var(--blue);
    color: white;
    border-radius: 99px;
    text-decoration: none;
    font-size: 0.78rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0,119,182,0.2);
}
.order-card__btn svg { width: 16px; height: 16px; }

.order-empty {
    width: 100%;
    text-align: center;
    padding: 30px 20px;
    background: var(--card);
    border: 1.5px dashed var(--border);
    border-radius: var(--radius);
    color: var(--ink-lt);
    font-size: 0.82rem;
    font-weight: 700;
}

/* ── QUICK ACTIONS ───────────────── */
.quick-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.quick-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 14px;
    text-decoration: none;
    color: inherit;
    box-shadow: var(--shadow);
    transition: transform 0.12s, box-shadow 0.12s;
}
.quick-item:active { transform: scale(0.97); }
.quick-item__icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.quick-item__label {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.3;
}

/* ── LOGOUT ──────────────────────── */
.logout-section { margin-top: 24px; margin-bottom: 12px; }
.logout-btn {
    width: 100%;
    padding: 13px;
    border-radius: var(--radius-sm);
    border: 1px solid #fecaca;
    background: #fef2f2;
    color: #b91c1c;
    font-family: var(--font);
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.version-tag {
    text-align: center;
    margin: 8px 0 12px;
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--ink-lt);
    letter-spacing: 0.3px;
}
</style>
</head>
<body>
<main class="wrap">

    {{-- Top Bar --}}
    <header class="top-bar js-in">
        <div class="brand">
            <div class="brand__icon">A</div>
            <span class="brand__name">AZKA LAUNDRY</span>
        </div>
        <a href="{{ route('admin.notifications') }}" class="notif-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
            @if(($adminUnread ?? 0) > 0)
                <span class="notif-badge">{{ $adminUnread }}</span>
            @endif
        </a>
    </header>

    {{-- Hero Greeting --}}
    <section class="hero js-in">
        <div class="hero__greeting">Halo, Admin! 👋</div>
        <div class="hero__sub">Berikut ringkasan operasional hari ini.</div>

        <div class="kpi-grid">
            <div class="kpi-card kpi-card--teal">
                <div class="kpi-card__icon">🧺</div>
                <div class="kpi-card__value">{{ $jumlahDiproses ?? 0 }}</div>
                <div class="kpi-card__label">Pesanan Masuk</div>
            </div>
            <div class="kpi-card kpi-card--orange">
                <div class="kpi-card__icon">🚚</div>
                <div class="kpi-card__value">{{ $jumlahPrioritas ?? 0 }}</div>
                <div class="kpi-card__label">Belum Dijemput</div>
            </div>
        </div>
    </section>

    {{-- Daftar Pesanan --}}
    <section class="section js-in">
        <div class="section__header">
            <span class="section__title">Daftar Pesanan</span>
            <a href="{{ route('admin.orders') }}" class="section__link">Lihat Semua</a>
        </div>
        <div class="order-scroll">
            @forelse(($orderPrioritas ?? collect()) as $order)
            <article class="order-card">
                <div class="order-card__top">
                    <div class="order-card__user">
                        <div class="order-card__avatar">👤</div>
                        <div>
                            <div class="order-card__name">{{ Str::limit($order->customer->name ?? 'Customer', 14) }}</div>
                            <div class="order-card__code">#{{ strtoupper($order->order_code) }}</div>
                        </div>
                    </div>
                    <span class="order-card__badge">Baru</span>
                </div>
                <div class="order-card__meta">
                    <span>📍 {{ Str::limit($order->address ?? '-', 30) }}</span>
                    <span>⏰ Dijemput {{ $order->pickup_time ?? '-' }}</span>
                </div>
                <a href="{{ route('admin.orders') }}" class="order-card__btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><polyline points="17 11 19 13 23 9"/></svg>
                    Tugaskan Kurir
                </a>
            </article>
            @empty
            <div class="order-empty">Belum ada pesanan masuk hari ini.</div>
            @endforelse
        </div>
    </section>

    {{-- Aksi Cepat --}}
    <section class="section js-in">
        <div class="section__header">
            <span class="section__title">Aksi Cepat</span>
        </div>
        <div class="quick-grid">
            <a href="{{ route('admin.walkin.form') }}" class="quick-item">
                <div class="quick-item__icon" style="background:var(--green-lt); color:var(--green);">👤</div>
                <span class="quick-item__label">Tambah Pelanggan</span>
            </a>
            <a href="{{ route('admin.finance.index') }}" class="quick-item">
                <div class="quick-item__icon" style="background:var(--orange-lt); color:#92400e;">🧾</div>
                <span class="quick-item__label">Laporan Keuangan</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="quick-item">
                <div class="quick-item__icon" style="background:#fef2f2; color:var(--red);">🐛</div>
                <span class="quick-item__label">Laporan Kendala</span>
            </a>
            <a href="{{ route('admin.orders') }}?status=selesai" class="quick-item">
                <div class="quick-item__icon" style="background:var(--teal-lt); color:var(--teal);">✅</div>
                <span class="quick-item__label">Pesanan Selesai</span>
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="quick-item">
                <div class="quick-item__icon" style="background:#fff7ed; color:#c2410c;">🎟️</div>
                <span class="quick-item__label">Voucher Promo</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="quick-item">
                <div class="quick-item__icon" style="background:var(--blue-lt); color:var(--blue);">📂</div>
                <span class="quick-item__label">Kategori Layanan</span>
            </a>
            <a href="{{ route('admin.audit.index') }}" class="quick-item">
                <div class="quick-item__icon" style="background:#f3f4f6; color:#475569;">📋</div>
                <span class="quick-item__label">Audit Trail</span>
            </a>
        </div>
    </section>

    {{-- Analitik 30 Hari --}}
    @include('roles.admin._analytics_section')

    {{-- Logout --}}
    <div class="logout-section js-in">
        <form action="{{ route('logout') }}" method="POST" id="form-logout">
            @csrf
            <button type="button" class="logout-btn" id="btn-logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar dari Akun
            </button>
        </form>
    </div>
    <div class="version-tag">Azka Laundry v{{ \App\Support\Laundry::version() }} &bull; Admin Panel</div>

</main>

@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])
@include('layouts.component._confirm_modal')

<script>
document.addEventListener('DOMContentLoaded', function(){
    gsap.from('.js-in', { y: 30, opacity: 0, duration: 0.6, stagger: 0.1, ease: 'power3.out' });

    var btnLogout = document.getElementById('btn-logout');
    if (btnLogout) {
        btnLogout.addEventListener('click', function() {
            showConfirmModal({
                title: 'Keluar dari Akun?',
                message: 'Kamu akan keluar dari sesi ini. Yakin ingin melanjutkan?',
                confirmText: 'Ya, Keluar',
                cancelText: 'Batal',
                type: 'danger',
                onConfirm: function() {
                    document.getElementById('form-logout').submit();
                }
            });
        });
    }
});
</script>
</body>
</html>
