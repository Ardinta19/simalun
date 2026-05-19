{{--
    Partial: _navbar_customer.blade.php
    Usage: @include('layouts.component.customer._navbar_customer', ['active' => 'beranda'])
    Active options: beranda | pesanan | pesan | notif | profil
--}}

@php $navActive = $active ?? 'beranda'; @endphp

<nav class="customer-nav" id="customer-nav" aria-label="Navigasi utama">
    <div class="customer-nav__inner">

        {{-- Beranda --}}
        <a href="{{ route('customer.dashboard') }}"
           class="customer-nav__item {{ $navActive === 'beranda' ? 'is-active' : '' }}"
           aria-label="Beranda"
           @if($navActive === 'beranda') aria-current="page" @endif>
            <span class="customer-nav__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </span>
            <span class="customer-nav__label">Beranda</span>
            @if($navActive === 'beranda')<span class="customer-nav__dot"></span>@endif
        </a>

        {{-- Pesanan --}}
        <a href="{{ route('customer.orders') }}"
           class="customer-nav__item {{ $navActive === 'pesanan' ? 'is-active' : '' }}"
           aria-label="Pesanan"
           @if($navActive === 'pesanan') aria-current="page" @endif>
            <span class="customer-nav__icon">
                @php
                    $navUnreadOrders = 0;
                    try { $navUnreadOrders = auth()->user()?->customerOrders()->whereIn('status', ['siap', 'dikirim'])->where('updated_at', '>=', now()->subHours(24))->count() ?? 0; } catch (\Exception $e) {}
                @endphp
                @if($navUnreadOrders > 0)
                    <span class="customer-nav__badge">{{ $navUnreadOrders > 9 ? '9+' : $navUnreadOrders }}</span>
                @endif
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
            </span>
            <span class="customer-nav__label">Pesanan</span>
            @if($navActive === 'pesanan')<span class="customer-nav__dot"></span>@endif
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
           aria-label="Notifikasi"
           @if($navActive === 'notif') aria-current="page" @endif>
            <span class="customer-nav__icon" style="position:relative">
                @php
                    $navUnreadNotif = 0;
                    try { $navUnreadNotif = auth()->user()?->unreadNotifications?->count() ?? 0; } catch (\Exception $e) {}
                @endphp
                @if($navUnreadNotif > 0)
                    <span class="customer-nav__badge">{{ $navUnreadNotif > 9 ? '9+' : $navUnreadNotif }}</span>
                @endif
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>
            </span>
            <span class="customer-nav__label">Notif</span>
            @if($navActive === 'notif')<span class="customer-nav__dot"></span>@endif
        </a>

        {{-- Profil --}}
        <a href="{{ route('customer.profile') }}"
           class="customer-nav__item {{ $navActive === 'profil' ? 'is-active' : '' }}"
           aria-label="Profil"
           @if($navActive === 'profil') aria-current="page" @endif>
            <span class="customer-nav__icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            <span class="customer-nav__label">Profil</span>
            @if($navActive === 'profil')<span class="customer-nav__dot"></span>@endif
        </a>

    </div>
</nav>

<style>
/* ═══════════════════════════════════════════════════════════════════
   CUSTOMER BOTTOM NAVBAR — Single Source of Truth
   Responsive: Mobile → Tablet → Laptop → Desktop
═══════════════════════════════════════════════════════════════════ */
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
.customer-nav__dot {
    width: 4px;
    height: 4px;
    background: #0077b6;
    border-radius: 50%;
    position: absolute;
    bottom: 0;
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

/* ═══════ RESPONSIVE ═══════ */
/* Tablet (768px+) */
@media (min-width: 768px) {
    .customer-nav__inner {
        max-width: 680px;
        height: 70px;
    }
    .customer-nav__label {
        font-size: 0.65rem;
    }
    .customer-nav__fab-btn {
        width: 56px;
        height: 56px;
    }
}

/* Laptop (1024px+) */
@media (min-width: 1024px) {
    .customer-nav__inner {
        max-width: 720px;
        height: 72px;
    }
    .customer-nav__item {
        gap: 4px;
    }
    .customer-nav__label {
        font-size: 0.68rem;
    }
}

/* Desktop (1280px+) */
@media (min-width: 1280px) {
    .customer-nav__inner {
        max-width: 800px;
    }
}

/* Body padding so content isn't hidden behind nav */
body {
    padding-bottom: calc(64px + env(safe-area-inset-bottom, 0px)) !important;
}
@media (min-width: 768px) {
    body {
        padding-bottom: calc(70px + env(safe-area-inset-bottom, 0px)) !important;
    }
}
</style>



{{-- ═══════════════════════════════════════════════════════════════════
     SHARED CONFIRMATION MODAL (replaces browser confirm())
═══════════════════════════════════════════════════════════════════ --}}
<div id="app-confirm" class="app-confirm" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="app-confirm-title">
    <div class="app-confirm__backdrop" data-confirm-cancel></div>
    <div class="app-confirm__panel" role="document">
        <div class="app-confirm__icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <h3 class="app-confirm__title" id="app-confirm-title">Konfirmasi</h3>
        <p class="app-confirm__message">Apakah kamu yakin?</p>
        <div class="app-confirm__actions">
            <button type="button" class="app-confirm__btn app-confirm__btn--cancel" data-confirm-cancel>Batal</button>
            <button type="button" class="app-confirm__btn app-confirm__btn--ok" data-confirm-ok>Ya</button>
        </div>
    </div>
</div>

<style>
/* ═══════════ CONFIRMATION MODAL ═══════════ */
.app-confirm {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    -webkit-tap-highlight-color: transparent;
}
.app-confirm.is-open { display: flex; }
.app-confirm__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(13, 33, 55, 0.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    cursor: pointer;
}
.app-confirm__panel {
    position: relative;
    background: #fff;
    border-radius: 22px;
    padding: 28px 22px 18px;
    width: 100%;
    max-width: 380px;
    text-align: center;
    box-shadow: 0 24px 64px rgba(0, 47, 92, 0.32), 0 8px 24px rgba(0, 47, 92, 0.18);
    border: 1.5px solid rgba(255,255,255,0.6);
    transform: scale(0.92) translateY(10px);
    opacity: 0;
    transition: transform 0.28s cubic-bezier(.34,1.56,.64,1), opacity 0.22s ease;
}
.app-confirm.is-open .app-confirm__panel {
    transform: scale(1) translateY(0);
    opacity: 1;
}
.app-confirm__icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 14px;
    border-radius: 50%;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d97706;
    box-shadow: 0 8px 20px rgba(217, 119, 6, 0.22);
}
.app-confirm__icon svg { width: 32px; height: 32px; }
.app-confirm.is-danger .app-confirm__icon {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    box-shadow: 0 8px 20px rgba(220, 38, 38, 0.25);
}
.app-confirm.is-danger .app-confirm__icon svg {
    stroke-width: 2.4;
}
.app-confirm__title {
    font-family: 'Fredoka One', cursive, sans-serif;
    font-size: 1.2rem;
    color: #0d2137;
    margin-bottom: 6px;
    line-height: 1.2;
}
.app-confirm__message {
    font-family: 'Nunito', sans-serif;
    font-size: 0.92rem;
    font-weight: 600;
    color: #5a6b7e;
    line-height: 1.5;
    margin-bottom: 22px;
    padding: 0 4px;
}
.app-confirm__actions {
    display: flex;
    gap: 10px;
}
.app-confirm__btn {
    flex: 1;
    padding: 13px 16px;
    border-radius: 14px;
    border: none;
    font-family: 'Nunito', sans-serif;
    font-size: 0.92rem;
    font-weight: 900;
    cursor: pointer;
    letter-spacing: 0.2px;
    transition: transform 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
    -webkit-tap-highlight-color: transparent;
}
.app-confirm__btn--cancel {
    background: #f1f5f9;
    color: #475569;
}
.app-confirm__btn--cancel:hover { background: #e2e8f0; }
.app-confirm__btn--cancel:active { transform: scale(0.97); }
.app-confirm__btn--ok {
    background: linear-gradient(135deg, #0077b6, #00b4d8);
    color: #fff;
    box-shadow: 0 6px 18px rgba(0, 119, 182, 0.35);
}
.app-confirm__btn--ok:hover { box-shadow: 0 8px 22px rgba(0, 119, 182, 0.5); }
.app-confirm__btn--ok:active { transform: scale(0.97); }
.app-confirm.is-danger .app-confirm__btn--ok {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    box-shadow: 0 6px 18px rgba(220, 38, 38, 0.4);
}
.app-confirm.is-danger .app-confirm__btn--ok:hover {
    box-shadow: 0 8px 22px rgba(220, 38, 38, 0.55);
}

/* Tablet+ */
@media (min-width: 768px) {
    .app-confirm__panel {
        max-width: 420px;
        padding: 32px 26px 20px;
        border-radius: 24px;
    }
    .app-confirm__title { font-size: 1.35rem; }
    .app-confirm__message { font-size: 0.95rem; }
}
</style>

<script>
/* ═══════════════════════════════════════════════════════════════════
   APP HELPERS — confirmation modal & smart back button
═══════════════════════════════════════════════════════════════════ */
(function () {
    if (window.__appHelpersLoaded) return;
    window.__appHelpersLoaded = true;

    /* ─── 1) showConfirm() — Promise-based custom confirm modal ─── */
    const modal = document.getElementById('app-confirm');
    if (!modal) return;

    const titleEl   = modal.querySelector('.app-confirm__title');
    const messageEl = modal.querySelector('.app-confirm__message');
    const okBtn     = modal.querySelector('[data-confirm-ok]');
    const cancelEls = modal.querySelectorAll('[data-confirm-cancel]');

    let resolver = null;

    function close(result) {
        modal.classList.remove('is-open', 'is-danger');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (resolver) {
            const r = resolver;
            resolver = null;
            r(result);
        }
    }

    function open() {
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        // focus the cancel button by default for safety
        setTimeout(() => {
            const cancelBtn = modal.querySelector('.app-confirm__btn--cancel');
            if (cancelBtn) cancelBtn.focus();
        }, 60);
    }

    okBtn.addEventListener('click', () => close(true));
    cancelEls.forEach(el => el.addEventListener('click', () => close(false)));
    modal.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close(false);
        if (e.key === 'Enter' && document.activeElement !== okBtn) {
            // pressing Enter on cancel keeps cancel; only OK confirms via click
        }
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('is-open')) close(false);
    });

    window.showConfirm = function ({
        title = 'Konfirmasi',
        message = 'Apakah kamu yakin?',
        confirmText = 'Ya, Lanjutkan',
        cancelText = 'Batal',
        danger = false,
    } = {}) {
        return new Promise((resolve) => {
            // close any existing
            if (resolver) { resolver(false); resolver = null; }

            titleEl.textContent = title;
            messageEl.textContent = message;
            okBtn.textContent = confirmText;
            modal.querySelectorAll('.app-confirm__btn--cancel').forEach(b => b.textContent = cancelText);
            modal.classList.toggle('is-danger', !!danger);

            resolver = resolve;
            open();
        });
    };

    /* ─── 2) Auto-wire forms with data-confirm attribute ─── */
    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('form[data-confirm]');
        if (!form || form.dataset.confirmed === '1') return;

        e.preventDefault();
        const ok = await window.showConfirm({
            title: form.dataset.confirmTitle || 'Konfirmasi',
            message: form.dataset.confirm,
            confirmText: form.dataset.confirmOk || 'Ya, Lanjutkan',
            cancelText: form.dataset.confirmCancel || 'Batal',
            danger: form.dataset.confirmDanger === 'true' || form.dataset.confirmDanger === '1',
        });
        if (ok) {
            form.dataset.confirmed = '1';
            form.submit();
        }
    }, true);

    /* ─── 3) Auto-wire buttons/links with data-confirm-action ─── */
    document.addEventListener('click', async (e) => {
        const trigger = e.target.closest('[data-confirm-action]');
        if (!trigger) return;

        e.preventDefault();
        e.stopPropagation();

        const ok = await window.showConfirm({
            title: trigger.dataset.confirmTitle || 'Konfirmasi',
            message: trigger.dataset.confirmAction,
            confirmText: trigger.dataset.confirmOk || 'Ya, Lanjutkan',
            cancelText: trigger.dataset.confirmCancel || 'Batal',
            danger: trigger.dataset.confirmDanger === 'true' || trigger.dataset.confirmDanger === '1',
        });

        if (!ok) return;

        // Determine action target
        const targetForm = trigger.dataset.confirmTarget
            ? document.getElementById(trigger.dataset.confirmTarget)
            : null;
        if (targetForm && targetForm.tagName === 'FORM') {
            targetForm.dataset.confirmed = '1';
            targetForm.submit();
            return;
        }
        if (trigger.tagName === 'A' && trigger.href) {
            window.location.href = trigger.href;
            return;
        }
        if (trigger.tagName === 'BUTTON' && trigger.form) {
            trigger.form.submit();
        }
    }, true);

    /* ─── 4) Smart back button ───
       Usage:
         <a href="{{ route('fallback') }}" data-smart-back>...</a>
         or call window.smartBack('/fallback') directly.
       Behavior:
         - If document.referrer is from same origin AND not the current URL,
           use history.back() (returns to actual previous page).
         - Otherwise navigate to the fallback URL provided in href.
    ─── */
    window.smartBack = function (fallbackUrl) {
        try {
            const ref = document.referrer;
            if (ref) {
                const refUrl = new URL(ref);
                if (refUrl.origin === window.location.origin && refUrl.href !== window.location.href) {
                    if (window.history.length > 1) {
                        window.history.back();
                        return;
                    }
                }
            }
        } catch (_) { /* ignore */ }
        if (fallbackUrl) {
            window.location.href = fallbackUrl;
        } else {
            // last resort — try history anyway
            window.history.back();
        }
    };

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-smart-back]');
        if (!btn) return;
        e.preventDefault();
        const fallback = btn.getAttribute('href') || btn.dataset.smartBack || '/';
        window.smartBack(fallback);
    });
})();
</script>
