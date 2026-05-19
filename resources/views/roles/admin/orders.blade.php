<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Manajemen Pesanan – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#64748b;--line:#ddeeff;--accent:#FF6B35;--ok:#00C48C;--r:20px;--nav-h:76px}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;padding-bottom:100px;overflow-x:hidden}

.top{background:linear-gradient(145deg, var(--blue-dark) 0%, var(--blue) 100%);color:#fff;padding:40px 20px;border-radius:0 0 30px 30px;position:relative;overflow:hidden}
.top-in{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:center;position:relative;z-index:2}
.title{font-family:'Fredoka One',cursive;font-size:1.8rem}

.wrap{max-width:1200px;margin:20px auto;padding:0 20px}

/* Stats Grid */
.stats{display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:15px;margin-bottom:25px}
.stat-card{background:#fff;padding:20px;border-radius:var(--r);border:1.5px solid var(--line);box-shadow:0 4px 12px rgba(0,0,0,0.03)}
.stat-l{font-size:0.75rem;font-weight:900;color:var(--muted);text-transform:uppercase;letter-spacing:1px}
.stat-v{font-family:'Fredoka One',cursive;font-size:2rem;color:var(--blue);margin-top:5px}

/* Table Card */
.card{background:#fff;border-radius:var(--r);border:1.5px solid var(--line);overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.02)}
.table-h{padding:20px;background:#f8fafc;border-bottom:1.5px solid var(--line);display:flex;justify-content:space-between;align-items:center}
.table-h h3{font-family:'Fredoka One',cursive;color:var(--blue-dark)}

table{width:100%;border-collapse:collapse}
th{text-align:left;padding:15px 20px;background:#f8fafc;font-size:0.75rem;font-weight:900;color:var(--muted);text-transform:uppercase;letter-spacing:1px;border-bottom:1.5px solid var(--line)}
td{padding:15px 20px;border-bottom:1px solid #f1f5f9;font-size:0.9rem;font-weight:700}
tr:last-child td{border-bottom:none}

.badge{display:inline-block;padding:5px 12px;border-radius:99px;font-size:0.7rem;font-weight:900;text-transform:uppercase}
.badge.menunggu{background:#fff9e6;color:#b45309}
.badge.proses{background:#e0f2fe;color:var(--blue)}
.badge.selesai{background:#ecfdf5;color:var(--ok)}

.btn{padding:8px 16px;border-radius:10px;font-weight:900;font-size:0.75rem;cursor:pointer;border:none;transition:0.2s;text-decoration:none;display:inline-flex;align-items:center;gap:6px}
.btn-pri{background:var(--blue);color:#fff}
.btn-sec{background:var(--bg);color:var(--blue-dark);border:1.5px solid var(--line)}

.sidebar{position:fixed;left:20px;top:20px;bottom:20px;width:260px;background:var(--blue-dark);border-radius:30px;padding:30px;color:#fff;z-index:100}
.nav-link{display:flex;align-items:center;gap:12px;color:rgba(255,255,255,0.6);text-decoration:none;padding:12px 15px;border-radius:15px;margin-bottom:5px;font-weight:800;transition:0.3s}
.nav-link.active{background:rgba(255,255,255,0.1);color:#fff}

.main-content{margin-left:300px}
@media (max-width: 1024px) {
    .sidebar{display:none}
    .main-content{margin-left:0}
}
</style>
</head>
<body>

<aside class="sidebar">
    <div style="font-family:'Fredoka One',cursive; font-size:1.5rem; margin-bottom:40px">SIMALUN</div>
    <nav>
        <a href="{{ route('dashboard.admin') }}" class="nav-link"><span>🏠</span> Beranda</a>
        <a href="{{ route('admin.orders') }}" class="nav-link active"><span>📋</span> Pesanan</a>
        <a href="{{ route('admin.walkin.form') }}" class="nav-link"><span>🆕</span> Walk-in</a>
        <a href="{{ route('admin.finance.index') }}" class="nav-link"><span>💰</span> Keuangan</a>
        <a href="{{ route('profile.edit') }}" class="nav-link"><span>👤</span> Profil</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:20px">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer"><span>🚪</span> Keluar</button>
        </form>
    </nav>
</aside>

<main class="main-content">
    <header class="top">
        <div class="top-in js-in">
            <div class="title">Manajemen Pesanan</div>
            <div style="display:flex; gap:10px">
                <a href="{{ route('admin.walkin.form') }}" class="btn" style="background:var(--accent); color:#fff; padding:12px 20px">Order Walk-in 🧺</a>
            </div>
        </div>
    </header>

    <div class="wrap">
        <div class="stats js-in">
            <div class="stat-card">
                <div class="stat-l">Semua Pesanan</div>
                <div class="stat-v">{{ $jumlahSemua ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-l">Sedang Proses</div>
                <div class="stat-v" style="color:var(--accent)">{{ $jumlahAktif ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-l">Tuntas Selesai</div>
                <div class="stat-v" style="color:var(--ok)">{{ $jumlahSelesai ?? 0 }}</div>
            </div>
        </div>

        <div class="card js-in">
            <div class="table-h">
                <h3>Daftar Antrian</h3>
                <form method="GET" action="{{ route('admin.orders') }}" style="display:flex; gap:10px">
                    @php $currentStatus = request('status'); @endphp
                    <select name="status" onchange="this.form.submit()" style="padding:8px; border-radius:10px; border:1.5px solid var(--line); font-weight:700">
                        <option value="">Semua Status</option>
                        @foreach(['menunggu'=>'Menunggu','dijemput'=>'Dijemput','dicuci'=>'Sedang Dicuci','disetrika'=>'Sedang Disetrika','siap'=>'Siap Dikirim','dikirim'=>'Dalam Pengiriman','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'] as $key=>$label)
                            <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div style="overflow-x:auto">
                <table>
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $o)
                        <tr>
                            <td><span style="font-family:monospace; font-weight:900">#{{ strtoupper($o->order_code) }}</span></td>
                            <td>
                                <div>{{ $o->customer->name ?? '-' }}</div>
                                <div style="font-size:0.7rem; color:var(--muted)">{{ $o->customer->phone ?? '' }}</div>
                            </td>
                            <td>{{ $o->service->name ?? '-' }}</td>
                            <td><span class="badge {{ $o->status == 'selesai' ? 'selesai' : ($o->status == 'menunggu' ? 'menunggu' : 'proses') }}">{{ $o->status_label }}</span></td>
                            <td>Rp {{ number_format($o->total_cost, 0, ',', '.') }}</td>
                            <td>
                                <div style="display:flex; flex-direction:column; gap:8px">
                                    {{-- Panel Penugasan Driver --}}
                                    @if($o->status == 'menunggu')
                                    <form method="POST" action="{{ route('admin.orders.assign-driver', $o) }}" style="display:flex; gap:5px; background:#f0f9ff; padding:8px; border-radius:10px">
                                        @csrf
                                        <select name="driver_id" required style="padding:5px; border-radius:8px; border:1.5px solid var(--line); font-size:0.75rem; flex:1">
                                            <option value="">Pilih Kurir Pickup</option>
                                            @foreach($daftarDriver ?? [] as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="assignment_type" value="pickup">
                                        <button type="submit" class="btn btn-pri">Tugaskan</button>
                                    </form>
                                    @endif

                                    {{-- Panel Kontrol Workshop --}}
                                    @if(in_array($o->status, ['dicuci', 'disetrika']))
                                    <form method="POST" action="{{ route('admin.orders.update-status', $o) }}" style="display:flex; gap:5px; background:#fff7ed; padding:8px; border-radius:10px">
                                        @csrf
                                        @method('PATCH')
                                        @php $nextStatus = $o->status == 'dicuci' ? 'disetrika' : 'siap' @endphp
                                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                                        <div style="font-size:0.7rem; font-weight:800; color:#9a3412; flex:1">Update ke: {{ $nextStatus == 'disetrika' ? 'Setrika' : 'Siap Kirim' }}</div>
                                        <button type="submit" class="btn" style="background:#f97316; color:#fff">Update Process ⚙️</button>
                                    </form>
                                    @endif

                                    {{-- Panel Penugasan Antar --}}
                                    @if($o->status == 'siap')
                                    <form method="POST" action="{{ route('admin.orders.assign-driver', $o) }}" style="display:flex; gap:5px; background:#f0fdf4; padding:8px; border-radius:10px">
                                        @csrf
                                        <select name="driver_id" required style="padding:5px; border-radius:8px; border:1.5px solid var(--line); font-size:0.75rem; flex:1">
                                            <option value="">Pilih Kurir Antar</option>
                                            @foreach($daftarDriver ?? [] as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="assignment_type" value="delivery">
                                        <button type="submit" class="btn" style="background:var(--ok); color:#fff">Kirim 🛵</button>
                                    </form>
                                    @endif

                                    <div style="display:flex; gap:8px">
                                        <a href="{{ route('order.show', $o->order_code) }}" class="btn btn-sec" style="flex:1; justify-content:center">Detail</a>
                                        <a href="{{ route('admin.orders.receipt', $o) }}" target="_blank" class="btn btn-sec" style="flex:1; justify-content:center">Struk 📄</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:50px">Belum ada pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:20px">
                {{ $pesanan->links() }}
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function(){
    gsap.from('.js-in', {y:20, opacity:0, duration:0.5, stagger:0.1, ease:'power2.out'});
});
</script>

</body>
</html>
