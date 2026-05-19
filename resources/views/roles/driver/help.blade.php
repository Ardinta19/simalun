<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Bantuan Driver – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#64748b;--line:#ddeeff;--r:16px}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink)}
.top{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue) 60%,var(--sky) 100%);color:#fff}
.top-in{max-width:560px;margin:0 auto;padding:20px 16px}
.tt{font-family:'Fredoka One',cursive;font-size:1.15rem}
.sub{font-size:.78rem;font-weight:700;opacity:.9;margin-top:4px}
.wrap{max-width:560px;margin:0 auto;padding:14px}
.card{background:#fff;border:1.5px solid var(--line);border-radius:var(--r);padding:14px;margin-bottom:10px}
.q{font-weight:900;font-size:.92rem}.a{font-size:.8rem;color:var(--muted);margin-top:4px;line-height:1.5}
</style>
</head>
<body>
<header class="top" id="top"><div class="top-in"><div class="tt">Bantuan Driver</div><div class="sub">Panduan operasional pickup & delivery.</div></div></header>
<main class="wrap">
  <section class="card js-in"><div class="q">Alamat tidak ditemukan</div><div class="a">Hubungi pelanggan, konfirmasi titik jemput, lalu perbarui catatan rute.</div></section>
  <section class="card js-in" style="background:#fff9e6; border-color:#ffe082"><div class="q" style="color:#854d0e">Butuh Bantuan Admin? 🆘</div><div class="a" style="color:#854d0e; font-weight:800; margin-bottom:10px">Hubungi admin operasional via WhatsApp untuk kendala di lapangan.</div><a href="https://wa.me/6281234567890?text=Halo%20Admin%20SIMALUN,%20saya%20kurir%20{{ urlencode(Auth::user()->name) }}%20mengalami%20kendala" target="_blank" style="display:inline-block; background:#25D366; color:#fff; padding:10px 20px; border-radius:12px; text-decoration:none; font-weight:900; font-size:0.8rem">Chat Admin WhatsApp</a></section>
  <section class="card js-in"><div class="q">Order tertunda</div><div class="a">Laporkan kendala ke admin melalui notifikasi internal agar status tugas tetap sinkron.</div></section>
  <section class="card js-in"><div class="q">Pelanggan tidak di lokasi</div><div class="a">Tunggu sesuai SLA, hubungi kembali pelanggan, lalu update hasil kunjungan pada tugas.</div></section>
</main>
<script>document.addEventListener('DOMContentLoaded',()=>{gsap.from('#top',{y:-16,opacity:0,duration:.45,ease:'power2.out'});gsap.from('.js-in',{y:18,opacity:0,duration:.42,stagger:.08,ease:'power2.out'});});</script>
</body>
</html>