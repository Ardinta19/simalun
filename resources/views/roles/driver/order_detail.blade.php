<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Detail Tugas #{{ strtoupper($order->order_code) }} – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{
  --blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;
  --blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;
  --red:#ef4444;--surface:#f4f8fc;--card:#fff;
  --ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;
  --border:#ddeeff;--radius:18px;--radius-sm:12px;
}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden}

.hero{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);padding:max(env(safe-area-inset-top,0px),20px) 20px 32px;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.06);top:-60px;right:-40px}
.hero-inner{max-width:520px;margin:0 auto;position:relative;z-index:2}
.hero-nav{display:flex;align-items:center;gap:12px;margin-bottom:16px}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;flex-shrink:0}
.hero-code{font-family:'Fredoka One',cursive;font-size:.85rem;color:rgba(255,255,255,.75);letter-spacing:1px;text-transform:uppercase}
.hero-status{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.28);border-radius:99px;padding:5px 12px;font-size:.7rem;font-weight:900;color:#fff;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px}
.hero-title{font-family:'Fredoka One',cursive;font-size:1.8rem;color:#fff;line-height:1.1;text-shadow:0 2px 12px rgba(0,0,0,.2)}
.hero-sub{font-size:.85rem;font-weight:700;color:rgba(255,255,255,.78);margin-top:4px}

.content{max-width:520px;margin:-16px auto 0;padding:0 16px;position:relative;z-index:10}

.card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);margin-bottom:12px;box-shadow:0 4px 16px rgba(0,47,92,.05);overflow:hidden}
.card-head{display:flex;align-items:center;gap:10px;padding:14px 16px;border-bottom:1px solid var(--border);background:linear-gradient(90deg,rgba(0,119,182,.04) 0%,transparent 100%)}
.card-icon{width:30px;height:30px;border-radius:10px;background:var(--blue-sky);display:flex;align-items:center;justify-content:center;font-size:.9rem}
.card-title{font-family:'Fredoka One',cursive;font-size:.92rem;color:var(--blue-dark)}

.row{display:flex;align-items:flex-start;justify-content:space-between;padding:11px 16px;border-bottom:1px solid #f1f5f9;gap:12px}
.row:last-child{border-bottom:none}
.row-l{font-size:.78rem;font-weight:700;color:var(--ink-lt);flex-shrink:0}
.row-v{font-size:.85rem;font-weight:800;color:var(--ink);text-align:right;max-width:60%;word-break:break-word;line-height:1.4}
.row-v.green{color:var(--green)}
.row-v.blue{color:var(--blue-mid)}

.contact-bar{display:grid;grid-template-columns:1fr 1fr;gap:8px;padding:12px 16px;background:#f8fafc}
.contact-btn{display:flex;align-items:center;justify-content:center;gap:6px;padding:11px;border-radius:var(--radius-sm);font-weight:900;font-size:.78rem;text-decoration:none;border:none;cursor:pointer}
.contact-btn.wa{background:#e6fff6;color:#065f46}
.contact-btn.call{background:var(--blue-sky);color:var(--blue-mid)}

.action-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:14px 16px;margin-bottom:12px;box-shadow:0 4px 16px rgba(0,47,92,.06)}
.action-title{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--blue-dark);margin-bottom:8px}
.action-hint{font-size:.74rem;font-weight:700;color:var(--ink-lt);margin-bottom:12px;line-height:1.5}
.field{display:flex;flex-direction:column;gap:6px;margin-bottom:10px}
.field-label{font-size:.72rem;font-weight:800;color:var(--ink-mid)}
.field input[type="number"],.field input[type="file"]{
    width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:10px;
    font-family:'Nunito',sans-serif;font-weight:700;font-size:.88rem;color:var(--ink);
    background:#f8fafc;
}
.field input[type="number"]:focus{outline:none;border-color:var(--blue-mid);background:#fff}

.btn-action{width:100%;padding:13px;border-radius:var(--radius-sm);border:none;font-family:'Nunito',sans-serif;font-weight:900;font-size:.88rem;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:transform .15s}
.btn-action:active{transform:scale(.97)}
.btn-action.primary{background:var(--blue-mid);color:#fff;box-shadow:0 4px 14px rgba(0,119,182,.3)}
.btn-action.success{background:var(--green);color:#fff;box-shadow:0 4px 14px rgba(0,196,140,.3)}
.btn-action.warn{background:var(--orange);color:#fff;box-shadow:0 4px 14px rgba(255,107,53,.3)}

.alert-ok{background:rgba(0,196,140,.12);border:1.5px solid rgba(0,196,140,.3);color:#065f46;padding:12px 14px;border-radius:12px;font-size:.84rem;font-weight:800;margin-bottom:12px}
.alert-err{background:rgba(239,68,68,.1);border:1.5px solid rgba(239,68,68,.25);color:#b91c1c;padding:12px 14px;border-radius:12px;font-size:.82rem;font-weight:800;margin-bottom:12px}

.history-item{display:flex;gap:12px;padding:10px 0;position:relative}
.history-item:not(:last-child)::before{content:'';position:absolute;left:6px;top:18px;bottom:-2px;width:2px;background:var(--border)}
.history-dot{width:14px;height:14px;border-radius:50%;background:var(--blue-mid);flex-shrink:0;margin-top:2px;z-index:2;box-shadow:0 0 0 3px rgba(0,119,182,.15)}
.history-text{flex:1}
.history-text .ht{font-size:.82rem;font-weight:800;color:var(--ink)}
.history-text .hs{font-size:.7rem;font-weight:700;color:var(--ink-lt);margin-top:2px}

.proof-img{width:100%;border-radius:12px;max-height:280px;object-fit:cover;border:1px solid var(--border)}

.driver-nav{position:fixed;bottom:0;left:0;right:0;z-index:999;background:rgba(255,255,255,.97);backdrop-filter:blur(20px);border-top:1px solid var(--border);box-shadow:0 -4px 24px rgba(0,47,92,.08);padding-bottom:env(safe-area-inset-bottom,0px)}
.driver-nav__inner{max-width:520px;margin:0 auto;display:flex;align-items:center;height:64px;padding:0 16px}
.driver-nav__item{flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:3px;text-decoration:none;color:#94a3b8;padding:6px 0}
.driver-nav__item.is-active{color:var(--blue-mid)}
.driver-nav__icon svg{width:22px;height:22px}
.driver-nav__label{font-size:.6rem;font-weight:800;text-transform:uppercase;letter-spacing:.4px}
</style>
</head>
<body>

<div class="hero">
    <div class="hero-inner">
        <div class="hero-nav">
            <a href="{{ route('driver.orders') }}" class="btn-back">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <div class="hero-code">#{{ strtoupper($order->order_code) }}</div>
        </div>
        <div class="hero-status">
            <span style="width:7px;height:7px;border-radius:50%;background:{{ $order->status_color ?? '#fff' }}"></span>
            {{ $order->status_label }}
        </div>
        <div class="hero-title">{{ $order->customer->name ?? 'Customer' }}</div>
        <div class="hero-sub">{{ $order->service->name ?? 'Layanan' }} • {{ ucfirst($order->pickup_time ?? '-') }}</div>
    </div>
</div>

<div class="content">

    @if(session('success'))
        <div class="alert-ok">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-err">⚠️ {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-err">⚠️ {{ $errors->first() }}</div>
    @endif

    {{-- Customer & Alamat --}}
    <div class="card">
        <div class="card-head">
            <div class="card-icon">📍</div>
            <div class="card-title">Lokasi Customer</div>
        </div>
        <div class="row">
            <span class="row-l">Alamat</span>
            <span class="row-v">{{ $order->customerAddress?->full_address ?? $order->address ?? '-' }}</span>
        </div>
        @if($order->address_note)
        <div class="row"><span class="row-l">Patokan</span><span class="row-v">{{ $order->address_note }}</span></div>
        @endif
        <div class="row"><span class="row-l">Zona</span><span class="row-v">Zona {{ $order->zone ?? 'A' }}</span></div>

        @if($order->customer?->phone)
        <div class="contact-bar">
            <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','', $order->customer->phone),'0') }}?text=Halo%20{{ urlencode($order->customer->name) }}%2C%20saya%20kurir%20Azka%20Laundry%20untuk%20pesanan%20%23{{ $order->order_code }}"
               target="_blank" class="contact-btn wa">💬 Chat WA</a>
            <a href="tel:{{ $order->customer->phone }}" class="contact-btn call">📞 Telepon</a>
        </div>
        @endif
    </div>

    {{-- Detail Pesanan --}}
    <div class="card">
        <div class="card-head">
            <div class="card-icon">🧺</div>
            <div class="card-title">Detail Pesanan</div>
        </div>
        <div class="row"><span class="row-l">Layanan</span><span class="row-v">{{ $order->service->name ?? '-' }}</span></div>
        <div class="row"><span class="row-l">Estimasi Berat</span><span class="row-v">{{ $order->weight_estimate }} kg</span></div>
        @if($order->weight_actual)
        <div class="row"><span class="row-l">Berat Aktual</span><span class="row-v blue">{{ $order->weight_actual }} kg</span></div>
        @endif
        <div class="row"><span class="row-l">Jadwal Jemput</span><span class="row-v">{{ $order->pickup_date?->format('d/m/Y') ?? '-' }}, {{ ucfirst($order->pickup_time ?? '-') }}</span></div>
        <div class="row"><span class="row-l">Total</span><span class="row-v green">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</span></div>
        <div class="row"><span class="row-l">Pembayaran</span><span class="row-v">{{ $order->is_paid ? '✓ Lunas' : 'COD (belum dibayar)' }}</span></div>
        @if($order->notes)
        <div class="row"><span class="row-l">Catatan</span><span class="row-v">{{ $order->notes }}</span></div>
        @endif

        @if($order->items && $order->items->count() > 1)
        <div style="padding:10px 16px 12px;background:#f8fafc;font-size:.74rem;font-weight:800;color:var(--ink-lt);text-transform:uppercase;letter-spacing:.5px">Item Satuan</div>
        @foreach($order->items->skip(1) as $item)
        <div class="row" style="background:#f8fafc">
            <span class="row-l">{{ $item->item_description }}</span>
            <span class="row-v">{{ $item->qty }}× • Rp {{ number_format($item->line_total, 0, ',', '.') }}</span>
        </div>
        @endforeach
        @endif
    </div>

    {{-- Aksi Berdasarkan Status --}}
    @if($order->status === 'dijemput')
    <div class="action-card">
        <div class="action-title">🛵 Konfirmasi Jemput</div>
        <div class="action-hint">Setelah pakaian diterima dari customer, masukkan berat aktual lalu konfirmasi.</div>
        <form method="POST" action="{{ route('driver.orders.action', $order) }}">
            @csrf
            <input type="hidden" name="status" value="dicuci">
            <div class="field">
                <label class="field-label" for="weight_actual">Berat Aktual (kg)</label>
                <input type="number" step="0.1" min="0.1" max="50" name="weight_actual" id="weight_actual"
                       placeholder="contoh: 4.5" value="{{ old('weight_actual', $order->weight_estimate) }}" required>
            </div>
            <button type="submit" class="btn-action success">✅ Konfirmasi Jemput</button>
        </form>
    </div>
    @endif

    @if($order->status === 'dikirim')
    <div class="action-card">
        <div class="action-title">📦 Konfirmasi Selesai</div>
        <div class="action-hint">Setelah pakaian diserahkan, foto buktinya wajib diunggah.</div>
        <form method="POST" action="{{ route('driver.orders.action', $order) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="status" value="selesai">
            <div class="field">
                <label class="field-label" for="proof_image">Foto Bukti Pengiriman</label>
                <input type="file" name="proof_image" id="proof_image" accept="image/*" capture="environment" required>
            </div>
            <button type="submit" class="btn-action warn">📸 Selesaikan Pesanan</button>
        </form>
    </div>
    @endif

    {{-- Bukti Foto (jika sudah selesai) --}}
    @if($order->status === 'selesai' && $order->proof_image)
    <div class="card">
        <div class="card-head">
            <div class="card-icon">📸</div>
            <div class="card-title">Bukti Pengiriman</div>
        </div>
        <div style="padding:14px">
            <img src="{{ asset('storage/' . $order->proof_image) }}" class="proof-img" alt="Bukti">
        </div>
    </div>
    @endif

    {{-- History --}}
    @if(isset($histori) && $histori->count() > 0)
    <div class="card">
        <div class="card-head">
            <div class="card-icon">🕒</div>
            <div class="card-title">Riwayat Status</div>
        </div>
        <div style="padding:8px 16px 14px">
            @foreach($histori as $h)
            <div class="history-item">
                <div class="history-dot"></div>
                <div class="history-text">
                    <div class="ht">{{ ucfirst(str_replace('_',' ',$h->status_code)) }}</div>
                    <div class="hs">{{ $h->status_note ?? '-' }} @if($h->updated_at) • {{ $h->updated_at->translatedFormat('d M, H:i') }}@endif</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

<nav class="driver-nav">
    <div class="driver-nav__inner">
        <a href="{{ route('driver.dashboard') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>
            <span class="driver-nav__label">Beranda</span>
        </a>
        <a href="{{ route('driver.orders') }}" class="driver-nav__item is-active">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/></svg></span>
            <span class="driver-nav__label">Tugas</span>
        </a>
        <a href="{{ route('driver.notifications') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg></span>
            <span class="driver-nav__label">Notif</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="driver-nav__item">
            <span class="driver-nav__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
            <span class="driver-nav__label">Profil</span>
        </a>
    </div>
</nav>

</body>
</html>
