<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nota #{{ $order->order_code }} — {{ $laundry['name'] }}</title>
<style>
/* ═══════════════════════════════════════════════════
   AZKA LAUNDRY — NOTA / RECEIPT
   Supports: 58mm, 80mm thermal printer, A5, A4
   ═══════════════════════════════════════════════════ */

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --ink: #1a1a2e;
    --ink-mid: #4a5568;
    --ink-lt: #8896a6;
    --accent: #0b5394;
    --accent-lt: #e8f4fd;
    --green: #059669;
    --green-lt: #ecfdf5;
    --red: #dc2626;
    --border: #e2e8f0;
    --border-dash: #cbd5e1;
    --surface: #f8fafc;
    --card: #ffffff;
    --font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
}

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: var(--font);
    color: var(--ink);
    background: var(--surface);
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
}

/* ── TOOLBAR (screen only) ──────────────── */
.toolbar {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--card);
    border-bottom: 1px solid var(--border);
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
}

.toolbar__left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.toolbar__back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--ink);
    text-decoration: none;
    transition: background 0.15s;
}
.toolbar__back:hover { background: var(--border); }

.toolbar__title {
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--ink);
}

.toolbar__actions {
    display: flex;
    gap: 8px;
}

.toolbar__btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    border-radius: 8px;
    font-family: var(--font);
    font-size: 0.78rem;
    font-weight: 700;
    cursor: pointer;
    border: 1px solid var(--border);
    background: var(--card);
    color: var(--ink);
    text-decoration: none;
    transition: all 0.15s;
}
.toolbar__btn:hover {
    background: var(--surface);
    border-color: var(--ink-lt);
}
.toolbar__btn--primary {
    background: var(--accent);
    border-color: var(--accent);
    color: white;
}
.toolbar__btn--primary:hover {
    background: #094478;
    border-color: #094478;
}
.toolbar__btn svg {
    width: 14px;
    height: 14px;
    flex-shrink: 0;
}

/* Format switcher */
.format-tabs {
    display: flex;
    gap: 4px;
    background: var(--surface);
    border-radius: 8px;
    padding: 3px;
    border: 1px solid var(--border);
}
.format-tab {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--ink-lt);
    cursor: pointer;
    border: none;
    background: transparent;
    font-family: var(--font);
    transition: all 0.15s;
}
.format-tab:hover { color: var(--ink); }
.format-tab.active {
    background: var(--card);
    color: var(--accent);
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}

/* ── RECEIPT SHEET ──────────────────────── */
.receipt-wrap {
    display: flex;
    justify-content: center;
    padding: 24px 16px 60px;
}

.receipt {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 2px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    position: relative;
    overflow: hidden;
}

/* Paper sizes */
.receipt--a5 { width: 148mm; min-height: 210mm; padding: 12mm; }
.receipt--a4 { width: 210mm; min-height: 297mm; padding: 15mm; }
.receipt--80mm { width: 80mm; min-height: auto; padding: 4mm; }
.receipt--58mm { width: 58mm; min-height: auto; padding: 3mm; }

/* Thermal mode - compact everything */
.receipt--80mm, .receipt--58mm {
    border-radius: 0;
    box-shadow: none;
    border: 1px dashed var(--border-dash);
}
.receipt--80mm .r-header__brand,
.receipt--58mm .r-header__brand { font-size: 0.9rem; }
.receipt--80mm .r-header__sub,
.receipt--58mm .r-header__sub { font-size: 0.6rem; }
.receipt--80mm .r-table th,
.receipt--80mm .r-table td,
.receipt--58mm .r-table th,
.receipt--58mm .r-table td { font-size: 0.62rem; padding: 3px 2px; }
.receipt--80mm .r-total__grand,
.receipt--58mm .r-total__grand { font-size: 1rem; }
.receipt--58mm .r-grid { grid-template-columns: 1fr; }

/* ── HEADER ─────────────────────────────── */
.r-header {
    text-align: center;
    padding-bottom: 12px;
    margin-bottom: 12px;
    border-bottom: 2px dashed var(--border-dash);
}

.r-header__brand {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--accent);
    letter-spacing: -0.3px;
    margin-bottom: 2px;
}

.r-header__sub {
    font-size: 0.7rem;
    font-weight: 500;
    color: var(--ink-mid);
    line-height: 1.6;
}

.r-header__code {
    display: inline-block;
    margin-top: 10px;
    padding: 4px 12px;
    background: var(--accent-lt);
    border-radius: 4px;
    font-size: 0.78rem;
    font-weight: 800;
    color: var(--accent);
    letter-spacing: 0.5px;
}

/* ── GRID INFO ──────────────────────────── */
.r-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 14px;
}

.r-box {
    padding: 10px;
    background: var(--surface);
    border-radius: 6px;
    border: 1px solid var(--border);
}

.r-box__label {
    font-size: 0.62rem;
    font-weight: 600;
    color: var(--ink-lt);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}

.r-box__value {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--ink);
    line-height: 1.4;
    word-break: break-word;
}

/* ── TABLE ──────────────────────────────── */
.r-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 14px;
}

.r-table thead {
    border-bottom: 1.5px solid var(--border);
}

.r-table th {
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--ink-lt);
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 6px 4px;
    text-align: left;
}

.r-table td {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--ink);
    padding: 8px 4px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}

.r-table .t-right { text-align: right; }
.r-table .t-mono { font-variant-numeric: tabular-nums; }

/* ── TOTALS ─────────────────────────────── */
.r-total {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 14px;
    background: var(--surface);
}

.r-total__row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 3px 0;
    font-size: 0.75rem;
}

.r-total__row-label {
    font-weight: 600;
    color: var(--ink-mid);
}

.r-total__row-value {
    font-weight: 700;
    color: var(--ink);
    font-variant-numeric: tabular-nums;
}

.r-total__divider {
    border: none;
    border-top: 1.5px dashed var(--border-dash);
    margin: 8px 0;
}

.r-total__grand-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 4px;
}

.r-total__grand-label {
    font-size: 0.82rem;
    font-weight: 800;
    color: var(--ink);
}

.r-total__grand {
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--accent);
}

/* ── PAYMENT BADGE ──────────────────────── */
.r-payment {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 6px;
    margin-bottom: 14px;
    font-size: 0.72rem;
    font-weight: 700;
}
.r-payment--paid {
    background: var(--green-lt);
    color: var(--green);
    border: 1px solid #a7f3d0;
}
.r-payment--unpaid {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

/* ── FOOTER ─────────────────────────────── */
.r-footer {
    text-align: center;
    padding-top: 12px;
    border-top: 1.5px dashed var(--border-dash);
    font-size: 0.65rem;
    color: var(--ink-lt);
    line-height: 1.7;
}

.r-footer__thanks {
    font-weight: 700;
    color: var(--ink-mid);
    margin-bottom: 4px;
}

/* ── PRINT STYLES ───────────────────────── */
@media print {
    .toolbar { display: none !important; }

    body {
        background: white;
        padding: 0;
        margin: 0;
    }

    .receipt-wrap {
        padding: 0;
        display: block;
    }

    .receipt {
        border: none;
        box-shadow: none;
        border-radius: 0;
        margin: 0;
        width: 100% !important;
        min-height: auto !important;
    }

    .receipt--a5 { padding: 8mm; }
    .receipt--a4 { padding: 12mm; }
    .receipt--80mm { padding: 2mm; }
    .receipt--58mm { padding: 2mm; }
}

/* A5 print */
@page { size: A5 portrait; margin: 5mm; }

/* Thermal overrides via class-triggered pages */
body.print-thermal-80 .receipt { width: 100%; }
body.print-thermal-58 .receipt { width: 100%; }

@media print and (max-width: 90mm) {
    @page { size: 80mm auto; margin: 2mm; }
}

/* ── RESPONSIVE (mobile toolbar) ────────── */
@media screen and (max-width: 640px) {
    .toolbar {
        flex-wrap: wrap;
        gap: 8px;
    }
    .toolbar__actions {
        width: 100%;
        justify-content: flex-end;
    }
    .format-tabs {
        width: 100%;
        order: 3;
        justify-content: center;
    }
    .receipt-wrap { padding: 16px 8px 40px; }
    .receipt--a5, .receipt--a4 { width: 100%; min-height: auto; padding: 16px; }
}
</style>
</head>
<body class="{{ in_array($format, ['58mm','thermal-58']) ? 'print-thermal-58' : (in_array($format, ['80mm','thermal']) ? 'print-thermal-80' : '') }}">

{{-- ═══ TOOLBAR (screen only) ═══ --}}
<div class="toolbar">
    <div class="toolbar__left">
        @php
            $backUrl = \App\Support\BackUrl::resolve(request(), match(Auth::user()->role ?? 'customer') {
                'admin' => 'admin.orders',
                'driver' => 'driver.orders',
                default => 'customer.orders',
            });
        @endphp
        <a href="{{ $backUrl }}" class="toolbar__back" title="Kembali">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <span class="toolbar__title">Nota {{ $order->order_code }}</span>
    </div>

    <div class="format-tabs">
        <button class="format-tab {{ $format === '58mm' ? 'active' : '' }}" onclick="switchFormat('58mm')">58mm</button>
        <button class="format-tab {{ $format === '80mm' ? 'active' : '' }}" onclick="switchFormat('80mm')">80mm</button>
        <button class="format-tab {{ $format === 'a5' ? 'active' : '' }}" onclick="switchFormat('a5')">A5</button>
        <button class="format-tab {{ $format === 'a4' ? 'active' : '' }}" onclick="switchFormat('a4')">A4</button>
    </div>

    <div class="toolbar__actions">
        <button class="toolbar__btn" onclick="window.print()" title="Cetak">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak
        </button>
        <a href="{{ route('orders.receipt.pdf', ['order' => $order->id, 'format' => $format]) }}" class="toolbar__btn toolbar__btn--primary" title="Download PDF">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            PDF
        </a>
    </div>
</div>

{{-- ═══ RECEIPT BODY ═══ --}}
@php
    $paperClass = match($format) {
        '58mm'    => 'receipt--58mm',
        '80mm'    => 'receipt--80mm',
        'thermal' => 'receipt--80mm',
        'a4'      => 'receipt--a4',
        default   => 'receipt--a5',
    };

    $subtotalService = (int) ($order->service_cost ?? 0);
    $itemTotal = $order->items ? $order->items->where('service_id', '!=', $order->service_id)->sum('line_total') : 0;
    $pickupCost = (int) ($order->pickup_cost ?? 0);
    $discount = (int) ($order->discount ?? 0);
    $total = (int) ($order->total_cost ?? 0);
@endphp

<div class="receipt-wrap">
    <div class="receipt {{ $paperClass }}">

        {{-- Header --}}
        <div class="r-header">
            <div class="r-header__brand">{{ $laundry['name'] }}</div>
            <div class="r-header__sub">
                {{ $laundry['address'] }}<br>
                Telp: {{ $laundry['phone'] }}
            </div>
            <div class="r-header__code">#{{ $order->order_code }}</div>
        </div>

        {{-- Info Grid --}}
        <div class="r-grid">
            <div class="r-box">
                <div class="r-box__label">Pelanggan</div>
                <div class="r-box__value">{{ $order->customer->name ?? '-' }}</div>
            </div>
            <div class="r-box">
                <div class="r-box__label">Tanggal</div>
                <div class="r-box__value">{{ optional($order->created_at)->translatedFormat('d M Y') }}</div>
            </div>
            <div class="r-box">
                <div class="r-box__label">Layanan</div>
                <div class="r-box__value">{{ $order->service->name ?? '-' }}</div>
            </div>
            <div class="r-box">
                <div class="r-box__label">Status</div>
                <div class="r-box__value">{{ $order->status_label }}</div>
            </div>
        </div>

        {{-- Alamat (compact) --}}
        @if($order->address && $order->address !== 'Walk-in (Datang Langsung)')
        <div class="r-box" style="margin-bottom: 14px;">
            <div class="r-box__label">Alamat Jemput</div>
            <div class="r-box__value">{{ $order->address }}@if($order->address_note) ({{ $order->address_note }})@endif</div>
        </div>
        @endif

        {{-- Items Table --}}
        <table class="r-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="t-right">Qty/Kg</th>
                    <th class="t-right">Harga</th>
                    <th class="t-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                <tr>
                    <td>{{ $item->item_description ?? ($item->service->name ?? '-') }}</td>
                    <td class="t-right t-mono">
                        @if(!is_null($item->weight_kg))
                            {{ rtrim(rtrim(number_format((float)$item->weight_kg, 1, '.', ''), '0'), '.') }} kg
                        @else
                            {{ (int)$item->qty }}
                        @endif
                    </td>
                    <td class="t-right t-mono">{{ number_format((int)$item->unit_price, 0, ',', '.') }}</td>
                    <td class="t-right t-mono">{{ number_format((int)$item->line_total, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td>{{ $order->service->name ?? '-' }}</td>
                    <td class="t-right t-mono">{{ rtrim(rtrim(number_format((float)$order->weight_estimate, 1, '.', ''), '0'), '.') }} kg</td>
                    <td class="t-right t-mono">-</td>
                    <td class="t-right t-mono">{{ number_format($subtotalService, 0, ',', '.') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Totals --}}
        <div class="r-total">
            <div class="r-total__row">
                <span class="r-total__row-label">Subtotal Layanan</span>
                <span class="r-total__row-value">Rp {{ number_format($subtotalService, 0, ',', '.') }}</span>
            </div>
            @if($itemTotal > 0)
            <div class="r-total__row">
                <span class="r-total__row-label">Item Satuan</span>
                <span class="r-total__row-value">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="r-total__row">
                <span class="r-total__row-label">Biaya Jemput (Zona {{ $order->zone }})</span>
                <span class="r-total__row-value">Rp {{ number_format($pickupCost, 0, ',', '.') }}</span>
            </div>
            @if($discount > 0)
            <div class="r-total__row">
                <span class="r-total__row-label">Diskon</span>
                <span class="r-total__row-value" style="color: var(--green);">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
            </div>
            @endif
            <hr class="r-total__divider">
            <div class="r-total__grand-row">
                <span class="r-total__grand-label">Total Bayar</span>
                <span class="r-total__grand">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment status --}}
        <div class="r-payment {{ $order->is_paid ? 'r-payment--paid' : 'r-payment--unpaid' }}">
            @if($order->is_paid)
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                Lunas {{ $order->paid_at ? '— ' . $order->paid_at->translatedFormat('d M Y') : '' }}
            @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                {{ strtoupper($order->payment_method ?? 'COD') }} — Bayar saat diterima
            @endif
        </div>

        {{-- Notes --}}
        @if($order->notes)
        <div class="r-box" style="margin-bottom: 14px;">
            <div class="r-box__label">Catatan</div>
            <div class="r-box__value">{{ $order->notes }}</div>
        </div>
        @endif

        {{-- Footer --}}
        <div class="r-footer">
            <div class="r-footer__thanks">Terima kasih telah mempercayakan cucian Anda kepada kami.</div>
            <div>{{ $laundry['name'] }} &middot; {{ $laundry['phone'] }}</div>
            <div>{{ $laundry['address'] }}</div>
        </div>
    </div>
</div>

{{-- ═══ SCRIPTS ═══ --}}
<script>
function switchFormat(fmt) {
    const url = new URL(window.location.href);
    url.searchParams.set('format', fmt);
    window.location.href = url.toString();
}
</script>
</body>
</html>
