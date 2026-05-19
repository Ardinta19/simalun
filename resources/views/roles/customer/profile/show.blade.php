<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Saya – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root {
  --blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;
  --blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--green-lt:#e6fff6;
  --white:#ffffff;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;
  --surface:#f4f8fc;--border:#ddeeff;--radius:20px;--radius-sm:14px;
  --red:#ef4444;--red-lt:#fff1f1;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
html{scroll-behavior:smooth;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

/* ── HEADER ── */
.page-header{
  background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);
  padding:0;position:relative;overflow:hidden;
}
.page-header::before{content:'';position:absolute;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.05);top:-70px;right:-50px;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 24px;max-width:520px;margin:0 auto;}
.header-row{display:flex;align-items:center;justify-content:space-between;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.15rem;color:#fff;}
.header-bell{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.14);border:1.5px solid rgba(255,255,255,.24);display:flex;align-items:center;justify-content:center;text-decoration:none;position:relative;}
.header-bell svg{width:18px;height:18px;stroke:#fff;}
.bell-pip{position:absolute;top:6px;right:6px;width:8px;height:8px;background:var(--orange);border-radius:50%;border:1.5px solid var(--blue-mid);}
.header-wave{display:block;width:104%;margin:-1px -2% 0;height:28px;}

/* ── PAGE BODY ── */
.page-body{max-width:520px;margin:0 auto;padding:0 16px;}

/* ── AVATAR CARD ── */
.avatar-card{
  background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);
  padding:28px 20px 24px;text-align:center;margin-top:-14px;position:relative;z-index:5;
  box-shadow:0 4px 20px rgba(0,47,92,.07);
}
.avatar-ring{
  width:88px;height:88px;border-radius:50%;
  background:linear-gradient(135deg,var(--blue-mid),var(--blue-light));
  padding:3px;margin:0 auto 14px;
}
.avatar-img{
  width:100%;height:100%;border-radius:50%;object-fit:cover;
  background:var(--blue-sky);display:flex;align-items:center;justify-content:center;
  font-size:2.2rem;border:3px solid #fff;
}
.avatar-img img{width:100%;height:100%;object-fit:cover;border-radius:50%;}
.profile-name{font-family:'Fredoka One',cursive;font-size:1.35rem;color:var(--ink);margin-bottom:4px;}
.profile-phone{font-size:.82rem;font-weight:700;color:var(--ink-lt);letter-spacing:.3px;}
.profile-location{
  display:inline-flex;align-items:center;gap:5px;
  background:var(--blue-sky);border-radius:99px;padding:5px 12px;margin-top:10px;
  font-size:.72rem;font-weight:800;color:var(--blue-mid);
}
.edit-fab{
  position:absolute;top:20px;right:20px;
  width:34px;height:34px;border-radius:50%;
  background:var(--blue-mid);border:2px solid #fff;
  display:flex;align-items:center;justify-content:center;
  box-shadow:0 4px 12px rgba(0,119,182,.3);
  text-decoration:none;cursor:pointer;
}
.edit-fab svg{width:14px;height:14px;stroke:#fff;}

/* ── ALAMAT UTAMA CARD ── */
.alamat-card{
  background:linear-gradient(135deg,#e6fff6 0%,#f0fff8 100%);
  border:1.5px solid rgba(0,196,140,.25);border-radius:var(--radius);
  padding:16px 18px;margin:16px 0;
  display:flex;align-items:flex-start;gap:12px;
}
.alamat-icon{
  width:42px;height:42px;border-radius:12px;
  background:var(--green);display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;flex-shrink:0;color:#fff;
}
.alamat-body{flex:1;min-width:0;}
.alamat-label{font-size:.68rem;font-weight:900;color:var(--green);text-transform:uppercase;letter-spacing:.5px;}
.alamat-text{font-size:.85rem;font-weight:700;color:var(--ink);margin-top:3px;line-height:1.4;}

/* ── MENU LIST ── */
.menu-card{
  background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);
  overflow:hidden;margin-bottom:16px;box-shadow:0 2px 10px rgba(0,47,92,.04);
}
.menu-item{
  display:flex;align-items:center;gap:14px;padding:15px 18px;
  border-bottom:1px solid var(--border);text-decoration:none;color:inherit;
  transition:background .15s;
}
.menu-item:last-child{border-bottom:none;}
.menu-item:active{background:var(--surface);}
.menu-icon{
  width:40px;height:40px;border-radius:12px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.1rem;flex-shrink:0;
}
.mi-blue{background:var(--blue-sky);}
.mi-green{background:var(--green-lt);}
.mi-orange{background:#fff3ee;}
.menu-text{flex:1;}
.menu-text-title{font-size:.88rem;font-weight:800;color:var(--ink);}
.menu-text-sub{font-size:.72rem;font-weight:700;color:var(--ink-lt);margin-top:1px;}
.menu-arrow{color:var(--ink-lt);font-size:.9rem;flex-shrink:0;}

/* ── LOGOUT ── */
.logout-card{
  background:#fff;border:1.5px solid #fecaca;border-radius:var(--radius);
  overflow:hidden;margin-bottom:16px;
}
.btn-logout{
  display:flex;align-items:center;justify-content:center;gap:10px;
  width:100%;padding:15px;border:none;background:transparent;
  font-family:'Nunito',sans-serif;font-weight:900;font-size:.92rem;
  color:var(--red);cursor:pointer;transition:background .2s;
}
.btn-logout:active{background:var(--red-lt);}
.btn-logout svg{width:18px;height:18px;}

/* ── FOOTER ── */
.profile-footer{text-align:center;padding:8px 0 16px;}
.footer-links{display:flex;align-items:center;justify-content:center;gap:16px;margin-bottom:8px;}
.footer-links a{font-size:.72rem;font-weight:700;color:var(--ink-lt);text-decoration:none;}
.footer-links a:hover{color:var(--blue-mid);}
.footer-version{font-size:.68rem;font-weight:800;color:var(--border);letter-spacing:.5px;}

/* ── GSAP ── */
.js-reveal{opacity:0;transform:translateY(18px);}
</style>
</head>
<body>

@php
  $alamatUtama = $user->customerAddresses()->where('is_primary', true)->first();
  $unreadNotif = $user->unreadNotifications->count();
@endphp

{{-- HEADER --}}
<div class="page-header">
  <div class="header-inner">
    <div class="header-row">
      <div class="header-title">Profil Saya</div>
      <a href="{{ route('customer.notifications') }}" class="header-bell">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
        @if($unreadNotif > 0)<span class="bell-pip"></span>@endif
      </a>
    </div>
  </div>
  <svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none">
    <path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/>
  </svg>
</div>

<div class="page-body">

  {{-- AVATAR CARD --}}
  <div class="avatar-card js-reveal">
    <a href="{{ route('profile.edit') }}" class="edit-fab" title="Edit profil">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
      </svg>
    </a>
    <div class="avatar-ring">
      <div class="avatar-img">
        @if($user->avatar)
          <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
        @else
          {{ strtoupper(mb_substr($user->name, 0, 1)) }}
        @endif
      </div>
    </div>
    <div class="profile-name">{{ $user->name }}</div>
    <div class="profile-phone">{{ $user->phone ?? $user->email }}</div>
    @if($alamatUtama)
    <div class="profile-location">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
      {{ $alamatUtama->label ?? 'Alamat Utama' }}
    </div>
    @endif
  </div>

  {{-- ALAMAT UTAMA --}}
  @if($alamatUtama)
  <div class="alamat-card js-reveal">
    <div class="alamat-icon">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
    </div>
    <div class="alamat-body">
      <div class="alamat-label">Alamat Utama</div>
      <div class="alamat-text">{{ $alamatUtama->full_address }}</div>
    </div>
  </div>
  @endif

  {{-- MENU LIST --}}
  <div class="menu-card js-reveal">
    <a href="{{ route('customer.addresses.index') }}" class="menu-item">
      <div class="menu-icon mi-blue">📍</div>
      <div class="menu-text">
        <div class="menu-text-title">Alamat Tersimpan</div>
        <div class="menu-text-sub">Kelola alamat jemput & antar</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>
    <a href="#" class="menu-item">
      <div class="menu-icon mi-green">💳</div>
      <div class="menu-text">
        <div class="menu-text-title">Metode Pembayaran</div>
        <div class="menu-text-sub">COD (Bayar saat diantar)</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>
    <a href="{{ route('customer.help') }}" class="menu-item">
      <div class="menu-icon mi-orange">🆘</div>
      <div class="menu-text">
        <div class="menu-text-title">Pusat Bantuan</div>
        <div class="menu-text-sub">FAQ & hubungi customer service</div>
      </div>
      <div class="menu-arrow">›</div>
    </a>
  </div>

  {{-- LOGOUT --}}
  <div class="logout-card js-reveal">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
          <polyline points="16 17 21 12 16 7"/>
          <line x1="21" y1="12" x2="9" y2="12"/>
        </svg>
        Keluar
      </button>
    </form>
  </div>

  {{-- FOOTER --}}
  <div class="profile-footer js-reveal">
    <div class="footer-links">
      <a href="#">Syarat & Ketentuan</a>
      <span style="color:var(--border);">•</span>
      <a href="#">Kebijakan Privasi</a>
    </div>
    <div class="footer-version">Versi 2.4.0 (Azka Laundry)</div>
  </div>

</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', function(){
  const reveals = document.querySelectorAll('.js-reveal');
  gsap.to(reveals, {
    opacity:1, y:0, duration:.5, stagger:.08, ease:'power3.out', delay:.15
  });

  // Avatar card subtle float
  const card = document.querySelector('.avatar-card');
  if(card){
    gsap.to(card, {
      boxShadow:'0 8px 28px rgba(0,47,92,.1)',
      duration:2, yoyo:true, repeat:-1, ease:'sine.inOut'
    });
  }
});
</script>

</body>
</html>
