<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Nota #{{ $order->order_code }}</title>
<style>
/* ═══════════════════════════════════════════════════
   AZKA LAUNDRY — PDF RECEIPT (DomPDF compatible)
   ═══════════════════════════════════════════════════ */

@page {
    margin: 8mm;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Helvetica', 'Arial', sans-serif;
    font-size: 10pt;
    color: #1a1a2e;
    line-height: 1.5;
}

/* ── HEADER ─────────────────────────────── */
.header {
    text-align: center;
    padding-bottom: 10px;
    margin-bottom: 12px;
    border-bottom: 2px dashed #cbd5e1;
}

.brand {
    font-size: 16pt;
    font-weight: bold;
    color: #0b5394;
    margin-bottom: 2px;
}

.brand-sub {
    font-size: 8pt;
    color: #4a5568;
    line-height: 1.6;
}

.order-code {
    display: inline-block;
    margin-top: 8px;
    padding: 3px 10px;
    background-color: #e8f4fd;
    font-size: 9pt;
    font-weight: bold;
    color: #0b5394;
    letter-spacing: 0.5px;
}

/* ── INFO GRID ──────────────────────────── */
.info-table {
    width: 100%;
    margin-bottom: 12px;
    border-collapse: collapse;
}

.info-table td {
    width: 50%;
    vertical-align: top;
    padding: 4px 0;
}

.info-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 8px;
    margin: 2px;
}

.info-label {
    font-size: 7pt;
    font-weight: bold;
    color: #8896a6;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 2px;
}

.info-value {
    font-size: 9pt;
    font-weight: bold;
    color: #1a1a2e;
}

/* ── ADDRESS ────────────────────────────── */
.address-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 12px;
}

/* ── ITEMS TABLE ────────────────────────── */
.items-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 14px;
}

.items-table th {
    font-size: 7.5pt;
    font-weight: bold;
    color: #8896a6;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 6px 4px;
    text-align: left;
    border-bottom: 1.5px solid #e2e8f0;
}

.items-table td {
    font-size: 9pt;
    padding: 7px 4px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
}

.items-table .right {
    text-align: right;
}

/* ── TOTALS ─────────────────────────────── */
.totals-box {
    border: 1.5px solid #e2e8f0;
    border-radius: 6px;
    padding: 10px 12px;
    margin-bottom: 12px;
    background-color: #f8fafc;
}

.total-row {
    padding: 3px 0;
    overflow: hidden;
}

.total-row .label {
    float: left;
    font-size: 9pt;
    color: #4a5568;
}

.total-row .value {
    float: right;
    font-size: 9pt;
    font-weight: bold;
    color: #1a1a2e;
}

.total-divider {
    border: none;
    border-top: 1.5px dashed #cbd5e1;
    margin: 8px 0;
}

.total-grand .label {
    font-size: 10pt;
    font-weight: bold;
    color: #1a1a2e;
}

.total-grand .value {
    font-size: 13pt;
    font-weight: bold;
    color: #0b5394;
}

/* ── PAYMENT ────────────────────────────── */
.payment-badge {
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 8.5pt;
    font-weight: bold;
    margin-bottom: 12px;
}

.payment-paid {
    background-color: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.payment-unpaid {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

/* ── NOTES ──────────────────────────────── */
.notes-box {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    padding: 8px;
    margin-bottom: 12px;
}

/* ── FOOTER ─────────────────────────────── */
.footer {
    text-align: center;
    padding-top: 10px;
    border-top: 1.5px dashed #cbd5e1;
    font-size: 8pt;
    color: #8896a6;
    line-height: 1.7;
}

.footer-thanks {
    font-weight: bold;
    color: #4a5568;
    margin-bottom: 3px;
}
</style>
</head>
<body>
@php
    $subtotalService = (int) ($order->service_cost ?? 0);
    $itemTotal = $order->items ? $order->items->where('service_id', '!=', $order->service_id)->sum('line_total') : 0;
    $pickupCost = (int) ($order->pickup_cost ?? 0);
    $discount = (int) ($order->discount ?? 0);
    $total = (int) ($order->service_cost ?? 0) + $itemTotal + $pickupCost - $discount;
@endphp

{{-- HEADER --}}
<div class="header">
    <div class="brand">{{ $laundry['name'] }}</div>
    <div class="brand-sub">
        {{ $laundry['address'] }}<br>
        Telp: {{ $laundry['phone'] }}
    </div>
    <div class="order-code">#{{ $order->order_code }}</div>
</div>

{{-- INFO GRID --}}
<table class="info-table">
    <tr>
        <td>
            <div class="info-box">
                <div class="info-label">Pelanggan</div>
                <div class="info-value">{{ $order->customer->name ?? '-' }}</div>
            </div>
        </td>
        <td>
            <div class="info-box">
                <div class="info-label">Tanggal</div>
                <div class="info-value">{{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <div class="info-box">
                <div class="info-label">Layanan</div>
                <div class="info-value">{{ $order->service->name ?? '-' }}</div>
            </div>
        </td>
        <td>
            <div class="info-box">
                <div class="info-label">Status</div>
                <div class="info-value">{{ $order->status_label }}</div>
            </div>
        </td>
    </tr>
</table>

{{-- ADDRESS --}}
@if($order->address && $order->address !== 'Walk-in (Datang Langsung)')
<div class="address-box">
    <div class="info-label">Alamat Jemput</div>
    <div class="info-value">{{ $order->address }}@if($order->address_note) &mdash; {{ $order->address_note }}@endif</div>
</div>
@endif

{{-- RIWAYAT KURIR PER LEG (pickup vs delivery) --}}
@php
    $pickupAssignment = $order->pickupAssignment();
    $deliveryAssignment = $order->deliveryAssignment();
@endphp
@if($pickupAssignment || $deliveryAssignment)
<table class="info-table">
    <tr>
        @if($pickupAssignment)
        <td>
            <div class="info-box">
                <div class="info-label">Dijemput oleh</div>
                <div class="info-value">
                    {{ $pickupAssignment->driver->name ?? '-' }}
                    @if($pickupAssignment->actual_time)
                        &mdash; {{ $pickupAssignment->actual_time->format('d/m H:i') }}
                    @endif
                </div>
            </div>
        </td>
        @endif
        @if($deliveryAssignment)
        <td>
            <div class="info-box">
                <div class="info-label">Diantar oleh</div>
                <div class="info-value">
                    {{ $deliveryAssignment->driver->name ?? '-' }}
                    @if($deliveryAssignment->actual_time)
                        &mdash; {{ $deliveryAssignment->actual_time->format('d/m H:i') }}
                    @endif
                </div>
            </div>
        </td>
        @endif
    </tr>
</table>
@endif

{{-- ITEMS --}}
<table class="items-table">
    <thead>
        <tr>
            <th>Item</th>
            <th class="right">Qty/Kg</th>
            <th class="right">Harga</th>
            <th class="right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @forelse($order->items as $item)
        <tr>
            <td>{{ $item->item_description ?? ($item->service->name ?? '-') }}</td>
            <td class="right">
                @if(!is_null($item->weight_kg))
                    {{ rtrim(rtrim(number_format((float)$item->weight_kg, 1, '.', ''), '0'), '.') }} kg
                @else
                    {{ (int)$item->qty }}
                @endif
            </td>
            <td class="right">Rp {{ number_format((int)$item->unit_price, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format((int)$item->line_total, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr>
            <td>{{ $order->service->name ?? '-' }}</td>
            <td class="right">{{ rtrim(rtrim(number_format((float)$order->weight_estimate, 1, '.', ''), '0'), '.') }} kg</td>
            <td class="right">-</td>
            <td class="right">Rp {{ number_format($subtotalService, 0, ',', '.') }}</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- TOTALS --}}
<div class="totals-box">
    <div class="total-row">
        <span class="label">Subtotal Layanan</span>
        <span class="value">Rp {{ number_format($subtotalService, 0, ',', '.') }}</span>
    </div>
    @if($itemTotal > 0)
    <div class="total-row">
        <span class="label">Item Satuan</span>
        <span class="value">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
    </div>
    @endif
    <div class="total-row">
        <span class="label">Biaya Jemput (Zona {{ $order->zone }})</span>
        <span class="value">Rp {{ number_format($pickupCost, 0, ',', '.') }}</span>
    </div>
    @if($discount > 0)
    <div class="total-row">
        <span class="label">Diskon</span>
        <span class="value" style="color: #059669;">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
    </div>
    @endif
    <hr class="total-divider">
    <div class="total-row total-grand">
        <span class="label">Total Bayar</span>
        <span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>
</div>

{{-- PAYMENT STATUS --}}
<div class="payment-badge {{ $order->is_paid ? 'payment-paid' : 'payment-unpaid' }}">
    @if($order->is_paid)
        &#10003; Lunas{{ $order->payment_channel ? ' via '.strtoupper($order->payment_channel) : '' }} {{ $order->paid_at ? '— ' . $order->paid_at->format('d/m/Y') : '' }}
    @else
        {{ strtoupper($order->payment_method ?? 'COD') }} — Bayar saat diterima
    @endif
</div>

{{-- NOTES --}}
@if($order->notes)
<div class="notes-box">
    <div class="info-label">Catatan</div>
    <div class="info-value">{{ $order->notes }}</div>
</div>
@endif

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-thanks">Terima kasih telah mempercayakan cucian Anda kepada kami.</div>
    <div>{{ $laundry['name'] }} &middot; {{ $laundry['phone'] }}</div>
    <div>{{ $laundry['address'] }}</div>
</div>

</body>
</html>
