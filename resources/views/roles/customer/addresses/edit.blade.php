<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Edit Alamat – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark:#002f5c; --blue-mid:#0077b6; --blue-light:#00b4d8;
    --orange:#FF6B35; --green:#00C48C; --red:#ef4444;
    --surface:#f4f8fc; --card:#ffffff; --ink:#1a2332; --ink-mid:#3d5066;
    --ink-lt:#8899aa; --border:#ddeeff; --radius:16px; --radius-sm:10px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(90px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

.page-header{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);padding:max(env(safe-area-inset-top,0px),16px) 20px 24px;position:sticky;top:0;z-index:100;}
.header-row{display:flex;align-items:center;gap:12px;max-width:520px;margin:0 auto;}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;flex-shrink:0;}
.btn-back svg{width:18px;height:18px;}
.header-title{flex:1;font-family:'Fredoka One',cursive;font-size:1.3rem;color:white;}
.header-sub{font-size:.68rem;font-weight:800;color:rgba(255,255,255,.65);margin-top:2px;letter-spacing:.5px;}

.page-body{max-width:520px;margin:0 auto;padding:16px;}

.section-card{background:white;border-radius:var(--radius);border:1.5px solid var(--border);margin-bottom:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,47,92,.05);opacity:0;transform:translateY(18px);}
.section-head{display:flex;align-items:center;gap:10px;padding:13px 16px;border-bottom:1.5px solid var(--border);background:linear-gradient(90deg,rgba(0,119,182,.04) 0%,transparent 100%);}
.section-num{width:28px;height:28px;border-radius:50%;background:var(--blue-mid);color:white;font-family:'Fredoka One',cursive;font-size:.88rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.section-title{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--blue-dark);}
.section-body{padding:14px 16px;display:flex;flex-direction:column;gap:12px;}

.field-label{font-size:.72rem;font-weight:900;color:var(--ink-mid);letter-spacing:.5px;text-transform:uppercase;margin-bottom:5px;display:flex;align-items:center;gap:6px;}
.field-label .required{color:var(--red);}
.field-input{width:100%;padding:12px 14px;border:1.5px solid var(--border);border-radius:var(--radius-sm);font-family:'Nunito',sans-serif;font-size:.93rem;font-weight:600;color:var(--ink);background:white;outline:none;transition:border-color .2s,box-shadow .2s;appearance:none;}
.field-input:focus{border-color:var(--blue-mid);box-shadow:0 0 0 3px rgba(0,119,182,.12);}
.field-input.is-error{border-color:var(--red);box-shadow:0 0 0 3px rgba(239,68,68,.1);}
.field-input::placeholder{color:var(--ink-lt);font-weight:600;}
textarea.field-input{resize:none;min-height:80px;line-height:1.5;}
.field-error{font-size:.72rem;font-weight:800;color:var(--red);margin-top:4px;}

.zone-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;}
.zone-pill{border:2px solid var(--border);border-radius:var(--radius-sm);padding:10px 6px;text-align:center;cursor:pointer;background:white;position:relative;transition:all .2s;}
.zone-pill input[type=radio]{position:absolute;opacity:0;}
.zone-name{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--ink);display:block;}
.zone-desc{font-size:.68rem;font-weight:800;color:var(--ink-lt);display:block;margin-top:2px;}
.zone-price{font-size:.7rem;font-weight:900;color:var(--green);display:block;margin-top:2px;}
.zone-pill:has(input:checked){border-color:var(--blue-mid);background:rgba(0,119,182,.06);box-shadow:0 0 0 3px rgba(0,119,182,.12);}
.zone-pill:has(input:checked) .zone-name{color:var(--blue-mid);}

#addr-map{height:180px;border-radius:var(--radius-sm);border:1.5px solid var(--border);z-index:10;margin-bottom:4px;}
.map-hint{font-size:.7rem;font-weight:700;color:var(--ink-lt);display:flex;align-items:center;gap:5px;}

.toggle-wrap{display:flex;align-items:center;justify-content:space-between;padding:2px 0;}
.toggle-label{font-size:.85rem;font-weight:800;color:var(--ink);}
.toggle-sub{font-size:.72rem;font-weight:700;color:var(--ink-lt);margin-top:2px;}
.toggle-switch{width:48px;height:26px;border-radius:99px;background:var(--border);cursor:pointer;position:relative;transition:background .2s;flex-shrink:0;border:none;}
.toggle-switch::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:white;top:3px;left:3px;transition:transform .2s;box-shadow:0 2px 6px rgba(0,0,0,.15);}
.toggle-switch.on{background:var(--green);}
.toggle-switch.on::after{transform:translateX(22px);}

.bottom-cta{position:fixed;bottom:0;left:0;right:0;padding:12px 16px max(env(safe-area-inset-bottom,0px),16px);background:rgba(255,255,255,.96);backdrop-filter:blur(12px);border-top:1.5px solid var(--border);z-index:50;}
.cta-inner{max-width:520px;margin:0 auto;}
.btn-submit{width:100%;padding:14px;background:linear-gradient(135deg,var(--blue-mid) 0%,var(--blue-light) 100%);color:white;font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;border:none;border-radius:var(--radius-sm);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 6px 20px rgba(0,119,182,.35);transition:transform .15s;}
.btn-submit:active{transform:scale(.97);}
.btn-submit svg{width:18px;height:18px;}

.alert-error{background:#fff1f1;border:1.5px solid #fecaca;border-radius:var(--radius-sm);padding:12px 14px;font-size:.82rem;font-weight:700;color:var(--red);margin-bottom:14px;}
</style>
</head>
<body>

<header class="page-header">
    <div class="header-row">
        <a href="{{ route('customer.addresses.index') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <div class="header-title">Edit Alamat</div>
            <div class="header-sub">{{ strtoupper($address->label ?? 'ALAMAT') }}</div>
        </div>
    </div>
</header>

<div class="page-body">

    @if($errors->any())
    <div class="alert-error">
        ⚠️ Periksa kembali isian:<br>
        @foreach($errors->all() as $e) • {{ $e }}<br> @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('customer.addresses.update', $address) }}" id="addr-form">
        @csrf @method('PUT')

        {{-- SECTION 1: Info Penerima --}}
        <div class="section-card js-card">
            <div class="section-head">
                <div class="section-num">1</div>
                <div class="section-title">Info Penerima</div>
            </div>
            <div class="section-body">
                <div>
                    <div class="field-label">Label Alamat <span class="required">*</span></div>
                    <input type="text" name="label" class="field-input {{ $errors->has('label') ? 'is-error' : '' }}"
                        placeholder="Rumah, Kantor, Kos, dll."
                        value="{{ old('label', $address->label) }}" required>
                    @error('label') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <div class="field-label">Nama Penerima <span class="required">*</span></div>
                    <input type="text" name="recipient_name" class="field-input {{ $errors->has('recipient_name') ? 'is-error' : '' }}"
                        value="{{ old('recipient_name', $address->recipient_name) }}" required>
                    @error('recipient_name') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <div class="field-label">No. HP / WhatsApp</div>
                    <input type="text" name="phone" class="field-input"
                        value="{{ old('phone', $address->phone) }}" placeholder="08xxxxxxxxxx">
                </div>
            </div>
        </div>

        {{-- SECTION 2: Lokasi --}}
        <div class="section-card js-card">
            <div class="section-head">
                <div class="section-num">2</div>
                <div class="section-title">Lokasi Alamat</div>
            </div>
            <div class="section-body">
                <div>
                    <div class="field-label">Titik Lokasi di Peta (Opsional)</div>
                    <div id="addr-map"></div>
                    <div class="map-hint">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Tap atau geser pin untuk memperbarui titik
                    </div>
                    <input type="hidden" name="latitude" id="map-lat" value="{{ $address->latitude ?? '' }}">
                    <input type="hidden" name="longitude" id="map-lng" value="{{ $address->longitude ?? '' }}">
                </div>
                <div>
                    <div class="field-label">Alamat Lengkap <span class="required">*</span></div>
                    <textarea name="full_address" class="field-input {{ $errors->has('full_address') ? 'is-error' : '' }}"
                        required>{{ old('full_address', $address->full_address) }}</textarea>
                    @error('full_address') <div class="field-error">{{ $message }}</div> @enderror
                </div>
                <div>
                    <div class="field-label">Catatan Tambahan</div>
                    <input type="text" name="notes" class="field-input"
                        placeholder="Warna pagar, patokan, dll."
                        value="{{ old('notes', $address->notes) }}">
                </div>
                <div>
                    <div class="field-label">Jarak dari Laundry (km)</div>
                    <input type="number" name="distance_km" class="field-input" id="distance-input"
                        placeholder="Contoh: 3.5" step="0.1" min="0" max="50"
                        value="{{ old('distance_km', $address->distance_km) }}">
                </div>
            </div>
        </div>

        {{-- SECTION 3: Zona --}}
        <div class="section-card js-card">
            <div class="section-head">
                <div class="section-num">3</div>
                <div class="section-title">Zona Pengiriman</div>
            </div>
            <div class="section-body">
                <div>
                    <div class="field-label">Pilih Zona <span class="required">*</span></div>
                    <div class="zone-grid">
                        @foreach([['A','0–3 km','Gratis'],['B','3–7 km','Rp 5rb'],['C','> 7 km','Rp 10rb']] as [$z,$desc,$price])
                        <label class="zone-pill">
                            <input type="radio" name="zone" value="{{ $z }}"
                                {{ old('zone', $address->zone) === $z ? 'checked' : '' }}>
                            <span class="zone-name">Zona {{ $z }}</span>
                            <span class="zone-desc">{{ $desc }}</span>
                            <span class="zone-price">{{ $price }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Jadikan Alamat Utama</div>
                            <div class="toggle-sub">Otomatis dipilih saat pesan baru</div>
                        </div>
                        <button type="button" class="toggle-switch {{ old('is_primary', $address->is_primary) ? 'on' : '' }}"
                                id="toggle-primary" onclick="togglePrimary()"></button>
                        <input type="hidden" name="is_primary" id="is-primary-val"
                               value="{{ old('is_primary', $address->is_primary ? '1' : '0') }}">
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

<div class="bottom-cta">
    <div class="cta-inner">
        <button type="submit" form="addr-form" class="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v14a2 2 0 01-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
            </svg>
            Simpan Perubahan
        </button>
    </div>
</div>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
// MAP
@php
    $lat = $address->latitude ?? -1.6101;
    $lng = $address->longitude ?? 103.6131;
@endphp
const initLat = {{ $lat }};
const initLng = {{ $lng }};

document.addEventListener('DOMContentLoaded', function () {
    const map = L.map('addr-map').setView([initLat, initLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    const marker = L.marker([initLat, initLng], { draggable: true }).addTo(map);

    function updateCoords(lat, lng) {
        document.getElementById('map-lat').value = lat.toFixed(6);
        document.getElementById('map-lng').value = lng.toFixed(6);
    }
    updateCoords(initLat, initLng);

    marker.on('dragend', e => {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });
    map.on('click', e => {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    // Animate
    gsap.to('.js-card', { opacity: 1, y: 0, duration: 0.45, stagger: 0.1, ease: 'power2.out', delay: 0.1 });
    gsap.from('.page-header', { opacity: 0, y: -16, duration: 0.4, ease: 'power2.out' });
    gsap.from('.bottom-cta', { opacity: 0, y: 20, duration: 0.4, ease: 'power2.out', delay: 0.3 });
});

// Zone auto-detect
document.getElementById('distance-input')?.addEventListener('input', function() {
    const km = parseFloat(this.value) || 0;
    const zone = km > 7 ? 'C' : km > 3 ? 'B' : 'A';
    const radio = document.querySelector(`input[name="zone"][value="${zone}"]`);
    if (radio) radio.checked = true;
});

function togglePrimary() {
    const btn = document.getElementById('toggle-primary');
    const inp = document.getElementById('is-primary-val');
    const isOn = btn.classList.contains('on');
    btn.classList.toggle('on', !isOn);
    inp.value = isOn ? '0' : '1';
    gsap.from(btn, { scale: 0.9, duration: 0.2, ease: 'back.out(2)' });
}
</script>
</body>
</html>