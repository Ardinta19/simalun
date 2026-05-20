<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Daftar Tugas – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --primary: #0d6fb8;
    --primary-dark: #002f5c;
    --primary-light: #e0f4ff;
    --accent: #FF6B35;
    --accent-light: #fff3ee;
    --success: #059669;
    --success-light: #ecfdf5;
    --warning: #d97706;
    --warning-light: #fffbeb;
    --danger: #dc2626;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-secondary: #475569;
    --ink-muted: #94a3b8;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --radius: 14px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
    --shadow-md: 0 4px 12px rgba(0,47,92,.08);
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    padding: max(env(safe-area-inset-top, 0px), 16px) 20px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
}
.page-header__inner {
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 520px;
    margin: 0 auto;
}
.page-header__back {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    text-decoration: none;
    flex-shrink: 0;
}
.page-header__back svg { width: 18px; height: 18px; }
.page-header__title {
    font-weight: 800;
    font-size: 1.15rem;
    color: #fff;
}
.page-header__subtitle {
    font-size: .68rem;
    font-weight: 600;
    color: rgba(255,255,255,.6);
    margin-top: 1px;
}

/* Body */
.page-body {
    max-width: 520px;
    margin: 0 auto;
    padding: 14px 16px;
}

/* Filter tabs */
.filter-summary {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 14px;
    padding: 0 2px;
}
.filter-summary__count {
    font-size: .78rem;
    font-weight: 700;
    color: var(--ink-secondary);
}
.filter-summary__badge {
    background: var(--primary-light);
    color: var(--primary);
    font-size: .68rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 99px;
}

/* Task Card */
.task-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
    transition: box-shadow .2s;
}
.task-card:active {
    box-shadow: var(--shadow-md);
}
.task-card__strip {
    height: 3px;
    width: 100%;
}
.task-card__body {
    padding: 14px 16px;
}
.task-card__top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 10px;
}
.task-card__code {
    font-size: .75rem;
    font-weight: 700;
    color: var(--primary);
    letter-spacing: .3px;
}
.task-card__customer {
    font-weight: 800;
    font-size: .92rem;
    color: var(--ink);
    margin-top: 2px;
}
.task-card__badge {
    font-size: .62rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 99px;
    text-transform: uppercase;
    white-space: nowrap;
    flex-shrink: 0;
    letter-spacing: .3px;
}
.task-card__badge--pickup { background: var(--warning-light); color: var(--warning); }
.task-card__badge--delivery { background: var(--success-light); color: var(--success); }

.task-card__address {
    font-size: .78rem;
    font-weight: 600;
    color: var(--ink-secondary);
    line-height: 1.5;
    margin-bottom: 10px;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}
.task-card__address svg {
    width: 14px; height: 14px;
    flex-shrink: 0;
    margin-top: 2px;
    color: var(--ink-muted);
}

.task-card__meta {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 12px;
    flex-wrap: wrap;
}
.task-card__chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: var(--border-light);
    color: var(--ink-secondary);
    border-radius: 99px;
    padding: 4px 10px;
    font-size: .68rem;
    font-weight: 700;
}

.task-card__actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.task-card__btn {
    padding: 10px;
    border-radius: var(--radius-sm);
    border: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .75rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-decoration: none;
    transition: transform .12s, opacity .12s;
}
.task-card__btn:active { transform: scale(.96); }
.task-card__btn--primary { background: var(--primary); color: #fff; }
.task-card__btn--success { background: var(--success); color: #fff; }
.task-card__btn--outline {
    background: var(--card);
    color: var(--ink-secondary);
    border: 1.5px solid var(--border);
}
.task-card__btn--disabled {
    background: var(--border-light);
    color: var(--ink-muted);
    cursor: default;
}

/* Quick Action */
.task-card__quick {
    background: var(--border-light);
    border-top: 1px solid var(--border);
    padding: 11px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.task-card__quick-label {
    font-size: .72rem;
    font-weight: 600;
    color: var(--ink-secondary);
    flex: 1;
}
.task-card__quick-btn {
    padding: 8px 14px;
    border: none;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .72rem;
    cursor: pointer;
    white-space: nowrap;
    text-decoration: none;
    transition: transform .12s;
}
.task-card__quick-btn:active { transform: scale(.96); }
.task-card__quick-btn--confirm { background: var(--success); color: #fff; }
.task-card__quick-btn--upload { background: var(--accent); color: #fff; }

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state__icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px;
}
.empty-state__icon svg { width: 28px; height: 28px; color: var(--primary); }
.empty-state__title {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--ink);
    margin-bottom: 6px;
}
.empty-state__desc {
    font-size: .82rem;
    font-weight: 500;
    color: var(--ink-muted);
    line-height: 1.5;
}

/* Pagination */
.pagination-wrap {
    padding: 12px 0;
}
.pagination-wrap nav { display: flex; justify-content: center; }
</style>
</head>
<body>

<header class="page-header">
    <div class="page-header__inner">
        <a href="{{ route('driver.dashboard') }}" class="page-header__back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <div class="page-header__title">Daftar Tugas</div>
            <div class="page-header__subtitle">Tugas jemput & antar yang aktif</div>
        </div>
    </div>
</header>

<div class="page-body">

    @if($pesanan->count() > 0)
    <div class="filter-summary">
        <span class="filter-summary__count">{{ $pesanan->total() }} tugas aktif</span>
        <span class="filter-summary__badge">Hari ini</span>
    </div>
    @endif

    @php
        $stripColors = [
            'dijemput' => '#3b82f6',
            'dikirim'  => '#f59e0b',
            'siap'     => '#059669',
            'menunggu' => '#94a3b8',
        ];
    @endphp

    @forelse($pesanan as $order)
    @php $strip = $stripColors[$order->status] ?? '#0d6fb8'; @endphp
    <div class="task-card">
        <div class="task-card__strip" style="background:{{ $strip }}"></div>
        <div class="task-card__body">
            <div class="task-card__top">
                <div>
                    <div class="task-card__code">#{{ strtoupper($order->order_code) }}</div>
                    <div class="task-card__customer">{{ $order->customer->name ?? 'Customer' }}</div>
                </div>
                <span class="task-card__badge {{ $order->status === 'dijemput' ? 'task-card__badge--pickup' : 'task-card__badge--delivery' }}">
                    {{ $order->status === 'dijemput' ? 'Jemput' : 'Antar' }}
                </span>
            </div>

            <div class="task-card__address">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>{{ Str::limit($order->customerAddress?->full_address ?? $order->address ?? '-', 70) }}</span>
            </div>

            <div class="task-card__meta">
                <span class="task-card__chip">{{ $order->weight_estimate }} kg</span>
                <span class="task-card__chip">{{ ucfirst($order->pickup_time ?? '-') }}</span>
                @if($order->pickup_date)
                <span class="task-card__chip">{{ $order->pickup_date->format('d/m') }}</span>
                @endif
            </div>

            <div class="task-card__actions">
                @if($order->customer?->phone)
                <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}"
                   target="_blank" class="task-card__btn task-card__btn--outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                    Chat WA
                </a>
                @else
                <div class="task-card__btn task-card__btn--disabled">Tidak ada WA</div>
                @endif
                <a href="{{ route('driver.orders.show', $order) }}" class="task-card__btn task-card__btn--primary">
                    Detail
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        @if($order->status === 'dijemput')
        <form method="POST" action="{{ route('driver.orders.action', $order) }}" class="task-card__quick">
            @csrf
            <input type="hidden" name="status" value="dicuci">
            <span class="task-card__quick-label">Pakaian sudah dijemput?</span>
            <button type="submit" class="task-card__quick-btn task-card__quick-btn--confirm">Konfirmasi</button>
        </form>
        @elseif($order->status === 'dikirim')
        <div class="task-card__quick">
            <span class="task-card__quick-label">Upload bukti di halaman detail</span>
            <a href="{{ route('driver.orders.show', $order) }}" class="task-card__quick-btn task-card__quick-btn--upload">Upload Bukti</a>
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="empty-state__title">Semua Tugas Selesai</div>
        <div class="empty-state__desc">Belum ada tugas baru saat ini.<br>Kamu akan mendapat notifikasi saat ada penugasan.</div>
    </div>
    @endforelse

    @if($pesanan->hasPages())
    <div class="pagination-wrap">
        {{ $pesanan->links() }}
    </div>
    @endif

</div>

@include('layouts.component.driver._navbar_driver', ['active' => 'tugas'])

</body>
</html>
