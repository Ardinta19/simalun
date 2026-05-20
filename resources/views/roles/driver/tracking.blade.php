<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Lacak Tugas Driver – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue:#0077b6;--sky:#00b4d8;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#64748b;--line:#ddeeff;--r:16px;--nav-h:72px}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink);padding-bottom:var(--nav-h)}
.top{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue) 60%,var(--sky) 100%);color:#fff}
.top-in{max-width:560px;margin:0 auto;padding:20px 16px}
.tt{font-family:'Fredoka One',cursive;font-size:1.15rem}.sub{font-size:.78rem;font-weight:700;opacity:.9;margin-top:4px}
.wrap{max-width:560px;margin:0 auto;padding:14px}
.card{background:#fff;border:1.5px solid var(--line);border-radius:var(--r);padding:14px;margin-bottom:10px}
.h{font-family:'Fredoka One',cursive;font-size:.95rem}
.list{display:grid;gap:8px;margin-top:10px}
.row{display:flex;justify-content:space-between;align-items:center;padding:10px;border:1.5px solid var(--line);border-radius:12px}
.badge{font-size:.68rem;font-weight:900;border-radius:999px;padding:.18rem .5rem;background:#e0f2fe;color:#0369a1}
.nav{position:fixed;left:0;right:0;bottom:0;height:var(--nav-h);background:#fff;border-top:1.5px solid var(--line);display:flex;align-items:center;justify-content:center}
.nav-in{max-width:560px;width:100%;display:flex}.nav a{flex:1;text-align:center;text-decoration:none;color:#8b9aad;font-size:.7rem;font-weight:800}.nav a.active{color:#0f766e}
</style>
</head>
<body>
<header class="top" id="top"><div class="top-in"><div class="tt">Lacak Tugas Driver</div><div class="sub">Pantau status tugas pickup/delivery secara real-time.</div></div></header>
<main class="wrap">
  <section class="card js-in">
    <div class="h">Status Operasional</div>
    <div class="list">
      <div class="row"><span>Pickup aktif</span><span class="badge">Live</span></div>
      <div class="row"><span>Delivery aktif</span><span class="badge">Live</span></div>
      <div class="row"><span>Selesai hari ini</span><span class="badge">Ringkas</span></div>
    </div>
  </section>
</main>
<nav class="nav"><div class="nav-in"><a href="{{ route('driver.dashboard') }}">Beranda</a><a href="{{ route('driver.orders') }}">Tugas</a><a class="active" href="{{ route('driver.tracking') }}">Lacak</a><a href="{{ route('driver.help') }}">Bantuan</a></div></nav>
<script>document.addEventListener('DOMContentLoaded',()=>{gsap.from('#top',{y:-16,opacity:0,duration:.45,ease:'power2.out'});gsap.from('.js-in',{y:18,opacity:0,duration:.42,stagger:.08,ease:'power2.out'});});</script>
</body>
</html>