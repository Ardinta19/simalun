<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resi {{ $order->order_code }}</title>
<style>
:root{--ink:#111827;--muted:#6b7280;--line:#d1d5db;--accent:#0f6fa6;}
*{box-sizing:border-box}
body{font-family:Arial,Helvetica,sans-serif;color:var(--ink);margin:0;padding:0;background:#fff}
.sheet{margin:0 auto;padding:10mm}
.paper-a4{width:210mm;min-height:297mm}
.paper-note{width:148mm;min-height:210mm}
.paper-thermal{width:80mm;min-height:160mm}
.head{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:1px dashed var(--line);padding-bottom:8px;margin-bottom:10px}
.brand{font-weight:800;font-size:20px;letter-spacing:.3px}
.tag{font-size:11px;color:var(--muted);margin-top:2px}
.code{font-weight:800;font-size:14px;color:var(--accent)}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px}
.box{border:1px solid var(--line);border-radius:6px;padding:8px}
.k{font-size:11px;color:var(--muted);margin-bottom:2px}
.v{font-size:13px;font-weight:700;line-height:1.35}
.table{width:100%;border-collapse:collapse;margin-top:8px}
.table th,.table td{border-bottom:1px solid var(--line);padding:6px 4px;font-size:12px;text-align:left;vertical-align:top}
.table th{font-size:11px;color:var(--muted);font-weight:700}
.t-right{text-align:right}
.total{margin-top:10px;border:1px solid var(--line);border-radius:6px;padding:8px}
.row{display:flex;justify-content:space-between;font-size:13px;padding:2px 0}
.row.total-line{font-weight:800;font-size:16px;color:#0b3f5f;padding-top:6px;margin-top:4px;border-top:1px dashed var(--line)}
.footer{margin-top:14px;font-size:11px;color:var(--muted);line-height:1.5}
.actions{position:fixed;right:12px;top:10px;display:flex;gap:8px}
.btn{border:0;border-radius:8px;padding:8px 10px;font-size:12px;font-weight:700;cursor:pointer;color:#fff;background:#0f6fa6}
.btn.alt{background:#374151}
@media print{
  .actions{display:none}
  body{background:#fff}
  .sheet{padding:0;margin:0 auto}
  @page{size:A4;margin:10mm}
}
@media print and (max-width:90mm){
  @page{size:80mm auto;margin:4mm}
}
</style>
</head>
<body>
<div class="actions">
  <button class="btn" onclick="window.print()">Cetak</button>
  <button class="btn alt" onclick="window.close()">Tutup</button>
</div>
@php
  $format = request('format', 'a4');
  $paperClass = $format === 'thermal' ? 'paper-thermal' : ($format === 'note' ? 'paper-note' : 'paper-a4');
  $subtotal = (int) ($order->service_cost ?? 0);
  $pickup = (int) ($order->pickup_cost ?? 0);
  $diskon = (int) ($order->discount ?? 0);
  $total = (int) ($order->total_cost ?? ($subtotal + $pickup - $diskon));
@endphp
<div class="sheet {{ $paperClass }}">
  <div class="head">
    <div>
      <div class="brand">SIMALUN Laundry</div>
      <div class="tag">Resi Pesanan</div>
    </div>
    <div style="text-align:right">
      <div class="code">{{ $order->order_code }}</div>
      <div class="tag">{{ optional($order->created_at)->translatedFormat('d M Y H:i') }}</div>
    </div>
  </div>

  <div class="grid">
    <div class="box">
      <div class="k">Customer</div>
      <div class="v">{{ $order->customer->name ?? '-' }}</div>
      <div class="k" style="margin-top:6px">Alamat</div>
      <div class="v" style="font-size:12px">{{ $order->address ?? '-' }}</div>
    </div>
    <div class="box">
      <div class="k">Layanan Utama</div>
      <div class="v">{{ $order->service->name ?? '-' }}</div>
      <div class="k" style="margin-top:6px">Status</div>
      <div class="v">{{ $order->status_label }}</div>
    </div>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th>Layanan</th>
        <th class="t-right">Qty/Kg</th>
        <th class="t-right">Harga</th>
        <th class="t-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($order->items as $item)
      <tr>
        <td>{{ $item->item_description ?? ($item->service->name ?? '-') }}</td>
        <td class="t-right">{{ !is_null($item->weight_kg) ? rtrim(rtrim(number_format((float)$item->weight_kg, 1, '.', ''), '0'), '.') . ' kg' : ((int)$item->qty) }}</td>
        <td class="t-right">Rp {{ number_format((int)$item->unit_price,0,',','.') }}</td>
        <td class="t-right">Rp {{ number_format((int)$item->line_total,0,',','.') }}</td>
      </tr>
      @empty
      <tr>
        <td>{{ $order->service->name ?? '-' }}</td>
        <td class="t-right">{{ rtrim(rtrim(number_format((float)$order->weight_estimate, 1, '.', ''), '0'), '.') }} kg</td>
        <td class="t-right">Rp {{ number_format((int)($order->service_cost ?? 0),0,',','.') }}</td>
        <td class="t-right">Rp {{ number_format((int)($order->service_cost ?? 0),0,',','.') }}</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="total">
    <div class="row"><span>Subtotal Layanan</span><strong>Rp {{ number_format($subtotal,0,',','.') }}</strong></div>
    <div class="row"><span>Biaya Jemput</span><strong>Rp {{ number_format($pickup,0,',','.') }}</strong></div>
    <div class="row"><span>Diskon</span><strong>- Rp {{ number_format($diskon,0,',','.') }}</strong></div>
    <div class="row total-line"><span>Total</span><span>Rp {{ number_format($total,0,',','.') }}</span></div>
  </div>

  <div class="footer">
    <div>Metode Bayar: {{ strtoupper($order->payment_method ?? 'COD') }}</div>
    <div>Catatan: {{ $order->notes ?: '-' }}</div>
    <div style="margin-top:6px">Terima kasih. Simpan resi ini untuk pengambilan/cross-check pesanan.</div>
  </div>
</div>
</body>
</html>
