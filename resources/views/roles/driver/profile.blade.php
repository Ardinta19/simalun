<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Kurir – Azka Laundry</title>
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
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-secondary: #475569;
    --ink-muted: #94a3b8;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --danger: #dc2626;
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
.profile-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: max(env(safe-area-inset-top, 0px), 16px) 20px 12px;
    max-width: 520px;
    margin: 0 auto;
}
.profile-header__left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.profile-header__avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border);
}
.profile-header__title {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--primary);
}
.profile-header__notif {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: var(--card);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none;
    position: relative;
    box-shadow: var(--shadow-sm);
}
.profile-header__notif svg { width: 18px; height: 18px; color: var(--ink-secondary); }
.profile-header__badge {
    position: absolute;
    top: -2px; right: -2px;
    min-width: 16px; height: 16px;
    background: var(--accent);
    color: #fff;
    border-radius: 99px;
    font-size: .55rem;
    font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    padding: 0 3px;
    border: 2px solid var(--surface);
}

/* Body */
.page-body { max-width: 520px; margin: 0 auto; padding: 6px 16px 16px; }

/* Profile Card */
.profile-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 24px 20px;
    text-align: center;
    margin-bottom: 14px;
    box-shadow: var(--shadow-md);
}
.profile-card__avatar-wrap {
    width: 80px; height: 80px;
    border-radius: 50%;
    border: 3px solid var(--primary);
    margin: 0 auto 12px;
    display: flex; align-items: center; justify-content: center;
    position: relative;
    overflow: visible;
}
.profile-card__avatar {
    width: 100%; height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.profile-card__status {
    position: absolute;
    bottom: -2px; right: -4px;
    background: var(--success);
    color: #fff;
    font-size: .52rem;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 99px;
    border: 2px solid #fff;
    text-transform: uppercase;
    letter-spacing: .3px;
}
.profile-card__name {
    font-weight: 800;
    font-size: 1.2rem;
    color: var(--ink);
    margin-bottom: 2px;
}
.profile-card__id {
    font-size: .76rem;
    font-weight: 600;
    color: var(--ink-muted);
}

/* Stats */
.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}
.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 14px;
    text-align: center;
    box-shadow: var(--shadow-sm);
}
.stat-card__icon {
    width: 32px; height: 32px;
    border-radius: 8px;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 8px;
}
.stat-card__icon svg { width: 16px; height: 16px; color: var(--primary); }
.stat-card__icon--star { background: #fffbeb; }
.stat-card__icon--star svg { color: var(--warning); }
.stat-card__num {
    font-weight: 800;
    font-size: 1.35rem;
    color: var(--primary);
    line-height: 1;
}
.stat-card__label {
    font-size: .64rem;
    font-weight: 700;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-top: 4px;
}
.stat-card__sub {
    font-size: .68rem;
    font-weight: 700;
    color: var(--ink-secondary);
    margin-top: 2px;
}

/* Menu */
.menu-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 14px;
    box-shadow: var(--shadow-sm);
}
.menu-card__item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-light);
    text-decoration: none;
    color: var(--ink);
    transition: background .12s;
}
.menu-card__item:last-child { border-bottom: none; }
.menu-card__item:active { background: var(--border-light); }
.menu-card__icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.menu-card__icon svg { width: 18px; height: 18px; }
.menu-card__icon--blue { background: var(--primary-light); color: var(--primary); }
.menu-card__icon--green { background: var(--success-light); color: var(--success); }
.menu-card__icon--amber { background: #fffbeb; color: var(--warning); }
.menu-card__body { flex: 1; }
.menu-card__label { font-weight: 700; font-size: .85rem; color: var(--ink); }
.menu-card__sub { font-size: .7rem; font-weight: 500; color: var(--ink-muted); margin-top: 1px; }
.menu-card__arrow { color: var(--ink-muted); }
.menu-card__arrow svg { width: 16px; height: 16px; }

/* Performance Chart */
.perf-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 14px;
    box-shadow: var(--shadow-sm);
}
.perf-card__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
.perf-card__title { font-weight: 700; font-size: .88rem; color: var(--ink); }
.perf-card__badge {
    font-size: .64rem;
    font-weight: 800;
    padding: 3px 9px;
    border-radius: 99px;
    background: var(--success-light);
    color: var(--success);
}
.perf-card__chart {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    height: 80px;
    padding-top: 4px;
}
.perf-card__bar {
    flex: 1;
    border-radius: 4px 4px 0 0;
    background: var(--primary-light);
    position: relative;
    min-height: 8px;
    transition: height .3s;
}
.perf-card__bar--today { background: var(--primary); }
.perf-card__labels {
    display: flex;
    gap: 8px;
    margin-top: 6px;
}
.perf-card__day {
    flex: 1;
    text-align: center;
    font-size: .58rem;
    font-weight: 700;
    color: var(--ink-muted);
    text-transform: uppercase;
}

/* Logout */
.btn-logout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px;
    border-radius: 99px;
    border: 1.5px solid rgba(220,38,38,.25);
    background: var(--card);
    color: var(--danger);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .85rem;
    cursor: pointer;
    transition: background .15s;
}
.btn-logout:active { background: #fef2f2; }
.btn-logout svg { width: 16px; height: 16px; }
</style>
</head>
<body>

@php
    $driver = auth()->user();
    $totalAntar = \App\Models\Order::where('driver_id', $driver->id)->where('status','selesai')->count();
    $antarBulanIni = \App\Models\Order::where('driver_id', $driver->id)->where('status','selesai')->whereMonth('updated_at', now()->month)->count();
    $unreadNotif = $driver->unreadNotifications->count();
@endphp

<header class="profile-header">
    <div class="profile-header__left">
        <img src="{{ $driver->avatar ? asset('storage/'.$driver->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=0d6fb8&color=fff&size=72' }}"
             alt="{{ $driver->name }}" class="profile-header__avatar">
        <span class="profile-header__title">Profil Kurir</span>
    </div>
    <a href="{{ route('driver.notifications') }}" class="profile-header__notif">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        @if($unreadNotif > 0)
            <span class="profile-header__badge">{{ $unreadNotif }}</span>
        @endif
    </a>
</header>

<div class="page-body">

    {{-- Profile Card --}}
    <div class="profile-card">
        <div class="profile-card__avatar-wrap">
            <img src="{{ $driver->avatar ? asset('storage/'.$driver->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($driver->name).'&background=0d6fb8&color=fff&size=176' }}"
                 alt="{{ $driver->name }}" class="profile-card__avatar">
            <span class="profile-card__status">Aktif</span>
        </div>
        <div class="profile-card__name">{{ $driver->name }}</div>
        <div class="profile-card__id">ID Kurir: LK-{{ str_pad($driver->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            </div>
            <div class="stat-card__num">{{ number_format($totalAntar) }}</div>
            <div class="stat-card__label">Total Antaran</div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--star">
                <svg viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div class="stat-card__num" style="color: var(--warning);">4.9</div>
            <div class="stat-card__label">Rating</div>
            <div class="stat-card__sub">Unggul</div>
        </div>
    </div>

    {{-- Menu --}}
    <div class="menu-card">
        <a href="{{ route('driver.orders') }}" class="menu-card__item">
            <div class="menu-card__icon menu-card__icon--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Riwayat Tugas</div>
                <div class="menu-card__sub">Lihat semua tugas jemput dan antar</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <a href="{{ route('driver.report') }}" class="menu-card__item">
            <div class="menu-card__icon menu-card__icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Lapor Kendala</div>
                <div class="menu-card__sub">Laporkan masalah atau kendala di lapangan</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <a href="{{ route('driver.help') }}" class="menu-card__item">
            <div class="menu-card__icon menu-card__icon--amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Bantuan</div>
                <div class="menu-card__sub">Pusat bantuan & panduan kurir</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
    </div>

    {{-- Performance Chart --}}
    <div class="perf-card">
        <div class="perf-card__header">
            <span class="perf-card__title">Performa Minggu Ini</span>
            @if($antarBulanIni > 0)
                <span class="perf-card__badge">+{{ $antarBulanIni }} bulan ini</span>
            @endif
        </div>
        @php
            $days = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
            $weekData = [];
            for($i=6; $i>=0; $i--) {
                $date = now()->subDays($i);
                $count = \App\Models\Order::where('driver_id', $driver->id)
                    ->where('status','selesai')
                    ->whereDate('updated_at', $date)
                    ->count();
                $weekData[] = $count;
            }
            $maxVal = max(max($weekData), 1);
        @endphp
        <div class="perf-card__chart">
            @foreach($weekData as $idx => $val)
                <div class="perf-card__bar {{ $idx === 6 ? 'perf-card__bar--today' : '' }}" style="height:{{ max(($val / $maxVal) * 100, 8) }}%;"></div>
            @endforeach
        </div>
        <div class="perf-card__labels">
            @foreach($days as $d)
                <span class="perf-card__day">{{ $d }}</span>
            @endforeach
        </div>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar Akun
        </button>
    </form>

</div>

@include('layouts.component.driver._navbar_driver', ['active' => 'profil'])

</body>
</html>
