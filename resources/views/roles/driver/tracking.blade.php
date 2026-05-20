<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Lacak Tugas – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --primary: #0d6fb8;
    --primary-dark: #002f5c;
    --primary-light: #e0f4ff;
    --accent: #FF6B35;
    --success: #059669;
    --success-light: #ecfdf5;
    --warning: #d97706;
    --warning-light: #fffbeb;
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
.page-header__title { font-weight: 800; font-size: 1.15rem; color: #fff; }
.page-header__subtitle { font-size: .68rem; font-weight: 600; color: rgba(255,255,255,.6); margin-top: 1px; }

.page-body { max-width: 520px; margin: 0 auto; padding: 14px 16px; }

/* Status Overview Card */
.overview-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: var(--shadow-sm);
}
.overview-card__title {
    font-weight: 700;
    font-size: .88rem;
    color: var(--ink);
    margin-bottom: 12px;
}
.overview-card__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.overview-card__stat {
    background: var(--border-light);
    border-radius: var(--radius-sm);
    padding: 12px;
    text-align: center;
}
.overview-card__stat-num {
    font-weight: 800;
    font-size: 1.3rem;
    color: var(--primary);
}
.overview-card__stat-label {
    font-size: .68rem;
    font-weight: 600;
    color: var(--ink-muted);
    margin-top: 2px;
}

/* Active Task */
.active-task {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 12px;
    box-shadow: var(--shadow-md);
}
.active-task__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 16px;
    border-bottom: 1px solid var(--border-light);
}
.active-task__title {
    font-weight: 700;
    font-size: .88rem;
    color: var(--ink);
}
.active-task__badge {
    font-size: .62rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 99px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.active-task__badge--pickup { background: var(--warning-light); color: var(--warning); }
.active-task__badge--delivery { background: var(--success-light); color: var(--success); }
.active-task__badge--live { background: var(--primary-light); color: var(--primary); }

.active-task__body { padding: 14px 16px; }
.active-task__customer {
    font-weight: 800;
    font-size: .95rem;
    color: var(--ink);
    margin-bottom: 6px;
}
.active-task__address {
    font-size: .78rem;
    font-weight: 600;
    color: var(--ink-secondary);
    line-height: 1.5;
    margin-bottom: 10px;
}
.active-task__meta {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}
.active-task__chip {
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
.active-task__actions {
    padding: 12px 16px;
    background: var(--border-light);
    display: flex;
    gap: 8px;
}
.active-task__btn {
    flex: 1;
    padding: 10px;
    border-radius: var(--radius-sm);
    border: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .76rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    text-decoration: none;
}
.active-task__btn--primary { background: var(--primary); color: #fff; }
.active-task__btn--wa { background: var(--success-light); color: var(--success); }

/* Empty */
.empty-state {
    text-align: center;
    padding: 50px 20px;
}
.empty-state__icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
}
.empty-state__icon svg { width: 24px; height: 24px; color: var(--primary); }
.empty-state__title { font-weight: 800; font-size: 1rem; color: var(--ink); margin-bottom: 6px; }
.empty-state__desc { font-size: .8rem; font-weight: 500; color: var(--ink-muted); line-height: 1.5; }
</style>
</head>
<body>

@php
    $driver = auth()->user();
    $activeOrders = \App\Models\Order::where('driver_id', $driver->id)
        ->whereIn('status', ['dijemput', 'dikirim'])
        ->with(['customer', 'customerAddress'])
        ->latest()
        ->get();
    $selesaiHariIni = \App\Models\Order::where('driver_id', $driver->id)
        ->where('status', 'selesai')
        ->whereDate('updated_at', today())
        ->count();
@endphp

<header class="page-header">
    <div class="page-header__inner">
        <a href="{{ route('driver.dashboard') }}" class="page-header__back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <div class="page-header__title">Lacak Tugas</div>
            <div class="page-header__subtitle">Status penjemputan & pengiriman</div>
        </div>
    </div>
</header>

<div class="page-body">

    {{-- Ringkasan --}}
    <div class="overview-card">
        <div class="overview-card__title">Ringkasan Hari Ini</div>
        <div class="overview-card__grid">
            <div class="overview-card__stat">
                <div class="overview-card__stat-num">{{ $activeOrders->where('status', 'dijemput')->count() }}</div>
                <div class="overview-card__stat-label">Pickup Aktif</div>
            </div>
            <div class="overview-card__stat">
                <div class="overview-card__stat-num">{{ $activeOrders->where('status', 'dikirim')->count() }}</div>
                <div class="overview-card__stat-label">Delivery Aktif</div>
            </div>
            <div class="overview-card__stat">
                <div class="overview-card__stat-num">{{ $selesaiHariIni }}</div>
                <div class="overview-card__stat-label">Selesai Hari Ini</div>
            </div>
            <div class="overview-card__stat">
                <div class="overview-card__stat-num">{{ $activeOrders->count() }}</div>
                <div class="overview-card__stat-label">Total Aktif</div>
            </div>
        </div>
    </div>

    {{-- Daftar tugas aktif --}}
    @forelse($activeOrders as $order)
    <div class="active-task">
        <div class="active-task__header">
            <div class="active-task__title">#{{ strtoupper($order->order_code) }}</div>
            <span class="active-task__badge {{ $order->status === 'dijemput' ? 'active-task__badge--pickup' : 'active-task__badge--delivery' }}">
                {{ $order->status === 'dijemput' ? 'Jemput' : 'Antar' }}
            </span>
        </div>
        <div class="active-task__body">
            <div class="active-task__customer">{{ $order->customer->name ?? 'Customer' }}</div>
            <div class="active-task__address">{{ $order->customerAddress?->full_address ?? $order->address ?? '-' }}</div>
            <div class="active-task__meta">
                <span class="active-task__chip">{{ $order->weight_estimate }} kg</span>
                @if($order->pickup_date)
                <span class="active-task__chip">{{ $order->pickup_date->format('d/m') }}</span>
                @endif
                <span class="active-task__chip">{{ ucfirst($order->pickup_time ?? '-') }}</span>
            </div>
        </div>
        <div class="active-task__actions">
            @if($order->customer?->phone)
            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}" target="_blank" class="active-task__btn active-task__btn--wa">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                WA
            </a>
            @endif
            <a href="{{ route('driver.orders.show', $order) }}" class="active-task__btn active-task__btn--primary">
                Detail & Aksi
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="empty-state__title">Tidak Ada Tugas Aktif</div>
        <div class="empty-state__desc">Semua tugas sudah diselesaikan.<br>Kamu akan mendapat notifikasi saat ada penugasan baru.</div>
    </div>
    @endforelse

</div>

@include('layouts.component.driver._navbar_driver', ['active' => 'tugas'])

</body>
</html>
