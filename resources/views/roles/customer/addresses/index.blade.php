<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Alamat Tersimpan – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark:#002f5c; --blue-mid:#0077b6; --blue-light:#00b4d8;
    --blue-sky:#e0f4ff; --orange:#FF6B35; --green:#00C48C; --red:#ef4444;
    --surface:#f4f8fc; --card:#fff; --ink:#1a2332; --ink-mid:#3d5066;
    --ink-lt:#8899aa; --border:#ddeeff; --radius:16px; --radius-sm:12px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

/* Header */
.page-header{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);padding:max(env(safe-area-inset-top,0px),16px) 20px 28px;position:sticky;top:0;z-index:100;}
.header-row{display:flex;align-items:center;gap:12px;max-width:520px;margin:0 auto;}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;flex-shrink:0;transition:transform .2s;}
.btn-back:hover{transform:translateX(-3px);}
.btn-back svg{width:18px;height:18px;}
.header-title{flex:1;font-family:'Fredoka One',cursive;font-size:1.3rem;color:white;letter-spacing:.2px;}
.header-sub{font-size:.68rem;font-weight:800;color:rgba(255,255,255,.65);letter-spacing:.5px;margin-top:2px;}

/* Body */
.page-body{max-width:520px;margin:-12px auto 0;padding:16px;position:relative;z-index:5;}

/* Add address button */
.btn-add-address{
    display:flex;align-items:center;justify-content:center;gap:8px;
    width:100%;padding:14px;margin-bottom:14px;
    background:linear-gradient(135deg,var(--orange) 0%,#ff8c5a 100%);
    color:white;border:none;border-radius:var(--radius-sm);
    font-family:'Nunito',sans-serif;font-weight:900;font-size:.92rem;
    text-decoration:none;cursor:pointer;box-shadow:0 6px 20px rgba(255,107,53,.35);
    transition:transform .15s;
}
.btn-add-address:active{transform:scale(.97);}
.btn-add-address svg{width:18px;height:18px;}

/* Alert */
.alert-success{
    background:#e6fff6;border:1.5px solid var(--green);
    border-radius:var(--radius);padding:12px 16px;margin-bottom:14px;
    font-size:.85rem;font-weight:700;color:#047857;
    display:flex;align-items:center;gap:8px;
}
.alert-error{
    background:#fff1f1;border:1.5px solid #fecaca;
    border-radius:var(--radius);padding:12px 16px;margin-bottom:14px;
    font-size:.85rem;font-weight:700;color:var(--red);
}

/* Empty state */
.empty-state{text-align:center;padding:60px 20px;background:white;border-radius:var(--radius);border:2px dashed var(--border);margin-top:8px;}
.empty-icon{font-size:3rem;margin-bottom:12px;display:block;opacity:.5;}
.empty-title{font-family:'Fredoka One',cursive;font-size:1.05rem;color:var(--ink-mid);margin-bottom:6px;}
.empty-sub{font-size:.82rem;font-weight:700;color:var(--ink-lt);line-height:1.5;}

/* Address card */
.addr-card{
    background:white;border:1.5px solid var(--border);
    border-radius:var(--radius);margin-bottom:12px;overflow:hidden;
    box-shadow:0 2px 12px rgba(0,47,92,.05);
    opacity:0;transform:translateY(16px);
}
.addr-card.is-primary{border-color:var(--blue-mid);box-shadow:0 4px 16px rgba(0,119,182,.12);}
.addr-strip{height:4px;background:linear-gradient(90deg,var(--blue-mid),var(--blue-light));}
.addr-card.is-primary .addr-strip{background:linear-gradient(90deg,var(--orange),#ff8c5a);}
.addr-body{padding:14px 16px;}

.addr-head{display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:10px;}
.addr-label-wrap{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.addr-icon{font-size:1.05rem;}
.addr-label{font-family:'Fredoka One',cursive;font-size:1rem;color:var(--ink);letter-spacing:.2px;}
.badge-primary{
    background:var(--orange);color:white;
    border-radius:99px;padding:3px 9px;
    font-size:.6rem;font-weight:900;
    letter-spacing:.6px;text-transform:uppercase;
    box-shadow:0 2px 6px rgba(255,107,53,.3);
}

.addr-info{display:flex;flex-direction:column;gap:4px;margin-bottom:10px;}
.addr-recipient{font-size:.78rem;font-weight:800;color:var(--ink);}
.addr-phone{font-size:.74rem;font-weight:700;color:var(--ink-lt);}
.addr-text{font-size:.78rem;font-weight:600;color:var(--ink-mid);line-height:1.5;margin-top:4px;word-break:break-word;}
.addr-zone{
    display:inline-flex;align-items:center;gap:5px;
    background:var(--blue-sky);color:var(--blue-mid);
    border-radius:99px;padding:3px 10px;
    font-size:.7rem;font-weight:900;
    margin-top:6px;align-self:flex-start;
}

/* Action buttons */
.addr-actions{
    display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;
    padding:10px 16px 14px;border-top:1px solid #f1f5f9;
    background:#fafbfd;
}
.addr-btn{
    padding:9px 8px;border-radius:10px;border:none;
    font-family:'Nunito',sans-serif;font-weight:900;font-size:.72rem;
    text-decoration:none;text-align:center;cursor:pointer;
    display:flex;align-items:center;justify-content:center;gap:4px;
    transition:transform .15s;
}
.addr-btn:active{transform:scale(.95);}
.addr-btn--edit{background:white;color:var(--blue-mid);border:1.5px solid var(--blue-sky);}
.addr-btn--edit:hover{background:var(--blue-sky);}
.addr-btn--primary{background:var(--blue-sky);color:var(--blue-mid);border:1.5px solid rgba(0,119,182,.2);}
.addr-btn--primary:hover{background:#cce8f7;}
.addr-btn--delete{background:#fff1f1;color:var(--red);border:1.5px solid #fecaca;}
.addr-btn--delete:hover{background:#fecaca;}
.addr-btn--delete svg{width:13px;height:13px;}
.addr-btn-form{display:contents;}

/* Disabled state for set-primary on already primary */
.addr-card.is-primary .addr-actions{grid-template-columns:1fr 1fr;}
.addr-card.is-primary .btn-set-primary{display:none;}

/* Responsive */
@media(min-width:768px){
    .page-header .header-row{max-width:680px;}
    .page-body{max-width:680px;padding:20px 32px;}
    .addr-card{border-radius:20px;}
}
@media(min-width:1024px){
    .page-header .header-row{max-width:720px;}
    .page-body{max-width:720px;padding:24px 40px;}
    .addr-card{border-radius:22px;}
}
@media(min-width:1280px){
    .page-header .header-row{max-width:800px;}
    .page-body{max-width:800px;}
}
</style>
</head>
<body>

{{-- HEADER --}}
<header class="page-header">
    <div class="header-row">
        @php
            $role = auth()->user()->role;
            $dashboardRoute = $role === 'admin' ? 'dashboard.admin' : ($role === 'driver' ? 'dashboard.driver' : 'customer.dashboard');
        @endphp
        <a href="{{ route($dashboardRoute) }}" class="btn-back" data-smart-back aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <div class="header-title">Alamat Tersimpan</div>
            <div class="header-sub">KELOLA ALAMAT JEMPUT-ANTAR</div>
        </div>
    </div>
</header>

<div class="page-body">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    @endif

    {{-- Add new address button --}}
    <a href="{{ route('customer.addresses.create') }}" class="btn-add-address">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Tambah Alamat Baru
    </a>

    {{-- Address list --}}
    @forelse($alamat as $address)
    <div class="addr-card js-card {{ $address->is_primary ? 'is-primary' : '' }}">
        <div class="addr-strip"></div>
        <div class="addr-body">
            <div class="addr-head">
                <div class="addr-label-wrap">
                    <span class="addr-icon">
                        @php
                            $iconMap = ['rumah'=>'🏠','kantor'=>'🏢','kos'=>'🏘️','sekolah'=>'🎓','kampus'=>'🎓'];
                            $key = strtolower($address->label ?? '');
                            $icon = $iconMap[$key] ?? '📍';
                        @endphp
                        {{ $icon }}
                    </span>
                    <span class="addr-label">{{ $address->label }}</span>
                </div>
                @if($address->is_primary)
                    <span class="badge-primary">Utama</span>
                @endif
            </div>

            <div class="addr-info">
                <div class="addr-recipient">{{ $address->recipient_name }}</div>
                @if($address->phone)
                    <div class="addr-phone">📱 {{ $address->phone }}</div>
                @endif
                <div class="addr-text">{{ $address->full_address }}</div>
                @if($address->notes)
                    <div class="addr-text" style="font-style:italic;font-size:.72rem;">📝 {{ $address->notes }}</div>
                @endif
                @if($address->zone)
                    <span class="addr-zone">Zona {{ $address->zone }}{{ $address->distance_km ? ' · '.number_format($address->distance_km,2).' km' : '' }}</span>
                @endif
            </div>
        </div>

        <div class="addr-actions">
            <a href="{{ route('customer.addresses.edit', $address) }}" class="addr-btn addr-btn--edit">
                ✏️ Ubah
            </a>

            @if(!$address->is_primary)
            <form method="POST" action="{{ route('customer.addresses.set-primary', $address) }}" class="addr-btn-form btn-set-primary">
                @csrf
                @method('PATCH')
                <button type="submit" class="addr-btn addr-btn--primary">
                    ⭐ Jadikan Utama
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}" class="addr-btn-form"
                  data-confirm="Alamat '{{ $address->label }}' akan dihapus permanen dan tidak bisa dikembalikan."
                  data-confirm-title="Hapus Alamat?"
                  data-confirm-ok="Ya, Hapus"
                  data-confirm-danger="true">
                @csrf
                @method('DELETE')
                <button type="submit" class="addr-btn addr-btn--delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-2 14a2 2 0 01-2 2H9a2 2 0 01-2-2L5 6M10 11v6M14 11v6"/>
                        <path d="M9 6V4a2 2 0 012-2h2a2 2 0 012 2v2"/>
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <span class="empty-icon">📍</span>
        <div class="empty-title">Belum Ada Alamat</div>
        <div class="empty-sub">Tambahkan alamat pertamamu untuk<br>memudahkan saat membuat pesanan.</div>
    </div>
    @endforelse

</div>

{{-- BOTTOM NAVBAR (also provides modal + smart back helpers) --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Header entrance
    gsap.from('.page-header', { opacity: 0, y: -20, duration: 0.4, ease: 'power2.out' });

    // Add button
    gsap.from('.btn-add-address', { opacity: 0, y: 15, duration: 0.4, delay: 0.15, ease: 'power2.out' });

    // Address cards stagger
    gsap.to('.js-card', {
        opacity: 1, y: 0,
        duration: 0.45, stagger: 0.08,
        ease: 'power2.out', delay: 0.2
    });

    // Empty state
    const empty = document.querySelector('.empty-state');
    if (empty) {
        gsap.from(empty, { opacity: 0, scale: 0.95, duration: 0.5, ease: 'back.out(1.5)', delay: 0.2 });
    }

    // Alert flash
    document.querySelectorAll('.alert-success, .alert-error').forEach(el => {
        gsap.from(el, { opacity: 0, y: -10, scale: 0.95, duration: 0.4, ease: 'back.out(1.5)' });
    });

    // Nav touch feedback
    document.querySelectorAll('.customer-nav__item, .customer-nav__fab').forEach(el => {
        el.addEventListener('touchstart', function() { gsap.to(this, {scale:.92, duration:.09, ease:'power2.out'}); }, {passive:true});
        el.addEventListener('touchend', function() { gsap.to(this, {scale:1, duration:.22, ease:'back.out(2.5)'}); }, {passive:true});
    });
});
</script>

</body>
</html>
