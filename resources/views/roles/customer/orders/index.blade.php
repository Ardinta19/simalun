<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Daftar Pesanan – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
/* ── CSS VARIABLES ─────────────────────── */
:root {
    --blue-dark:  #002f5c;
    --blue-mid:   #0077b6;
    --blue-light: #00b4d8;
    --blue-sky:   #e0f4ff;
    --orange:     #FF6B35;
    --orange-lt:  #fff3ee;
    --green:      #00C48C;
    --green-lt:   #e6fff6;
    --red:        #ef4444;
    --red-lt:     #fff1f1;
    --yellow:     #f59e0b;
    --yellow-lt:  #fffbeb;
    --surface:    #f4f8fc;
    --card:       #ffffff;
    --ink:        #1a2332;
    --ink-mid:    #3d5066;
    --ink-lt:     #8899aa;
    --border:     #ddeeff;
    --radius:     20px;
    --radius-sm:  12px;
}

*, *::before, *::after {
    margin: 0; padding: 0;
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}
html { scroll-behavior: smooth; }
body {
    font-family: 'Nunito', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* ── TOP HEADER ──────────────────────── */
.page-header {
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
    padding: max(env(safe-area-inset-top, 0px), 16px) 20px 28px;
    position: sticky;
    top: 0;
    z-index: 100;
}
.header-row {
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 520px;
    margin: 0 auto;
}
.btn-back {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center;
    color: white;
    text-decoration: none;
    flex-shrink: 0;
    transition: background 0.2s;
}
.btn-back:hover { background: rgba(255,255,255,0.25); }
.btn-back svg { width: 18px; height: 18px; }
.header-title {
    flex: 1;
    font-family: 'Fredoka One', cursive;
    font-size: 1.3rem;
    color: white;
    letter-spacing: 0.2px;
}
.header-sub {
    font-size: 0.68rem;
    font-weight: 800;
    color: rgba(255,255,255,0.65);
    letter-spacing: 0.5px;
    margin-top: 2px;
}

/* ── FILTER TABS ─────────────────────── */
.filter-wrap {
    background: white;
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 80px;
    z-index: 90;
}
.filter-inner {
    max-width: 520px;
    margin: 0 auto;
    display: flex;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 12px 16px;
    gap: 8px;
}
.filter-inner::-webkit-scrollbar { display: none; }
.filter-chip {
    flex-shrink: 0;
    padding: 7px 16px;
    border-radius: 99px;
    font-size: 0.75rem;
    font-weight: 800;
    border: 1.5px solid var(--border);
    background: white;
    color: var(--ink-lt);
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
    font-family: 'Nunito', sans-serif;
}
.filter-chip.is-active {
    background: var(--blue-mid);
    border-color: var(--blue-mid);
    color: white;
    box-shadow: 0 4px 12px rgba(0,119,182,0.25);
}

/* ── PAGE BODY ───────────────────────── */
.page-body {
    max-width: 520px;
    margin: 0 auto;
    padding: 16px;
}

/* ── EMPTY STATE ─────────────────────── */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-icon {
    font-size: 3.5rem;
    margin-bottom: 16px;
    display: block;
    opacity: 0.6;
}
.empty-title {
    font-family: 'Fredoka One', cursive;
    font-size: 1.1rem;
    color: var(--ink-mid);
    margin-bottom: 8px;
}
.empty-sub {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink-lt);
    margin-bottom: 20px;
    line-height: 1.5;
}
.btn-order-now {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--orange);
    color: white;
    padding: 12px 24px;
    border-radius: 99px;
    font-weight: 900;
    font-size: 0.88rem;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(255,107,53,0.35);
}

/* ── ORDER CARD ──────────────────────── */
.order-card {
    background: white;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 12px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,47,92,0.05);
    text-decoration: none;
    color: inherit;
    display: block;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    opacity: 0;
    transform: translateY(20px);
}
.order-card:active {
    transform: scale(0.98);
    box-shadow: 0 1px 6px rgba(0,47,92,0.08);
}

/* Status strip on the left */
.order-card__strip {
    height: 4px;
    width: 100%;
}
.order-card__body {
    padding: 14px 16px;
}
.order-card__top {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 12px;
}
.order-card__icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.order-card__info { flex: 1; min-width: 0; }
.order-card__code {
    font-size: 0.72rem;
    font-weight: 900;
    color: var(--blue-mid);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.order-card__service {
    font-family: 'Fredoka One', cursive;
    font-size: 1rem;
    color: var(--ink);
    line-height: 1.2;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.order-card__date {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--ink-lt);
    margin-top: 3px;
}
.order-card__status {
    flex-shrink: 0;
    text-align: right;
}
.status-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 99px;
    font-size: 0.65rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    white-space: nowrap;
}
/* Status color variants */
.status-badge.menunggu     { background: var(--yellow-lt); color: #92400e; }
.status-badge.dijemput     { background: #e0f2fe; color: #0369a1; }
.status-badge.dicuci       { background: var(--blue-sky); color: var(--blue-mid); }
.status-badge.disetrika    { background: #f3e8ff; color: #7c3aed; }
.status-badge.siap         { background: var(--green-lt); color: #065f46; }
.status-badge.dikirim      { background: #fff3ee; color: #9a3412; }
.status-badge.selesai      { background: var(--green-lt); color: #065f46; }
.status-badge.dibatalkan   { background: var(--red-lt); color: #b91c1c; }

/* Progress bar */
.order-card__progress {
    margin-bottom: 12px;
}
.progress-steps {
    display: flex;
    align-items: center;
    gap: 0;
}
.progress-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: var(--border);
    flex-shrink: 0;
}
.progress-dot.done { background: var(--green); }
.progress-dot.active { background: var(--blue-mid); box-shadow: 0 0 0 3px rgba(0,119,182,0.15); }
.progress-line {
    flex: 1;
    height: 2px;
    background: var(--border);
}
.progress-line.done { background: var(--green); }
.progress-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 4px;
}
.progress-lbl {
    font-size: 0.55rem;
    font-weight: 800;
    color: var(--ink-lt);
    text-align: center;
    flex: 1;
}
.progress-lbl.active { color: var(--blue-mid); }
.progress-lbl.done   { color: var(--green); }

/* Bottom row */
.order-card__footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 10px;
    border-top: 1px solid #f1f5f9;
}
.order-card__price {
    font-family: 'Fredoka One', cursive;
    font-size: 1.05rem;
    color: var(--blue-mid);
}
.order-card__price-est {
    font-size: 0.62rem;
    font-weight: 700;
    color: var(--ink-lt);
    margin-top: 1px;
}
.order-card__cta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 900;
    color: var(--blue-mid);
}
.order-card__cta svg { width: 14px; height: 14px; }

/* ── SECTION TITLE ───────────────────── */
.section-title {
    font-family: 'Fredoka One', cursive;
    font-size: 0.9rem;
    color: var(--ink-mid);
    margin: 4px 0 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}

/* ── PAGINATION ──────────────────────── */
.pagination-wrap {
    padding: 8px 0 4px;
}
.pagination-wrap nav { display: flex; justify-content: center; }

/* ── ACTIVE ORDER BANNER ─────────────── */
.active-banner {
    background: linear-gradient(135deg, #002f5c 0%, #0077b6 100%);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
    position: relative;
    overflow: hidden;
}
.active-banner::before {
    content: '';
    position: absolute;
    right: -20px; top: -20px;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.active-banner__pulse {
    width: 44px; height: 44px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
    animation: nav-pulse 2s ease-in-out infinite;
}
@keyframes nav-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
    50%       { box-shadow: 0 0 0 10px rgba(255,255,255,0); }
}
.active-banner__info { flex: 1; }
.active-banner__label {
    font-size: 0.7rem;
    font-weight: 800;
    color: rgba(255,255,255,0.7);
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.active-banner__title {
    font-family: 'Fredoka One', cursive;
    font-size: 1rem;
    color: white;
    margin-top: 2px;
}
.active-banner__status {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    color: white;
    border-radius: 99px;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 3px 8px;
    margin-top: 5px;
    letter-spacing: 0.3px;
}
.active-banner__arrow {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,0.15);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
</style>
</head>
<body>

{{-- PAGE HEADER --}}
<header class="page-header">
    <div class="header-row">
        <a href="{{ route('customer.dashboard') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <div class="header-title">Pesanan Saya</div>
            <div class="header-sub">AZKA LAUNDRY • SIMALUN</div>
        </div>
    </div>
</header>

{{-- FILTER TABS --}}
<div class="filter-wrap">
    <div class="filter-inner">
        @php
            $filters = [
                'semua'    => 'Semua',
                'aktif'    => 'Berjalan',
                'selesai'  => 'Selesai',
                'batal'    => 'Dibatalkan',
            ];
            $currentFilter = request('filter', 'semua');
        @endphp
        @foreach($filters as $key => $label)
        <a href="{{ route('customer.orders', ['filter' => $key]) }}"
           class="filter-chip {{ $currentFilter === $key ? 'is-active' : '' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>

{{-- PAGE BODY --}}
<div class="page-body">

    {{-- Pesanan Aktif Banner --}}
    @if(isset($pesananAktif) && $pesananAktif)
    <a href="{{ route('customer.order.detail', ['order' => $pesananAktif->id, 'back' => route('customer.orders')]) }}" class="active-banner" id="active-banner">
        <div class="active-banner__pulse">🛵</div>
        <div class="active-banner__info">
            <div class="active-banner__label">Pesanan Aktif</div>
            <div class="active-banner__title">#{{ strtoupper($pesananAktif->order_code) }}</div>
            <span class="active-banner__status">{{ $pesananAktif->status_label }}</span>
        </div>
        <div class="active-banner__arrow">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 18l6-6-6-6"/>
            </svg>
        </div>
    </a>
    @endif

    {{-- Order List --}}
    @if(isset($pesanan) && $pesanan->count() > 0)

        @if($currentFilter === 'semua')
        <div class="section-title">{{ $pesanan->total() }} Pesanan</div>
        @endif

        @php
        $statusProgress = [
            'menunggu'   => 0,
            'dijemput'   => 1,
            'dicuci'     => 2,
            'disetrika'  => 2,
            'siap'       => 3,
            'dikirim'    => 3,
            'selesai'    => 4,
            'dibatalkan' => -1,
        ];
        $progressLabels = ['Pesan', 'Jemput', 'Cuci', 'Siap', 'Selesai'];
        $serviceIcons = [
            'cuci-saja'        => ['icon' => '🧺', 'bg' => '#e0f4ff'],
            'cuci-setrika'     => ['icon' => '👕', 'bg' => '#e0f4ff'],
            'express-1-hari'   => ['icon' => '⚡', 'bg' => '#fff3ee'],
            'jas-kemeja'       => ['icon' => '👔', 'bg' => '#f3e8ff'],
            'bedcover-kecil'   => ['icon' => '🛏️', 'bg' => '#f0fff8'],
            'bedcover-sedang'  => ['icon' => '🛏️', 'bg' => '#f0fff8'],
            'bedcover-jumbo'   => ['icon' => '🛏️', 'bg' => '#f0fff8'],
        ];
        $stripColors = [
            'menunggu'   => '#f59e0b',
            'dijemput'   => '#0ea5e9',
            'dicuci'     => '#0077b6',
            'disetrika'  => '#7c3aed',
            'siap'       => '#00C48C',
            'dikirim'    => '#FF6B35',
            'selesai'    => '#00C48C',
            'dibatalkan' => '#ef4444',
        ];
        @endphp

        @foreach($pesanan as $o)
        @php
            $slug     = $o->service->slug ?? 'cuci-setrika';
            $svcIcon  = $serviceIcons[$slug] ?? ['icon' => '🧺', 'bg' => '#e0f4ff'];
            $curStep  = $statusProgress[$o->status] ?? 0;
            $strip    = $stripColors[$o->status] ?? '#0077b6';
        @endphp
        <a href="{{ route('customer.order.detail', ['order' => $o->id, 'back' => route('customer.orders')]) }}"
           class="order-card js-card"
           style="--strip-color: {{ $strip }}">
            {{-- Status Strip --}}
            <div class="order-card__strip" style="background: {{ $strip }}"></div>

            <div class="order-card__body">
                {{-- Top Row --}}
                <div class="order-card__top">
                    <div class="order-card__icon" style="background: {{ $svcIcon['bg'] }}">
                        {{ $svcIcon['icon'] }}
                    </div>
                    <div class="order-card__info">
                        <div class="order-card__code">#{{ strtoupper($o->order_code) }}</div>
                        <div class="order-card__service">{{ $o->service->name ?? 'Layanan Laundry' }}</div>
                        <div class="order-card__date">
                            {{ $o->created_at->translatedFormat('D, d M Y') }}
                            • {{ $o->weight_estimate }} kg est.
                        </div>
                    </div>
                    <div class="order-card__status">
                        <span class="status-badge {{ $o->status }}">{{ $o->status_label }}</span>
                    </div>
                </div>

                {{-- Progress Steps (only for active orders) --}}
                @if(!in_array($o->status, ['selesai', 'dibatalkan']))
                <div class="order-card__progress">
                    <div class="progress-steps">
                        @foreach($progressLabels as $si => $sl)
                            <div class="progress-dot {{ $curStep > $si ? 'done' : ($curStep == $si ? 'active' : '') }}"></div>
                            @if(!$loop->last)
                            <div class="progress-line {{ $curStep > $si ? 'done' : '' }}"></div>
                            @endif
                        @endforeach
                    </div>
                    <div class="progress-labels">
                        @foreach($progressLabels as $si => $sl)
                        <div class="progress-lbl {{ $curStep > $si ? 'done' : ($curStep == $si ? 'active' : '') }}">{{ $sl }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Footer --}}
                <div class="order-card__footer">
                    <div>
                        <div class="order-card__price">Rp {{ number_format($o->total_cost, 0, ',', '.') }}</div>
                        <div class="order-card__price-est">
                            {{ $o->is_paid ? '✓ Lunas' : 'Bayar COD' }}
                        </div>
                    </div>
                    <div class="order-card__cta">
                        Lihat Detail
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
        @endforeach

        {{-- Pagination --}}
        <div class="pagination-wrap">
            {{ $pesanan->appends(request()->query())->links('vendor.pagination.customer-simple') }}
        </div>

    @else
    {{-- Empty State --}}
    <div class="empty-state">
        <span class="empty-icon">
            @if($currentFilter === 'aktif') 🛵
            @elseif($currentFilter === 'selesai') ✅
            @elseif($currentFilter === 'batal') ❌
            @else 📭
            @endif
        </span>
        <div class="empty-title">
            @if($currentFilter === 'aktif') Belum Ada Pesanan Aktif
            @elseif($currentFilter === 'selesai') Belum Ada Pesanan Selesai
            @elseif($currentFilter === 'batal') Tidak Ada Pesanan Dibatalkan
            @else Belum Ada Pesanan
            @endif
        </div>
        <div class="empty-sub">
            @if($currentFilter === 'aktif')
                Semua cucianmu sudah beres! Yuk buat pesanan baru.
            @elseif($currentFilter === 'selesai')
                Riwayat pesanan selesai akan muncul di sini.
            @else
                Pesan layanan laundry sekarang dan nikmati kemudahan jemput-antar!
            @endif
        </div>
        <a href="{{ route('order.create') }}" class="btn-order-now">
            🧺 Buat Pesanan
        </a>
    </div>
    @endif

</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'pesanan'])

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined') {
            document.querySelectorAll('.js-card').forEach(function(el) {
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
            return;
        }

        var cards = document.querySelectorAll('.js-card');
        if (cards.length > 0) {
            gsap.to(cards, {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.07,
                ease: 'power2.out',
                delay: 0.1,
            });
        }

        var animate = function(selector, options) {
            var els = document.querySelectorAll(selector);
            if (els.length > 0) gsap.from(els, options);
        };

        animate('#active-banner', { opacity: 0, y: -20, duration: 0.5, ease: 'back.out(1.5)' });
        animate('.page-header', { opacity: 0, y: -20, duration: 0.4, ease: 'power2.out' });
        animate('.empty-state', { opacity: 0, y: 30, duration: 0.6, ease: 'power2.out' });
    });
</script>

</body>
</html>