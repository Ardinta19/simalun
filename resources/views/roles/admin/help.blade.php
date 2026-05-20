<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Panduan Admin – Azka Laundry</title>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--orange:#f59e0b;--green:#10b981;--red:#ef4444;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--ink-mid:#3d5066;--muted:#64748b;--line:#ddeeff;--r:16px;--nav-h:74px;}
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--bg);color:var(--ink);min-height:100vh;padding-bottom:calc(var(--nav-h) + 24px);}
.top{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue) 60%,var(--sky) 100%);color:#fff;position:relative;}
.top-in{max-width:560px;margin:0 auto;padding:max(env(safe-area-inset-top,0px),20px) 16px 20px;}
.top-row{display:flex;align-items:center;gap:12px;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.tt{font-weight:800;font-size:1.15rem;flex:1;}
.sub{font-size:.76rem;font-weight:700;opacity:.85;margin-top:4px;padding-left:50px;}
.top-wave{display:block;width:100%;margin-bottom:-2px;}
.wrap{max-width:560px;margin:0 auto;padding:14px 16px;}

.tab-row{display:flex;gap:6px;overflow-x:auto;padding-bottom:8px;margin-bottom:14px;scrollbar-width:none;}
.tab-row::-webkit-scrollbar{display:none;}
.tab-btn{white-space:nowrap;padding:8px 14px;border-radius:99px;border:1.5px solid var(--line);background:#fff;font-size:.76rem;font-weight:800;color:var(--ink-mid);cursor:pointer;transition:all .2s;font-family:'Plus Jakarta Sans',sans-serif;}
.tab-btn.active{background:var(--blue);color:#fff;border-color:var(--blue);}
.tab-panel{display:none;}.tab-panel.active{display:block;}

.section-title{font-weight:800;font-size:.92rem;color:var(--ink);margin-bottom:10px;margin-top:14px;}
.section-title:first-child{margin-top:0;}
.card{background:#fff;border:1.5px solid var(--line);border-radius:var(--r);padding:14px 16px;margin-bottom:10px;box-shadow:0 2px 8px rgba(0,47,92,.05);}
.card h4{font-weight:800;font-size:.88rem;margin-bottom:6px;color:var(--ink);}
.card p,.card li{font-size:.8rem;font-weight:700;color:var(--muted);line-height:1.7;}
.card ul{padding-left:16px;margin-top:4px;}
.card ul li{margin-bottom:4px;}
.card-tip{background:#ecfdf5;border-color:#a7f3d0;}
.card-tip h4{color:#065f46;}
.card-warning{background:#fff9e6;border-color:#ffe082;}
.card-warning h4{color:#854d0e;}

.flow{display:flex;flex-direction:column;gap:0;margin:8px 0;}
.flow-item{display:flex;align-items:flex-start;gap:12px;position:relative;padding-bottom:14px;}
.flow-item:last-child{padding-bottom:0;}
.flow-dot{width:10px;height:10px;border-radius:50%;background:var(--blue);flex-shrink:0;margin-top:4px;position:relative;z-index:1;}
.flow-item:not(:last-child) .flow-dot::after{content:'';position:absolute;top:10px;left:4px;width:2px;height:calc(100% + 8px);background:var(--line);}
.flow-text{flex:1;}
.flow-label{font-weight:900;font-size:.82rem;color:var(--ink);}
.flow-desc{font-size:.72rem;font-weight:700;color:var(--muted);margin-top:2px;}

.faq-card{background:#fff;border:1.5px solid var(--line);border-radius:var(--r);overflow:hidden;margin-bottom:10px;box-shadow:0 2px 8px rgba(0,47,92,.05);}
.faq-item{border-bottom:1px solid var(--line);}.faq-item:last-child{border-bottom:none;}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:13px 16px;cursor:pointer;font-weight:800;font-size:.84rem;gap:8px;user-select:none;}
.faq-q span{transition:transform .2s;flex-shrink:0;}
.faq-item.open .faq-q span{transform:rotate(180deg);}
.faq-a{padding:0 16px 13px;font-size:.78rem;font-weight:700;color:var(--muted);line-height:1.7;display:none;}
.faq-item.open .faq-a{display:block;}
</style>
</head>
<body>
<header class="top">
  <div class="top-in">
    <div class="top-row">
      <a href="{{ route('dashboard.admin') }}" class="back-btn">&#8249;</a>
      <div class="tt">Panduan Admin</div>
    </div>
    <div class="sub">Referensi operasional manajemen laundry pickup-delivery.</div>
  </div>
  <svg class="top-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg>
</header>

<main class="wrap">
  <div class="tab-row">
    <button class="tab-btn active" onclick="switchTab('manajemen')">Manajemen Pesanan</button>
    <button class="tab-btn" onclick="switchTab('kurir')">Pengelolaan Kurir</button>
    <button class="tab-btn" onclick="switchTab('keuangan')">Keuangan</button>
    <button class="tab-btn" onclick="switchTab('faq')">FAQ</button>
  </div>

  <!-- TAB: MANAJEMEN PESANAN -->
  <div class="tab-panel active" id="tab-manajemen">
    <div class="section-title">Alur Manajemen Pesanan</div>
    <div class="flow">
      <div class="flow-item">
        <div class="flow-dot"></div>
        <div class="flow-text">
          <div class="flow-label">Pesanan Masuk (Menunggu)</div>
          <div class="flow-desc">Pelanggan membuat pesanan baru. Muncul di daftar "Belum Dijemput". Admin harus segera menugaskan kurir.</div>
        </div>
      </div>
      <div class="flow-item">
        <div class="flow-dot"></div>
        <div class="flow-text">
          <div class="flow-label">Tugaskan Kurir Jemput</div>
          <div class="flow-desc">Pilih kurir yang tersedia dan tugaskan untuk penjemputan. Status otomatis berubah ke "Dijemput".</div>
        </div>
      </div>
      <div class="flow-item">
        <div class="flow-dot"></div>
        <div class="flow-text">
          <div class="flow-label">Proses di Outlet</div>
          <div class="flow-desc">Setelah cucian tiba, update status ke "Dicuci" lalu "Disetrika". Kurir menginput berat aktual saat serah terima.</div>
        </div>
      </div>
      <div class="flow-item">
        <div class="flow-dot"></div>
        <div class="flow-text">
          <div class="flow-label">Siap Diantar</div>
          <div class="flow-desc">Update status ke "Siap". Kemudian tugaskan kurir untuk pengantaran. Status berubah ke "Dikirim".</div>
        </div>
      </div>
      <div class="flow-item">
        <div class="flow-dot" style="background:var(--green);"></div>
        <div class="flow-text">
          <div class="flow-label">Selesai</div>
          <div class="flow-desc">Kurir mengkonfirmasi penyerahan dan pembayaran. Pesanan selesai. Pemasukan otomatis tercatat.</div>
        </div>
      </div>
    </div>

    <div class="section-title">Fitur Pesanan Walk-in</div>
    <div class="card">
      <h4>Pelanggan Datang Langsung</h4>
      <p>Untuk pelanggan yang datang langsung ke outlet tanpa memesan via aplikasi:</p>
      <ul>
        <li>Buka menu "Tambah Pelanggan" atau "Walk-in" di navigasi</li>
        <li>Input nama dan nomor HP pelanggan</li>
        <li>Pilih layanan dan estimasi berat</li>
        <li>Sistem akan membuat akun pelanggan otomatis</li>
        <li>Status langsung masuk ke "Dicuci" (tanpa proses jemput)</li>
        <li>Tidak ada biaya ongkir untuk walk-in</li>
      </ul>
    </div>

    <div class="section-title">Update Status Manual</div>
    <div class="card">
      <h4>Status yang Bisa Diubah Admin</h4>
      <ul>
        <li><strong>Dicuci</strong> – Cucian masuk proses pencucian</li>
        <li><strong>Disetrika</strong> – Cucian selesai cuci, masuk setrika</li>
        <li><strong>Siap</strong> – Cucian selesai, siap untuk diantar</li>
        <li><strong>Selesai</strong> – Finalisasi manual (jika kurir tidak update)</li>
        <li><strong>Dibatalkan</strong> – Pembatalan pesanan (sebelum proses cuci)</li>
      </ul>
    </div>
  </div>

  <!-- TAB: KURIR -->
  <div class="tab-panel" id="tab-kurir">
    <div class="section-title">Penugasan Kurir</div>
    <div class="card">
      <h4>Cara Menugaskan Kurir</h4>
      <ul>
        <li>Buka halaman "Pesanan" dan pilih pesanan yang berstatus "Menunggu"</li>
        <li>Pada panel penugasan, pilih kurir dari dropdown</li>
        <li>Pilih jenis tugas: "Pickup" (jemput) atau "Delivery" (antar)</li>
        <li>Tekan "Tugaskan" - kurir otomatis mendapat notifikasi</li>
        <li>Hanya kurir aktif yang muncul di daftar pilihan</li>
      </ul>
    </div>
    <div class="card">
      <h4>Pertimbangan Penugasan</h4>
      <ul>
        <li>Prioritaskan kurir yang berada di zona terdekat dengan pelanggan</li>
        <li>Perhatikan jumlah tugas aktif kurir (hindari overload)</li>
        <li>Untuk pesanan Express, tugaskan kurir yang paling cepat tersedia</li>
        <li>Pantau performa melalui total antar bulanan di profil kurir</li>
      </ul>
    </div>
    <div class="card card-tip">
      <h4>Monitoring Kurir</h4>
      <ul>
        <li>Cek jumlah "Kurir Aktif" di dashboard</li>
        <li>Pantau tugas yang belum di-update statusnya lebih dari 2 jam</li>
        <li>Hubungi kurir jika ada keluhan pelanggan soal keterlambatan</li>
        <li>Nonaktifkan akun kurir yang bermasalah melalui panel admin</li>
      </ul>
    </div>
  </div>

  <!-- TAB: KEUANGAN -->
  <div class="tab-panel" id="tab-keuangan">
    <div class="section-title">Laporan Keuangan</div>
    <div class="card">
      <h4>Pemasukan Otomatis</h4>
      <p>Setiap pesanan yang berhasil dibuat otomatis tercatat sebagai pemasukan di modul keuangan, termasuk:</p>
      <ul>
        <li>Biaya layanan (per kg atau per item)</li>
        <li>Ongkos kirim (sesuai zona)</li>
        <li>Pesanan walk-in</li>
      </ul>
    </div>
    <div class="card">
      <h4>Pencatatan Manual</h4>
      <p>Admin bisa menambahkan entri keuangan manual untuk:</p>
      <ul>
        <li>Pengeluaran operasional (detergen, listrik, sewa)</li>
        <li>Gaji karyawan dan komisi kurir</li>
        <li>Biaya perawatan kendaraan</li>
        <li>Pengeluaran tak terduga</li>
      </ul>
    </div>
    <div class="card">
      <h4>Export Laporan</h4>
      <p>Fitur export tersedia untuk mengunduh data keuangan dalam format spreadsheet. Berguna untuk pelaporan bulanan dan analisis.</p>
    </div>
  </div>

  <!-- TAB: FAQ -->
  <div class="tab-panel" id="tab-faq">
    <div class="section-title">FAQ Admin</div>
    <div class="faq-card">
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Pesanan sudah lama tapi belum dijemput?<span>&#9662;</span></div>
        <div class="faq-a">Cek apakah kurir sudah ditugaskan. Jika sudah, hubungi kurir untuk konfirmasi. Jika belum, segera tugaskan kurir yang tersedia. Prioritaskan pesanan berdasarkan urutan waktu masuk.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Pelanggan komplain cucian rusak/hilang?<span>&#9662;</span></div>
        <div class="faq-a">Verifikasi klaim dengan mencocokkan catatan pesanan. Minta foto bukti dari pelanggan. Koordinasikan dengan tim outlet untuk pengecekan. Ganti rugi sesuai kebijakan (maks 10x biaya cuci item bersangkutan).</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Kurir tidak update status?<span>&#9662;</span></div>
        <div class="faq-a">Hubungi kurir untuk konfirmasi progress tugas. Jika kurir sudah menyelesaikan tapi lupa update, admin bisa update status secara manual melalui panel pesanan.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Bagaimana membatalkan pesanan?<span>&#9662;</span></div>
        <div class="faq-a">Pesanan bisa dibatalkan melalui panel status di halaman pesanan. Pilih status "Dibatalkan". Pastikan cucian belum masuk proses. Pelanggan akan mendapat notifikasi otomatis.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Berat aktual sangat berbeda dari estimasi?<span>&#9662;</span></div>
        <div class="faq-a">Total biaya otomatis dihitung ulang berdasarkan berat aktual saat kurir input di tahap serah terima. Pelanggan mendapat notifikasi perubahan. Tidak perlu intervensi admin kecuali ada komplain.</div>
      </div>
    </div>
  </div>

</main>

<script>
function toggleFaq(el){
  var item = el.parentElement;
  var wasOpen = item.classList.contains('open');
  document.querySelectorAll('.faq-item.open').forEach(function(i){i.classList.remove('open');});
  if(!wasOpen) item.classList.add('open');
}
function switchTab(tabName){
  document.querySelectorAll('.tab-btn').forEach(function(b){b.classList.remove('active');});
  document.querySelectorAll('.tab-panel').forEach(function(c){c.classList.remove('active');});
  event.target.classList.add('active');
  var target = document.getElementById('tab-'+tabName);
  if(target) target.classList.add('active');
}
</script>

@include('layouts.component.admin._navbar_admin', ['active' => ''])

</body>
</html>
