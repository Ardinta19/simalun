<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Bantuan – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--surface:#f4f8fc;--border:#ddeeff;--radius:16px;--nav-h:72px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(var(--nav-h) + 24px);}
.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);position:relative;overflow:hidden;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 20px;max-width:520px;margin:0 auto;}
.header-top{display:flex;align-items:center;gap:12px;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;flex:1;}
.header-wave{display:block;width:100%;margin-bottom:-2px;}
.page-body{max-width:520px;margin:0 auto;padding:16px;}

/* Tabs */
.tab-bar{display:flex;gap:6px;overflow-x:auto;padding-bottom:8px;margin-bottom:16px;scrollbar-width:none;}
.tab-bar::-webkit-scrollbar{display:none;}
.tab-btn{white-space:nowrap;padding:8px 16px;border-radius:99px;border:1.5px solid var(--border);background:#fff;font-size:.78rem;font-weight:800;color:var(--ink-mid);cursor:pointer;transition:all .2s;font-family:'Nunito',sans-serif;}
.tab-btn.active{background:var(--blue-mid);color:#fff;border-color:var(--blue-mid);}
.tab-content{display:none;}.tab-content.active{display:block;}

/* Section */
.section-title{font-family:'Fredoka One',cursive;font-size:.95rem;color:var(--ink);margin-bottom:10px;margin-top:16px;}
.section-title:first-child{margin-top:0;}

/* Contact Card */
.contact-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.contact-row{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--ink);transition:background .15s;}
.contact-row:last-child{border-bottom:none;}.contact-row:hover{background:var(--blue-sky);}
.contact-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.contact-body{flex:1;}.contact-label{font-weight:800;font-size:.88rem;}.contact-sub{font-size:.73rem;color:var(--ink-lt);margin-top:2px;}

/* FAQ */
.faq-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:12px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.faq-item{border-bottom:1px solid var(--border);}.faq-item:last-child{border-bottom:none;}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;cursor:pointer;font-weight:800;font-size:.85rem;gap:8px;user-select:none;}
.faq-q span{transition:transform .2s;flex-shrink:0;}
.faq-item.open .faq-q span{transform:rotate(180deg);}
.faq-a{padding:0 16px 14px;font-size:.8rem;font-weight:700;color:var(--ink-mid);line-height:1.7;display:none;}
.faq-item.open .faq-a{display:block;}

/* Info Box */
.info-box{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);padding:16px;margin-bottom:12px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.info-box h4{font-family:'Fredoka One',cursive;font-size:.88rem;margin-bottom:6px;color:var(--ink);}
.info-box p,.info-box li{font-size:.8rem;font-weight:700;color:var(--ink-mid);line-height:1.7;}
.info-box ul{padding-left:16px;margin-top:4px;}
.info-box ul li{margin-bottom:4px;}

/* Table */
.price-table{width:100%;border-collapse:collapse;font-size:.78rem;margin-top:8px;}
.price-table th{background:var(--blue-sky);padding:10px 12px;text-align:left;font-weight:900;color:var(--blue-dark);border:1px solid var(--border);}
.price-table td{padding:10px 12px;border:1px solid var(--border);font-weight:700;color:var(--ink-mid);}
.price-table tr:nth-child(even) td{background:#fafcfe;}

/* Status Flow */
.status-flow{display:flex;flex-direction:column;gap:0;margin:10px 0;}
.sf-item{display:flex;align-items:flex-start;gap:12px;position:relative;padding-bottom:16px;}
.sf-item:last-child{padding-bottom:0;}
.sf-dot{width:12px;height:12px;border-radius:50%;background:var(--blue-mid);flex-shrink:0;margin-top:4px;position:relative;z-index:1;}
.sf-item:not(:last-child) .sf-dot::after{content:'';position:absolute;top:12px;left:5px;width:2px;height:calc(100% + 8px);background:var(--border);}
.sf-text{flex:1;}
.sf-label{font-weight:900;font-size:.82rem;color:var(--ink);}
.sf-desc{font-size:.72rem;font-weight:700;color:var(--ink-lt);margin-top:2px;}
</style>
</head>
<body>
<div class="top-header">
  <div class="header-inner">
    <div class="header-top">
      <a href="{{ route('customer.dashboard') }}" class="back-btn">&#8249;</a>
      <div class="header-title">Pusat Bantuan</div>
    </div>
  </div>
  <svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg>
</div>

<div class="page-body">

  <!-- Tab Navigation -->
  <div class="tab-bar">
    <button class="tab-btn active" onclick="switchTab('faq')">FAQ</button>
    <button class="tab-btn" onclick="switchTab('layanan')">Layanan</button>
    <button class="tab-btn" onclick="switchTab('pesanan')">Pesanan</button>
    <button class="tab-btn" onclick="switchTab('pembayaran')">Pembayaran</button>
    <button class="tab-btn" onclick="switchTab('kontak')">Hubungi Kami</button>
  </div>

  <!-- TAB: FAQ -->
  <div class="tab-content active" id="tab-faq">
    <div class="section-title">Pertanyaan Umum</div>
    <div class="faq-card">
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Bagaimana cara memesan jasa laundry?<span>&#9662;</span></div>
        <div class="faq-a">Buka aplikasi, tekan tombol "Pesan Jemput Sekarang" di halaman utama. Pilih layanan, atur alamat penjemputan, tentukan jadwal, lalu konfirmasi pesanan. Kurir kami akan datang sesuai waktu yang dipilih.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Berapa lama proses laundry selesai?<span>&#9662;</span></div>
        <div class="faq-a">Layanan Reguler membutuhkan waktu 2-3 hari kerja. Layanan Express selesai dalam 1 hari (24 jam). Waktu dihitung sejak cucian diterima di outlet kami.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Apakah bisa membatalkan pesanan?<span>&#9662;</span></div>
        <div class="faq-a">Pesanan bisa dibatalkan selama status masih "Menunggu Kurir" (kurir belum berangkat menjemput). Setelah kurir menjemput, pembatalan tidak dapat dilakukan.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Bagaimana jika berat cucian berbeda dari estimasi?<span>&#9662;</span></div>
        <div class="faq-a">Berat yang tertera saat pemesanan adalah estimasi. Berat aktual akan ditimbang di outlet kami dan biaya akan disesuaikan berdasarkan berat sebenarnya. Anda akan mendapat notifikasi jika terjadi perubahan.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Jam operasional penjemputan?<span>&#9662;</span></div>
        <div class="faq-a">Penjemputan tersedia di tiga sesi:<br>- Pagi: 08.00 - 11.00 WIB<br>- Siang: 12.00 - 15.00 WIB<br>- Sore: 15.00 - 18.00 WIB<br>Layanan buka setiap hari Senin - Sabtu.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Area mana saja yang dijangkau?<span>&#9662;</span></div>
        <div class="faq-a">Kami melayani area Simalungun dan sekitarnya. Wilayah layanan dibagi menjadi 3 zona:<br>- Zona A (0-3 km): Gratis ongkir<br>- Zona B (3-7 km): Rp 5.000<br>- Zona C (7-12 km): Rp 10.000</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Bagaimana jika pakaian hilang atau rusak?<span>&#9662;</span></div>
        <div class="faq-a">Kami menjamin keamanan pakaian Anda. Jika terjadi kehilangan atau kerusakan akibat kelalaian kami, silakan laporkan dalam waktu 1x24 jam setelah cucian diterima. Ganti rugi akan diberikan sesuai kebijakan yang berlaku (maksimal 10x biaya cuci per item).</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Apakah pewangi bisa dipilih sendiri?<span>&#9662;</span></div>
        <div class="faq-a">Ya, saat membuat pesanan Anda bisa menambahkan catatan khusus di kolom "Catatan Tambahan" mengenai preferensi pewangi, detergen khusus, atau instruksi pencucian lainnya.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Berapa batas maksimal berat per pesanan?<span>&#9662;</span></div>
        <div class="faq-a">Batas maksimal per pesanan adalah 50 kg. Jika cucian lebih dari 50 kg, silakan buat pesanan terpisah. Anda juga bisa memesan hingga 3 pesanan aktif sekaligus.</div>
      </div>
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">Bagaimana cara melacak status pesanan?<span>&#9662;</span></div>
        <div class="faq-a">Buka menu "Pesanan" di navigasi bawah, lalu pilih pesanan yang ingin dilacak. Anda juga bisa menekan tombol "Lacak" pada kartu pesanan aktif di halaman utama. Notifikasi otomatis akan dikirim setiap kali status berubah.</div>
      </div>
    </div>
  </div>

  <!-- TAB: LAYANAN -->
  <div class="tab-content" id="tab-layanan">
    <div class="section-title">Jenis Layanan</div>
    <div class="info-box">
      <h4>Cuci Reguler (Kiloan)</h4>
      <p>Layanan cuci standar dengan waktu pengerjaan 2-3 hari kerja. Cocok untuk cucian harian yang tidak mendesak.</p>
      <ul>
        <li>Proses: Cuci, Keringkan, Setrika, Lipat</li>
        <li>Estimasi selesai: 2-3 hari kerja</li>
        <li>Minimal: 1 kg</li>
      </ul>
    </div>
    <div class="info-box">
      <h4>Cuci Express (Kiloan)</h4>
      <p>Layanan cuci cepat dengan prioritas pengerjaan, selesai dalam 1 hari (24 jam).</p>
      <ul>
        <li>Proses: Cuci, Keringkan, Setrika, Lipat</li>
        <li>Estimasi selesai: 1 hari (24 jam)</li>
        <li>Minimal: 1 kg</li>
      </ul>
    </div>
    <div class="info-box">
      <h4>Layanan Satuan (Per Item)</h4>
      <p>Untuk item khusus yang memerlukan penanganan tersendiri seperti jas, gaun, selimut, gordyn, sepatu, dll.</p>
      <ul>
        <li>Harga bervariasi per jenis item</li>
        <li>Bisa dikombinasikan dengan layanan kiloan</li>
        <li>Penanganan khusus sesuai jenis bahan</li>
      </ul>
    </div>

    <div class="section-title">Zona & Ongkos Kirim</div>
    <table class="price-table">
      <tr><th>Zona</th><th>Jarak</th><th>Ongkir</th></tr>
      <tr><td>Zona A</td><td>0 - 3 km</td><td>Gratis</td></tr>
      <tr><td>Zona B</td><td>3 - 7 km</td><td>Rp 5.000</td></tr>
      <tr><td>Zona C</td><td>7 - 12 km</td><td>Rp 10.000</td></tr>
    </table>
  </div>

  <!-- TAB: PESANAN -->
  <div class="tab-content" id="tab-pesanan">
    <div class="section-title">Alur Pesanan</div>
    <div class="info-box">
      <p>Berikut tahapan pesanan dari awal hingga selesai:</p>
    </div>
    <div class="status-flow">
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">1. Menunggu Kurir</div>
          <div class="sf-desc">Pesanan masuk, menunggu admin menugaskan kurir untuk penjemputan.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">2. Dijemput</div>
          <div class="sf-desc">Kurir sedang menuju lokasi Anda untuk mengambil cucian.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">3. Sedang Dicuci</div>
          <div class="sf-desc">Cucian sudah sampai di outlet dan sedang dalam proses pencucian.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">4. Sedang Disetrika</div>
          <div class="sf-desc">Cucian sudah bersih dan sedang dalam proses penyetrikaan.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">5. Siap Diantar</div>
          <div class="sf-desc">Cucian sudah selesai, menunggu kurir untuk diantar ke alamat Anda.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot"></div>
        <div class="sf-text">
          <div class="sf-label">6. Sedang Diantar</div>
          <div class="sf-desc">Kurir sedang dalam perjalanan mengantar cucian ke lokasi Anda.</div>
        </div>
      </div>
      <div class="sf-item">
        <div class="sf-dot" style="background:var(--green);"></div>
        <div class="sf-text">
          <div class="sf-label">7. Selesai</div>
          <div class="sf-desc">Cucian sudah diterima oleh pelanggan. Pesanan selesai.</div>
        </div>
      </div>
    </div>

    <div class="section-title">Ketentuan Pesanan</div>
    <div class="info-box">
      <h4>Batas Pesanan Aktif</h4>
      <p>Setiap pelanggan maksimal memiliki 3 pesanan aktif bersamaan. Selesaikan pesanan yang ada sebelum membuat pesanan baru.</p>
    </div>
    <div class="info-box">
      <h4>Jadwal Penjemputan</h4>
      <p>Penjemputan bisa dijadwalkan paling cepat hari ini dan paling lambat 14 hari ke depan. Pilih slot waktu pagi, siang, atau sore.</p>
    </div>
    <div class="info-box">
      <h4>Alamat Tersimpan</h4>
      <p>Anda bisa menyimpan beberapa alamat penjemputan di menu Alamat. Tetapkan salah satu sebagai alamat utama agar otomatis terpilih saat membuat pesanan baru.</p>
    </div>
  </div>

  <!-- TAB: PEMBAYARAN -->
  <div class="tab-content" id="tab-pembayaran">
    <div class="section-title">Metode Pembayaran</div>
    <div class="info-box">
      <h4>Cash on Delivery (COD)</h4>
      <p>Pembayaran dilakukan secara tunai saat kurir mengantar cucian yang sudah selesai ke alamat Anda. Pastikan menyiapkan uang pas untuk memudahkan transaksi.</p>
    </div>

    <div class="section-title">Rincian Biaya</div>
    <div class="info-box">
      <h4>Komponen Biaya</h4>
      <ul>
        <li><strong>Biaya Layanan:</strong> Dihitung berdasarkan berat aktual (kg) x harga per kg sesuai jenis layanan yang dipilih</li>
        <li><strong>Biaya Item Satuan:</strong> Jika ada item khusus, dihitung per item sesuai harga masing-masing</li>
        <li><strong>Ongkos Kirim:</strong> Berdasarkan zona alamat penjemputan (Zona A gratis, Zona B Rp 5.000, Zona C Rp 10.000)</li>
        <li><strong>Diskon:</strong> Potongan harga jika ada promo yang berlaku</li>
      </ul>
    </div>
    <div class="info-box">
      <h4>Perubahan Biaya</h4>
      <p>Biaya awal dihitung berdasarkan estimasi berat saat pemesanan. Jika berat aktual berbeda setelah ditimbang di outlet, total biaya akan otomatis disesuaikan dan Anda akan mendapat notifikasi perubahan.</p>
    </div>
  </div>

  <!-- TAB: KONTAK -->
  <div class="tab-content" id="tab-kontak">
    <div class="section-title">Hubungi Kami</div>
    <div class="contact-card">
      <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Azka%20Laundry%2C%20saya%20ingin%20bertanya%20mengenai%20layanan" target="_blank" class="contact-row">
        <div class="contact-icon" style="background:#e6fff6;font-size:1.2rem;">&#128172;</div>
        <div class="contact-body">
          <div class="contact-label">WhatsApp</div>
          <div class="contact-sub">Chat langsung dengan admin (0812-3456-7890)</div>
        </div>
      </a>
      <a href="tel:081234567890" class="contact-row">
        <div class="contact-icon" style="background:#e0f4ff;font-size:1.2rem;">&#128222;</div>
        <div class="contact-body">
          <div class="contact-label">Telepon</div>
          <div class="contact-sub">Hubungi layanan pelanggan kami</div>
        </div>
      </a>
      <a href="mailto:cs@azkalaundry.id" class="contact-row">
        <div class="contact-icon" style="background:#fff3e0;font-size:1.2rem;">&#9993;</div>
        <div class="contact-body">
          <div class="contact-label">Email</div>
          <div class="contact-sub">cs@azkalaundry.id (balasan 1x24 jam)</div>
        </div>
      </a>
      <a href="{{ route('customer.report') }}" class="contact-row">
        <div class="contact-icon" style="background:#fef2f2;font-size:1.2rem;">🐛</div>
        <div class="contact-body">
          <div class="contact-label">Lapor Kendala</div>
          <div class="contact-sub">Kirim bug, saran, atau komplain langsung dari aplikasi</div>
        </div>
      </a>
    </div>

    <div class="section-title">Lokasi Outlet</div>
    <div class="info-box">
      <h4>Azka Laundry - Simalungun</h4>
      <p>Jl. Asahan No. 45, Kec. Siantar, Kab. Simalungun, Sumatera Utara 21142</p>
      <ul>
        <li>Senin - Sabtu: 07.00 - 20.00 WIB</li>
        <li>Minggu: 08.00 - 17.00 WIB</li>
        <li>Hari Libur Nasional: Tutup</li>
      </ul>
    </div>

    <div class="section-title">Kebijakan Pengaduan</div>
    <div class="info-box">
      <h4>Prosedur Komplain</h4>
      <ul>
        <li>Laporkan keluhan dalam 1x24 jam setelah menerima cucian</li>
        <li>Sertakan nomor pesanan dan foto bukti (jika ada)</li>
        <li>Tim kami akan merespons dalam 1 hari kerja</li>
        <li>Penyelesaian komplain maksimal 3 hari kerja</li>
      </ul>
    </div>
  </div>

</div>

<script>
function toggleFaq(el){
  var item = el.parentElement;
  var wasOpen = item.classList.contains('open');
  // Close all
  document.querySelectorAll('.faq-item.open').forEach(function(i){i.classList.remove('open');});
  // Toggle current
  if(!wasOpen) item.classList.add('open');
}

function switchTab(tabName){
  document.querySelectorAll('.tab-btn').forEach(function(b){b.classList.remove('active');});
  document.querySelectorAll('.tab-content').forEach(function(c){c.classList.remove('active');});
  event.target.classList.add('active');
  var target = document.getElementById('tab-'+tabName);
  if(target) target.classList.add('active');
}
</script>

@include('layouts.component.customer._navbar_customer', ['active' => 'beranda'])
</body>
</html>
