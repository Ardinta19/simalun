<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Laporan Kendala – Admin – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --blue-dark: #002f5c;
    --blue: #0077b6;
    --blue-lt: #e8f4fd;
    --green: #059669;
    --green-lt: #ecfdf5;
    --orange: #f59e0b;
    --orange-lt: #fef3c7;
    --red: #dc2626;
    --red-lt: #fef2f2;
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

.page-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px; padding-top: max(env(safe-area-inset-top, 0px), 8px);
}
.page-title { font-size: 1.1rem; font-weight: 800; color: var(--blue-dark); }

/* STATS */
.stats-row { display: flex; gap: 8px; margin-bottom: 16px; }
.stat-pill {
    flex: 1; padding: 10px 12px; border-radius: var(--radius-sm);
    background: var(--card); border: 1px solid var(--border);
    text-align: center;
}
.stat-pill__value { font-size: 1.2rem; font-weight: 800; }
.stat-pill__label { font-size: 0.6rem; font-weight: 700; color: var(--ink-lt); text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }

/* FILTER */
.filter-row {
    display: flex; gap: 6px; margin-bottom: 14px; overflow-x: auto;
    scrollbar-width: none; padding-bottom: 4px;
}
.filter-row::-webkit-scrollbar { display: none; }
.filter-chip {
    padding: 7px 14px; border-radius: 99px; white-space: nowrap;
    font-size: 0.7rem; font-weight: 700; text-decoration: none;
    border: 1px solid var(--border); background: var(--card); color: var(--ink-mid);
    transition: all 0.15s;
}
.filter-chip.active { background: var(--blue); color: white; border-color: var(--blue); }

/* REPORT CARD */
.report-card {
    background: var(--card); border-radius: var(--radius);
    border: 1px solid var(--border); margin-bottom: 10px;
    overflow: hidden;
}
.report-card__header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 14px; border-bottom: 1px solid #f8fafc;
}
.report-card__user {
    display: flex; align-items: center; gap: 10px;
}
.report-card__avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--blue-lt); display: flex; align-items: center;
    justify-content: center; font-size: 0.75rem; font-weight: 800;
    color: var(--blue); flex-shrink: 0;
}
.report-card__name { font-size: 0.78rem; font-weight: 700; color: var(--ink); }
.report-card__role { font-size: 0.6rem; font-weight: 600; color: var(--ink-lt); }
.report-card__badge {
    font-size: 0.58rem; font-weight: 800; padding: 3px 8px;
    border-radius: 99px; text-transform: uppercase;
}
.report-card__body { padding: 12px 14px; }
.report-card__category {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 0.65rem; font-weight: 700; color: var(--ink-mid);
    margin-bottom: 6px;
}
.report-card__desc {
    font-size: 0.78rem; font-weight: 600; color: var(--ink);
    line-height: 1.5; margin-bottom: 8px;
}
.report-card__img {
    width: 100%; max-height: 180px; object-fit: cover;
    border-radius: 8px; border: 1px solid var(--border); margin-bottom: 8px;
}
.report-card__time { font-size: 0.62rem; font-weight: 600; color: var(--ink-lt); }
.report-card__footer {
    padding: 10px 14px; border-top: 1px solid #f8fafc;
    display: flex; align-items: center; gap: 8px;
}
.status-select {
    flex: 1; padding: 8px 10px; border-radius: 8px;
    border: 1px solid var(--border); font-family: var(--font);
    font-size: 0.72rem; font-weight: 600; color: var(--ink); outline: none;
}
.status-btn {
    padding: 8px 14px; border-radius: 8px; background: var(--blue);
    color: white; border: none; font-family: var(--font);
    font-size: 0.7rem; font-weight: 700; cursor: pointer;
}

.empty-state {
    text-align: center; padding: 40px 20px;
    color: var(--ink-lt); font-size: 0.82rem; font-weight: 700;
}

/* TOAST */
.toast {
    position: fixed; top: 16px; left: 50%; transform: translateX(-50%);
    background: var(--green); color: white; padding: 10px 20px;
    border-radius: 10px; font-size: 0.78rem; font-weight: 700;
    z-index: 9999; box-shadow: 0 4px 20px rgba(5,150,105,0.3);
    animation: toastIn 0.3s ease, toastOut 0.3s ease 2.5s forwards;
}
@keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(-20px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
@keyframes toastOut { to { opacity:0; transform:translateX(-50%) translateY(-20px); } }
</style>
</head>
<body>

@if(session('success'))
<div class="toast">{{ session('success') }}</div>
@endif

<main class="wrap">

    <header class="page-header">
        <h1 class="page-title">Laporan Kendala</h1>
    </header>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-pill">
            <div class="stat-pill__value" style="color: var(--orange);">{{ $countOpen }}</div>
            <div class="stat-pill__label">Menunggu</div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill__value" style="color: var(--blue);">{{ $countInProgress }}</div>
            <div class="stat-pill__label">Ditangani</div>
        </div>
        <div class="stat-pill">
            <div class="stat-pill__value" style="color: var(--green);">{{ $countResolved }}</div>
            <div class="stat-pill__label">Selesai</div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="filter-row">
        <a href="{{ route('admin.reports') }}" class="filter-chip {{ !$status && !$category ? 'active' : '' }}">Semua</a>
        <a href="{{ route('admin.reports', ['status' => 'open']) }}" class="filter-chip {{ $status === 'open' ? 'active' : '' }}">Menunggu</a>
        <a href="{{ route('admin.reports', ['status' => 'in_progress']) }}" class="filter-chip {{ $status === 'in_progress' ? 'active' : '' }}">Ditangani</a>
        <a href="{{ route('admin.reports', ['category' => 'bug']) }}" class="filter-chip {{ $category === 'bug' ? 'active' : '' }}">Bug</a>
        <a href="{{ route('admin.reports', ['category' => 'komplain']) }}" class="filter-chip {{ $category === 'komplain' ? 'active' : '' }}">Komplain</a>
        <a href="{{ route('admin.reports', ['category' => 'saran']) }}" class="filter-chip {{ $category === 'saran' ? 'active' : '' }}">Saran</a>
    </div>

    {{-- Reports List --}}
    @forelse($reports as $report)
    <div class="report-card">
        <div class="report-card__header">
            <div class="report-card__user">
                <div class="report-card__avatar">{{ strtoupper(substr($report->user->name ?? 'U', 0, 2)) }}</div>
                <div>
                    <div class="report-card__name">{{ $report->user->name ?? '-' }}</div>
                    <div class="report-card__role">{{ ucfirst($report->user->role ?? 'user') }}</div>
                </div>
            </div>
            <span class="report-card__badge" style="background: {{ $report->status_color }}20; color: {{ $report->status_color }};">
                {{ $report->status_label }}
            </span>
        </div>
        <div class="report-card__body">
            <div class="report-card__category">
                @if($report->category === 'bug') 🐛
                @elseif($report->category === 'saran') 💡
                @else 😤
                @endif
                {{ $report->category_label }}
            </div>
            <div class="report-card__desc">{{ $report->description }}</div>
            @if($report->screenshot_path)
                <img src="{{ asset('storage/' . $report->screenshot_path) }}" class="report-card__img" alt="Screenshot">
            @endif
            <div class="report-card__time">{{ $report->created_at->translatedFormat('d M Y, H:i') }} — {{ $report->created_at->diffForHumans() }}</div>
        </div>
        <div class="report-card__footer">
            <form method="POST" action="{{ route('admin.reports.update-status', $report) }}" style="display:flex; gap:8px; width:100%;">
                @csrf
                @method('PATCH')
                <select name="status" class="status-select">
                    <option value="open" {{ $report->status === 'open' ? 'selected' : '' }}>Menunggu</option>
                    <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>Ditangani</option>
                    <option value="resolved" {{ $report->status === 'resolved' ? 'selected' : '' }}>Selesai</option>
                    <option value="closed" {{ $report->status === 'closed' ? 'selected' : '' }}>Ditutup</option>
                </select>
                <button type="submit" class="status-btn">Update</button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state">Belum ada laporan kendala masuk.</div>
    @endforelse

    @if($reports->hasPages())
    <div style="display:flex; justify-content:center; margin-top:12px;">
        {{ $reports->links('pagination::simple-tailwind') }}
    </div>
    @endif

</main>

@include('layouts.component.admin._navbar_admin', ['active' => ''])

</body>
</html>
