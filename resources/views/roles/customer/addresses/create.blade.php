<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Tambah Alamat – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
:root {
    --blue-dark:  #002f5c;
    --blue-mid:   #0077b6;
    --blue-light: #00b4d8;
    --blue-sky:   #e0f4ff;
    --orange:     #FF6B35;
    --green:      #00C48C;
    --surface:    #f4f8fc;
    --card:       #ffffff;
    --ink:        #1a2332;
    --ink-mid:    #3d5066;
    --ink-lt:     #8899aa;
    --border:     #ddeeff;
    --radius:     16px;
    --radius-sm:  10px;
    --red:        #ef4444;
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
body {
    font-family: 'Nunito', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(90px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* ── HEADER ──────────────────────────── */
.page-header {
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
    padding: max(env(safe-area-inset-top,0px), 16px) 20px 24px;
    position: sticky; top: 0; z-index: 100;
}
.header-row { display:flex; align-items:center; gap:12px; max-width:520px; margin:0 auto; }
.btn-back {
    width:38px; height:38px; border-radius:50%;
    background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.25);
    display:flex; align-items:center; justify-content:center;
    color:white; text-decoration:none; flex-shrink:0;
}
.btn-back svg { width:18px; height:18px; }
.header-title { flex:1; font-family:'Fredoka One',cursive; font-size:1.3rem; color:white; }
.header-sub { font-size:.68rem; font-weight:800; color:rgba(255,255,255,.65); margin-top:2px; letter-spacing:.5px; }

/* ── BODY ────────────────────────────── */
.page-body { max-width:520px; margin:0 auto; padding:16px; }

/* ── SECTION CARD ────────────────────── */
.section-card {
    background:white;
    border-radius:var(--radius);
    border:1.5px solid var(--border);
    margin-bottom:14px;
    overflow:hidden;
    box-shadow:0 2px 12px rgba(0,47,92,.05);
    opacity:0; transform:translateY(18px);
}
.section-head {
    display:flex; align-items:center; gap:10px;
    padding:13px 16px;
    border-bottom:1.5px solid var(--border);
    background:linear-gradient(90deg, rgba(0,119,182,.04) 0%, transparent 100%);
}
.section-num {
    width:28px; height:28px; border-radius:50%;
    background:var(--blue-mid); color:white;
    font-family:'Fredoka One',cursive; font-size:.88rem;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}
.section-title { font-family:'Fredoka One',cursive; font-size:.95rem; color:var(--blue-dark); }
.section-body { padding:14px 16px; display:flex; flex-direction:column; gap:12px; }

/* ── FORM ELEMENTS ───────────────────── */
.field-label {
    font-size:.72rem; font-weight:900; color:var(--ink-mid);
    letter-spacing:.5px; text-transform:uppercase; margin-bottom:5px;
    display:flex; align-items:center; gap:6px;
}
.field-label .required { color:var(--red); }
.field-input {
    width:100%; padding:12px 14px;
    border:1.5px solid var(--border); border-radius:var(--radius-sm);
    font-family:'Nunito',sans-serif; font-size:.93rem; font-weight:600;
    color:var(--ink); background:white; outline:none;
    transition:border-color .2s, box-shadow .2s;
    appearance:none;
}
.field-input:focus {
    border-color:var(--blue-mid);
    box-shadow:0 0 0 3px rgba(0,119,182,.12);
}
.field-input.is-error { border-color:var(--red); box-shadow:0 0 0 3px rgba(239,68,68,.1); }
.field-input::placeholder { color:var(--ink-lt); font-weight:600; }
textarea.field-input { resize:none; min-height:80px; line-height:1.5; }
.field-error { font-size:.72rem; font-weight:800; color:var(--red); margin-top:4px; }

/* ── ZONE PILLS ──────────────────────── */
.zone-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.zone-pill {
    border:2px solid var(--border); border-radius:var(--radius-sm);
    padding:10px 6px; text-align:center; cursor:pointer;
    background:white; position:relative; transition:all .2s;
}
.zone-pill input[type=radio] { position:absolute; opacity:0; }
.zone-name { font-family:'Fredoka One',cursive; font-size:.95rem; color:var(--ink); display:block; }
.zone-desc { font-size:.68rem; font-weight:800; color:var(--ink-lt); display:block; margin-top:2px; }
.zone-price { font-size:.7rem; font-weight:900; color:var(--green); display:block; margin-top:2px; }
.zone-pill:has(input:checked) {
    border-color:var(--blue-mid);
    background:rgba(0,119,182,.06);
    box-shadow:0 0 0 3px rgba(0,119,182,.12);
}
.zone-pill:has(input:checked) .zone-name { color:var(--blue-mid); }

/* ── MAP ─────────────────────────────── */
#addr-map {
    height:180px; border-radius:var(--radius-sm);
    border:1.5px solid var(--border); z-index:10;
    margin-bottom:4px;
}
.map-hint {
    font-size:.7rem; font-weight:700; color:var(--ink-lt);
    display:flex; align-items:center; gap:5px;
}
.map-hint svg { width:12px; height:12px; }

/* ── TOGGLE ──────────────────────────── */
.toggle-wrap {
    display:flex; align-items:center; justify-content:space-between;
    padding:2px 0;
}
.toggle-label { font-size:.85rem; font-weight:800; color:var(--ink); }
.toggle-sub   { font-size:.72rem; font-weight:700; color:var(--ink-lt); margin-top:2px; }
.toggle-switch {
    width:48px; height:26px; border-radius:99px;
    background:var(--border); cursor:pointer;
    position:relative; transition:background .2s; flex-shrink:0;
    border:none;
}
.toggle-switch::after {
    content:''; position:absolute;
    width:20px; height:20px; border-radius:50%;
    background:white; top:3px; left:3px;
    transition:transform .2s;
    box-shadow:0 2px 6px rgba(0,0,0,.15);
}
.toggle-switch.on { background:var(--green); }
.toggle-switch.on::after { transform:translateX(22px); }

/* ── SUBMIT BUTTON ───────────────────── */
.bottom-cta {
    position:fixed; bottom:0; left:0; right:0;
    padding:12px 16px max(env(safe-area-inset-bottom,0px),16px);
    background:rgba(255,255,255,.96);
    backdrop-filter:blur(12px);
    border-top:1.5px solid var(--border);
    z-index:50;
}
.cta-inner { max-width:520px; margin:0 auto; }
.btn-submit {
    width:100%; padding:14px;
    background:linear-gradient(135deg, var(--blue-mid) 0%, var(--blue-light) 100%);
    color:white; font-family:'Nunito',sans-serif; font-weight:900;
    font-size:.95rem; border:none; border-radius:var(--radius-sm);
    cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
    box-shadow:0 6px 20px rgba(0,119,182,.35);
    transition:transform .15s;
}
.btn-submit:active { transform:scale(.97); }
.btn-submit svg { width:18px; height:18px; }

/* ── ALERT ERROR ─────────────────────── */
.alert-error {
    background:#fff1f1; border:1.5px solid #fecaca;
    border-radius:var(--radius-sm); padding:12px 14px;
    font-size:.82rem; font-weight:700; color:var(--red);
    margin-bottom:14px;
}
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
            <div class="header-title">Tambah Alamat</div>
            <div class="header-sub">ALAMAT JEMPUT & ANTAR</div>
        </div>
    </div>
</header>

<div class="page-body">

    @if($errors->any())
    <div class="alert-error">
        ⚠️ Periksa kembali isian berikut:<br>
        @foreach($errors->all() as $e) • {{ $e }}<br> @endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('customer.addresses.store') }}" id="addr-form">
        @csrf

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
                        value="{{ old('label', 'Rumah') }}" required>
                    @error('label') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Nama Penerima <span class="required">*</span></div>
                    <input type="text" name="recipient_name" class="field-input {{ $errors->has('recipient_name') ? 'is-error' : '' }}"
                        placeholder="Nama pemilik pakaian"
                        value="{{ old('recipient_name', auth()->user()->name) }}" required>
                    @error('recipient_name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">No. HP / WhatsApp</div>
                    <input type="text" name="phone" class="field-input"
                        placeholder="08xxxxxxxxxx"
                        value="{{ old('phone', auth()->user()->phone) }}">
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
                        Tap atau geser pin untuk menentukan titik akurat
                    </div>
                    <input type="hidden" name="latitude" id="map-lat">
                    <input type="hidden" name="longitude" id="map-lng">
                </div>

                <div>
                    <div class="field-label">Alamat Lengkap <span class="required">*</span></div>
                    <textarea name="full_address" class="field-input {{ $errors->has('full_address') ? 'is-error' : '' }}"
                        placeholder="Nama jalan, nomor rumah, RT/RW, Kelurahan, Kecamatan..."
                        required>{{ old('full_address') }}</textarea>
                    @error('full_address') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Catatan Tambahan</div>
                    <input type="text" name="notes" class="field-input"
                        placeholder="Warna pagar, patokan, titipkan di mana, dll."
                        value="{{ old('notes') }}">
                </div>

                <div>
                    <div class="field-label">Jarak dari Laundry (km)</div>
                    <input type="number" name="distance_km" class="field-input"
                        placeholder="Contoh: 3.5" step="0.1" min="0" max="50"
                        value="{{ old('distance_km') }}"
                        id="distance-input">
                    <div style="font-size:.7rem;font-weight:700;color:var(--ink-lt);margin-top:4px;">
                        💡 Jarak > 15 km = ada tarif jemput-antar tambahan
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION 3: Zona & Pengaturan --}}
        <div class="section-card js-card">
            <div class="section-head">
                <div class="section-num">3</div>
                <div class="section-title">Zona Pengiriman</div>
            </div>
            <div class="section-body">
                <div>
                    <div class="field-label">Pilih Zona <span class="required">*</span></div>
                    <div class="zone-grid">
                        @foreach([
                            ['A', '0–3 km', 'Gratis'],
                            ['B', '3–7 km', 'Rp 5rb'],
                            ['C', '> 7 km', 'Rp 10rb'],
                        ] as [$z, $desc, $price])
                        <label class="zone-pill">
                            <input type="radio" name="zone" value="{{ $z }}"
                                {{ old('zone', 'A') === $z ? 'checked' : '' }}>
                            <span class="zone-name">Zona {{ $z }}</span>
                            <span class="zone-desc">{{ $desc }}</span>
                            <span class="zone-price">{{ $price }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('zone') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="toggle-wrap">
                        <div>
                            <div class="toggle-label">Jadikan Alamat Utama</div>
                            <div class="toggle-sub">Otomatis dipilih saat pesan baru</div>
                        </div>
                        <button type="button" class="toggle-switch {{ old('is_primary') ? 'on' : '' }}"
                                id="toggle-primary" onclick="togglePrimary()"></button>
                        <input type="hidden" name="is_primary" id="is-primary-val"
                               value="{{ old('is_primary', '0') }}">
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

{{-- SUBMIT BUTTON --}}
<div class="bottom-cta">
    <div class="cta-inner">
        <button type="submit" form="addr-form" class="btn-submit">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            Simpan Alamat
        </button>
    </div>
</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
/* ── MAP PICKER ──────────────────────── */
let map, marker;
const defaultPos = [-1.6101, 103.6131]; // Jambi

function initMap() {
    map = L.map('addr-map', { zoomControl: true }).setView(defaultPos, 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    marker = L.marker(defaultPos, { draggable: true }).addTo(map);

    function updateCoords(lat, lng) {
        document.getElementById('map-lat').value = lat.toFixed(6);
        document.getElementById('map-lng').value = lng.toFixed(6);
    }

    marker.on('dragend', e => {
        const pos = e.target.getLatLng();
        updateCoords(pos.lat, pos.lng);
    });

    map.on('click', e => {
        marker.setLatLng(e.latlng);
        updateCoords(e.latlng.lat, e.latlng.lng);
    });

    // Coba GPS
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(pos => {
            const loc = [pos.coords.latitude, pos.coords.longitude];
            map.setView(loc, 16);
            marker.setLatLng(loc);
            updateCoords(loc[0], loc[1]);
        });
    }
}

/* ── ZONE AUTO-DETECT dari JARAK ─────── */
document.getElementById('distance-input')?.addEventListener('input', function() {
    const km = parseFloat(this.value) || 0;
    let zone = 'A';
    if (km > 7)  zone = 'C';
    else if (km > 3) zone = 'B';

    const radio = document.querySelector(`input[name="zone"][value="${zone}"]`);
    if (radio) radio.checked = true;
});

/* ── TOGGLE PRIMARY ──────────────────── */
function togglePrimary() {
    const btn = document.getElementById('toggle-primary');
    const inp = document.getElementById('is-primary-val');
    if (!btn || !inp) return;

    const isOn = btn.classList.contains('on');
    btn.classList.toggle('on', !isOn);
    inp.value = isOn ? '0' : '1';

    if (typeof gsap !== 'undefined') {
        gsap.from(btn, { scale: 0.9, duration: 0.2, ease: 'back.out(2)' });
    }
}

/* ── ANIMATIONS ──────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    initMap();

    if (typeof gsap === 'undefined') return;

    const animate = (selector, options, useFromTo = false) => {
        const els = document.querySelectorAll(selector);
        if (els.length === 0) return;

        if (useFromTo) {
            gsap.to(els, options);
        } else {
            gsap.from(els, options);
        }
    };

    gsap.to('.js-card', {
        opacity: 1, y: 0, duration: 0.45,
        stagger: 0.1, ease: 'power2.out', delay: 0.1,
    });

    animate('.page-header', { opacity: 0, y: -16, duration: 0.4, ease: 'power2.out' });
    animate('.bottom-cta', { opacity: 0, y: 20, duration: 0.4, ease: 'power2.out', delay: 0.3 });
});
</script>
</body>
</html>