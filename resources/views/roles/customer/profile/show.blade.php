<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil — Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root {
  --brand-deep:    #002f5c;
  --brand-primary: #0077b6;
  --brand-light:   #00b4d8;
  --brand-accent:  #FF6B35;
  --ink:           #0d1f33;
  --ink-2:         #3d5066;
  --ink-3:         #6b7c8f;
  --ink-mute:      #8c9bab;
  --line:          #e3ecf3;
  --line-2:        #f0f5f9;
  --bg:            #f7fafd;
  --card:          #ffffff;
  --ok:            #16a34a;
  --danger:        #dc2626;
}
* { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--ink);
  min-height: 100vh;
  padding-bottom: calc(76px + env(safe-area-inset-bottom, 0));
  -webkit-font-smoothing: antialiased;
}

/* App bar */
.app-bar {
  background: var(--card);
  border-bottom: 1px solid var(--line);
  padding: max(env(safe-area-inset-top, 0), 14px) 20px 14px;
  position: sticky; top: 0; z-index: 50;
}
.app-bar__row {
  max-width: 520px; margin: 0 auto;
  display: grid; grid-template-columns: 36px 1fr 36px;
  gap: 12px; align-items: center;
}
.icon-btn {
  width: 36px; height: 36px;
  border-radius: 10px;
  border: 1px solid var(--line);
  background: var(--card);
  display: grid; place-items: center;
  color: var(--ink-2);
  text-decoration: none;
  cursor: pointer;
  position: relative;
}
.icon-btn:hover { background: var(--line-2); }
.icon-btn svg { width: 18px; height: 18px; }
.icon-btn .pip {
  position: absolute;
  top: 8px; right: 8px;
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--brand-accent);
}
.app-bar__title {
  text-align: center;
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.01em;
}

/* Identity card */
.identity {
  max-width: 520px; margin: 0 auto;
  padding: 24px 20px 8px;
}
.identity__row {
  display: flex;
  align-items: center;
  gap: 16px;
}
.avatar {
  width: 64px; height: 64px;
  border-radius: 50%;
  background: var(--line-2);
  display: grid; place-items: center;
  flex-shrink: 0;
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--brand-primary);
  border: 1px solid var(--line);
  overflow: hidden;
}
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.id-info { flex: 1; min-width: 0; }
.id-name {
  font-size: 1.15rem;
  font-weight: 800;
  color: var(--ink);
  letter-spacing: -0.02em;
  margin-bottom: 2px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.id-meta {
  font-size: 0.84rem;
  color: var(--ink-3);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.btn-edit {
  background: var(--card);
  border: 1px solid var(--line);
  color: var(--ink-2);
  padding: 8px 14px;
  border-radius: 99px;
  font-size: 0.78rem;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
  transition: border-color .12s, color .12s;
}
.btn-edit:hover { border-color: var(--brand-primary); color: var(--brand-primary); }
.btn-edit svg { width: 14px; height: 14px; }

/* Section */
.section {
  max-width: 520px; margin: 0 auto;
  padding: 16px 20px;
}
.section__title {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--ink-mute);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 10px;
  padding: 0 4px;
}

/* Address summary */
.addr-card {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 14px 16px;
  display: grid;
  grid-template-columns: 36px 1fr;
  gap: 12px;
  text-decoration: none;
  color: inherit;
  transition: border-color .12s;
}
.addr-card:hover { border-color: var(--brand-primary); }
.addr-card__icon {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: rgba(0,119,182,.08);
  color: var(--brand-primary);
  display: grid; place-items: center;
}
.addr-card__icon svg { width: 18px; height: 18px; }
.addr-card__label {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--ink-mute);
  letter-spacing: 0.05em;
  text-transform: uppercase;
  margin-bottom: 3px;
}
.addr-card__text {
  font-size: 0.88rem;
  color: var(--ink);
  line-height: 1.5;
  font-weight: 500;
}

/* Menu list */
.menu {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 12px;
  overflow: hidden;
}
.menu-row {
  display: grid;
  grid-template-columns: 36px 1fr 16px;
  gap: 14px;
  align-items: center;
  padding: 14px 16px;
  text-decoration: none;
  color: inherit;
  border-bottom: 1px solid var(--line-2);
  cursor: pointer;
  background: var(--card);
  border-left: 0; border-right: 0; border-top: 0;
  width: 100%;
  font-family: inherit;
  text-align: left;
  transition: background .12s;
}
.menu-row:last-child { border-bottom: 0; }
.menu-row:hover { background: var(--line-2); }
.menu-row__icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: var(--line-2);
  display: grid; place-items: center;
  color: var(--ink-2);
}
.menu-row__icon svg { width: 18px; height: 18px; }
.menu-row__title {
  font-size: 0.9rem;
  font-weight: 600;
  color: var(--ink);
  letter-spacing: -0.005em;
}
.menu-row__sub {
  font-size: 0.74rem;
  color: var(--ink-3);
  margin-top: 2px;
}
.menu-row__chev {
  color: var(--ink-mute);
}

/* Logout button */
.logout {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 14px 16px;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: var(--danger);
  font-family: inherit;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: background .12s, border-color .12s;
}
.logout:hover { background: rgba(220,38,38,.04); border-color: rgba(220,38,38,.3); }
.logout svg { width: 16px; height: 16px; }

/* Footer */
.foot {
  max-width: 520px;
  margin: 0 auto;
  padding: 24px 20px 8px;
  text-align: center;
}
.foot__links {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  margin-bottom: 8px;
  flex-wrap: wrap;
}
.foot__links a {
  font-size: 0.78rem;
  color: var(--ink-3);
  text-decoration: none;
  font-weight: 500;
}
.foot__links a:hover { color: var(--brand-primary); }
.foot__links span { color: var(--line); }
.foot__version {
  font-size: 0.72rem;
  color: var(--ink-mute);
  letter-spacing: 0.05em;
}

/* Alert */
.alert {
  max-width: 520px; margin: 12px auto 0;
  padding: 0 20px;
}
.alert__inner {
  background: rgba(22,163,74,.08);
  border: 1px solid rgba(22,163,74,.25);
  border-radius: 10px;
  padding: 10px 14px;
  font-size: 0.84rem;
  color: var(--ok);
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 8px;
}
.alert__inner svg { width: 16px; height: 16px; }
</style>
</head>
<body>

@php
  $alamatUtama = $user->customerAddresses()->where('is_primary', true)->first();
  $unreadNotif = $user->unreadNotifications->count();
@endphp

<div class="app-bar">
  <div class="app-bar__row">
    <a href="{{ route('customer.dashboard') }}" class="icon-btn" aria-label="Kembali">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div class="app-bar__title">Profil</div>
    <a href="{{ route('customer.notifications') }}" class="icon-btn" aria-label="Notifikasi">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      @if($unreadNotif > 0)<span class="pip"></span>@endif
    </a>
  </div>
</div>

@if(session('status') === 'profile-updated')
<div class="alert">
  <div class="alert__inner">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Profil berhasil diperbarui
  </div>
</div>
@endif

<header class="identity" data-reveal>
  <div class="identity__row">
    <div class="avatar">
      @if($user->avatar)
        <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
      @else
        {{ strtoupper(mb_substr($user->name, 0, 1)) }}
      @endif
    </div>
    <div class="id-info">
      <div class="id-name">{{ $user->name }}</div>
      <div class="id-meta">{{ $user->phone ?? $user->email }}</div>
    </div>
    <a href="{{ route('profile.edit') }}" class="btn-edit">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      Ubah
    </a>
  </div>
</header>

@if($alamatUtama)
<section class="section" data-reveal>
  <div class="section__title">Alamat Utama</div>
  <a href="{{ route('customer.addresses.index') }}" class="addr-card">
    <div class="addr-card__icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
    </div>
    <div>
      <div class="addr-card__label">{{ $alamatUtama->label }}</div>
      <div class="addr-card__text">{{ $alamatUtama->full_address }}</div>
    </div>
  </a>
</section>
@endif

<section class="section" data-reveal>
  <div class="section__title">Pengaturan Akun</div>
  <div class="menu">

    <a href="{{ route('customer.addresses.index') }}" class="menu-row">
      <div class="menu-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <div>
        <div class="menu-row__title">Alamat Tersimpan</div>
        <div class="menu-row__sub">Kelola alamat jemput dan antar</div>
      </div>
      <svg class="menu-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>

    <button type="button" class="menu-row" onclick="alert('Saat ini hanya tersedia COD (bayar saat antar)')">
      <div class="menu-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <div>
        <div class="menu-row__title">Metode Pembayaran</div>
        <div class="menu-row__sub">Cash on Delivery (COD)</div>
      </div>
      <svg class="menu-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </button>

    <a href="{{ route('customer.help') }}" class="menu-row">
      <div class="menu-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
      </div>
      <div>
        <div class="menu-row__title">Pusat Bantuan</div>
        <div class="menu-row__sub">FAQ, jam operasional, kontak</div>
      </div>
      <svg class="menu-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>

  </div>
</section>

<section class="section" data-reveal>
  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="logout">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Keluar dari Akun
    </button>
  </form>
</section>

<div class="foot">
  <div class="foot__links">
    <a href="#">Syarat &amp; Ketentuan</a>
    <span>·</span>
    <a href="#">Kebijakan Privasi</a>
  </div>
  <div class="foot__version">Versi 1.0 · Azka Laundry</div>
</div>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined') return;
  gsap.from('[data-reveal]', {
    y: 12,
    opacity: 0,
    duration: 0.45,
    ease: 'power2.out',
    stagger: 0.06
  });
});
</script>
</body>
</html>
