<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Laporan Keuangan – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#64748b;--line:#ddeeff;--accent:#FF6B35;--ok:#00C48C;--r:20px}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
body{font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;overflow-x:hidden}

.sidebar{position:fixed;left:20px;top:20px;bottom:20px;width:260px;background:var(--blue-dark);border-radius:30px;padding:30px;color:#fff;z-index:100}
.nav-link{display:flex;align-items:center;gap:12px;color:rgba(255,255,255,0.6);text-decoration:none;padding:12px 15px;border-radius:15px;margin-bottom:5px;font-weight:800;transition:0.3s}
.nav-link.active{background:rgba(255,255,255,0.1);color:#fff}

.main-content{margin-left:300px; padding:40px}
.top-h{display:flex; justify-content:space-between; align-items:center; margin-bottom:30px}
.title{font-family:'Fredoka One',cursive; font-size:1.8rem; color:var(--blue-dark)}

.stats-grid{display:grid; grid-template-columns:repeat(3, 1fr); gap:20px; margin-bottom:30px}
.stat-card{background:#fff; border-radius:var(--r); border:1.5px solid var(--line); padding:25px; box-shadow:0 10px 30px rgba(0,0,0,0.02)}
.stat-l{font-size:0.75rem; font-weight:900; color:var(--muted); text-transform:uppercase; letter-spacing:1px}
.stat-v{font-family:'Fredoka One',cursive; font-size:1.8rem; margin-top:5px}

.card{background:#fff; border-radius:var(--r); border:1.5px solid var(--line); padding:30px; margin-bottom:20px; box-shadow:0 10px 30px rgba(0,0,0,0.02)}
.card-h{font-family:'Fredoka One',cursive; font-size:1.2rem; color:var(--blue-dark); margin-bottom:25px; display:flex; align-items:center; justify-content:space-between}

table{width:100%; border-collapse:collapse}
th{text-align:left; padding:15px 20px; background:#f8fafc; font-size:0.75rem; font-weight:900; color:var(--muted); text-transform:uppercase; letter-spacing:1px; border-bottom:1.5px solid var(--line)}
td{padding:15px 20px; border-bottom:1px solid #f1f5f9; font-size:0.9rem; font-weight:700}

.badge{padding:5px 12px; border-radius:99px; font-size:0.7rem; font-weight:900; text-transform:uppercase}
.badge-in{background:#ecfdf5; color:var(--ok)}
.badge-out{background:#fff1f1; color:#ef4444}

.btn{padding:12px 25px; border-radius:12px; font-weight:900; font-size:0.9rem; cursor:pointer; border:none; text-decoration:none; display:inline-flex; align-items:center; gap:10px; transition:0.3s}
.btn-pri{background:var(--blue); color:#fff}
.btn-export{background:var(--ok); color:#fff}

@media (max-width: 1024px) {
    .sidebar{display:none}
    .main-content{margin-left:0; padding:20px}
    .stats-grid{grid-template-columns:1fr}
}
</style>
</head>
<body>

<aside class="sidebar">
    <div style="font-family:'Fredoka One',cursive; font-size:1.5rem; margin-bottom:40px">SIMALUN</div>
    <nav>
        <a href="{{ route('dashboard.admin') }}" class="nav-link"><span>🏠</span> Beranda</a>
        <a href="{{ route('admin.orders') }}" class="nav-link"><span>📋</span> Pesanan</a>
        <a href="{{ route('admin.walkin.form') }}" class="nav-link"><span>🆕</span> Walk-in</a>
        <a href="{{ route('admin.finance.index') }}" class="nav-link active"><span>💰</span> Keuangan</a>
        <a href="{{ route('profile.edit') }}" class="nav-link"><span>👤</span> Profil</a>
        <form method="POST" action="{{ route('logout') }}" style="margin-top:20px">
            @csrf
            <button type="submit" class="nav-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer"><span>🚪</span> Keluar</button>
        </form>
    </nav>
</aside>

<main class="main-content">
    <div class="top-h js-in">
        <h1 class="title">Laporan Keuangan</h1>
        <div style="display:flex; gap:10px">
            <a href="{{ route('admin.finance.export') }}" class="btn btn-export">📥 Export Excel</a>
        </div>
    </div>

    <div class="stats-grid js-in">
        <div class="stat-card">
            <div class="stat-l">Pemasukan</div>
            <div class="stat-v" style="color:var(--ok)">Rp {{ number_format($masuk, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-l">Pengeluaran</div>
            <div class="stat-v" style="color:#ef4444">Rp {{ number_format($keluar, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-l">Saldo Bersih</div>
            <div class="stat-v" style="color:var(--blue)">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="card js-in">
        <div class="card-h">
            <span>📊 Riwayat Transaksi</span>
            <div style="display:flex; gap:10px">
                <a href="{{ route('admin.finance.index', ['range' => 'harian']) }}" class="btn {{ request('range') == 'harian' ? 'btn-pri' : '' }}" style="padding:8px 15px; font-size:0.75rem; background:#f8fafc; color:var(--ink); border:1.5px solid var(--line)">Harian</a>
                <a href="{{ route('admin.finance.index', ['range' => 'bulanan']) }}" class="btn {{ request('range', 'bulanan') == 'bulanan' ? 'btn-pri' : '' }}" style="padding:8px 15px; font-size:0.75rem; background:#f8fafc; color:var(--ink); border:1.5px solid var(--line)">Bulanan</a>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksi ?? [] as $t)
                    <tr>
                        <td>{{ $t->date->format('d/m/Y') }}</td>
                        <td>{{ $t->description }}</td>
                        <td><span class="badge {{ $t->type == 'in' ? 'badge-in' : 'badge-out' }}">{{ $t->type == 'in' ? 'Masuk' : 'Keluar' }}</span></td>
                        <td style="color:{{ $t->type == 'in' ? 'var(--ok)' : '#ef4444' }}">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:40px; color:var(--muted)">Belum ada transaksi di periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card js-in">
        <div class="card-h">💸 Catat Pengeluaran</div>
        <form action="{{ route('admin.finance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="entry_type" value="expense">
            <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:15px">
                <input type="text" name="description" placeholder="Keterangan (Contoh: Beli Sabun)" class="input" style="width:100%; height:50px; border-radius:12px; border:1.5px solid var(--line); padding:0 15px; outline:none" required>
                <input type="number" name="amount" placeholder="Jumlah (Rp)" class="input" style="width:100%; height:50px; border-radius:12px; border:1.5px solid var(--line); padding:0 15px; outline:none" required>
                <button type="submit" class="btn btn-pri" style="height:50px; justify-content:center">Simpan</button>
            </div>
        </form>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function(){
    gsap.from('.js-in', {y:20, opacity:0, duration:0.6, stagger:0.1, ease:'power2.out'});
});
</script>

</body>
</html>
