<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Pusat Bantuan — Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fredoka+One&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
  --brand-deep:    #002f5c;
  --brand-primary: #0077b6;
  --brand-accent:  #FF6B35;
  --ink:           #0d1f33;
  --ink-2:         #3d5066;
  --ink-3:         #6b7c8f;
  --ink-mute:      #8c9bab;
  --line:          #e3ecf3;
  --line-2:        #f0f5f9;
  --bg:            #f7fafd;
  --card:          #ffffff;
  --ok:            #16a34a;
  --warn:          #d97706;
}

* { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--ink);
  min-height: 100vh;
  padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px));
  -webkit-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
}

/* Header — restrained, editorial */
.app-bar {
  background: var(--card);
  border-bottom: 1px solid var(--line);
  padding: max(env(safe-area-inset-top, 0), 14px) 20px 14px;
  position: sticky;
  top: 0;
  z-index: 50;
}
.app-bar__row {
  max-width: 680px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 36px 1fr 36px;
  align-items: center;
  gap: 12px;
}
.icon-btn {
  width: 36px; height: 36px;
  border-radius: 10px;
  border: 1px solid var(--line);
  background: var(--card);
  display: grid; place-items: center;
  color: var(--ink-2);
  text-decoration: none;
  cursor: pointer;
  transition: background .15s, border-color .15s;
}
.icon-btn:hover { background: var(--line-2); border-color: var(--ink-mute); }
.icon-btn svg { width: 18px; height: 18px; }
.app-bar__title {
  text-align: center;
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.01em;
}

/* Hero block */
.hero {
  max-width: 680px;
  margin: 0 auto;
  padding: 28px 20px 8px;
}
.hero__eyebrow {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--brand-primary);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 8px;
}
.hero__title {
  font-size: 1.6rem;
  font-weight: 800;
  color: var(--ink);
  letter-spacing: -0.02em;
  line-height: 1.2;
  margin-bottom: 8px;
}
.hero__lead {
  font-size: 0.92rem;
  color: var(--ink-3);
  line-height: 1.55;
  max-width: 30ch;
}

/* Sections */
.section {
  max-width: 680px;
  margin: 0 auto;
  padding: 16px 20px;
}
.section__title {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--ink-mute);
  letter-spacing: 0.12em;
  text-transform: uppercase;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.section__title::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--line);
}

/* Quick contact — editorial cards */
.quick-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.q-card {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 16px;
  text-decoration: none;
  color: inherit;
  display: flex;
  flex-direction: column;
  gap: 12px;
  transition: border-color .15s, transform .12s;
}
.q-card:active { transform: translateY(1px); }
.q-card:hover { border-color: var(--brand-primary); }
.q-card__icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: var(--line-2);
  display: grid; place-items: center;
  color: var(--brand-primary);
}
.q-card__icon svg { width: 18px; height: 18px; }
.q-card__label {
  font-size: 0.78rem;
  color: var(--ink-mute);
  font-weight: 600;
}
.q-card__value {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.01em;
}
.q-card__meta {
  font-size: 0.72rem;
  color: var(--ink-3);
  display: flex;
  align-items: center;
  gap: 6px;
  margin-top: -4px;
}
.q-card__meta .dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--ok);
}

/* Topic list — list-row style, editorial */
.topic-list {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 14px;
  overflow: hidden;
}
.topic-row {
  display: grid;
  grid-template-columns: 36px 1fr 16px;
  align-items: center;
  gap: 14px;
  padding: 14px 16px;
  text-decoration: none;
  color: inherit;
  border-bottom: 1px solid var(--line-2);
  cursor: pointer;
  transition: background .12s;
}
.topic-row:last-child { border-bottom: 0; }
.topic-row:hover { background: var(--line-2); }
.topic-row__icon {
  width: 36px; height: 36px;
  border-radius: 10px;
  background: var(--line-2);
  display: grid; place-items: center;
  color: var(--brand-primary);
}
.topic-row__icon svg { width: 18px; height: 18px; }
.topic-row__title {
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--ink);
}
.topic-row__sub {
  font-size: 0.74rem;
  color: var(--ink-3);
  margin-top: 2px;
}
.topic-row__chev {
  color: var(--ink-mute);
}

/* FAQ — accordion, low-key */
.faq {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 14px;
  overflow: hidden;
}
.faq__item { border-bottom: 1px solid var(--line-2); }
.faq__item:last-child { border-bottom: 0; }
.faq__q {
  width: 100%;
  text-align: left;
  background: none;
  border: 0;
  padding: 16px;
  font-family: inherit;
  font-size: 0.92rem;
  font-weight: 600;
  color: var(--ink);
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 12px;
  cursor: pointer;
  transition: background .12s;
}
.faq__q:hover { background: var(--line-2); }
.faq__q-chev {
  flex-shrink: 0;
  margin-top: 2px;
  color: var(--ink-mute);
  transition: transform .25s;
}
.faq__item[aria-expanded="true"] .faq__q-chev { transform: rotate(180deg); color: var(--brand-primary); }
.faq__a {
  display: grid;
  grid-template-rows: 0fr;
  transition: grid-template-rows .3s ease;
  font-size: 0.86rem;
  color: var(--ink-2);
  line-height: 1.6;
}
.faq__a-inner {
  overflow: hidden;
  padding: 0 16px;
}
.faq__item[aria-expanded="true"] .faq__a { grid-template-rows: 1fr; }
.faq__item[aria-expanded="true"] .faq__a-inner { padding: 0 16px 16px; }
.faq__a p + p { margin-top: 8px; }

/* Hours card */
.hours {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 16px;
}
.hours__title {
  font-size: 0.86rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.hours__status {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--ok);
  background: rgba(22, 163, 74, .08);
  padding: 3px 8px;
  border-radius: 99px;
}
.hours__status .dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--ok);
  box-shadow: 0 0 0 0 rgba(22,163,74,.4);
  animation: pulse 2s infinite;
}
@keyframes pulse {
  0% { box-shadow: 0 0 0 0 rgba(22,163,74,.5); }
  70% { box-shadow: 0 0 0 6px rgba(22,163,74,0); }
  100% { box-shadow: 0 0 0 0 rgba(22,163,74,0); }
}
.hours__lines { font-size: 0.84rem; color: var(--ink-2); line-height: 1.6; }
.hours__lines strong { color: var(--ink); font-weight: 700; }

/* Footer note */
.foot {
  max-width: 680px;
  margin: 0 auto;
  padding: 24px 20px 8px;
  text-align: center;
  font-size: 0.72rem;
  color: var(--ink-mute);
  line-height: 1.6;
}
.foot a { color: var(--brand-primary); text-decoration: none; font-weight: 600; }
</style>
</head>
<body>

<div class="app-bar">
  <div class="app-bar__row">
    <a href="{{ route('customer.profile') }}" class="icon-btn" aria-label="Kembali">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div class="app-bar__title">Pusat Bantuan</div>
    <span></span>
  </div>
</div>

<header class="hero" data-reveal>
  <div class="hero__eyebrow">DUKUNGAN PELANGGAN</div>
  <h1 class="hero__title">Ada yang bisa kami bantu?</h1>
  <p class="hero__lead">Tim Azka Laundry siap menjawab setiap pertanyaan tentang pesanan, layanan, dan pembayaran.</p>
</header>

{{-- Quick contact --}}
<section class="section" data-reveal>
  <div class="section__title">Kontak Langsung</div>
  <div class="quick-grid">
    <a href="https://wa.me/6281234567890?text=Halo%20Azka%20Laundry%2C%20saya%20butuh%20bantuan" target="_blank" rel="noopener" class="q-card">
      <div class="q-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>
        </svg>
      </div>
      <div>
        <div class="q-card__label">WhatsApp</div>
        <div class="q-card__value">0812-3456-7890</div>
      </div>
      <div class="q-card__meta"><span class="dot"></span> Balasan rata-rata 5 menit</div>
    </a>

    <a href="tel:081234567890" class="q-card">
      <div class="q-card__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
        </svg>
      </div>
      <div>
        <div class="q-card__label">Telepon</div>
        <div class="q-card__value">0812-3456-7890</div>
      </div>
      <div class="q-card__meta"><span class="dot"></span> 08.00–19.00 WIB</div>
    </a>
  </div>
</section>

{{-- Topic browse --}}
<section class="section" data-reveal>
  <div class="section__title">Topik Bantuan</div>
  <div class="topic-list">
    <a href="#faq-pesanan" class="topic-row">
      <div class="topic-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
      </div>
      <div>
        <div class="topic-row__title">Pesanan & Penjadwalan</div>
        <div class="topic-row__sub">Cara pesan, ubah jadwal, batalkan</div>
      </div>
      <svg class="topic-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>
    <a href="#faq-layanan" class="topic-row">
      <div class="topic-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
      </div>
      <div>
        <div class="topic-row__title">Layanan & Tarif</div>
        <div class="topic-row__sub">Cuci kiloan, satuan, express</div>
      </div>
      <svg class="topic-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>
    <a href="#faq-pembayaran" class="topic-row">
      <div class="topic-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      </div>
      <div>
        <div class="topic-row__title">Pembayaran</div>
        <div class="topic-row__sub">COD, transfer, struk</div>
      </div>
      <svg class="topic-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>
    <a href="#faq-akun" class="topic-row">
      <div class="topic-row__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <div>
        <div class="topic-row__title">Akun & Keamanan</div>
        <div class="topic-row__sub">Ubah profil, kata sandi, alamat</div>
      </div>
      <svg class="topic-row__chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16"><path d="M9 18l6-6-6-6"/></svg>
    </a>
  </div>
</section>

{{-- FAQ --}}
<section class="section" data-reveal>
  <div class="section__title" id="faq-pesanan">Pertanyaan Umum</div>
  <div class="faq">

    <div class="faq__item" aria-expanded="false">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Berapa lama proses laundry sampai siap diantar?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Untuk layanan reguler, estimasi 2–3 hari kerja sejak cucian dijemput. Layanan express selesai pada hari yang sama bila dijemput sebelum jam 12.00 WIB.</p>
      </div></div>
    </div>

    <div class="faq__item" aria-expanded="false" id="faq-layanan">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Bagaimana tarif laundry dihitung?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Tarif kiloan dihitung berdasarkan berat aktual setelah dijemput. Cuci saja Rp 5.000/kg, cuci + setrika Rp 7.000/kg, dan express 1 hari Rp 10.000/kg.</p>
        <p>Untuk barang khusus (jas, gorden, bedcover) dikenakan tarif satuan yang dapat dilihat saat membuat pesanan.</p>
      </div></div>
    </div>

    <div class="faq__item" aria-expanded="false" id="faq-pembayaran">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Metode pembayaran apa yang diterima?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Pembayaran utama menggunakan tunai (COD) saat cucian diantar kembali. Pembayaran transfer juga tersedia—silakan hubungi kami sebelum pengantaran.</p>
      </div></div>
    </div>

    <div class="faq__item" aria-expanded="false">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Apakah ada batasan area jemput-antar?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Kami melayani seluruh wilayah Kota Jambi. Untuk lokasi dengan jarak lebih dari 15 km dari outlet, ada tambahan ongkos kirim sesuai zona.</p>
      </div></div>
    </div>

    <div class="faq__item" aria-expanded="false">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Bagaimana jika cucian tertukar atau ada kerusakan?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Setiap cucian dilabeli dengan kode pesanan untuk mencegah tertukar. Jika tetap terjadi kekeliruan, laporkan dalam 1×24 jam setelah pengantaran melalui WhatsApp—kami akan menindaklanjuti tanpa biaya tambahan.</p>
      </div></div>
    </div>

    <div class="faq__item" aria-expanded="false" id="faq-akun">
      <button class="faq__q" type="button" onclick="toggleFaq(this.parentElement)">
        <span>Bagaimana cara membatalkan pesanan?</span>
        <svg class="faq__q-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M6 9l6 6 6-6"/></svg>
      </button>
      <div class="faq__a"><div class="faq__a-inner">
        <p>Pembatalan dapat dilakukan melalui detail pesanan selama status masih "Menunggu". Setelah kurir berangkat menjemput, pembatalan harus dikonfirmasi oleh tim kami.</p>
      </div></div>
    </div>

  </div>
</section>

{{-- Hours --}}
<section class="section" data-reveal>
  <div class="section__title">Jam Operasional</div>
  <div class="hours">
    <div class="hours__title">
      Outlet Azka Laundry
      <span class="hours__status"><span class="dot"></span> Buka sekarang</span>
    </div>
    <div class="hours__lines">
      <strong>Senin – Minggu</strong> · 08.00 – 19.00 WIB<br>
      Jl. Mayang Mangurai, Kota Jambi
    </div>
  </div>
</section>

<div class="foot">
  Belum menemukan jawaban? Hubungi kami melalui <a href="https://wa.me/6281234567890" target="_blank" rel="noopener">WhatsApp</a>.
</div>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
function toggleFaq(item) {
  const expanded = item.getAttribute('aria-expanded') === 'true';
  item.setAttribute('aria-expanded', expanded ? 'false' : 'true');
}

// Smooth scroll for topic anchors
document.querySelectorAll('a[href^="#faq-"]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault();
    const target = document.querySelector(a.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      // If it's a FAQ item, expand it
      if (target.classList.contains('faq__item')) {
        target.setAttribute('aria-expanded', 'true');
      }
    }
  });
});

// Subtle entrance — no bounce, no overshoot
document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined') return;
  gsap.from('[data-reveal]', {
    y: 12,
    opacity: 0,
    duration: 0.45,
    ease: 'power2.out',
    stagger: 0.06
  });
});
</script>
</body>
</html>
