{{--
    Partial: _navbar_driver.blade.php
    Usage: @include('layouts.component.driver._navbar_driver', ['active' => 'beranda'])
    Active options: beranda | tugas | profil
--}}
@php $navActive = $active ?? 'beranda'; @endphp

<nav class="driver-nav" id="driver-nav">
    <div class="driver-nav__inner">
        <a href="{{ route('driver.dashboard') }}"
           class="driver-nav__item {{ $navActive === 'beranda' ? 'is-active' : '' }}"
           aria-label="Beranda">
            <span class="driver-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <span class="driver-nav__label">Beranda</span>
        </a>

        <a href="{{ route('driver.orders') }}"
           class="driver-nav__item {{ $navActive === 'tugas' ? 'is-active' : '' }}"
           aria-label="Tugas">
            <span class="driver-nav__icon" style="position:relative">
                @php
                    $driverTaskCount = 0;
                    try { $driverTaskCount = auth()->user()?->driverOrders()->whereIn('status', ['dijemput','dikirim'])->count() ?? 0; } catch (\Exception $e) {}
                @endphp
                @if($driverTaskCount > 0)
                    <span class="driver-nav__badge">{{ $driverTaskCount > 9 ? '9+' : $driverTaskCount }}</span>
                @endif
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
            </span>
            <span class="driver-nav__label">Tugas</span>
        </a>

        <a href="{{ route('driver.profile') }}"
           class="driver-nav__item {{ $navActive === 'profil' ? 'is-active' : '' }}"
           aria-label="Profil">
            <span class="driver-nav__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <span class="driver-nav__label">Profil</span>
        </a>
    </div>
</nav>

<style>
.driver-nav {
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
.driver-nav__inner {
    max-width: 520px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    height: 64px;
    padding: 0 16px;
}
.driver-nav__item {
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
.driver-nav__item.is-active { color: #0d6fb8; }
.driver-nav__item.is-active .driver-nav__icon svg { stroke-width: 2.6; }
.driver-nav__icon {
    display: flex;
    align-items: center;
    justify-content: center;
}
.driver-nav__icon svg { width: 22px; height: 22px; }
.driver-nav__label {
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-family: 'Plus Jakarta Sans', 'Nunito', sans-serif;
}
.driver-nav__badge {
    position: absolute;
    top: -6px; right: -8px;
    background: #FF6B35;
    color: white;
    font-size: 0.55rem;
    font-weight: 900;
    min-width: 16px; height: 16px;
    border-radius: 99px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid white;
}
.driver-nav__item.is-active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    width: 4px; height: 4px;
    background: #0d6fb8;
    border-radius: 50%;
}
</style>
