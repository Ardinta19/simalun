<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Alamat Saya – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark:  #002f5c;
    --blue-mid:   #0077b6;
    --blue-light: #00b4d8;
    --blue-sky:   #e0f4ff;
    --orange:     #FF6B35;
    --orange-lt:  #fff3ee;
    --green:      #00C48C;
    --green-lt:   #e6fff6;
    --red:        #ef4444;
    --red-lt:     #fff1f1;
    --surface:    #f4f8fc;
    --card:       #ffffff;
    --ink:        #1a2332;
    --ink-mid:    #3d5066;
    --ink-lt:     #8899aa;
    --border:     #ddeeff;
    --radius:     20px;
    --radius-sm:  12px;
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
body {
    font-family: 'Nunito', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* ── HEADER ──────────────────────────── */
.page-header {
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
    padding: max(env(safe-area-inset-top, 0px), 16px) 20px 28px;
    position: sticky; top: 0; z-index: 100;
}
.header-row {
    display: flex; align-items: center; gap: 12px;
    max-width: 520px; margin: 0 auto;
}
.btn-back {
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center;
    color: white; text-decoration: none; flex-shrink: 0;
}
.btn-back svg { width: 18px; height: 18px; }
.header-title {
    flex: 1; font-family: 'Fredoka One', cursive;
    font-size: 1.3rem; color: white;
}
.header-sub {
    font-size: 0.68rem; font-weight: 800;
    color: rgba(255,255,255,0.65); margin-top: 2px; letter-spacing: 0.5px;
}
.btn-add {
    width: 38px; height: 38px; border-radius: 50%;
    background: var(--orange);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(255,107,53,0.4);
    transition: transform 0.15s;
}
.btn-add:active { transform: scale(0.92); }
.btn-add svg { width: 18px; height: 18px; color: white; }

/* ── BODY ────────────────────────────── */
.page-body { max-width: 520px; margin: 0 auto; padding: 16px; }

/* ── ALERT ───────────────────────────── */
.alert {
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    font-size: 0.82rem;
    font-weight: 700;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 8px;
}
.alert-success { background: var(--green-lt); color: #065f46; border: 1.5px solid rgba(0,196,140,0.25); }
.alert-error { background: var(--red-lt); color: #b91c1c; border: 1.5px solid #fecaca; }

/* ── ADDRESS CARD ────────────────────── */
.addr-card {
    background: white;
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 2px 12px rgba(0,47,92,0.05);
    position: relative;
    opacity: 0; transform: translateY(16px);
    transition: border-color 0.2s;
}
.addr-card.is-primary { border-color: var(--green); }
.addr-card.is-primary::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0;
    height: 3px; background: var(--green);
    border-radius: var(--radius) var(--radius) 0 0;
}

.addr-top {
    display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px;
}
.addr-icon {
    width: 40px; height: 40px; border-radius: 12px;
    background: var(--blue-sky);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.addr-info { flex: 1; min-width: 0; }
.addr-label {
    font-family: 'Fredoka One', cursive;
    font-size: 0.95rem; color: var(--ink);
    display: flex; align-items: center; gap: 6px;
}
.primary-badge {
    background: var(--green-lt);
    color: #065f46;
    font-size: 0.6rem;
    font-weight: 900;
    padding: 2px 7px;
    border-radius: 99px;
    border: 1px solid rgba(0,196,140,0.2);
    font-family: 'Nunito', sans-serif;
    letter-spacing: 0.3px;
}
.addr-recipient {
    font-size: 0.78rem; font-weight: 700;
    color: var(--ink-mid); margin-top: 2px;
}
.addr-full {
    font-size: 0.8rem; font-weight: 600;
    color: var(--ink-mid); line-height: 1.4;
    margin-top: 4px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.addr-meta {
    display: flex; gap: 12px; margin-top: 8px;
    flex-wrap: wrap;
}
.addr-meta-item {
    font-size: 0.7rem; font-weight: 800;
    color: var(--ink-lt);
    display: flex; align-items: center; gap: 4px;
}

/* Actions */
.addr-actions {
    display: flex; gap: 8px; margin-top: 12px;
    padding-top: 12px; border-top: 1px solid var(--border);
}
.addr-btn {
    flex: 1; padding: 9px;
    border-radius: var(--radius-sm);
    font-family: 'Nunito', sans-serif;
    font-size: 0.75rem; font-weight: 900;
    text-align: center; text-decoration: none;
    cursor: pointer; border: none;
    transition: transform 0.15s;
}
.addr-btn:active { transform: scale(0.96); }
.addr-btn.edit {
    background: var(--blue-sky); color: var(--blue-mid);
}
.addr-btn.primary {
    background: var(--green-lt); color: #065f46;
}
.addr-btn.delete {
    background: var(--red-lt); color: var(--red);
}

/* ── EMPTY STATE ─────────────────────── */
.empty-state {
    text-align: center; padding: 60px 20px;
}
.empty-icon { font-size: 3.5rem; margin-bottom: 16px; display: block; opacity: 0.6; }
.empty-title {
    font-family: 'Fredoka One', cursive;
    font-size: 1.1rem; color: var(--ink-mid); margin-bottom: 8px;
}
.empty-sub {
    font-size: 0.82rem; font-weight: 700;
    color: var(--ink-lt); margin-bottom: 20px; line-height: 1.5;
}
.btn-add-big {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--orange); color: white;
    padding: 12px 24px; border-radius: 99px;
    font-weight: 900; font-size: 0.88rem;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(255,107,53,0.35);
    transition: transform 0.15s;
}
.btn-add-big:active { transform: scale(0.96); }
</style>
</head>
<body>

{{-- HEADER --}}
<header class="page-header">
    <div class="header-row">
        <a href="{{ route('customer.dashboard') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <div class="header-title">Alamat Saya</div>
            <div class="header-sub">KELOLA ALAMAT JEMPUT & ANTAR</div>
        </div>
        <a href="{{ route('customer.addresses.create') }}" class="btn-add" title="Tambah Alamat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
        </a>
    </div>
</header>

{{-- BODY --}}
<div class="page-body">

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="alert alert-success">
        <span>✓</span> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-error">
        <span>!</span> {{ session('error') }}
    </div>
    @endif

    @if(isset($alamat) && $alamat->count() > 0)
        @foreach($alamat as $address)
        <div class="addr-card js-card {{ $address->is_primary ? 'is-primary' : '' }}">
            <div class="addr-top">
                <div class="addr-icon">
                    @php
                        $icons = ['Rumah'=>'🏠','Kantor'=>'🏢','Kos'=>'🏘️'];
                        $icon = $icons[$address->label] ?? '📍';
                    @endphp
                    {{ $icon }}
                </div>
                <div class="addr-info">
                    <div class="addr-label">
                        {{ $address->label }}
                        @if($address->is_primary)
                            <span class="primary-badge">UTAMA</span>
                        @endif
                    </div>
                    <div class="addr-recipient">{{ $address->recipient_name }} @if($address->phone)· {{ $address->phone }}@endif</div>
                    <div class="addr-full">{{ $address->full_address }}</div>
                    <div class="addr-meta">
                        @if($address->zone)
                        <span class="addr-meta-item">🗺️ Zona {{ $address->zone }}</span>
                        @endif
                        @if($address->distance_km)
                        <span class="addr-meta-item">📏 {{ $address->distance_km }} km</span>
                        @endif
                        @if($address->notes)
                        <span class="addr-meta-item">📝 {{ Str::limit($address->notes, 20) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="addr-actions">
                <a href="{{ route('customer.addresses.edit', $address) }}" class="addr-btn edit">
                    ✏️ Edit
                </a>
                @if(!$address->is_primary)
                <form method="POST" action="{{ route('customer.addresses.set-primary', $address) }}" style="flex:1;display:flex;">
                    @csrf @method('PATCH')
                    <button type="submit" class="addr-btn primary" style="width:100%;">
                        ⭐ Utamakan
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}" style="flex:1;display:flex;"
                      onsubmit="return confirm('Hapus alamat ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="addr-btn delete" style="width:100%;">
                        🗑️ Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    @else
        {{-- Empty State --}}
        <div class="empty-state">
            <span class="empty-icon">📍</span>
            <div class="empty-title">Belum Ada Alamat</div>
            <div class="empty-sub">Tambahkan alamat jemput-antar agar pesanan lebih cepat diproses.</div>
            <a href="{{ route('customer.addresses.create') }}" class="btn-add-big">
                ➕ Tambah Alamat Baru
            </a>
        </div>
    @endif
</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.js-card');
    gsap.to(cards, {
        opacity: 1, y: 0,
        duration: 0.45, stagger: 0.08,
        ease: 'power2.out', delay: 0.1
    });

    gsap.from('.page-header', {
        opacity: 0, y: -16,
        duration: 0.4, ease: 'power2.out'
    });
});
</script>
</body>
</html>
