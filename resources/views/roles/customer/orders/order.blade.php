<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Detail Pesanan #{{ strtoupper($order->order_code ?? 'N/A') }} – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark:  #002f5c;
    --blue-mid:   #0077b6;
    --blue-light: #00b4d8;
    --blue-sky:   #e0f4ff;
    --orange:     #FF6B35;
    --green:      #00C48C;
    --green-lt:   #e6fff6;
    --red:        #ef4444;
    --yellow:     #f59e0b;
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
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* ── HERO HEADER ─────────────────────── */
.hero {
    background: linear-gradient(145deg, var(--blue-dark) 0%, var(--blue-mid) 60%, var(--blue-light) 100%);
    padding: max(env(safe-area-inset-top, 0px), 20px) 20px 36px;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    top: -60px; right: -40px;
}
.hero::after {
    content: '';
    position: absolute;
    width: 100px; height: 100px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    bottom: -30px; left: 20px;
}
.hero-inner { position: relative; z-index: 2; max-width: 520px; margin: 0 auto; }
.hero-nav { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.btn-back {
    width: 38px; height: 38px; border-radius: 50%;
    background: rgba(255,255,255,0.15);
    border: 1.5px solid rgba(255,255,255,0.25);
    display: flex; align-items: center; justify-content: center;
    color: white; text-decoration: none; flex-shrink: 0;
}
.hero-code {
    font-weight: 800;
    font-size: 0.9rem; color: rgba(255,255,255,0.75);
    letter-spacing: 1px; text-transform: uppercase;
}
.hero-status {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 99px; padding: 5px 12px;
    font-size: 0.72rem; font-weight: 900; color: white;
    text-transform: uppercase; letter-spacing: 0.5px;
    margin-bottom: 10px;
}
.hero-status-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #00C48C;
}
.hero-status-dot.pulse { animation: hdot 1.5s ease-in-out infinite; }
@keyframes hdot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%       { opacity: 0.5; transform: scale(0.8); }
}
.hero-price {
    font-weight: 800;
    font-size: 2rem; color: white;
    text-shadow: 0 2px 12px rgba(0,0,0,0.2);
    line-height: 1;
}
.hero-price-est {
    font-size: 0.72rem; font-weight: 700;
    color: rgba(255,255,255,0.65);
    margin-top: 4px;
}

/* ── CONTENT ─────────────────────────── */
.content {
    max-width: 520px;
    margin: -20px auto 0;
    padding: 0 16px;
    position: relative;
    z-index: 10;
}

/* ── PROGRESS TIMELINE ───────────────── */
.timeline-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 20px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
}
.timeline-card__title {
    font-weight: 800;
    font-size: 0.9rem;
    color: var(--blue-dark);
    margin-bottom: 18px;
    display: flex; align-items: center; gap: 8px;
}
/* Horizontal steps */
.steps-row {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}
.step-node {
    display: flex; flex-direction: column; align-items: center;
    position: relative; z-index: 2;
}
.step-circle {
    width: 32px; height: 32px; border-radius: 50%;
    border: 2.5px solid var(--border);
    background: white;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 900; color: var(--ink-lt);
    transition: all 0.4s ease;
}
.step-circle.done {
    background: var(--green); border-color: var(--green); color: white;
}
.step-circle.active {
    background: var(--blue-mid); border-color: var(--blue-mid); color: white;
    box-shadow: 0 0 0 4px rgba(0,119,182,0.15);
}
.step-label {
    font-size: 0.58rem; font-weight: 800; color: var(--ink-lt);
    margin-top: 6px; white-space: nowrap;
    text-align: center;
}
.step-label.active { color: var(--blue-mid); }
.step-label.done   { color: var(--green); }
.step-connector {
    flex: 1;
    height: 2.5px;
    background: var(--border);
    margin-bottom: 20px;
    border-radius: 99px;
    transition: background 0.4s;
}
.step-connector.done { background: var(--green); }

/* Vertical history */
.history-list { margin-top: 16px; border-top: 1.5px solid var(--border); padding-top: 16px; }
.history-item {
    display: flex; gap: 14px; position: relative;
    padding-bottom: 20px;
}
.history-item:last-child { padding-bottom: 0; }
.history-item::before {
    content: '';
    position: absolute;
    left: 7px; top: 20px; bottom: 0;
    width: 2px;
    background: var(--border);
}
.history-item:last-child::before { display: none; }
.history-dot {
    width: 16px; height: 16px; border-radius: 50%;
    border: 2.5px solid var(--border);
    background: white; flex-shrink: 0; z-index: 2;
    margin-top: 2px;
}
.history-dot.done   { background: var(--green); border-color: var(--green); }
.history-dot.active { background: var(--blue-mid); border-color: var(--blue-mid); box-shadow: 0 0 0 3px rgba(0,119,182,0.15); }
.history-text .ht { font-size: 0.82rem; font-weight: 800; color: var(--ink); line-height: 1.3; }
.history-text .hs { font-size: 0.7rem; font-weight: 700; color: var(--ink-lt); margin-top: 2px; }

/* ── DRIVER CARD ─────────────────────── */
.driver-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 16px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
    display: flex; align-items: center; gap: 14px;
}
.driver-avatar {
    width: 52px; height: 52px; border-radius: 16px;
    object-fit: cover;
    border: 2px solid var(--border);
    flex-shrink: 0;
}
.driver-info { flex: 1; min-width: 0; }
.driver-name { font-weight: 900; font-size: 0.95rem; color: var(--ink); }
.driver-meta { font-size: 0.72rem; font-weight: 700; color: var(--ink-lt); margin-top: 2px; }
.driver-actions { display: flex; gap: 8px; flex-shrink: 0; }
.driver-btn {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; font-size: 1rem;
    transition: transform 0.15s;
}
.driver-btn:active { transform: scale(0.9); }
.driver-btn.wa { background: #e6fff6; }
.driver-btn.call { background: var(--blue-sky); }

/* ── INFO SECTION ────────────────────── */
.info-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    margin-bottom: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
}
.info-card__head {
    display: flex; align-items: center; gap: 10px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(90deg, rgba(0,119,182,0.03) 0%, transparent 100%);
}
.info-card__icon {
    width: 32px; height: 32px; border-radius: 10px;
    background: var(--blue-sky);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.info-card__title {
    font-weight: 800;
    font-size: 0.9rem; color: var(--blue-dark);
}
.info-row {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 11px 16px;
    border-bottom: 1px solid #f8fafc;
    gap: 12px;
}
.info-row:last-child { border-bottom: none; }
.ir-label {
    font-size: 0.78rem; font-weight: 700; color: var(--ink-lt);
    flex-shrink: 0;
}
.ir-value {
    font-size: 0.82rem; font-weight: 800; color: var(--ink);
    text-align: right;
    max-width: 60%;
}
.ir-value.highlight { color: var(--blue-mid); }
.ir-value.green     { color: var(--green); }
.ir-value.orange    { color: var(--orange); }

/* Items list in order */
.items-mini {
    background: #f8fafc;
    border-radius: 10px;
    margin: 0 16px 12px;
    overflow: hidden;
    border: 1px dashed var(--border);
}
.items-mini-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 9px 12px;
    border-bottom: 1px solid var(--border);
    gap: 10px;
}
.items-mini-row:last-child { border-bottom: none; }
.items-mini-name { font-size: 0.78rem; font-weight: 800; color: var(--ink); }
.items-mini-qty  { font-size: 0.72rem; font-weight: 700; color: var(--ink-lt); }
.items-mini-price{ font-size: 0.78rem; font-weight: 900; color: var(--blue-mid); }

/* ── TOTAL CARD ──────────────────────── */
.total-card {
    background: linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
    border-radius: var(--radius);
    padding: 20px;
    margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.total-card::before {
    content: '';
    position: absolute;
    width: 120px; height: 120px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    right: -30px; top: -30px;
}
.total-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 5px 0; position: relative; z-index: 2;
}
.total-label { font-size: 0.8rem; font-weight: 700; color: rgba(255,255,255,0.7); }
.total-value { font-size: 0.85rem; font-weight: 800; color: white; }
.total-divider { border: none; border-top: 1px dashed rgba(255,255,255,0.2); margin: 10px 0; }
.total-main-label {
    font-weight: 800;
    font-size: 1rem; color: rgba(255,255,255,0.85);
}
.total-main-value {
    font-weight: 800;
    font-size: 1.4rem; color: white;
}
.payment-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.15); border-radius: 99px;
    padding: 5px 12px; margin-top: 12px;
    font-size: 0.72rem; font-weight: 800; color: white;
    position: relative; z-index: 2;
}

/* ── ACTION BUTTONS ──────────────────── */
.actions-row {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 10px; margin-bottom: 14px;
}
.action-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 13px;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 900; font-size: 0.82rem;
    cursor: pointer; border: none;
    text-decoration: none;
    transition: transform 0.15s, box-shadow 0.15s;
}
.action-btn:active { transform: scale(0.97); }
.action-btn.primary {
    background: var(--blue-mid); color: white;
    box-shadow: 0 4px 14px rgba(0,119,182,0.3);
}
.action-btn.secondary {
    background: white; color: var(--blue-dark);
    border: 1.5px solid var(--border);
}
.action-btn.track {
    background: var(--orange); color: white;
    box-shadow: 0 4px 14px rgba(255,107,53,0.3);
    grid-column: 1 / -1;
}
.action-btn svg { width: 16px; height: 16px; }

/* ── PROOF IMAGE ─────────────────────── */
.proof-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 16px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
}
.proof-img {
    width: 100%;
    border-radius: 12px;
    object-fit: cover;
    max-height: 240px;
    border: 1px solid var(--border);
}

/* ── CANCEL UX ───────────────────────── */
.cancel-trigger-btn {
    width: 100%;
    padding: 13px;
    background: #fff;
    border: 1.5px solid #fecaca;
    border-radius: var(--radius-sm);
    color: #dc2626;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 900;
    font-size: .85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .15s;
}
.cancel-trigger-btn:active {
    transform: scale(0.97);
    background: #fef2f2;
}
.cancel-hint {
    text-align: center;
    font-size: 0.7rem;
    font-weight: 700;
    color: var(--ink-lt);
    margin-top: 8px;
}
.cancel-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    z-index: 9999;
    display: none;
    align-items: flex-end;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.25s ease;
}
.cancel-overlay.active {
    display: flex;
    opacity: 1;
}
.cancel-sheet {
    background: white;
    width: 100%;
    max-width: 520px;
    border-radius: 24px 24px 0 0;
    padding: 20px 24px calc(24px + env(safe-area-inset-bottom, 0px));
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.32, 0.72, 0, 1);
}
.cancel-overlay.active .cancel-sheet {
    transform: translateY(0);
}
.cancel-sheet__handle {
    width: 36px;
    height: 4px;
    background: #d1d5db;
    border-radius: 99px;
    margin: 0 auto 18px;
}
.cancel-sheet__icon {
    text-align: center;
    font-size: 2.4rem;
    margin-bottom: 10px;
}
.cancel-sheet__title {
    font-weight: 800;
    font-size: 1.2rem;
    text-align: center;
    color: var(--ink);
    margin-bottom: 6px;
}
.cancel-sheet__desc {
    text-align: center;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink-mid);
    line-height: 1.5;
    margin-bottom: 20px;
}
.cancel-field {
    margin-bottom: 20px;
}
.cancel-field__label {
    display: block;
    font-size: 0.8rem;
    font-weight: 800;
    color: var(--ink);
    margin-bottom: 8px;
}
.cancel-field__input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--ink);
    resize: none;
    outline: none;
    transition: border-color 0.2s;
}
.cancel-field__input:focus {
    border-color: var(--blue-mid);
}
.cancel-field__input::placeholder {
    color: var(--ink-lt);
    font-weight: 600;
}
.cancel-sheet__actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.cancel-btn {
    padding: 14px;
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 900;
    font-size: 0.85rem;
    cursor: pointer;
    border: none;
    transition: transform 0.12s;
}
.cancel-btn:active { transform: scale(0.96); }
.cancel-btn--secondary {
    background: #f3f4f6;
    color: var(--ink-mid);
}
.cancel-btn--danger {
    background: #dc2626;
    color: white;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
}
</style>
</head>
<body>

{{-- HERO HEADER --}}
@php
    // Smart back: gunakan ?back= URL atau fallback ke halaman pesanan
    $backUrl = \App\Support\BackUrl::resolve(request(), 'customer.orders');
@endphp
<div class="hero" id="js-hero">
    <div class="hero-inner">
        <div class="hero-nav">
            <a href="{{ $backUrl }}" class="btn-back" aria-label="Kembali" title="Kembali">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
            </a>
            <div class="hero-code">#{{ strtoupper($order->order_code) }}</div>
        </div>

        <div class="hero-status">
            <span class="hero-status-dot {{ !in_array($order->status, ['selesai','dibatalkan']) ? 'pulse' : '' }}"
                  style="background: {{ $order->status_color ?? '#00C48C' }}"></span>
            {{ $order->status_label }}
        </div>

        @php
            $heroItemTotal = $order->items ? $order->items->where('service_id', '!=', $order->service_id)->sum('line_total') : 0;
            $heroTotal = (int)($order->service_cost ?? 0) + $heroItemTotal + (int)($order->pickup_cost ?? 0) - (int)($order->discount ?? 0);
        @endphp
        <div class="hero-price">Rp {{ number_format($heroTotal, 0, ',', '.') }}</div>
        <div class="hero-price-est">
            @if($order->weight_actual)
                Berat aktual: {{ $order->weight_actual }} kg
            @else
                Estimasi berat: {{ $order->weight_estimate }} kg
            @endif
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="content">

    {{-- Proof of Delivery (if completed) --}}
    @if($order->status === 'selesai' && $order->proof_image)
    <div class="proof-card js-section">
        <div class="info-card__head" style="padding: 0 0 12px; border: none; background: none;">
            <div class="info-card__icon">📸</div>
            <div class="info-card__title">Bukti Pengiriman</div>
        </div>
        <img src="{{ asset('storage/' . $order->proof_image) }}" class="proof-img" alt="Bukti pengiriman">
    </div>
    @endif

    {{-- Progress Timeline --}}
    @php
        $allSteps = [
            ['key' => 'menunggu',          'label' => 'Pesan',    'icon' => '📋'],
            ['key' => 'dijemput',          'label' => 'Jemput',   'icon' => '🛵'],
            ['key' => 'dicuci',            'label' => 'Cuci',     'icon' => '🧺'],
            ['key' => 'siap',              'label' => 'Siap',     'icon' => '✅'],
            ['key' => 'selesai',           'label' => 'Selesai',  'icon' => '🎉'],
        ];
        $statusOrder = ['menunggu' => 0, 'dijemput' => 1, 'dicuci' => 1, 'disetrika' => 2, 'siap' => 3, 'dikirim' => 3, 'selesai' => 4, 'dibatalkan' => -1];
        $currentStep = $statusOrder[$order->status] ?? 0;
    @endphp

    <div class="timeline-card js-section">
        <div class="timeline-card__title">
            🗺️ Status Pesanan
        </div>

        {{-- Horizontal Steps --}}
        <div class="steps-row">
            @foreach($allSteps as $si => $step)
                <div class="step-node">
                    <div class="step-circle {{ $currentStep > $si ? 'done' : ($currentStep == $si ? 'active' : '') }}">
                        @if($currentStep > $si)
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            {{ $si + 1 }}
                        @endif
                    </div>
                    <span class="step-label {{ $currentStep > $si ? 'done' : ($currentStep == $si ? 'active' : '') }}">
                        {{ $step['label'] }}
                    </span>
                </div>
                @if(!$loop->last)
                    <div class="step-connector {{ $currentStep > $si ? 'done' : '' }}"></div>
                @endif
            @endforeach
        </div>

        {{-- Status History --}}
        @if(isset($histori) && $histori->count() > 0)
        <div class="history-list">
            @foreach($histori as $h)
            <div class="history-item">
                <div class="history-dot {{ $loop->first ? 'active' : 'done' }}"></div>
                <div class="history-text">
                    <div class="ht">{{ ucfirst(str_replace('_', ' ', $h->status_code)) }}</div>
                    <div class="hs">
                        {{ $h->status_note ?? 'Status diperbarui' }}
                        @if($h->updated_at)
                            • {{ $h->updated_at->translatedFormat('d M, H:i') }}
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Driver Info (if assigned) --}}
    @if($order->driver)
    <div class="driver-card js-section">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($order->driver->name) }}&background=0077b6&color=fff&size=128"
             class="driver-avatar" alt="{{ $order->driver->name }}">
        <div class="driver-info">
            <div class="driver-name">{{ $order->driver->name }}</div>
            <div class="driver-meta">Kurir Azka Laundry</div>
        </div>
        <div class="driver-actions">
            @if($order->driver->phone)
            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/', '', $order->driver->phone), '0') }}?text=Halo%20Kurir%20Azka%20Laundry%2C%20saya%20ingin%20tanya%20status%20pesanan%20%23{{ $order->order_code }}"
               target="_blank" class="driver-btn wa" title="WhatsApp Kurir">
                💬
            </a>
            <a href="tel:{{ $order->driver->phone }}" class="driver-btn call" title="Telepon Kurir">
                📞
            </a>
            @endif
        </div>
    </div>
    @endif

    {{-- Order Detail --}}
    <div class="info-card js-section">
        <div class="info-card__head">
            <div class="info-card__icon">📦</div>
            <div class="info-card__title">Rincian Pesanan</div>
        </div>

        <div class="info-row">
            <span class="ir-label">Layanan Utama</span>
            <span class="ir-value">{{ $order->service->name ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="ir-label">Estimasi Berat</span>
            <span class="ir-value">{{ $order->weight_estimate }} kg</span>
        </div>
        @if($order->weight_actual)
        <div class="info-row">
            <span class="ir-label">Berat Aktual</span>
            <span class="ir-value highlight">{{ $order->weight_actual }} kg</span>
        </div>
        @endif
        <div class="info-row">
            <span class="ir-label">Tanggal Pesan</span>
            <span class="ir-value">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
        </div>
        @if($order->pickup_date)
        <div class="info-row">
            <span class="ir-label">Jadwal Jemput</span>
            <span class="ir-value">
                {{ $order->pickup_date->translatedFormat('d M Y') }},
                {{ ucfirst($order->pickup_time ?? '-') }}
            </span>
        </div>
        @endif

        {{-- Item Satuan --}}
        @if($order->items && $order->items->count() > 0)
        <div class="info-row" style="flex-direction: column; align-items: flex-start; gap: 8px;">
            <span class="ir-label">Item Satuan</span>
        </div>
        <div class="items-mini">
            @foreach($order->items as $item)
            <div class="items-mini-row">
                <div>
                    <div class="items-mini-name">{{ $item->item_description ?? $item->service->name ?? 'Item' }}</div>
                    <div class="items-mini-qty">
                        @if($item->weight_kg) {{ $item->weight_kg }} kg
                        @else {{ $item->qty }} item
                        @endif
                    </div>
                </div>
                <div class="items-mini-price">Rp {{ number_format($item->line_total, 0, ',', '.') }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Lokasi --}}
    <div class="info-card js-section">
        <div class="info-card__head">
            <div class="info-card__icon">📍</div>
            <div class="info-card__title">Alamat Jemput</div>
        </div>
        <div class="info-row" style="align-items: flex-start;">
            <span class="ir-label" style="padding-top: 2px;">Alamat</span>
            <span class="ir-value" style="max-width: 70%; word-break: break-word; white-space: normal; text-align: right; line-height: 1.4;">{{ $order->address ?? '-' }}</span>
        </div>
        @if($order->address_note)
        <div class="info-row">
            <span class="ir-label">Patokan</span>
            <span class="ir-value">{{ $order->address_note }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="ir-label">Zona</span>
            <span class="ir-value">Zona {{ $order->zone ?? 'A' }}</span>
        </div>
        @if($order->notes)
        <div class="info-row">
            <span class="ir-label">Catatan</span>
            <span class="ir-value" style="max-width: 65%; white-space: normal; text-align: right; line-height: 1.4;">{{ $order->notes }}</span>
        </div>
        @endif
    </div>

    {{-- Total Biaya --}}
    <div class="total-card js-section">
        @php
            $serviceCost = $order->service_cost ?? ($order->weight_estimate * ($order->service->effective_unit_price ?? 0));
            $pickupCost  = $order->pickup_cost ?? 0;
            $discount    = $order->discount ?? 0;
            $itemTotal   = $order->items ? $order->items->where('service_id', '!=', $order->service_id)->sum('line_total') : 0;
            $total       = (int) $serviceCost + $itemTotal + $pickupCost - $discount;
        @endphp
        <div class="total-row" style="position:relative;z-index:2">
            <span class="total-label">Layanan Utama</span>
            <span class="total-value">Rp {{ number_format((int)($order->service_cost ?? 0), 0, ',', '.') }}</span>
        </div>
        @if($itemTotal > 0)
        <div class="total-row" style="position:relative;z-index:2">
            <span class="total-label">Item Satuan</span>
            <span class="total-value">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="total-row" style="position:relative;z-index:2">
            <span class="total-label">Biaya Jemput (Zona {{ $order->zone }})</span>
            <span class="total-value">Rp {{ number_format($pickupCost, 0, ',', '.') }}</span>
        </div>
        @if($discount > 0)
        <div class="total-row" style="position:relative;z-index:2">
            <span class="total-label">Diskon</span>
            <span class="total-value">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
        </div>
        @endif
        <hr class="total-divider">
        <div class="total-row" style="position:relative;z-index:2">
            <span class="total-main-label">Total Bayar</span>
            <span class="total-main-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <div style="position:relative;z-index:2">
            <span class="payment-badge">
                @if($order->is_paid)
                    ✓ Lunas {{ $order->paid_at ? '• '.$order->paid_at->format('d/m/Y') : '' }}
                @else
                    💳 COD – Bayar saat diterima
                @endif
            </span>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="actions-row js-section">
        @if(!in_array($order->status, ['selesai', 'dibatalkan']))
        <a href="{{ route('customer.tracking') }}" class="action-btn track">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="3"/>
                <line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/>
                <line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/>
            </svg>
            Lacak Posisi Kurir
        </a>
        @endif
        <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
            </svg>
            Lihat Struk
        </a>
        <a href="{{ route('customer.orders') }}" class="action-btn secondary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Semua Pesanan
        </a>
    </div>

    {{-- Reorder button if completed --}}
    @if($order->status === 'selesai')
    <div style="margin-bottom: 14px;">
        <a href="{{ route('order.create') }}" class="action-btn primary" style="width: 100%; display: flex;">
            🧺 Pesan Lagi Layanan Ini
        </a>
    </div>
    @endif

    {{-- Cancel button — hanya muncul selama status masih menunggu/dijemput --}}
    @if(in_array($order->status, ['menunggu', 'dijemput']))
    <div style="margin-bottom: 14px;">
        <button type="button" onclick="openCancelSheet()" class="cancel-trigger-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Batalkan Pesanan
        </button>
        <p class="cancel-hint">Pembatalan hanya bisa dilakukan sebelum cucian dijemput kurir.</p>
    </div>

    {{-- Cancel Bottom Sheet --}}
    <div class="cancel-overlay" id="cancelOverlay" onclick="closeCancelSheet(event)">
        <div class="cancel-sheet" id="cancelSheet">
            <div class="cancel-sheet__handle"></div>
            <div class="cancel-sheet__icon">⚠️</div>
            <h3 class="cancel-sheet__title">Batalkan Pesanan?</h3>
            <p class="cancel-sheet__desc">Pesanan yang sudah dibatalkan tidak bisa dikembalikan. Yakin ingin melanjutkan?</p>

            <form method="POST" action="{{ route('customer.order.cancel', $order) }}" id="form-cancel-order">
                @csrf
                <div class="cancel-field">
                    <label for="cancel_reason" class="cancel-field__label">Alasan pembatalan <span style="color:var(--ink-lt)">(opsional)</span></label>
                    <textarea id="cancel_reason" name="cancel_reason" class="cancel-field__input" rows="3" maxlength="300" placeholder="Contoh: Ganti jadwal, salah pilih layanan..."></textarea>
                </div>
                <div class="cancel-sheet__actions">
                    <button type="button" onclick="closeCancelSheet()" class="cancel-btn cancel-btn--secondary">Tidak Jadi</button>
                    <button type="submit" class="cancel-btn cancel-btn--danger">Ya, Batalkan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'pesanan'])

<script>
    // Cancel bottom sheet
    function openCancelSheet() {
        const overlay = document.getElementById('cancelOverlay');
        if (!overlay) return;
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCancelSheet(e) {
        if (e && e.target !== e.currentTarget) return;
        const overlay = document.getElementById('cancelOverlay');
        if (!overlay) return;
        const sheet = document.getElementById('cancelSheet');
        sheet.style.transform = 'translateY(100%)';
        setTimeout(function() {
            overlay.classList.remove('active');
            sheet.style.transform = '';
            document.body.style.overflow = '';
        }, 250);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof gsap === 'undefined') return;

        const animate = (target, options) => {
            const list = typeof target === 'string'
                ? document.querySelectorAll(target)
                : target;

            if (!list || list.length === 0) return;
            gsap.from(list, options);
        };

        animate('#js-hero', {
            opacity: 0,
            y: -30,
            duration: 0.6,
            ease: 'power2.out',
        });

        animate('.js-section', {
            opacity: 0,
            y: 25,
            duration: 0.5,
            stagger: 0.1,
            ease: 'power2.out',
            delay: 0.2,
        });

        animate('.step-circle', {
            scale: 0,
            duration: 0.4,
            stagger: 0.08,
            ease: 'back.out(1.5)',
            delay: 0.4,
        });

        animate('.step-connector.done', {
            scaleX: 0,
            transformOrigin: 'left center',
            duration: 0.5,
            stagger: 0.1,
            ease: 'power2.out',
            delay: 0.6,
        });

        animate('.driver-card', {
            x: -20,
            opacity: 0,
            duration: 0.5,
            ease: 'power2.out',
            delay: 0.3,
        });
    });
</script>

</body>
</html>