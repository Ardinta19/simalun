<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Bantuan – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--surface:#f4f8fc;--border:#ddeeff;--radius:16px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(72px + env(safe-area-inset-bottom,0px));}
.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);position:relative;overflow:hidden;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 20px;max-width:520px;margin:0 auto;}
.header-top{display:flex;align-items:center;gap:12px;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;flex:1;}
.header-wave{display:block;width:100%;margin-bottom:-2px;}
.page-body{max-width:520px;margin:0 auto;padding:16px;}
.section-title{font-family:'Fredoka One',cursive;font-size:.97rem;color:var(--ink);margin-bottom:10px;margin-top:4px;}
.contact-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.06);}
.contact-row{display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:var(--ink);transition:background .15s;}
.contact-row:last-child{border-bottom:none;}.contact-row:hover{background:var(--blue-sky);}
.contact-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.contact-body{flex:1;}.contact-label{font-weight:800;font-size:.9rem;}.contact-sub{font-size:.75rem;color:var(--ink-lt);margin-top:2px;}
.faq-card{background:#fff;border:1.5px solid var(--border);border-radius:var(--radius);overflow:hidden;margin-bottom:16px;box-shadow:0 2px 8px rgba(0,47,92,.06);} .faq-item{border-bottom:1px solid var(--border);} .faq-item:last-child{border-bottom:none;}
.faq-q{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;cursor:pointer;font-weight:800;font-size:.88rem;}
.faq-a{padding:0 16px 14px;font-size:.82rem;font-weight:700;color:var(--ink-lt);line-height:1.6;display:none;} .faq-item.open .faq-a{display:block;}
</style>
</head>
<body>
<div class="top-header"><div class="header-inner"><div class="header-top"><a href="{{ route('customer.dashboard') }}" class="back-btn">‹</a><div class="header-title">Bantuan</div></div></div><svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg></div>
<div class="page-body">
  <div class="section-title">Hubungi Kami</div>
  <div class="contact-card">
    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20SIMALUN,%20saya%20ingin%20tanya%20mengenai%20layanan" target="_blank" class="contact-row">
      <div class="contact-icon" style="background:#e6fff6;">💬</div>
      <div class="contact-body">
        <div class="contact-label">WhatsApp</div>
        <div class="contact-sub">Chat langsung dengan admin (0812-3456-7890)</div>
      </div>
    </a>
    <a href="tel:081234567890" class="contact-row">
      <div class="contact-icon" style="background:#e0f4ff;">📞</div>
      <div class="contact-body">
        <div class="contact-label">Telepon</div>
        <div class="contact-sub">Kontak layanan pelanggan fast response</div>
      </div>
    </a>
  </div>
  <div class="section-title">FAQ</div>
  <div class="faq-card">
    <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)">Berapa lama proses laundry?<span>▾</span></div><div class="faq-a">Reguler 2–3 hari, Express 1 hari.</div></div>
    <div class="faq-item"><div class="faq-q" onclick="toggleFaq(this)">Bagaimana pembatalan pesanan?<span>▾</span></div><div class="faq-a">Bisa dibatalkan sebelum driver menjemput.</div></div>
  </div>
</div>
<script>function toggleFaq(el){el.parentElement.classList.toggle('open');}</script>
@include('layouts.component.customer._navbar_customer', ['active' => 'beranda'])
</html>