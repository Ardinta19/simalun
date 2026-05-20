<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Manajemen Pesanan – Azka Laundry</title>
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
    justify-content: space-between;
    max-width: 520px;
    margin: 0 auto;
}
.page-header__left {
    display: flex;
    align-items: center;
    gap: 12px;
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
.page-header__title { font-weight: 800; font-size: 1.1rem; color: #fff; }
.page-header__subtitle { font-size: .66rem; font-weight: 600; color: rgba(255,255,255,.6); margin-top: 1px; }
.page-header__action {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--accent);
    color: #fff;
    text-decoration: none;
    font-weight: 700;
    font-size: .72rem;
    padding: 8px 14px;
    border-radius: 99px;
    box-shadow: 0 3px 10px rgba(255,107,53,.3);
    transition: transform .12s;
}
.page-header__action:active { transform: scale(.95); }
.page-header__action svg { width: 14px; height: 14px; }

/* Body */
.page-body { max-width: 520px; margin: 0 auto; padding: 14px 16px; }

/* Stats Row */
.stats-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-bottom: 14px;
}
.stats-row__card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 10px;
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.stats-row__num {
    font-weight: 800;
    font-size: 1.2rem;
    color: var(--primary);
    line-height: 1;
}
.stats-row__num--accent { color: var(--accent); }
.stats-row__num--success { color: var(--success); }
.stats-row__label {
    font-size: .6rem;
    font-weight: 700;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-top: 4px;
}

/* Filter */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    overflow-x: auto;
    padding-bottom: 4px;
    -webkit-overflow-scrolling: touch;
}
.filter-bar::-webkit-scrollbar { display: none; }
.filter-bar__chip {
    display: inline-flex;
    align-items: center;
    padding: 7px 14px;
    border-radius: 99px;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
    text-decoration: none;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--ink-secondary);
    transition: all .15s;
}
.filter-bar__chip--active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Order Card */
.order-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 10px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
.order-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border-light);
}
.order-card__code {
    font-size: .75rem;
    font-weight: 700;
    color: var(--primary);
    font-variant-numeric: tabular-nums;
}
.order-card__badge {
    font-size: .6rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 99px;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.order-card__badge--menunggu { background: var(--warning-light); color: var(--warning); }
.order-card__badge--proses { background: var(--primary-light); color: var(--primary); }
.order-card__badge--selesai { background: var(--success-light); color: var(--success); }
.order-card__badge--batal { background: #fef2f2; color: var(--danger); }

.order-card__body { padding: 12px 14px; }
.order-card__customer {
    font-weight: 700;
    font-size: .88rem;
    color: var(--ink);
    margin-bottom: 2px;
}
.order-card__phone {
    font-size: .7rem;
    font-weight: 600;
    color: var(--ink-muted);
    margin-bottom: 8px;
}
.order-card__meta {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}
.order-card__chip {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    background: var(--border-light);
    color: var(--ink-secondary);
    border-radius: 99px;
    padding: 3px 9px;
    font-size: .66rem;
    font-weight: 700;
}
.order-card__total {
    font-weight: 800;
    font-size: .85rem;
    color: var(--success);
}

/* Action Panels inside order card */
.order-card__panel {
    padding: 10px 14px;
    border-top: 1px solid var(--border-light);
    background: var(--border-light);
}
.order-card__panel-form {
    display: flex;
    align-items: center;
    gap: 6px;
}
.order-card__panel-label {
    font-size: .68rem;
    font-weight: 700;
    color: var(--ink-secondary);
    margin-bottom: 6px;
}
.order-card__select {
    flex: 1;
    padding: 8px 10px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .72rem;
    font-weight: 600;
    color: var(--ink);
    background: #fff;
    outline: none;
}
.order-card__select:focus { border-color: var(--primary); }
.order-card__panel-btn {
    padding: 8px 14px;
    border: none;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .7rem;
    cursor: pointer;
    white-space: nowrap;
    color: #fff;
    transition: transform .12s;
}
.order-card__panel-btn:active { transform: scale(.95); }
.order-card__panel-btn--primary { background: var(--primary); }
.order-card__panel-btn--accent { background: var(--accent); }
.order-card__panel-btn--success { background: var(--success); }

.order-card__footer {
    padding: 10px 14px;
    border-top: 1px solid var(--border-light);
}
.order-card__detail-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    width: 100%;
    padding: 9px;
    border-radius: var(--radius-sm);
    background: var(--border-light);
    color: var(--ink-secondary);
    font-weight: 700;
    font-size: .75rem;
    text-decoration: none;
    transition: background .12s;
}
.order-card__detail-btn:active { background: var(--border); }

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
.empty-state__desc { font-size: .8rem; font-weight: 500; color: var(--ink-muted); }

/* Pagination */
.pagination-wrap { padding: 12px 0; }
.pagination-wrap nav { display: flex; justify-content: center; }

/* Alert */
.page-alert {
    padding: 10px 14px;
    border-radius: var(--radius-sm);
    font-size: .8rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.page-alert--success { background: var(--success-light); color: var(--success); border: 1px solid rgba(5,150,105,.2); }
.page-alert--error { background: #fef2f2; color: var(--danger); border: 1px solid rgba(220,38,38,.15); }
</style>
</head>
<body>

<header class="page-header">
    <div class="page-header__inner">
        <div class="page-header__left">
            <a href="{{ route('dashboard.admin') }}" class="page-header__back" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div>
                <div class="page-header__title">Manajemen Pesanan</div>
                <div class="page-header__subtitle">Kelola semua order masuk</div>
            </div>
        </div>
        <a href="{{ route('admin.walkin.form') }}" class="page-header__action">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Walk-in
        </a>
    </div>
</header>

<div class="page-body">

    @if(session('success'))
        <div class="page-alert page-alert--success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="page-alert page-alert--error">{{ session('error') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stats-row__card">
            <div class="stats-row__num">{{ $jumlahSemua ?? 0 }}</div>
            <div class="stats-row__label">Total</div>
        </div>
        <div class="stats-row__card">
            <div class="stats-row__num stats-row__num--accent">{{ $jumlahAktif ?? 0 }}</div>
            <div class="stats-row__label">Aktif</div>
        </div>
        <div class="stats-row__card">
            <div class="stats-row__num stats-row__num--success">{{ $jumlahSelesai ?? 0 }}</div>
            <div class="stats-row__label">Selesai</div>
        </div>
    </div>

    {{-- Filter --}}
    @php $currentStatus = request('status', ''); @endphp
    <div class="filter-bar">
        <a href="{{ route('admin.orders') }}" class="filter-bar__chip {{ !$currentStatus ? 'filter-bar__chip--active' : '' }}">Semua</a>
        <a href="{{ route('admin.orders', ['status' => 'menunggu']) }}" class="filter-bar__chip {{ $currentStatus === 'menunggu' ? 'filter-bar__chip--active' : '' }}">Menunggu</a>
        <a href="{{ route('admin.orders', ['status' => 'dijemput']) }}" class="filter-bar__chip {{ $currentStatus === 'dijemput' ? 'filter-bar__chip--active' : '' }}">Dijemput</a>
        <a href="{{ route('admin.orders', ['status' => 'dicuci']) }}" class="filter-bar__chip {{ $currentStatus === 'dicuci' ? 'filter-bar__chip--active' : '' }}">Dicuci</a>
        <a href="{{ route('admin.orders', ['status' => 'disetrika']) }}" class="filter-bar__chip {{ $currentStatus === 'disetrika' ? 'filter-bar__chip--active' : '' }}">Setrika</a>
        <a href="{{ route('admin.orders', ['status' => 'siap']) }}" class="filter-bar__chip {{ $currentStatus === 'siap' ? 'filter-bar__chip--active' : '' }}">Siap</a>
        <a href="{{ route('admin.orders', ['status' => 'dikirim']) }}" class="filter-bar__chip {{ $currentStatus === 'dikirim' ? 'filter-bar__chip--active' : '' }}">Dikirim</a>
        <a href="{{ route('admin.orders', ['status' => 'selesai']) }}" class="filter-bar__chip {{ $currentStatus === 'selesai' ? 'filter-bar__chip--active' : '' }}">Selesai</a>
    </div>

    {{-- Order Cards --}}
    @forelse($pesanan as $o)
    @php
        $badgeClass = match(true) {
            $o->status === 'selesai' => 'order-card__badge--selesai',
            $o->status === 'dibatalkan' => 'order-card__badge--batal',
            $o->status === 'menunggu' => 'order-card__badge--menunggu',
            default => 'order-card__badge--proses',
        };
    @endphp
    <div class="order-card">
        <div class="order-card__head">
            <span class="order-card__code">#{{ strtoupper($o->order_code) }}</span>
            <span class="order-card__badge {{ $badgeClass }}">{{ $o->status_label }}</span>
        </div>
        <div class="order-card__body">
            <div class="order-card__customer">{{ $o->customer->name ?? '-' }}</div>
            <div class="order-card__phone">{{ $o->customer->phone ?? '' }}</div>
            <div class="order-card__meta">
                <span class="order-card__chip">{{ $o->service->name ?? '-' }}</span>
                <span class="order-card__chip">{{ $o->weight_estimate }} kg</span>
                @if($o->pickup_date)
                <span class="order-card__chip">{{ $o->pickup_date->format('d/m') }}</span>
                @endif
            </div>
            <div class="order-card__total">Rp {{ number_format($o->total_cost, 0, ',', '.') }}</div>
        </div>

        {{-- Panel: Tugaskan Driver Pickup --}}
        @if($o->status === 'menunggu')
        <div class="order-card__panel">
            <div class="order-card__panel-label">Tugaskan Kurir Penjemputan</div>
            <form method="POST" action="{{ route('admin.orders.assign-driver', $o) }}" class="order-card__panel-form">
                @csrf
                <input type="hidden" name="assignment_type" value="pickup">
                <select name="driver_id" required class="order-card__select">
                    <option value="">Pilih kurir</option>
                    @foreach($daftarDriver ?? [] as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="order-card__panel-btn order-card__panel-btn--primary">Tugaskan</button>
            </form>
        </div>
        @endif

        {{-- Panel: Update Status Workshop --}}
        @if(in_array($o->status, ['dicuci', 'disetrika']))
        <div class="order-card__panel">
            @php $nextStatus = $o->status === 'dicuci' ? 'disetrika' : 'siap'; @endphp
            <div class="order-card__panel-label">Update ke: {{ $nextStatus === 'disetrika' ? 'Setrika' : 'Siap Kirim' }}</div>
            <form method="POST" action="{{ route('admin.orders.update-status', $o) }}" class="order-card__panel-form">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $nextStatus }}">
                <button type="submit" class="order-card__panel-btn order-card__panel-btn--accent" style="width:100%;">
                    Update Status
                </button>
            </form>
        </div>
        @endif

        {{-- Panel: Tugaskan Driver Antar --}}
        @if($o->status === 'siap')
        <div class="order-card__panel">
            <div class="order-card__panel-label">Tugaskan Kurir Pengantaran</div>
            <form method="POST" action="{{ route('admin.orders.assign-driver', $o) }}" class="order-card__panel-form">
                @csrf
                <input type="hidden" name="assignment_type" value="delivery">
                <select name="driver_id" required class="order-card__select">
                    <option value="">Pilih kurir</option>
                    @foreach($daftarDriver ?? [] as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="order-card__panel-btn order-card__panel-btn--success">Kirim</button>
            </form>
        </div>
        @endif

        <div class="order-card__footer">
            <a href="{{ route('admin.orders.receipt', $o) }}" target="_blank" class="order-card__detail-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                Lihat Detail / Struk
            </a>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <div class="empty-state__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
        </div>
        <div class="empty-state__title">Belum Ada Pesanan</div>
        <div class="empty-state__desc">Pesanan baru akan muncul di sini.</div>
    </div>
    @endforelse

    @if($pesanan->hasPages())
    <div class="pagination-wrap">
        {{ $pesanan->links() }}
    </div>
    @endif

</div>

@include('layouts.component.admin._navbar_admin', ['active' => 'pesanan'])

</body>
</html>
