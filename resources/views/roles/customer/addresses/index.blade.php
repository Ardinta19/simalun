<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Alamat Tersimpan – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--green-lt:#e6fff6;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--surface:#f4f8fc;--border:#ddeeff;--radius:18px;--radius-sm:12px;--red:#ef4444;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

/* Header */
.page-header{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);padding:max(env(safe-area-inset-top,0px),16px) 20px 24px;position:sticky;top:0;z-index:100;}
.header-row{display:flex;align-items:center;gap:12px;max-width:520px;margin:0 auto;}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;flex-shrink:0;}
.btn-back svg{width:18px;height:18px;}
.header-title{flex:1;font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;}
.btn-add{display:inline-flex;align-items:center;gap:5px;background:rgba(255,255,255,.18);border:1.5px solid rgba(255,255,255,.3);border-radius:99px;padding:7px 14px;text-decoration:none;color:#fff;font-size:.75rem;font-weight:800;transition:background .2s;}
.btn-add:active{background:rgba(255,255,255,.3);}
.btn-add svg{width:14px;height:14px;}

/* Body */
.page-body{max-width:520px;margin:0 auto;padding:16px;}

/* Alert */
.alert{border-radius:var(--radius-sm);padding:12px 16px;margin-bottom:14px;font-size:.82rem;font-weight:700;}
.alert-success{background:var(--green-lt);color:#065f46;border:1.5px solid rgba(0,196,140,.3);}
.alert-error{background:#fff1f1;color:var(--red);border:1.5px solid #fecaca;}

/* Address card */
.addr-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:12px;position:relative;box-shadow:0 2px 10px rgba(0,47,92,.04);transition:transform .15s;opacity:0;transform:translateY(16px);}
.addr-card:active{transform:scale(.98);}
.addr-card.is-primary{border-color:var(--green);background:linear-gradient(135deg,#f8fffb,#fff);}
.addr-card.is-primary::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--green);border-radius:var(--radius) var(--radius) 0 0;}

.addr-top{display:flex;align-items:flex-start;gap:12px;margin-bottom:10px;}
.addr-icon{width:40px;height:40px;border-radius:12px;background:var(--blue-sky);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.addr-card.is-primary .addr-icon{background:var(--green-lt);}
.addr-info{flex:1;min-width:0;}
.addr-label{font-family:'Fredoka One',cursive;font-size:.92rem;color:var(--ink);display:flex;align-items:center;gap:6px;}
.addr-primary-badge{font-family:'Nunito',sans-serif;font-size:.6rem;font-weight:900;background:var(--green);color:#fff;border-radius:99px;padding:2px 7px;text-transform:uppercase;letter-spacing:.3px;}
.addr-recipient{font-size:.78rem;font-weight:700;color:var(--ink-mid);margin-top:2px;}
.addr-full{font-size:.8rem;font-weight:700;color:var(--ink-lt);margin-top:6px;line-height:1.4;}
.addr-zone{display:inline-block;margin-top:6px;font-size:.68rem;font-weight:900;background:var(--blue-sky);color:var(--blue-mid);border-radius:99px;padding:3px 8px;}

/* Actions */
.addr-actions{display:flex;align-items:center;gap:8px;margin-top:12px;padding-top:10px;border-top:1px solid var(--border);}
.addr-btn{padding:6px 12px;border-radius:99px;font-size:.72rem;font-weight:800;text-decoration:none;border:1.5px solid var(--border);color:var(--ink-mid);transition:all .15s;background:#fff;}
.addr-btn:active{background:var(--surface);}
.addr-btn.primary{border-color:var(--green);color:var(--green);}
.addr-btn.primary:active{background:var(--green-lt);}
.addr-btn.delete{border-color:#fecaca;color:var(--red);}
.addr-btn.delete:active{background:#fff1f1;}

/* Empty */
.empty-state{text-align:center;padding:60px 20px;}
.empty-icon{font-size:3rem;display:block;margin-bottom:12px;opacity:.6;}
.empty-title{font-family:'Fredoka One',cursive;font-size:1.05rem;color:var(--ink-mid);margin-bottom:6px;}
.empty-sub{font-size:.82rem;font-weight:700;color:var(--ink-lt);margin-bottom:20px;line-height:1.5;}
.btn-cta{display:inline-flex;align-items:center;gap:8px;background:var(--orange);color:#fff;padding:12px 24px;border-radius:99px;font-weight:900;font-size:.88rem;text-decoration:none;box-shadow:0 6px 20px rgba(255,107,53,.35);}
</style>
</head>
<body>

{{-- HEADER --}}
<header class="page-header">
  <div class="header-row">
    <a href="{{ route('customer.profile') }}" class="btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
    </a>
    <div class="header-title">Alamat Tersimpan</div>
    <a href="{{ route('customer.addresses.create') }}" class="btn-add">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah
    </a>
  </div>
</header>

<div class="page-body">

  {{-- Alerts --}}
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="alert alert-error">{{ session('error') }}</div>
  @endif

  @if(isset($alamat) && $alamat->count() > 0)

    @foreach($alamat as $addr)
    <div class="addr-card {{ $addr->is_primary ? 'is-primary' : '' }} js-card">
      <div class="addr-top">
        <div class="addr-icon">{{ $addr->is_primary ? '🏠' : '📍' }}</div>
        <div class="addr-info">
          <div class="addr-label">
            {{ $addr->label }}
            @if($addr->is_primary)
            <span class="addr-primary-badge">Utama</span>
            @endif
          </div>
          <div class="addr-recipient">{{ $addr->recipient_name }} {{ $addr->phone ? '• '.$addr->phone : '' }}</div>
        </div>
      </div>
      <div class="addr-full">{{ $addr->full_address }}</div>
      @if($addr->zone)
      <span class="addr-zone">Zona {{ $addr->zone }} {{ $addr->distance_km ? '• '.$addr->distance_km.' km' : '' }}</span>
      @endif

      <div class="addr-actions">
        <a href="{{ route('customer.addresses.edit', $addr) }}" class="addr-btn">Edit</a>
        @if(!$addr->is_primary)
        <form method="POST" action="{{ route('customer.addresses.set-primary', $addr) }}" style="display:inline;">
          @csrf @method('PATCH')
          <button type="submit" class="addr-btn primary" style="cursor:pointer;font-family:'Nunito',sans-serif;">Jadikan Utama</button>
        </form>
        @endif
        <form method="POST" action="{{ route('customer.addresses.destroy', $addr) }}" style="display:inline;"
              onsubmit="return confirm('Hapus alamat ini?')">
          @csrf @method('DELETE')
          <button type="submit" class="addr-btn delete" style="cursor:pointer;font-family:'Nunito',sans-serif;">Hapus</button>
        </form>
      </div>
    </div>
    @endforeach

  @else

  <div class="empty-state">
    <span class="empty-icon">📍</span>
    <div class="empty-title">Belum Ada Alamat</div>
    <div class="empty-sub">Tambahkan alamat untuk mempermudah pemesanan jemput antar.</div>
    <a href="{{ route('customer.addresses.create') }}" class="btn-cta">
      + Tambah Alamat Baru
    </a>
  </div>

  @endif

</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', function(){
  const cards = document.querySelectorAll('.js-card');
  if(cards.length && typeof gsap !== 'undefined'){
    gsap.to(cards, {
      opacity:1, y:0, duration:.45, stagger:.07, ease:'power2.out', delay:.1
    });
  }
});
</script>

</body>
</html>
