<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Buat Pesanan – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
:root {
  --blue-dark:  #002f5c;
  --blue-mid:   #0077b6;
  --blue-light: #00b4d8;
  --blue-sky:   #e0f4ff;
  --orange:     #FF6B35;
  --orange-lt:  #fff3ee;
  --green:      #00C48C;
  --white:      #ffffff;
  --ink:        #1a2332;
  --ink-mid:    #3d5066;
  --ink-lt:     #8899aa;
  --surface:    #f5f9ff;
  --border:     #ddeeff;
  --radius:     16px;
  --radius-sm:  10px;
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html { scroll-behavior:smooth; }
body { font-family:'Nunito',sans-serif; background:var(--surface); color:var(--ink); min-height:100vh; }

/* ── TOP HEADER ── */
.top-header {
  background:linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
  padding:0;
  position:sticky; top:0; z-index:100;
  box-shadow:0 2px 20px rgba(0,47,92,.3);
}
.header-inner {
  display:flex; align-items:center; gap:.75rem;
  padding:clamp(14px,4vw,20px) clamp(16px,5vw,24px);
  max-width:520px; margin:0 auto;
}
.btn-back {
  width:38px; height:38px; border-radius:50%;
  background:rgba(255,255,255,.15);
  border:1.5px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  cursor:pointer; text-decoration:none;
  transition:background .2s;
  flex-shrink:0;
}
.btn-back:hover { background:rgba(255,255,255,.25); }
.btn-back svg { width:18px; height:18px; color:#fff; }
.header-title { flex:1; }
.header-title h1 {
  font-family:'Fredoka One',cursive;
  font-size:clamp(1.1rem,4.5vw,1.4rem);
  color:#fff; line-height:1;
  text-shadow:0 2px 8px rgba(0,0,0,.2);
}
.header-title p { font-size:.72rem; color:rgba(255,255,255,.65); font-weight:600; margin-top:2px; letter-spacing:.5px; }

/* Step progress in header */
.step-chips {
  display:flex; gap:5px; align-items:center;
}
.step-chip {
  width:26px; height:6px; border-radius:99px;
  background:rgba(255,255,255,.25);
  transition:background .35s, width .35s;
}
.step-chip.done { background:var(--green); }
.step-chip.active { background:#fff; width:36px; }

/* ── SCROLL BODY ── */
.page-body { max-width:520px; margin:0 auto; padding:20px 16px 120px; }

/* ── SECTION CARD ── */
.section-card {
  background:#fff;
  border-radius:var(--radius);
  border:1.5px solid var(--border);
  margin-bottom:14px;
  overflow:hidden;
  box-shadow:0 2px 12px rgba(0,47,92,.06);
  opacity:0; transform:translateY(18px);
  animation:slideUp .5s ease forwards;
}
.section-card:nth-child(1){ animation-delay:.05s }
.section-card:nth-child(2){ animation-delay:.12s }
.section-card:nth-child(3){ animation-delay:.19s }
.section-card:nth-child(4){ animation-delay:.26s }
@keyframes slideUp { to { opacity:1; transform:translateY(0); } }

.section-head {
  display:flex; align-items:center; gap:.7rem;
  padding:14px 16px 12px;
  border-bottom:1.5px solid var(--border);
  background:linear-gradient(90deg, rgba(0,119,182,.04) 0%, transparent 100%);
}
.section-num {
  width:28px; height:28px; border-radius:50%;
  background:var(--blue-mid); color:#fff;
  font-family:'Fredoka One',cursive; font-size:.9rem;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
}
.section-head h2 {
  font-family:'Fredoka One',cursive;
  font-size:1rem; color:var(--blue-dark); letter-spacing:.2px;
}
.section-body { padding:14px 16px; display:flex; flex-direction:column; gap:12px; }

/* ── FORM ELEMENTS ── */
.field-label {
  font-size:.75rem; font-weight:800; color:var(--ink-mid);
  letter-spacing:.5px; text-transform:uppercase; margin-bottom:5px;
}
.field-input {
  width:100%; padding:12px 14px;
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  font-family:'Nunito',sans-serif; font-size:.95rem; font-weight:600;
  color:var(--ink); background:#fff;
  transition:border-color .2s, box-shadow .2s;
  outline:none; appearance:none;
}
.field-input:focus {
  border-color:var(--blue-mid);
  box-shadow:0 0 0 3px rgba(0,119,182,.12);
}
.field-input.addr-selected {
  border-color:var(--green);
  box-shadow:0 0 0 3px rgba(0,196,140,.14);
}
.addr-hint {
  margin-top:6px;
  font-size:.74rem;
  font-weight:800;
  color:#0f766e;
  display:none;
}
.addr-hint.show { display:block; }
.addr-head {
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:8px;
  margin-bottom:5px;
}
.addr-link {
  font-size:.72rem;
  font-weight:900;
  color:var(--blue-mid);
  text-decoration:none;
}
.addr-link:hover { text-decoration:underline; }
.manual-locked {
  background:#f8fafc;
  color:#64748b;
}
.manual-note {
  margin-top:6px;
  font-size:.73rem;
  font-weight:800;
  color:#64748b;
  display:none;
}
.manual-note.show { display:block; }
.field-input::placeholder { color:var(--ink-lt); font-weight:600; }
textarea.field-input { resize:none; min-height:80px; line-height:1.5; }

/* ── ZONE PILLS ── */
.zone-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.zone-pill {
  border:2px solid var(--border); border-radius:var(--radius-sm);
  padding:10px 6px; text-align:center; cursor:pointer;
  transition:all .2s; background:#fff; position:relative;
}
.zone-pill input[type=radio] { position:absolute; opacity:0; }
.zone-pill .zone-name { font-family:'Fredoka One',cursive; font-size:1rem; color:var(--ink); display:block; }
.zone-pill .zone-price { font-size:.72rem; font-weight:800; color:var(--ink-lt); display:block; margin-top:2px; }
.zone-pill:has(input:checked) {
  border-color:var(--blue-mid);
  background:rgba(0,119,182,.06);
  box-shadow:0 0 0 3px rgba(0,119,182,.12);
}
.zone-pill:has(input:checked) .zone-name { color:var(--blue-mid); }
.zone-pill:has(input:checked) .zone-price { color:var(--blue-mid); }

/* ── SERVICE CARDS ── */
.service-list { display:flex; flex-direction:column; gap:9px; }
.service-card {
  border:2px solid var(--border); border-radius:var(--radius-sm);
  padding:12px 14px; cursor:pointer;
  transition:all .22s; background:#fff;
  display:flex; align-items:center; gap:12px; position:relative;
}
.service-card input[type=radio] { position:absolute; opacity:0; }
.service-icon {
  width:40px; height:40px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  flex-shrink:0; font-size:1.2rem;
}
.service-info { flex:1; min-width:0; }
.service-name { font-family:'Fredoka One',cursive; font-size:.95rem; color:var(--ink); line-height:1; }
.service-eta  { font-size:.72rem; font-weight:700; color:var(--ink-lt); margin-top:2px; }
.service-price { font-family:'Fredoka One',cursive; font-size:1.05rem; color:var(--blue-mid); flex-shrink:0; }

.service-card:has(input:checked) {
  border-color:var(--orange);
  background:var(--orange-lt);
  box-shadow:0 0 0 3px rgba(255,107,53,.12);
}
.service-card:has(input:checked) .service-name { color:var(--orange); }
.service-card:has(input:checked) .service-price { color:var(--orange); }

/* service icon colors per type */
.icon-reguler  { background:#e0f4ff; }
.icon-express  { background:#fff3ee; }
.icon-prioritas{ background:#f0fff8; }

/* ── ITEM ROW (ACCESSIBLE) ── */
.item-row {
  border:1.5px solid var(--border);
  border-radius:var(--radius-sm);
  background:#fff;
  padding:10px;
  margin-top:-2px;
}
.item-row-head {
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
.item-stepper {
  display:flex;
  align-items:center;
  border:1.5px solid var(--border);
  border-radius:12px;
  overflow:hidden;
  min-width:170px;
}
.item-stepper-btn {
  width:44px;
  height:44px;
  border:none;
  background:var(--surface);
  color:var(--blue-mid);
  font-size:1.25rem;
  font-weight:900;
  cursor:pointer;
}
.item-stepper-btn:active { background:var(--blue-mid); color:#fff; }
.item-stepper-value {
  width:70px;
  text-align:center;
  border-left:1.5px solid var(--border);
  border-right:1.5px solid var(--border);
  font-family:'Fredoka One',cursive;
  font-size:1.1rem;
  color:var(--ink);
}
.item-note-toggle {
  border:none;
  background:none;
  color:var(--blue-mid);
  font-size:.75rem;
  font-weight:800;
  cursor:pointer;
  text-align:left;
  padding:0;
}
.item-note-wrap {
  margin-top:8px;
  display:none;
}
.item-note-wrap.open {
  display:block;
}

/* ── WEIGHT STEPPER ── */
.weight-stepper {
  display:flex; align-items:center; gap:0;
  border:1.5px solid var(--border); border-radius:var(--radius-sm);
  overflow:hidden; background:#fff;
}
.ws-btn {
  width:48px; height:48px; border:none;
  background:var(--surface); cursor:pointer;
  font-size:1.3rem; color:var(--blue-mid); font-weight:900;
  transition:background .15s; display:flex; align-items:center; justify-content:center;
  flex-shrink:0;
}
.ws-btn:hover { background:var(--border); }
.ws-btn:active { background:var(--blue-mid); color:#fff; }
.ws-display {
  flex:1; text-align:center;
  font-family:'Fredoka One',cursive; font-size:1.4rem; color:var(--ink);
  padding:0 8px;
  border-left:1.5px solid var(--border); border-right:1.5px solid var(--border);
}
.ws-unit { font-family:'Nunito',sans-serif; font-size:.8rem; color:var(--ink-lt); font-weight:700; }

/* ── DATE CHIPS ── */
.date-scroll { display:flex; gap:8px; overflow-x:auto; padding-bottom:4px; scrollbar-width:none; }
.date-scroll::-webkit-scrollbar { display:none; }
.date-chip {
  flex-shrink:0; width:54px; padding:8px 0;
  border:2px solid var(--border); border-radius:var(--radius-sm);
  text-align:center; cursor:pointer; background:#fff;
  transition:all .2s; position:relative;
}
.date-chip input[type=radio] { position:absolute; opacity:0; }
.date-chip .dc-day { font-size:.65rem; font-weight:800; color:var(--ink-lt); text-transform:uppercase; letter-spacing:.5px; }
.date-chip .dc-num { font-family:'Fredoka One',cursive; font-size:1.3rem; color:var(--ink); line-height:1; }
.date-chip:has(input:checked) {
  border-color:var(--blue-mid); background:var(--blue-mid);
}
.date-chip:has(input:checked) .dc-day { color:rgba(255,255,255,.75); }
.date-chip:has(input:checked) .dc-num { color:#fff; }

/* ── TIME PILLS ── */
.time-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; }
.time-pill {
  border:2px solid var(--border); border-radius:var(--radius-sm);
  padding:10px 4px; text-align:center; cursor:pointer;
  transition:all .2s; background:#fff; position:relative;
}
.time-pill input[type=radio] { position:absolute; opacity:0; }
.time-pill .tp-icon { font-size:1.2rem; display:block; }
.time-pill .tp-label { font-size:.78rem; font-weight:800; color:var(--ink); display:block; margin-top:3px; }
.time-pill:has(input:checked) {
  border-color:var(--blue-mid); background:rgba(0,119,182,.07);
  box-shadow:0 0 0 3px rgba(0,119,182,.12);
}
.time-pill:has(input:checked) .tp-label { color:var(--blue-mid); }

/* ── SUMMARY BOX ── */
.summary-box {
  background:var(--blue-sky); border:1.5px solid rgba(0,119,182,.18);
  border-radius:var(--radius-sm); padding:14px;
}
.sum-row { display:flex; justify-content:space-between; align-items:center; padding:4px 0; }
.sum-label { font-size:.82rem; font-weight:700; color:var(--ink-mid); }
.sum-value { font-size:.82rem; font-weight:800; color:var(--ink); }
.sum-discount { color:#e53e3e; }
.sum-divider { border:none; border-top:1.5px dashed rgba(0,119,182,.2); margin:8px 0; }
.sum-total-label { font-family:'Fredoka One',cursive; font-size:.95rem; color:var(--blue-dark); }
.sum-total-value { font-family:'Fredoka One',cursive; font-size:1.2rem; color:var(--blue-mid); }

/* ── FIXED BOTTOM CTA ── */
.bottom-cta {
  position:fixed; bottom:0; left:0; right:0;
  padding:12px 16px max(env(safe-area-inset-bottom,0px),16px);
  background:rgba(255,255,255,.95);
  backdrop-filter:blur(12px);
  border-top:1.5px solid var(--border);
  z-index:50;
}
.cta-inner { max-width:520px; margin:0 auto; }
.btn-order {
  width:100%; padding:15px;
  background:linear-gradient(135deg,var(--orange) 0%,#ff8c5a 100%);
  color:#fff; font-family:'Nunito',sans-serif; font-weight:900;
  font-size:1rem; border:none; border-radius:var(--radius-sm);
  cursor:pointer; letter-spacing:.3px;
  box-shadow:0 6px 20px rgba(255,107,53,.4);
  transition:transform .15s, box-shadow .15s;
  display:flex; align-items:center; justify-content:center; gap:.5rem;
  position:relative; overflow:hidden;
}
.btn-order::before {
  content:''; position:absolute; top:0; left:-100%; width:60%; height:100%;
  background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
  animation:shimmer 2.8s ease-in-out infinite;
}
@keyframes shimmer { 0%{left:-100%} 60%,100%{left:160%} }
.btn-order:active { transform:scale(.97); }
.btn-order:disabled { background:#ccc; box-shadow:none; cursor:not-allowed; }

/* ── VALIDATION ERROR ── */
.err { font-size:.75rem; color:#e53e3e; font-weight:700; margin-top:4px; }
.field-input.is-error { border-color:#e53e3e; box-shadow:0 0 0 3px rgba(229,62,62,.1); }
.alert-error {
  background:#fff5f5; border:1.5px solid #fed7d7; border-radius:var(--radius-sm);
  padding:12px 14px; margin-bottom:14px;
  font-size:.85rem; font-weight:700; color:#e53e3e;
}

/* ── RESPONSIVE ── */
@media(min-width:560px){
  .page-body { padding:24px 24px 120px; }
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="top-header">
  <div class="header-inner">
    <a href="{{ route('customer.dashboard') }}" class="btn-back">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 12H5M12 5l-7 7 7 7"/>
      </svg>
    </a>
    <div class="header-title">
      <h1>Buat Pesanan</h1>
      <p>AZKA LAUNDRY • SIMALUN</p>
    </div>
    <div class="step-chips">
      <div class="step-chip active" id="sc1"></div>
      <div class="step-chip" id="sc2"></div>
      <div class="step-chip" id="sc3"></div>
    </div>
  </div>
</div>

<div class="page-body">

  {{-- ERROR SUMMARY --}}
  @if($errors->any())
  <div class="alert-error">
    ⚠️ Periksa kembali isian berikut:<br>
    @foreach($errors->all() as $e)
      • {{ $e }}<br>
    @endforeach
  </div>
  @endif

  <form method="POST" action="{{ route('order.store') }}" id="order-form">
    @csrf

    {{-- ══ SECTION 1: ALAMAT JEMPUT ══ --}}
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">1</div>
        <h2>Alamat Jemput</h2>
      </div>
      <div class="section-body">

        @if(isset($alamatTersimpan) && $alamatTersimpan->count() > 0)
        <div>
          <div class="addr-head">
            <div class="field-label" style="margin-bottom:0">Alamat Tersimpan</div>
            <a href="{{ route('customer.addresses.index') }}" class="addr-link">Kelola Alamat</a>
          </div>
          <select name="customer_address_id" id="customer-address-id" class="field-input">
            <option value="">Gunakan input alamat manual</option>
            @foreach($alamatTersimpan as $alamat)
              <option value="{{ $alamat->id }}"
                data-full-address="{{ e($alamat->full_address) }}"
                data-zone="{{ $alamat->zone }}"
                data-notes="{{ e($alamat->notes ?? '') }}"
                {{ ((string) old('customer_address_id') === (string) $alamat->id) || (empty(old('customer_address_id')) && $loop->first) ? 'selected' : '' }}>
                {{ $alamat->label }} — {{ $alamat->recipient_name }}
              </option>
            @endforeach
          </select>
          <div class="addr-hint" id="addr-hint">Alamat otomatis diterapkan dari data tersimpan.</div>
          @error('customer_address_id') <div class="err">{{ $message }}</div> @enderror
        </div>
        @endif

        <div>
          <div class="field-label">Lokasi di Peta (Opsional)</div>
          <div id="map" style="height: 180px; border-radius: var(--radius-sm); border: 1.5px solid var(--border); margin-bottom: 10px; z-index: 10;"></div>
          <input type="hidden" name="latitude" id="lat">
          <input type="hidden" name="longitude" id="lng">
          <div class="field-label">Alamat Lengkap Jemput</div>
          <textarea name="address" id="address-input" class="field-input {{ $errors->has('address') ? 'is-error' : '' }}"
            placeholder="Jl. Merdeka No. 45, Kelurahan, Kecamatan..."
            required>{{ old('address') }}</textarea>
          <div class="manual-note" id="manual-note">Alamat manual dikunci sementara karena kamu memilih alamat tersimpan.</div>
          @error('address') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div>
          <div class="field-label">Patokan (Opsional)</div>
          <input type="text" name="address_note" id="address-note-input" class="field-input"
            placeholder="Samping Indomaret, Depan Masjid..."
            value="{{ old('address_note') }}">
        </div>

        <div>
          <div class="field-label">Zona Pengiriman</div>
          <div class="zone-grid">
            @foreach([
              ['A', 'Rp 5k', '0–3 km'],
              ['B', 'Rp 10k', '3–7 km'],
              ['C', 'Rp 15k', '> 7 km'],
            ] as [$z, $price, $range])
            <label class="zone-pill">
              <input type="radio" name="zone" value="{{ $z }}"
                {{ old('zone','A') === $z ? 'checked' : '' }}>
              <span class="zone-name">Zona {{ $z }}</span>
              <span class="zone-price">{{ $price }}</span>
            </label>
            @endforeach
          </div>
          @error('zone') <div class="err">{{ $message }}</div> @enderror
        </div>

      </div>
    </div>

    {{-- ══ SECTION 2: PILIH LAYANAN ══ --}}
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">2</div>
        <h2>Pilih Layanan</h2>
      </div>
      <div class="section-body">

        <div class="service-list">
          @foreach(($kgServices ?? $services->where('pricing_model', 'per_kg')) as $svc)
          @php
            $icons = ['reguler'=>'🧺','express'=>'⚡','prioritas'=>'💎'];
            $iconClasses = ['reguler'=>'icon-reguler','express'=>'icon-express','prioritas'=>'icon-prioritas'];
            $icon = $icons[$svc->slug] ?? '🫧';
            $iconCls = $iconClasses[$svc->slug] ?? 'icon-reguler';
          @endphp
          <label class="service-card">
            <input type="radio" name="service_id" value="{{ $svc->id }}"
              {{ old('service_id', $kgServices->first()?->id ?? $services->first()?->id) == $svc->id ? 'checked' : '' }}
              data-price="{{ $svc->effective_unit_price ?? $svc->price_per_kg }}"
              data-pricing-model="{{ $svc->pricing_model ?? 'per_kg' }}">
            <div class="service-icon {{ $iconCls }}">{{ $icon }}</div>
            <div class="service-info">
              <div class="service-name">{{ $svc->name }}</div>
              <div class="service-eta">{{ $svc->estimated_label }}</div>
            </div>
            <div class="service-price">Rp {{ number_format(($svc->effective_unit_price ?? $svc->price_per_kg)/1000,0) }}k/{{ ($svc->unit_type ?? 'kg') }}</div>
          </label>
          @endforeach
        </div>

        @error('service_id') <div class="err">{{ $message }}</div> @enderror

        {{-- WEIGHT --}}
        <div>
          <div class="field-label">Estimasi Berat</div>
          <div class="weight-stepper">
            <button type="button" class="ws-btn" id="ws-minus">−</button>
            <div class="ws-display">
              <span id="ws-val">{{ old('weight_estimate', 5) }}</span>
              <span class="ws-unit"> kg</span>
            </div>
            <button type="button" class="ws-btn" id="ws-plus">+</button>
          </div>
          <input type="hidden" name="weight_estimate" id="weight-input" value="{{ old('weight_estimate', 5) }}">
          @error('weight_estimate') <div class="err">{{ $message }}</div> @enderror
        </div>

        @if(isset($itemServices) && $itemServices->count() > 0)
        <div>
          <div class="field-label">Item Satuan (Opsional)</div>
          <div class="service-list">
            @foreach($itemServices as $itemSvc)
            <div class="service-card" style="cursor:default; flex-direction:column; align-items:stretch; gap:10px;">
              <input type="hidden" name="item_lines[{{ $loop->index }}][service_id]" value="{{ $itemSvc->id }}">
              <div style="display:flex; align-items:center; gap:12px;">
                <div class="service-icon icon-prioritas">🧾</div>
                <div class="service-info">
                  <div class="service-name">{{ $itemSvc->name }}</div>
                  <div class="service-eta">Harga satuan</div>
                </div>
                <div class="service-price">Rp {{ number_format(($itemSvc->effective_unit_price ?? $itemSvc->price_per_kg)/1000,0) }}k/item</div>
              </div>
              <div class="item-row" style="margin-top:0; border-style:dashed;">
                <div class="item-row-head">
                  <div class="item-stepper">
                    <button type="button" class="item-stepper-btn item-minus" data-target="item-qty-{{ $loop->index }}">−</button>
                    <div class="item-stepper-value" id="item-view-{{ $loop->index }}">{{ old('item_lines.'.$loop->index.'.qty', 0) }}</div>
                    <button type="button" class="item-stepper-btn item-plus" data-target="item-qty-{{ $loop->index }}">+</button>
                  </div>
                  <button type="button" class="item-note-toggle" data-note="item-note-{{ $loop->index }}">Tambah catatan</button>
                </div>
                <input type="hidden" min="0" max="999" value="{{ old('item_lines.'.$loop->index.'.qty', 0) }}"
                  id="item-qty-{{ $loop->index }}"
                  name="item_lines[{{ $loop->index }}][qty]" class="item-qty"
                  data-item-price="{{ $itemSvc->effective_unit_price ?? $itemSvc->price_per_kg }}"
                  data-view="item-view-{{ $loop->index }}">
                <div class="item-note-wrap" id="item-note-{{ $loop->index }}">
                  <input type="text" name="item_lines[{{ $loop->index }}][notes]" class="field-input"
                    value="{{ old('item_lines.'.$loop->index.'.notes') }}"
                    placeholder="Catatan item (opsional)">
                </div>
              </div>
            </div>

            @error('item_lines.'.$loop->index.'.qty') <div class="err">{{ $message }}</div> @enderror
            @error('item_lines.'.$loop->index.'.notes') <div class="err">{{ $message }}</div> @enderror

            @endforeach
          </div>
        </div>
        @endif

        <div class="summary-box" id="summary-box">
          <div class="sum-row">
            <span class="sum-label" id="sum-service-name">Layanan Reguler (5 kg)</span>
            <span class="sum-value" id="sum-service-cost">Rp 40.000</span>
          </div>
          <div class="sum-row">
            <span class="sum-label">Subtotal Item Satuan</span>
            <span class="sum-value" id="sum-item-cost">Rp 0</span>
          </div>
          <div class="sum-row">
            <span class="sum-label">Biaya Jemput</span>
            <span class="sum-value" id="sum-pickup-cost">Rp 5.000</span>
          </div>
          <hr class="sum-divider">
          <div class="sum-row">
            <span class="sum-label sum-total-label">Total Sementara</span>
            <span class="sum-value sum-total-value" id="sum-total">Rp 45.000</span>
          </div>
        </div>
        <p style="margin-top:10px; font-size:.7rem; color:var(--ink-lt); font-weight:700; line-height:1.4;">
          * Harga di atas hanyalah estimasi awal. Berat aktual dan harga final akan ditentukan setelah kurir melakukan penimbangan di lokasi.
        </p>
      </div>
    </div>

    {{-- ══ SECTION 3: JADWAL JEMPUT ══ --}}
    <div class="section-card">
      <div class="section-head">
        <div class="section-num">3</div>
        <h2>Jadwal Jemput</h2>
      </div>
      <div class="section-body">

        {{-- DATE CHIPS --}}
        <div>
          <div class="field-label">Pilih Tanggal</div>
          <div class="date-scroll" id="date-scroll">
            {{-- Tanggal akan ditampilkan secara dinamis --}}
          </div>
          <input type="hidden" name="pickup_date" id="pickup-date-input" value="{{ old('pickup_date') }}">
          @error('pickup_date') <div class="err">{{ $message }}</div> @enderror
        </div>

        {{-- TIME --}}
        <div>
          <div class="field-label">Waktu Jemput</div>
          <div class="time-grid">
            @foreach([
              ['pagi',  '☀️', 'Pagi', '07.00–11.00'],
              ['siang', '🌤️', 'Siang', '11.00–15.00'],
              ['sore',  '🌅', 'Sore', '15.00–18.00'],
            ] as [$val, $ico, $lbl, $range])
            <label class="time-pill">
              <input type="radio" name="pickup_time" value="{{ $val }}"
                {{ old('pickup_time','siang') === $val ? 'checked' : '' }}>
              <span class="tp-icon">{{ $ico }}</span>
              <span class="tp-label">{{ $lbl }}</span>
            </label>
            @endforeach
          </div>
          @error('pickup_time') <div class="err">{{ $message }}</div> @enderror
        </div>

        {{-- NOTES --}}
        <div>
          <div class="field-label">Catatan (Opsional)</div>
          <textarea name="notes" class="field-input" rows="2"
            placeholder="Titip di lobby, pakaian khusus, dll...">{{ old('notes') }}</textarea>
        </div>

      </div>
    </div>

  </form><!-- end form -->
</div><!-- end page-body -->

<!-- FIXED BOTTOM CTA -->
<div class="bottom-cta">
  <div class="cta-inner">
    <button type="submit" form="order-form" class="btn-order" id="btn-submit">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0"/>
      </svg>
      Pesan Sekarang
    </button>
  </div>
</div>

<script>
/* ═══════════════════════════════════════════
   MAP PICKER (LEAFLET)
════════════════════════════════════════════ */
let map, marker;
function initMap() {
  const defaultLoc = [-6.200000, 106.816666]; // Jakarta
  map = L.map('map').setView(defaultLoc, 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  marker = L.marker(defaultLoc, {draggable: true}).addTo(map);

  function updatePos(lat, lng) {
    document.getElementById('lat').value = lat;
    document.getElementById('lng').value = lng;
  }

  marker.on('dragend', function(e) {
    const pos = e.target.getLatLng();
    updatePos(pos.lat, pos.lng);
  });

  map.on('click', function(e) {
    marker.setLatLng(e.latlng);
    updatePos(e.latlng.lat, e.latlng.lng);
  });

  // Try geo-location
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(pos => {
      const loc = [pos.coords.latitude, pos.coords.longitude];
      map.setView(loc, 15);
      marker.setLatLng(loc);
      updatePos(loc[0], loc[1]);
    });
  }
}
document.addEventListener('DOMContentLoaded', initMap);

/* ═══════════════════════════════════════════
   DATE CHIP GENERATOR (7 hari ke depan)
════════════════════════════════════════════ */
(function buildDates() {
  const days  = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
  const scroll = document.getElementById('date-scroll');
  const input  = document.getElementById('pickup-date-input');
  const saved  = input.value;
  let   firstDate = null;

  for (let i = 0; i < 8; i++) {
    const d    = new Date();
    d.setDate(d.getDate() + i);
    const iso  = d.toISOString().split('T')[0];
    const isFirst = i === 0;
    if (i === 0) firstDate = iso;

    const chip = document.createElement('label');
    chip.className = 'date-chip';
    const checked  = (saved && saved === iso) || (!saved && isFirst);
    chip.innerHTML = `
      <input type="radio" name="pickup_date_chip" value="${iso}" ${checked?'checked':''}>
      <div class="dc-day">${days[d.getDay()]}</div>
      <div class="dc-num">${d.getDate()}</div>
    `;
    chip.querySelector('input').addEventListener('change', e => {
      input.value = e.target.value;
    });
    scroll.appendChild(chip);
  }
  if (!saved) input.value = firstDate;
})();

/* ═══════════════════════════════════════════
   WEIGHT STEPPER
════════════════════════════════════════════ */
const wsVal   = document.getElementById('ws-val');
const wsInput = document.getElementById('weight-input');
let   weight  = parseFloat(wsInput.value) || 5;

function setWeight(v) {
  weight = Math.max(1, Math.min(100, v));
  wsVal.textContent  = weight % 1 === 0 ? weight : weight.toFixed(1);
  wsInput.value      = weight;
  recalc();
}

document.getElementById('ws-minus').addEventListener('click', () => setWeight(weight - 1));
document.getElementById('ws-plus').addEventListener('click',  () => setWeight(weight + 1));

/* long-press support */
let pressTimer;
function startPress(fn) { pressTimer = setInterval(fn, 120); }
function stopPress()    { clearInterval(pressTimer); }
document.getElementById('ws-minus').addEventListener('pointerdown', () => startPress(() => setWeight(weight-1)));
document.getElementById('ws-plus').addEventListener('pointerdown',  () => startPress(() => setWeight(weight+1)));
document.addEventListener('pointerup', stopPress);

/* ═══════════════════════════════════════════
   LIVE PRICE CALCULATOR
════════════════════════════════════════════ */
const zoneCosts = {A:5000, B:10000, C:15000};

function fmt(n) {
  return 'Rp ' + n.toLocaleString('id-ID');
}

function recalc() {
  const svcInput  = document.querySelector('input[name="service_id"]:checked');
  const svcName   = svcInput ? svcInput.closest('.service-card').querySelector('.service-name').textContent : '';
  const pricePerKg= svcInput ? parseInt(svcInput.dataset.price) : 8000;

  const zoneInput = document.querySelector('input[name="zone"]:checked');
  const zone      = zoneInput ? zoneInput.value : 'A';
  const pickupCost= zoneCosts[zone] || 5000;

  const svcCost   = pricePerKg * weight;

  let itemCost = 0;
  document.querySelectorAll('.item-qty').forEach((input) => {
    const qty = parseInt(input.value || 0, 10);
    const unit = parseInt(input.dataset.itemPrice || 0, 10);
    if (qty > 0 && unit > 0) {
      itemCost += qty * unit;
    }
  });

  const total = svcCost + itemCost + pickupCost;

  document.getElementById('sum-service-name').textContent = `${svcName} (${weight} kg)`;
  document.getElementById('sum-service-cost').textContent = fmt(svcCost);
  const itemCostEl = document.getElementById('sum-item-cost');
  if (itemCostEl) itemCostEl.textContent = fmt(itemCost);
  document.getElementById('sum-pickup-cost').textContent  = fmt(pickupCost);
  document.getElementById('sum-total').textContent        = fmt(total);
}

document.querySelectorAll('input[name="service_id"], input[name="zone"], .item-qty')
  .forEach(el => el.addEventListener('change', recalc));

const alamatSelect = document.getElementById('customer-address-id');
if (alamatSelect) {
  alamatSelect.addEventListener('change', function () {
    const opt = this.options[this.selectedIndex];
    const addressInput = document.getElementById('address-input');
    const addressNoteInput = document.getElementById('address-note-input');
    const addrHint = document.getElementById('addr-hint');
    const manualNote = document.getElementById('manual-note');

    if (!opt || !opt.value) {
      if (addressInput) {
        addressInput.classList.remove('addr-selected', 'manual-locked');
        addressInput.readOnly = false;
      }
      if (addressNoteInput) {
        addressNoteInput.classList.remove('manual-locked');
        addressNoteInput.readOnly = false;
      }
      if (addrHint) addrHint.classList.remove('show');
      if (manualNote) manualNote.classList.remove('show');
      return;
    }

    const fullAddress = opt.getAttribute('data-full-address') || '';
    const zone = opt.getAttribute('data-zone') || '';
    const note = opt.getAttribute('data-notes') || '';

    if (addressInput) {
      addressInput.value = fullAddress || addressInput.value;
      addressInput.classList.add('manual-locked');
      addressInput.readOnly = true;
    }

    if (addressNoteInput) {
      addressNoteInput.value = note || addressNoteInput.value;
      addressNoteInput.classList.add('manual-locked');
      addressNoteInput.readOnly = true;
    }

    if (zone && ['A','B','C'].includes(zone)) {
      const zoneRadio = document.querySelector(`input[name="zone"][value="${zone}"]`);
      if (zoneRadio) {
        zoneRadio.checked = true;
      }
    }

    if (addressInput) {
      addressInput.classList.add('addr-selected');
      setTimeout(() => addressInput.classList.remove('addr-selected'), 1200);
    }

    if (addrHint) {
      addrHint.classList.add('show');
      setTimeout(() => addrHint.classList.remove('show'), 1800);
    }

    if (manualNote) manualNote.classList.add('show');

    recalc();
  });

  if (alamatSelect.value) {
    const evt = new Event('change');
    alamatSelect.dispatchEvent(evt);
  }
}

document.querySelectorAll('.item-plus').forEach((btn) => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    const current = parseInt(input.value || 0, 10);
    const next = Math.min(999, current + 1);
    input.value = next;
    const view = document.getElementById(input.dataset.view);
    if (view) view.textContent = String(next);
    recalc();
  });
});

document.querySelectorAll('.item-minus').forEach((btn) => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    const current = parseInt(input.value || 0, 10);
    const next = Math.max(0, current - 1);
    input.value = next;
    const view = document.getElementById(input.dataset.view);
    if (view) view.textContent = String(next);
    recalc();
  });
});

document.querySelectorAll('.item-note-toggle').forEach((btn) => {
  btn.addEventListener('click', () => {
    const box = document.getElementById(btn.dataset.note);
    if (!box) return;
    box.classList.toggle('open');
    btn.textContent = box.classList.contains('open') ? 'Sembunyikan catatan' : 'Tambah catatan';
  });
});

const sections = document.querySelectorAll('.section-card');
const chips = [
  document.getElementById('sc1'),
  document.getElementById('sc2'),
  document.getElementById('sc3'),
];

const obs = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const idx = [...sections].indexOf(entry.target);
      chips.forEach((c,i) => {
        c.classList.toggle('active', i === idx);
        c.classList.toggle('done', i < idx);
      });
    }
  });
}, { threshold: 0.5 });

sections.forEach(s => obs.observe(s));

const orderForm = document.getElementById('order-form');
if (orderForm) {
  orderForm.addEventListener('submit', function () {
    const btn = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.innerHTML = `
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:spin .7s linear infinite">
        <path d="M21 12a9 9 0 11-6.219-8.56"/>
      </svg>
      Memproses...
    `;
  });
}

recalc();

// Section step animation handled by CSS @keyframes slideUp
</script>

<style>
@keyframes spin { to { transform:rotate(360deg); } }
</style>

</body>
</html>