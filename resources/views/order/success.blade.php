<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Pesanan Berhasil – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--orange:#FF6B35;--green:#00C48C;--surface:#f5f9ff;--border:#ddeeff;--radius:16px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Nunito',sans-serif;background:var(--surface);min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px 16px;}

.card{background:#fff;border-radius:var(--radius);border:1.5px solid var(--border);padding:32px 24px;max-width:400px;width:100%;text-align:center;box-shadow:0 8px 40px rgba(0,47,92,.1);}

/* success icon */
.success-ring{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--green),#00e0a0);margin:0 auto 20px;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(0,196,140,.35);animation:pop .5s cubic-bezier(.175,.885,.32,1.275) both;}
@keyframes pop{from{transform:scale(0);opacity:0}to{transform:scale(1);opacity:1}}
.success-ring svg{width:38px;height:38px;color:#fff;}

h1{font-family:'Fredoka One',cursive;font-size:1.5rem;color:var(--blue-dark);margin-bottom:6px;}
.subtitle{font-size:.88rem;font-weight:600;color:#6b7a8d;margin-bottom:24px;}

/* order code badge */
.order-code{background:var(--surface);border:1.5px dashed var(--border);border-radius:10px;padding:12px;margin-bottom:20px;}
.oc-label{font-size:.7rem;font-weight:800;color:#8899aa;letter-spacing:.8px;text-transform:uppercase;}
.oc-value{font-family:'Fredoka One',cursive;font-size:1.3rem;color:var(--blue-mid);letter-spacing:1px;}

/* detail rows */
.detail-list{text-align:left;margin-bottom:24px;}
.detail-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid var(--border);}
.detail-row:last-child{border-bottom:none;}
.dr-label{font-size:.8rem;font-weight:700;color:#8899aa;}
.dr-value{font-size:.85rem;font-weight:800;color:var(--blue-dark);}
.dr-value.total{font-family:'Fredoka One',cursive;font-size:1rem;color:var(--orange);}

/* status badge */
.status-badge{display:inline-flex;align-items:center;gap:5px;background:#fff8f0;border:1.5px solid #ffd4b8;border-radius:99px;padding:5px 12px;margin-bottom:24px;}
.status-dot{width:8px;height:8px;border-radius:50%;background:var(--orange);animation:pulse-dot 1.5s ease-in-out infinite;}
@keyframes pulse-dot{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.8)}}
.status-label{font-size:.78rem;font-weight:800;color:var(--orange);}

.btn-home{display:block;width:100%;padding:14px;background:linear-gradient(135deg,var(--orange) 0%,#ff8c5a 100%);color:#fff;font-family:'Nunito',sans-serif;font-weight:900;font-size:.95rem;border:none;border-radius:10px;cursor:pointer;text-decoration:none;text-align:center;box-shadow:0 6px 18px rgba(255,107,53,.35);transition:transform .15s;}
.btn-home:active{transform:scale(.97);}
.btn-secondary{display:block;width:100%;padding:12px;margin-top:10px;background:none;color:var(--blue-mid);font-family:'Nunito',sans-serif;font-weight:800;font-size:.9rem;border:1.5px solid var(--border);border-radius:10px;cursor:pointer;text-decoration:none;text-align:center;transition:background .2s;}
.btn-secondary:hover{background:var(--surface);}
</style>
</head>
<body>

<div class="card">
  <div class="success-ring">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="20 6 9 17 4 12"/>
    </svg>
  </div>

  <h1>Pesanan Dibuat! 🎉</h1>
  <p class="subtitle">Driver kami akan segera menghubungimu untuk konfirmasi penjemputan.</p>

  <div class="order-code">
    <div class="oc-label">Kode Pesanan</div>
    <div class="oc-value">{{ $order->order_code }}</div>
  </div>

  <div class="status-badge">
    <span class="status-dot"></span>
    <span class="status-label">Menunggu Driver</span>
  </div>

  <div class="detail-list">
    @if($order->items && $order->items->count() > 0)
    <div class="detail-row">
      <span class="dr-label">Rincian Item</span>
      <span class="dr-value">{{ $order->items->count() }} baris layanan</span>
    </div>
    @foreach($order->items as $it)
    <div class="detail-row">
      <span class="dr-label">- {{ $it->item_description ?? ($it->service->name ?? 'Layanan') }}</span>
      <span class="dr-value">
        @if(!is_null($it->weight_kg))
          {{ $it->weight_kg }} kg
        @else
          {{ $it->qty }} item
        @endif
        · Rp {{ number_format($it->line_total, 0, ',', '.') }}
      </span>
    </div>
    @endforeach
    @endif
    <div class="detail-row">
      <span class="dr-label">Layanan</span>
      <span class="dr-value">{{ $order->service->name }}</span>
    </div>
    <div class="detail-row">
      <span class="dr-label">Estimasi Berat</span>
      <span class="dr-value">{{ $order->weight_estimate }} kg</span>
    </div>
    <div class="detail-row">
      <span class="dr-label">Jadwal Jemput</span>
      <span class="dr-value">
        {{ \Carbon\Carbon::parse($order->pickup_date)->translatedFormat('D, d M Y') }},
        {{ ucfirst($order->pickup_time) }}
      </span>
    </div>
    <div class="detail-row">
      <span class="dr-label">Zona</span>
      <span class="dr-value">Zona {{ $order->zone }}</span>
    </div>
    <div class="detail-row">
      <span class="dr-label">Pembayaran</span>
      <span class="dr-value">COD (Bayar di Tempat)</span>
    </div>
    <div class="detail-row">
      <span class="dr-label">Total Estimasi</span>
      <span class="dr-value total">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</span>
    </div>
  </div>

  <a href="{{ route('customer.dashboard') }}" class="btn-home">Kembali ke Beranda</a>
  <a href="{{ route('order.show', $order->order_code) }}" class="btn-secondary">Lacak Pesanan →</a>
</div>

</body>
</html>