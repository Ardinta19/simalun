{{--
    Partial: _navbar_customer.blade.php
    Usage: @include('layouts.component.customer._navbar_customer', ['active' => 'beranda'])
    Active options: beranda | pesanan | pesan | notif | profil
--}}

@php $navActive = $active ?? 'beranda'; @endphp

<nav class="customer-nav" id="customer-nav">
    <div class="customer-nav__inner">

        {{-- Beranda --}}
        <a href="{{ route('customer.dashboard') }}"
           class="customer-nav__item {{ $navActive === 'beranda' ? 'is-active' : '' }}"
           aria-label="Beranda">
            <span class="customer-nav__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <span class="customer-nav__label">Beranda</span>
        </a>

        {{-- Pesanan --}}
        <a href="{{ route('customer.orders') }}"
           class="customer-nav__item {{ $navActive === 'pesanan' ? 'is-active' : '' }}"
           aria-label="Pesanan">
            <span class="customer-nav__icon">
                @php
                    $unreadOrders = 0;
                    try { $unreadOrders = auth()->user()?->customerOrders()->whereIn('status', ['siap', 'dikirim', 'selesai'])->where('updated_at', '>=', now()->subHours(24))->count() ?? 0; } catch (\Exception $e) {}
                @endphp
                @if($unreadOrders > 0)
                    <span class="customer-nav__badge">{{ $unreadOrders > 9 ? '9+' : $unreadOrders }}</span>
                @endif
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
            </span>
            <span class="customer-nav__label">Pesanan</span>
        </a>

        {{-- FAB: Pesan Baru --}}
        <a href="{{ route('order.create') }}"
           class="customer-nav__fab"
           aria-label="Buat Pesanan Baru">
            <span class="customer-nav__fab-btn">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
            </span>
            <span class="customer-nav__fab-label">Pesan</span>
        </a>

        {{-- Notifikasi --}}
        <a href="{{ route('customer.notifications') }}"
           class="customer-nav__item {{ $navActive === 'notif' ? 'is-active' : '' }}"
           aria-label="Notifikasi">
            <span class="customer-nav__icon" style="position:relative">
                @php
                    $unreadNotif = 0;
                    try { $unreadNotif = auth()->user()?->unreadNotifications?->count() ?? 0; } catch (\Exception $e) {}
                @endphp
                @if($unreadNotif > 0)
                    <span class="customer-nav__badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
                @endif
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </span>
            <span class="customer-nav__label">Notif</span>
        </a>

        {{-- Profil --}}
        <a href="{{ route('customer.profile') }}"
           class="customer-nav__item {{ $navActive === 'profil' ? 'is-active' : '' }}"
           aria-label="Profil">
            <span class="customer-nav__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <span class="customer-nav__label">Profil</span>
        </a>

    </div>
</nav>

<style>
/* ═══════════════════════════════════════════════
   CUSTOMER BOTTOM NAVBAR — Global Component
   Tambahkan ke file CSS utama atau inline di layout
═══════════════════════════════════════════════ */
.customer-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 999;
    background: rgba(255,255,255,0.97);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-top: 1px solid #e8f0fe;
    box-shadow: 0 -4px 24px rgba(0, 47, 92, 0.08);
    padding-bottom: env(safe-area-inset-bottom, 0px);
}
.customer-nav__inner {
    max-width: 520px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    height: 64px;
    padding: 0 8px;
}
.customer-nav__item {
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
.customer-nav__item.is-active {
    color: #0077b6;
}
.customer-nav__item.is-active .customer-nav__icon svg {
    stroke-width: 2.6;
}
.customer-nav__icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.customer-nav__badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: #FF6B35;
    color: white;
    font-size: 0.55rem;
    font-weight: 900;
    min-width: 16px;
    height: 16px;
    border-radius: 99px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid white;
    font-family: 'Nunito', sans-serif;
}
.customer-nav__label {
    font-size: 0.6rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-family: 'Nunito', sans-serif;
}
/* FAB Button */
.customer-nav__fab {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    gap: 3px;
    -webkit-tap-highlight-color: transparent;
}
.customer-nav__fab-btn {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FF6B35 0%, #ff8c5a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(255,107,53,0.45);
    margin-top: -22px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.customer-nav__fab:active .customer-nav__fab-btn {
    transform: scale(0.94);
    box-shadow: 0 3px 10px rgba(255,107,53,0.3);
}
.customer-nav__fab-label {
    font-size: 0.6rem;
    font-weight: 800;
    color: #FF6B35;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    font-family: 'Nunito', sans-serif;
    margin-top: 2px;
}
/* Active indicator dot */
.customer-nav__item.is-active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    width: 4px;
    height: 4px;
    background: #0077b6;
    border-radius: 50%;
}
/* Body padding so content isn't hidden behind nav */
body {
    padding-bottom: calc(64px + env(safe-area-inset-bottom, 0px));
}
</style>