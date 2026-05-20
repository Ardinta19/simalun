{{--
    Partial: _navbar_admin.blade.php
    Usage: @include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])
    Active options: beranda | pesanan | profil
--}}
@php $navActive = $active ?? 'beranda'; @endphp

<nav class="admin-nav" id="admin-nav">
    <div class="admin-nav__inner">
        <a href="{{ route('dashboard.admin') }}"
           class="admin-nav__item {{ $navActive === 'beranda' ? 'is-active' : '' }}"
           aria-label="Beranda">
            <span class="admin-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <span class="admin-nav__label">Beranda</span>
        </a>

        <a href="{{ route('admin.orders') }}"
           class="admin-nav__item {{ $navActive === 'pesanan' ? 'is-active' : '' }}"
           aria-label="Pesanan">
            <span class="admin-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                </svg>
            </span>
            <span class="admin-nav__label">Pesanan</span>
        </a>

        <a href="{{ route('admin.profile') }}"
           class="admin-nav__item {{ $navActive === 'profil' ? 'is-active' : '' }}"
           aria-label="Profil">
            <span class="admin-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <span class="admin-nav__label">Profil</span>
        </a>
    </div>
</nav>

<style>
.admin-nav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    z-index: 999;
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid #e8f0fe;
    box-shadow: 0 -4px 24px rgba(0, 47, 92, 0.08);
    padding-bottom: env(safe-area-inset-bottom, 0px);
}
.admin-nav__inner {
    max-width: 520px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    height: 64px;
    padding: 0 16px;
}
.admin-nav__item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    text-decoration: none;
    color: #94a3b8;
    position: relative;
    padding: 6px 0;
    transition: color 0.2s ease;
    -webkit-tap-highlight-color: transparent;
}
.admin-nav__item.is-active { color: #0d6fb8; }
.admin-nav__item.is-active .admin-nav__icon svg { stroke-width: 2.6; }
.admin-nav__icon {
    display: flex;
    align-items: center;
    justify-content: center;
}
.admin-nav__icon svg { width: 22px; height: 22px; }
.admin-nav__label {
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
}
.admin-nav__item.is-active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    width: 4px; height: 4px;
    background: #0d6fb8;
    border-radius: 50%;
}
</style>
