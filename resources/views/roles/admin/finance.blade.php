<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Laporan Keuangan – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --blue-dark: #002f5c;
    --blue: #0077b6;
    --blue-lt: #e8f4fd;
    --green: #059669;
    --green-lt: #ecfdf5;
    --red: #dc2626;
    --red-lt: #fef2f2;
    --orange: #f59e0b;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-mid: #4a5568;
    --ink-lt: #8896a6;
    --border: #e2e8f0;
    --radius: 16px;
    --radius-sm: 10px;
    --font: 'Plus Jakarta Sans', -apple-system, sans-serif;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
body {
    font-family: var(--font);
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}
.wrap { max-width: 540px; margin: 0 auto; padding: 16px; }

/* PAGE HEADER */
.page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; padding-top: max(env(safe-area-inset-top, 0px), 8px);
}
.page-header__left { display: flex; align-items: center; gap: 10px; }
.page-header__back {
    width: 34px; height: 34px; border-radius: 50%;
    background: var(--blue-lt); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    color: var(--blue-dark); text-decoration: none; flex-shrink: 0;
    transition: background .15s;
}
.page-header__back:active { background: #cce7f7; }
.page-header__back svg { width: 16px; height: 16px; }
.page-title { font-size: 1.2rem; font-weight: 800; color: var(--blue-dark); }
.header-actions { display: flex; gap: 8px; }
.header-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 8px 12px; border-radius: 8px;
    font-family: var(--font); font-size: 0.72rem; font-weight: 700;
    text-decoration: none; border: 1px solid var(--border);
    background: var(--card); color: var(--ink); cursor: pointer;
    transition: all 0.15s;
}
.header-btn:hover { background: var(--surface); }
.header-btn--green { background: var(--green); color: white; border-color: var(--green); }
.header-btn--blue { background: var(--blue); color: white; border-color: var(--blue); }
.header-btn svg { width: 14px; height: 14px; }

/* SUMMARY CARDS */
.summary-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px; margin-bottom: 16px; }
.summary-card {
    background: var(--card); border-radius: var(--radius-sm);
    border: 1px solid var(--border); padding: 14px 12px;
    text-align: center;
}
.summary-card__label { font-size: 0.62rem; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; letter-spacing: 0.5px; }
.summary-card__value { font-size: 1.1rem; font-weight: 800; margin-top: 4px; }
.summary-card__value.income { color: var(--green); }
.summary-card__value.expense { color: var(--red); }
.summary-card__value.balance { color: var(--blue); }

/* CHART MINI */
.chart-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 16px;
    margin-bottom: 16px;
}
.chart-card__title { font-size: 0.82rem; font-weight: 800; color: var(--ink); margin-bottom: 12px; }
.chart-bars { display: flex; align-items: flex-end; gap: 6px; height: 80px; }
.chart-bar {
    flex: 1; display: flex; flex-direction: column; align-items: center; gap: 4px;
}
.chart-bar__fill {
    width: 100%; border-radius: 4px 4px 0 0;
    background: linear-gradient(180deg, var(--blue) 0%, #4db8e8 100%);
    min-height: 4px; transition: height 0.3s ease;
}
.chart-bar__label { font-size: 0.58rem; font-weight: 700; color: var(--ink-lt); }

/* FILTER SECTION */
.filter-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 14px;
    margin-bottom: 16px;
}
.filter-card__title { font-size: 0.75rem; font-weight: 800; color: var(--ink-mid); margin-bottom: 10px; }
.filter-row { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
.filter-tabs { display: flex; gap: 4px; background: var(--surface); border-radius: 8px; padding: 3px; }
.filter-tab {
    padding: 6px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 700;
    color: var(--ink-lt); cursor: pointer; border: none; background: transparent;
    font-family: var(--font); text-decoration: none; transition: all 0.15s;
}
.filter-tab:hover { color: var(--ink); }
.filter-tab.active { background: var(--card); color: var(--blue); box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.filter-select {
    flex: 1; min-width: 120px; padding: 8px 12px; border-radius: 8px;
    border: 1px solid var(--border); font-family: var(--font);
    font-size: 0.75rem; font-weight: 600; color: var(--ink);
    background: var(--card); outline: none;
}
.filter-input {
    flex: 1; min-width: 100px; padding: 8px 12px; border-radius: 8px;
    border: 1px solid var(--border); font-family: var(--font);
    font-size: 0.75rem; font-weight: 600; color: var(--ink); outline: none;
}
.filter-btn {
    padding: 8px 16px; border-radius: 8px; background: var(--blue);
    color: white; font-family: var(--font); font-size: 0.72rem;
    font-weight: 700; border: none; cursor: pointer;
}

/* TRANSACTION TABLE */
.tx-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); overflow: hidden;
    margin-bottom: 16px;
}
.tx-card__header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; border-bottom: 1px solid var(--border);
}
.tx-card__title { font-size: 0.85rem; font-weight: 800; color: var(--ink); }
.tx-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; border-bottom: 1px solid #f8fafc;
    transition: background 0.1s;
}
.tx-item:last-child { border-bottom: none; }
.tx-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.85rem; flex-shrink: 0;
}
.tx-icon.income { background: var(--green-lt); }
.tx-icon.expense { background: var(--red-lt); }
.tx-body { flex: 1; min-width: 0; }
.tx-desc { font-size: 0.78rem; font-weight: 700; color: var(--ink); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.tx-meta { font-size: 0.65rem; font-weight: 600; color: var(--ink-lt); margin-top: 2px; }
.tx-amount { font-size: 0.82rem; font-weight: 800; text-align: right; flex-shrink: 0; }
.tx-amount.income { color: var(--green); }
.tx-amount.expense { color: var(--red); }

.tx-empty { padding: 40px 16px; text-align: center; color: var(--ink-lt); font-size: 0.82rem; font-weight: 700; }

/* EXPENSE FORM */
.expense-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); padding: 16px;
    margin-bottom: 16px;
}
.expense-card__title { font-size: 0.82rem; font-weight: 800; color: var(--ink); margin-bottom: 12px; }
.expense-form { display: flex; flex-wrap: wrap; gap: 8px; }
.expense-input {
    flex: 1; min-width: 120px; padding: 10px 14px; border-radius: var(--radius-sm);
    border: 1px solid var(--border); font-family: var(--font);
    font-size: 0.8rem; font-weight: 600; color: var(--ink); outline: none;
}
.expense-input:focus { border-color: var(--blue); }
.expense-btn {
    padding: 10px 20px; border-radius: var(--radius-sm);
    background: var(--blue-dark); color: white; font-family: var(--font);
    font-size: 0.78rem; font-weight: 700; border: none; cursor: pointer;
}

/* PAGINATION */
.pagination-wrap { display: flex; justify-content: center; margin-bottom: 16px; }
.pagination-wrap nav { font-size: 0.75rem; }

/* SUCCESS MSG */
.toast {
    position: fixed; top: 16px; left: 50%; transform: translateX(-50%);
    background: var(--green); color: white; padding: 10px 20px;
    border-radius: 10px; font-size: 0.78rem; font-weight: 700;
    z-index: 9999; box-shadow: 0 4px 20px rgba(5,150,105,0.3);
    animation: toastIn 0.3s ease, toastOut 0.3s ease 2.5s forwards;
}
.toast--error { background: var(--red); box-shadow: 0 4px 20px rgba(220,38,38,0.3); }
@keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(-20px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
@keyframes toastOut { to { opacity:0; transform:translateX(-50%) translateY(-20px); } }
</style>
</head>
<body>

@if(session('success'))
<div class="toast">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="toast toast--error">{{ session('error') }}</div>
@endif

<main class="wrap">

    {{-- Page Header --}}
    <header class="page-header">
        <div class="page-header__left">
            <a href="{{ route('admin.dashboard') }}" class="page-header__back" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <h1 class="page-title">Laporan Keuangan</h1>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.finance.export', request()->query()) }}" class="header-btn header-btn--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Excel
            </a>
            <a href="{{ route('admin.finance.export-pdf', request()->query()) }}" class="header-btn header-btn--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                PDF
            </a>
        </div>
    </header>

    {{-- Summary --}}
    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card__label">Pemasukan</div>
            <div class="summary-card__value income">{{ number_format($masuk / 1000, 0, ',', '.') }}k</div>
        </div>
        <div class="summary-card">
            <div class="summary-card__label">Pengeluaran</div>
            <div class="summary-card__value expense">{{ number_format($keluar / 1000, 0, ',', '.') }}k</div>
        </div>
        <div class="summary-card">
            <div class="summary-card__label">Saldo</div>
            <div class="summary-card__value balance">{{ number_format($saldo / 1000, 0, ',', '.') }}k</div>
        </div>
    </div>

    {{-- Revenue Chart --}}
    @if(isset($chartData) && $chartData->sum('total') > 0)
    <div class="chart-card">
        <div class="chart-card__title">Pemasukan 7 Hari Terakhir</div>
        @php $maxChart = $chartData->max('total') ?: 1; @endphp
        <div class="chart-bars">
            @foreach($chartData as $bar)
            <div class="chart-bar">
                <div class="chart-bar__fill" style="height: {{ max(4, ($bar['total'] / $maxChart) * 70) }}px;"></div>
                <span class="chart-bar__label">{{ $bar['date'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <div class="filter-card">
        <div class="filter-card__title">Filter Laporan</div>
        <form method="GET" action="{{ route('admin.finance.index') }}">
            <div class="filter-row">
                <div class="filter-tabs">
                    <a href="{{ route('admin.finance.index', ['range' => 'harian']) }}" class="filter-tab {{ ($filters['range'] ?? '') === 'harian' ? 'active' : '' }}">Hari Ini</a>
                    <a href="{{ route('admin.finance.index', ['range' => 'mingguan']) }}" class="filter-tab {{ ($filters['range'] ?? '') === 'mingguan' ? 'active' : '' }}">Minggu</a>
                    <a href="{{ route('admin.finance.index', ['range' => 'bulanan']) }}" class="filter-tab {{ ($filters['range'] ?? 'bulanan') === 'bulanan' ? 'active' : '' }}">Bulan</a>
                </div>
            </div>
            <div class="filter-row">
                <input type="date" name="start_date" class="filter-input" value="{{ $filters['start_date'] ?? '' }}" placeholder="Dari">
                <input type="date" name="end_date" class="filter-input" value="{{ $filters['end_date'] ?? '' }}" placeholder="Sampai">
            </div>
            <div class="filter-row">
                <select name="driver_id" class="filter-select">
                    <option value="">Semua Kurir</option>
                    @foreach($daftarDriver as $d)
                        <option value="{{ $d->id }}" {{ ($filters['driver_id'] ?? '') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
                <select name="service_id" class="filter-select">
                    <option value="">Semua Layanan</option>
                    @foreach($daftarLayanan as $s)
                        <option value="{{ $s->id }}" {{ ($filters['service_id'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-row">
                <select name="type" class="filter-select">
                    <option value="">Semua Tipe</option>
                    <option value="income" {{ ($filters['type'] ?? '') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                    <option value="expense" {{ ($filters['type'] ?? '') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                </select>
                <button type="submit" class="filter-btn">Terapkan</button>
            </div>
        </form>
    </div>

    {{-- Transaction List --}}
    <div class="tx-card">
        <div class="tx-card__header">
            <span class="tx-card__title">Riwayat Transaksi</span>
            <span style="font-size:0.68rem; font-weight:700; color:var(--ink-lt);">{{ $transaksi->total() }} data</span>
        </div>

        @forelse($transaksi as $t)
        <div class="tx-item">
            <div class="tx-icon {{ $t->entry_type }}">
                {{ $t->entry_type === 'income' ? '↓' : '↑' }}
            </div>
            <div class="tx-body">
                <div class="tx-desc">{{ $t->notes ?? 'Transaksi' }}</div>
                <div class="tx-meta">
                    {{ $t->entry_date->format('d/m/Y') }}
                    @if($t->order)
                        &middot; {{ $t->order->order_code }}
                    @endif
                    @if($t->order?->customer)
                        &middot; {{ Str::limit($t->order->customer->name, 15) }}
                    @endif
                </div>
            </div>
            <div class="tx-amount {{ $t->entry_type }}">
                {{ $t->entry_type === 'income' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
            </div>
        </div>
        @empty
        <div class="tx-empty">Belum ada transaksi di periode ini.</div>
        @endforelse
    </div>

    @if($transaksi->hasPages())
    <div class="pagination-wrap">
        {{ $transaksi->links('pagination::simple-tailwind') }}
    </div>
    @endif

    {{-- Quick Expense Entry --}}
    <div class="expense-card">
        <div class="expense-card__title">Catat Pengeluaran</div>
        <form method="POST" action="{{ route('admin.finance.store') }}" class="expense-form">
            @csrf
            <input type="text" name="description" class="expense-input" placeholder="Keterangan (cth: Beli deterjen)" required>
            <input type="number" name="amount" class="expense-input" style="min-width:100px;" placeholder="Jumlah (Rp)" required min="1">
            <select name="category" class="expense-input" style="min-width:100px;">
                <option value="operational">Operasional</option>
                <option value="salary">Gaji</option>
                <option value="investment">Investasi</option>
                <option value="other">Lainnya</option>
            </select>
            <button type="submit" class="expense-btn">Simpan</button>
        </form>
    </div>

</main>

{{-- Admin Navbar --}}
@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])

</body>
</html>
