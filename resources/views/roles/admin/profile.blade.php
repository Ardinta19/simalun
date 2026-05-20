<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Admin – Azka Laundry</title>
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
.profile-header__back {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: rgba(13,111,184,.08);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    color: var(--ink);
    text-decoration: none;
    flex-shrink: 0;
    transition: background .15s;
}
.profile-header__back:active { background: rgba(13,111,184,.15); }
.profile-header__back svg { width: 16px; height: 16px; }
.profile-header__avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--border);
}
.profile-header__title {
    font-weight: 800;
    font-size: 1.05rem;
    color: var(--ink);
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

/* Alert */
.page-alert {
    padding: 10px 14px;
    border-radius: var(--radius-sm);
    font-size: .8rem;
    font-weight: 700;
    margin-bottom: 12px;
}
.page-alert--success { background: var(--success-light); color: var(--success); border: 1px solid rgba(5,150,105,.2); }

/* Profile Card */
.profile-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 14px;
    box-shadow: var(--shadow-md);
}
.profile-card__name {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--ink);
    line-height: 1.2;
}
.profile-card__role {
    font-size: .76rem;
    font-weight: 600;
    color: var(--ink-muted);
    margin-top: 3px;
}
.profile-card__status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    padding: 5px 12px;
    border-radius: 99px;
    background: var(--success-light);
    border: 1px solid rgba(5,150,105,.2);
    font-size: .68rem;
    font-weight: 700;
    color: var(--success);
}
.profile-card__status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--success);
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
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: var(--shadow-sm);
}
.stat-card__icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-card__icon svg { width: 18px; height: 18px; }
.stat-card__icon--blue { background: var(--primary-light); color: var(--primary); }
.stat-card__icon--amber { background: var(--warning-light); color: var(--warning); }
.stat-card__num {
    font-weight: 800;
    font-size: 1.25rem;
    color: var(--ink);
    line-height: 1;
}
.stat-card__label {
    font-size: .62rem;
    font-weight: 700;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-top: 2px;
}

/* Section Label */
.section-label {
    font-size: .68rem;
    font-weight: 800;
    color: var(--ink-muted);
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 18px 0 10px;
    padding-left: 2px;
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
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.menu-card__icon svg { width: 18px; height: 18px; color: var(--primary); }
.menu-card__body { flex: 1; }
.menu-card__label { font-weight: 700; font-size: .85rem; color: var(--ink); }
.menu-card__sub { font-size: .7rem; font-weight: 500; color: var(--ink-muted); margin-top: 1px; }
.menu-card__arrow { color: var(--ink-muted); }
.menu-card__arrow svg { width: 16px; height: 16px; }

/* Banner */
.ops-banner {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    border-radius: var(--radius);
    padding: 18px 20px;
    margin-bottom: 14px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-md);
}
.ops-banner::after {
    content: '';
    position: absolute;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
    top: -30px; right: -20px;
}
.ops-banner__title {
    font-weight: 800;
    font-size: 1rem;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 3px;
    position: relative;
    z-index: 1;
}
.ops-banner__sub {
    font-size: .74rem;
    font-weight: 500;
    color: rgba(255,255,255,.7);
    position: relative;
    z-index: 1;
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

.version-text {
    text-align: center;
    margin-top: 12px;
    font-size: .68rem;
    font-weight: 600;
    color: var(--ink-muted);
    letter-spacing: .3px;
}
</style>
</head>
<body>

@php
    $adminUnread = auth()->user()->unreadNotifications->count();
@endphp

<header class="profile-header">
    <div class="profile-header__left">
        <a href="{{ route('admin.dashboard') }}" class="profile-header__back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0d6fb8&color=fff&size=72' }}"
             alt="{{ $user->name }}" class="profile-header__avatar">
        <span class="profile-header__title">Profil Admin</span>
    </div>
    <a href="{{ route('admin.notifications') }}" class="profile-header__notif">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        @if($adminUnread > 0)
            <span class="profile-header__badge">{{ $adminUnread }}</span>
        @endif
    </a>
</header>

<div class="page-body">

    @if(session('status') === 'profile-updated')
        <div class="page-alert page-alert--success">Profil berhasil diperbarui.</div>
    @endif

    {{-- Profile Card --}}
    <div class="profile-card">
        <div class="profile-card__name">{{ $user->name }}</div>
        <div class="profile-card__role">Administrator Utama &middot; {{ config('laundry.name', 'Azka Laundry') }}</div>
        <div class="profile-card__status">
            <span class="profile-card__status-dot"></span>
            Sistem Aktif
        </div>
    </div>

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
                <div class="stat-card__num">{{ \App\Models\User::where('role','driver')->where('is_active',true)->count() }}</div>
                <div class="stat-card__label">Kurir Aktif</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div>
                <div class="stat-card__num">{{ \App\Models\Order::whereDate('created_at', today())->count() }}</div>
                <div class="stat-card__label">Pesanan Hari Ini</div>
            </div>
        </div>
    </div>

    {{-- Pengaturan Operasional --}}
    <div class="section-label">Pengaturan Operasional</div>
    <div class="menu-card">
        <a href="{{ route('admin.orders') }}" class="menu-card__item">
            <div class="menu-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Manajemen Pesanan</div>
                <div class="menu-card__sub">Kelola status, tugaskan kurir</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <a href="{{ route('admin.walkin.form') }}" class="menu-card__item">
            <div class="menu-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Order Walk-in</div>
                <div class="menu-card__sub">Buat pesanan pelanggan langsung</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <a href="{{ route('admin.finance.index') }}" class="menu-card__item">
            <div class="menu-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Laporan Keuangan</div>
                <div class="menu-card__sub">Rekap pemasukan dan pengeluaran</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
        <a href="{{ route('admin.reports') }}" class="menu-card__item">
            <div class="menu-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="menu-card__body">
                <div class="menu-card__label">Laporan Masalah</div>
                <div class="menu-card__sub">Kelola laporan bug & kendala</div>
            </div>
            <span class="menu-card__arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></span>
        </a>
    </div>

    {{-- Banner --}}
    <div class="ops-banner">
        <div class="ops-banner__title">Optimalkan Operasi</div>
        <div class="ops-banner__sub">Cek performa outlet dan kelola semua dari sini</div>
    </div>

    {{-- Logout --}}
    <form method="POST" action="{{ route('logout') }}" id="form-logout">
        @csrf
        <button type="button" class="btn-logout" id="btn-logout">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Keluar dari Akun
        </button>
    </form>
    <div class="version-text">{{ config('laundry.name', 'Azka Laundry') }} v{{ config('laundry.version', '2.4.0') }} &middot; Admin Panel</div>

</div>

@include('layouts.component.admin._navbar_admin', ['active' => 'profil'])
@include('layouts.component._confirm_modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
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
