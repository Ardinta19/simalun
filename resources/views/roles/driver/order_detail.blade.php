<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Detail Tugas #{{ strtoupper($order->order_code) }} – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --primary: #0d6fb8;
    --primary-dark: #002f5c;
    --primary-light: #e0f4ff;
    --accent: #FF6B35;
    --accent-light: #fff3ee;
    --success: #059669;
    --success-light: #ecfdf5;
    --warning: #d97706;
    --warning-light: #fffbeb;
    --danger: #dc2626;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-secondary: #475569;
    --ink-muted: #94a3b8;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --radius: 14px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
    --shadow-md: 0 4px 12px rgba(0,47,92,.08);
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* Hero Header */
.detail-hero {
    background: linear-gradient(145deg, var(--primary-dark) 0%, var(--primary) 100%);
    padding: max(env(safe-area-inset-top, 0px), 18px) 20px 28px;
    position: relative;
    overflow: hidden;
}
.detail-hero::after {
    content: '';
    position: absolute;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
    top: -50px; right: -40px;
}
.detail-hero__inner {
    max-width: 520px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}
.detail-hero__nav {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.detail-hero__back {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    text-decoration: none;
}
.detail-hero__back svg { width: 18px; height: 18px; }
.detail-hero__code {
    font-size: .72rem;
    font-weight: 700;
    color: rgba(255,255,255,.6);
    letter-spacing: .8px;
}
.detail-hero__status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.14);
    border: 1px solid rgba(255,255,255,.22);
    border-radius: 99px;
    padding: 5px 12px;
    font-size: .68rem;
    font-weight: 700;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-bottom: 8px;
}
.detail-hero__status-dot {
    width: 7px; height: 7px;
    border-radius: 50%;
}
.detail-hero__name {
    font-weight: 800;
    font-size: 1.4rem;
    color: #fff;
    line-height: 1.2;
}
.detail-hero__service {
    font-size: .82rem;
    font-weight: 600;
    color: rgba(255,255,255,.7);
    margin-top: 4px;
}

/* Content */
.detail-content {
    max-width: 520px;
    margin: -14px auto 0;
    padding: 0 16px;
    position: relative;
    z-index: 10;
}

/* Alert */
.detail-alert {
    padding: 11px 14px;
    border-radius: var(--radius-sm);
    font-size: .82rem;
    font-weight: 700;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-alert--success { background: var(--success-light); color: var(--success); border: 1px solid rgba(5,150,105,.2); }
.detail-alert--error { background: #fef2f2; color: var(--danger); border: 1px solid rgba(220,38,38,.15); }

/* Section Card */
.section-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 12px;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}
.section-card__head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    border-bottom: 1px solid var(--border-light);
}
.section-card__icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--primary-light);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.section-card__icon svg { width: 16px; height: 16px; color: var(--primary); }
.section-card__title {
    font-weight: 700;
    font-size: .88rem;
    color: var(--ink);
}

/* Data Row */
.data-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 11px 16px;
    border-bottom: 1px solid var(--border-light);
    gap: 12px;
}
.data-row:last-child { border-bottom: none; }
.data-row__label {
    font-size: .76rem;
    font-weight: 600;
    color: var(--ink-muted);
}
.data-row__value {
    font-size: .82rem;
    font-weight: 700;
    color: var(--ink);
    text-align: right;
    max-width: 60%;
    word-break: break-word;
}
.data-row__value--accent { color: var(--success); }
.data-row__value--primary { color: var(--primary); }

/* Contact Bar */
.contact-bar {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    padding: 12px 16px;
    background: var(--border-light);
}
.contact-bar__btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px;
    border-radius: var(--radius-sm);
    font-weight: 700;
    font-size: .76rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.contact-bar__btn--wa { background: var(--success-light); color: var(--success); }
.contact-bar__btn--call { background: var(--primary-light); color: var(--primary); }

/* Action Card */
.action-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: var(--shadow-md);
}
.action-card__title {
    font-weight: 800;
    font-size: .92rem;
    color: var(--ink);
    margin-bottom: 4px;
}
.action-card__hint {
    font-size: .74rem;
    font-weight: 500;
    color: var(--ink-muted);
    margin-bottom: 14px;
}
.action-card__field {
    margin-bottom: 12px;
}
.action-card__label {
    font-size: .7rem;
    font-weight: 700;
    color: var(--ink-secondary);
    margin-bottom: 6px;
    display: block;
}
.action-card__input {
    width: 100%;
    padding: 11px 13px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    font-size: .88rem;
    background: var(--border-light);
    color: var(--ink);
    outline: none;
    transition: border-color .2s;
}
.action-card__input:focus {
    border-color: var(--primary);
    background: #fff;
}
.action-card__submit {
    width: 100%;
    padding: 13px;
    border-radius: var(--radius-sm);
    border: none;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .88rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: transform .12s;
}
.action-card__submit:active { transform: scale(.97); }
.action-card__submit--success { background: var(--success); color: #fff; }
.action-card__submit--accent { background: var(--accent); color: #fff; }

/* Proof */
.proof-img {
    width: 100%;
    border-radius: var(--radius-sm);
    max-height: 260px;
    object-fit: cover;
    display: block;
}

/* History Timeline */
.timeline {
    padding: 12px 16px;
}
.timeline__item {
    display: flex;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-light);
}
.timeline__item:last-child { border-bottom: none; }
.timeline__dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--primary);
    margin-top: 4px;
    flex-shrink: 0;
}
.timeline__text {
    font-size: .8rem;
    font-weight: 700;
    color: var(--ink);
}
.timeline__meta {
    font-size: .7rem;
    font-weight: 500;
    color: var(--ink-muted);
    margin-top: 2px;
}
</style>
</head>
<body>

<div class="detail-hero">
    <div class="detail-hero__inner">
        @php
            $backUrl = \App\Support\BackUrl::resolve(request(), 'driver.orders');
        @endphp
        <div class="detail-hero__nav">
            <a href="{{ $backUrl }}" class="detail-hero__back" aria-label="Kembali">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="detail-hero__code">#{{ strtoupper($order->order_code) }}</div>
        </div>
        <div class="detail-hero__status">
            <span class="detail-hero__status-dot" style="background:{{ $order->status_color }}"></span>
            {{ $order->status_label }}
        </div>
        <div class="detail-hero__name">{{ $order->customer->name ?? 'Customer' }}</div>
        <div class="detail-hero__service">{{ $order->service->name ?? 'Layanan' }} &middot; {{ ucfirst($order->pickup_time ?? '-') }}</div>
    </div>
</div>

<div class="detail-content">

    @if(session('success'))
        <div class="detail-alert detail-alert--success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="detail-alert detail-alert--error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="detail-alert detail-alert--error">{{ $errors->first() }}</div>
    @endif

    {{-- Lokasi Customer --}}
    <div class="section-card">
        <div class="section-card__head">
            <div class="section-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="section-card__title">Lokasi Customer</div>
        </div>
        <div class="data-row">
            <span class="data-row__label">Alamat</span>
            <span class="data-row__value">{{ $order->customerAddress?->full_address ?? $order->address ?? '-' }}</span>
        </div>
        @if($order->address_note)
        <div class="data-row">
            <span class="data-row__label">Patokan</span>
            <span class="data-row__value">{{ $order->address_note }}</span>
        </div>
        @endif
        <div class="data-row">
            <span class="data-row__label">Zona</span>
            <span class="data-row__value">Zona {{ $order->zone ?? 'A' }}</span>
        </div>
        @if($order->customer?->phone)
        <div class="contact-bar">
            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}" target="_blank" class="contact-bar__btn contact-bar__btn--wa">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                Chat WA
            </a>
            <a href="tel:{{ $order->customer->phone }}" class="contact-bar__btn contact-bar__btn--call">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                Telepon
            </a>
        </div>
        @endif
    </div>

    {{-- Detail Pesanan --}}
    <div class="section-card">
        <div class="section-card__head">
            <div class="section-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div class="section-card__title">Detail Pesanan</div>
        </div>
        <div class="data-row">
            <span class="data-row__label">Layanan</span>
            <span class="data-row__value">{{ $order->service->name ?? '-' }}</span>
        </div>
        <div class="data-row">
            <span class="data-row__label">Estimasi Berat</span>
            <span class="data-row__value">{{ $order->weight_estimate }} kg</span>
        </div>
        @if($order->weight_actual)
        <div class="data-row">
            <span class="data-row__label">Berat Aktual</span>
            <span class="data-row__value data-row__value--primary">{{ $order->weight_actual }} kg</span>
        </div>
        @endif
        <div class="data-row">
            <span class="data-row__label">Jadwal Jemput</span>
            <span class="data-row__value">{{ $order->pickup_date?->format('d/m/Y') ?? '-' }}, {{ ucfirst($order->pickup_time ?? '-') }}</span>
        </div>
        <div class="data-row">
            <span class="data-row__label">Total</span>
            <span class="data-row__value data-row__value--accent">Rp {{ number_format($order->calculated_total, 0, ',', '.') }}</span>
        </div>
        <div class="data-row">
            <span class="data-row__label">Pembayaran</span>
            <span class="data-row__value">{{ $order->is_paid ? 'Lunas' : 'COD' }}</span>
        </div>
        @if($order->notes)
        <div class="data-row">
            <span class="data-row__label">Catatan</span>
            <span class="data-row__value">{{ $order->notes }}</span>
        </div>
        @endif
    </div>

    {{-- Aksi: Konfirmasi Jemput --}}
    @if($order->status === 'dijemput')
    <div class="action-card">
        <div class="action-card__title">Konfirmasi Penjemputan</div>
        <div class="action-card__hint">Masukkan berat aktual setelah pakaian diterima dari customer.</div>
        <form method="POST" action="{{ route('driver.orders.action', $order) }}" id="form-confirm-pickup">
            @csrf
            <input type="hidden" name="status" value="dicuci">
            <div class="action-card__field">
                <label class="action-card__label" for="weight_actual">Berat Aktual (kg)</label>
                <input type="number" step="0.1" min="0.1" max="50" name="weight_actual" id="weight_actual"
                       class="action-card__input" placeholder="Contoh: 4.5"
                       value="{{ old('weight_actual', $order->weight_estimate) }}" required>
            </div>
            <button type="button" class="action-card__submit action-card__submit--success" id="btn-confirm-pickup">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Konfirmasi Jemput
            </button>
        </form>
    </div>
    @endif

    {{-- Aksi: Selesaikan Pesanan --}}
    @if($order->status === 'dikirim')
    <div class="action-card">
        <div class="action-card__title">Konfirmasi Pengiriman</div>
        <div class="action-card__hint">Upload foto bukti setelah pakaian diserahkan ke customer.</div>
        <form method="POST" action="{{ route('driver.orders.action', $order) }}" enctype="multipart/form-data" id="form-confirm-delivery">
            @csrf
            <input type="hidden" name="status" value="selesai">
            <div class="action-card__field">
                <label class="action-card__label" for="proof_image">Foto Bukti Pengiriman</label>
                <input type="file" name="proof_image" id="proof_image" accept="image/*" capture="environment"
                       class="action-card__input" required>
            </div>
            <button type="button" class="action-card__submit action-card__submit--accent" id="btn-confirm-delivery">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
                Selesaikan Pesanan
            </button>
        </form>
    </div>
    @endif

    {{-- Bukti Foto --}}
    @if($order->status === 'selesai' && $order->proof_image)
    <div class="section-card">
        <div class="section-card__head">
            <div class="section-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <div class="section-card__title">Bukti Pengiriman</div>
        </div>
        <div style="padding:12px 16px">
            <img src="{{ asset('storage/' . $order->proof_image) }}" class="proof-img" alt="Bukti pengiriman">
        </div>
    </div>
    @endif

    {{-- Riwayat Status --}}
    @if(isset($histori) && $histori->count() > 0)
    <div class="section-card">
        <div class="section-card__head">
            <div class="section-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="section-card__title">Riwayat Status</div>
        </div>
        <div class="timeline">
            @foreach($histori as $h)
            <div class="timeline__item">
                <div class="timeline__dot"></div>
                <div>
                    <div class="timeline__text">{{ ucfirst(str_replace('_',' ',$h->status_code)) }}</div>
                    <div class="timeline__meta">{{ $h->status_note ?? '-' }} @if($h->updated_at)&middot; {{ $h->updated_at->format('d M, H:i') }}@endif</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@include('layouts.component.driver._navbar_driver', ['active' => 'tugas'])
@include('layouts.component._confirm_modal')
@include('layouts.component._form_loading')

<script>
(function() {
    var btnPickup = document.getElementById('btn-confirm-pickup');
    if (btnPickup) {
        btnPickup.addEventListener('click', function() {
            var weightInput = document.getElementById('weight_actual');
            if (!weightInput || !weightInput.value || parseFloat(weightInput.value) < 0.1) {
                weightInput.focus();
                return;
            }
            showConfirmModal({
                title: 'Konfirmasi Penjemputan?',
                message: 'Pakaian akan ditandai sudah dijemput dengan berat ' + weightInput.value + ' kg. Pastikan sudah diterima dari customer.',
                confirmText: 'Ya, Konfirmasi',
                cancelText: 'Batal',
                type: 'success',
                onConfirm: function() {
                    document.getElementById('form-confirm-pickup').submit();
                }
            });
        });
    }

    var btnDelivery = document.getElementById('btn-confirm-delivery');
    if (btnDelivery) {
        btnDelivery.addEventListener('click', function() {
            var fileInput = document.getElementById('proof_image');
            if (!fileInput || !fileInput.files.length) {
                fileInput.focus();
                return;
            }
            showConfirmModal({
                title: 'Selesaikan Pesanan?',
                message: 'Pesanan akan ditandai selesai dan pembayaran COD dicatat. Pastikan cucian sudah diserahkan ke customer.',
                confirmText: 'Ya, Selesaikan',
                cancelText: 'Batal',
                type: 'warning',
                onConfirm: function() {
                    document.getElementById('form-confirm-delivery').submit();
                }
            });
        });
    }
})();
</script>

</body>
</html>
