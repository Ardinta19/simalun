<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Dashboard – Azka Laundry SIMALUN</title>
@include('layouts.component.customer._head_meta')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>

<style>
/* ═══════════════════════════════════════════════
   TOKENS
═══════════════════════════════════════════════ */
:root {
  --c-ocean-deep:  #002f5c;
  --c-ocean-mid:   #0077b6;
  --c-ocean-light: #00b4d8;
  --c-ocean-sky:   #48cae4;
  --c-fire:        #FF6B35;
  --c-fire-soft:   #ff8c5a;
  --c-mint:        #00C48C;
  --c-surface:     #f2f8fd;
  --c-card:        #ffffff;
  --c-ink:         #0d2137;
  --c-ink-mid:     #3a546b;
  --c-ink-soft:    #7a9ab0;
  --c-line:        #daedf8;
  --c-line-soft:   #eef6fb;
  --nav-h:         68px;
  --r-card:        20px;
  --r-pill:        99px;
}

*, *::before, *::after {
  margin: 0; padding: 0;
  box-sizing: border-box;
  -webkit-tap-highlight-color: transparent;
}
html { scroll-behavior: smooth; }
body {
  font-family: 'Nunito', sans-serif;
  background: var(--c-surface);
  color: var(--c-ink);
  min-height: 100vh;
  overflow-x: hidden;
  padding-bottom: calc(var(--nav-h) + env(safe-area-inset-bottom, 0px) + 16px);
}

/* ═══════════════════════════════════════════════
   HEADER
═══════════════════════════════════════════════ */
.hd {
  background: linear-gradient(168deg,
    var(--c-ocean-deep) 0%,
    var(--c-ocean-mid)  55%,
    var(--c-ocean-light) 100%
  );
  position: relative;
  overflow: hidden;
  padding-top: max(env(safe-area-inset-top, 0px), 12px);
  /* wave bottom — menyambung ke konten */
  padding-bottom: 48px;
}

/* gelembung dekoratif */
.hd-bubble {
  position: absolute;
  border-radius: 50%;
  background: radial-gradient(circle at 30% 28%,
    rgba(255,255,255,.55), rgba(255,255,255,.04));
  border: 1.5px solid rgba(255,255,255,.22);
  pointer-events: none;
}

/* wave SVG di bawah header */
.hd-wave {
  position: absolute;
  bottom: -1px; left: -2%; right: -2%;
  pointer-events: none;
}

.hd-inner {
  max-width: 520px;
  margin: 0 auto;
  padding: 14px 20px 0;
  position: relative;
  z-index: 2;
}

/* baris logo + aksi */
.hd-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}
.hd-logo {
  display: flex;
  align-items: center;
  gap: 9px;
}
.hd-logo-text {
  font-family: 'Fredoka One', cursive;
  font-size: 1.1rem;
  color: #fff;
  letter-spacing: .2px;
  text-shadow: 0 2px 8px rgba(0,0,0,.2);
  line-height: 1;
}
.hd-logo-sub {
  display: block;
  font-size: .52rem;
  font-weight: 800;
  color: rgba(144,224,239,.85);
  letter-spacing: 3.5px;
  text-transform: uppercase;
  margin-top: 1px;
}
.hd-icons {
  display: flex;
  align-items: center;
  gap: 8px;
}
.hd-icon-btn {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: rgba(255,255,255,.14);
  border: 1.5px solid rgba(255,255,255,.24);
  display: flex; align-items: center; justify-content: center;
  cursor: pointer;
  transition: background .2s;
  text-decoration: none;
  position: relative;
  flex-shrink: 0;
}
.hd-icon-btn:hover { background: rgba(255,255,255,.26); }
.hd-icon-btn svg { width: 17px; height: 17px; stroke: #fff; }
.notif-pip {
  position: absolute;
  top: 6px; right: 6px;
  width: 8px; height: 8px;
  background: var(--c-fire);
  border-radius: 50%;
  border: 1.5px solid var(--c-ocean-mid);
}

/* greeting */
.hd-greet {
  font-size: .72rem;
  font-weight: 700;
  color: rgba(255,255,255,.68);
  margin-bottom: 3px;
  letter-spacing: .2px;
}
.hd-name {
  font-family: 'Fredoka One', cursive;
  font-size: clamp(1.65rem, 6.5vw, 2rem);
  color: #fff;
  line-height: 1;
  margin-bottom: 5px;
  text-shadow: 0 2px 14px rgba(0,0,0,.22);
}
.hd-slogan {
  font-size: .72rem;
  font-weight: 700;
  color: rgba(144,224,239,.9);
  letter-spacing: .3px;
  font-style: italic;
}

/* ═══════════════════════════════════════════════
   HERO CARD (pesanan aktif) — melayang di atas wave
   menggunakan negative margin supaya overlap
═══════════════════════════════════════════════ */
.page-body {
  max-width: 520px;
  margin: 0 auto;
  padding: 0 16px;
  /* overlap hero card ke atas wave */
  margin-top: -36px;
  position: relative;
  z-index: 5;
}

/* ═══════════════════════════════════════════════
   CTA JEMPUT — HERO UTAMA (80% pelanggan jemput antar!)
═══════════════════════════════════════════════ */
.cta-jemput {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: linear-gradient(135deg, var(--c-fire) 0%, var(--c-fire-soft) 100%);
  border-radius: var(--r-card);
  padding: 16px 18px;
  margin-bottom: 14px;
  text-decoration: none;
  color: #fff;
  box-shadow:
    0 12px 32px rgba(255,107,53,.38),
    0 4px 8px rgba(255,107,53,.2),
    inset 0 1px 0 rgba(255,255,255,.2);
  position: relative;
  overflow: hidden;
  transition: transform .15s, box-shadow .15s;
}
.cta-jemput:active {
  transform: scale(.97);
  box-shadow: 0 6px 16px rgba(255,107,53,.3);
}
.cta-jemput::before {
  content: '';
  position: absolute;
  top: 0; left: -100%; width: 55%; height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,.22), transparent);
  animation: shimmer 3s ease-in-out infinite;
}
@keyframes shimmer { 0%{left:-100%} 55%,100%{left:160%} }

/* drum icon di dalam CTA */
.cta-drum {
  width: 48px; height: 48px;
  background: rgba(255,255,255,.18);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.cta-texts { flex: 1; padding: 0 12px; }
.cta-label {
  display: block;
  font-family: 'Fredoka One', cursive;
  font-size: 1.05rem;
  line-height: 1.1;
  margin-bottom: 3px;
}
.cta-sub {
  display: block;
  font-size: .68rem;
  font-weight: 700;
  color: rgba(255,255,255,.78);
}
.cta-arr {
  width: 34px; height: 34px;
  background: rgba(255,255,255,.2);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

/* ═══════════════════════════════════════════════
   ACTIVE ORDER HERO CARD
═══════════════════════════════════════════════ */
.active-hero {
  background: #fff;
  border-radius: var(--r-card);
  border: 1.5px solid var(--c-line);
  margin-bottom: 14px;
  overflow: hidden;
  box-shadow:
    0 8px 28px rgba(0,47,92,.08),
    0 2px 6px rgba(0,47,92,.04);
}
/* accent bar kiri — seperti status aktif */
.active-hero::before {
  content: '';
  display: block;
  height: 4px;
  background: linear-gradient(90deg, var(--c-ocean-mid), var(--c-ocean-sky));
  border-radius: 99px 99px 0 0;
}
.ah-top {
  padding: 14px 16px 12px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
}
.ah-order-id {
  font-family: 'Fredoka One', cursive;
  font-size: .82rem;
  color: var(--c-ocean-mid);
  letter-spacing: .5px;
}
.ah-service {
  font-size: .72rem;
  font-weight: 700;
  color: var(--c-ink-soft);
  margin-top: 2px;
}
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: .62rem;
  font-weight: 900;
  padding: 4px 10px;
  border-radius: 99px;
  white-space: nowrap;
  letter-spacing: .3px;
}
.sp-washing {
  background: rgba(0,116,180,.1);
  color: var(--c-ocean-mid);
  border: 1px solid rgba(0,116,180,.22);
}
.sp-pickup {
  background: rgba(255,107,53,.1);
  color: #b84a14;
  border: 1px solid rgba(255,107,53,.2);
}
.sp-ready {
  background: rgba(0,196,140,.1);
  color: #047857;
  border: 1px solid rgba(0,196,140,.22);
}
.sp-done {
  background: rgba(0,196,140,.12);
  color: #065f46;
  border: 1px solid rgba(0,196,140,.25);
}
/* pulsing dot */
.live-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: currentColor;
  animation: livepulse 1.6s ease-in-out infinite;
}
@keyframes livepulse {
  0%,100% { opacity: 1; transform: scale(1); }
  50%      { opacity: .4; transform: scale(1.5); }
}

/* ── STEPPER PROGRESS ── */
.stepper {
  display: flex;
  align-items: flex-start;
  padding: 0 16px 14px;
  gap: 0;
}
.st-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 5px;
  flex: 1;
}
.st-line {
  flex: 1;
  height: 2px;
  background: var(--c-line);
  border-radius: 99px;
  margin-top: 12px;
  transition: background .4s;
}
.st-line.done { background: var(--c-ocean-mid); }

.st-dot {
  width: 24px; height: 24px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  font-size: .6rem;
  font-weight: 900;
  border: 2px solid var(--c-line);
  background: #fff;
  color: var(--c-ink-soft);
  transition: all .35s;
  z-index: 1;
}
.st-dot.done {
  background: var(--c-ocean-mid);
  border-color: var(--c-ocean-mid);
  color: #fff;
}
.st-dot.active {
  background: #fff;
  border-color: var(--c-ocean-mid);
  border-width: 2.5px;
  color: var(--c-ocean-mid);
  box-shadow: 0 0 0 4px rgba(0,119,182,.12);
}
.st-lbl {
  font-size: .58rem;
  font-weight: 800;
  color: var(--c-ink-soft);
  text-align: center;
  line-height: 1.2;
  white-space: nowrap;
}
.st-lbl.done   { color: var(--c-ocean-mid); }
.st-lbl.active { color: var(--c-ink); }

/* ── FOOTER INFO ── */
.ah-footer {
  display: flex;
  align-items: center;
  padding: 11px 16px;
  border-top: 1.5px solid var(--c-line-soft);
  gap: 12px;
  background: rgba(0,116,180,.02);
}
.ah-meta {
  flex: 1;
  display: flex;
  gap: 16px;
}
.ah-meta-item {}
.ah-meta-lbl {
  font-size: .58rem;
  font-weight: 900;
  text-transform: uppercase;
  letter-spacing: .7px;
  color: var(--c-ink-soft);
}
.ah-meta-val {
  font-size: .82rem;
  font-weight: 900;
  color: var(--c-ink);
  margin-top: 1px;
}
.btn-lacak {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: var(--c-ocean-mid);
  color: #fff;
  text-decoration: none;
  border-radius: 99px;
  padding: 7px 15px;
  font-size: .72rem;
  font-weight: 900;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(0,119,182,.3);
  transition: background .15s, transform .15s;
  letter-spacing: .2px;
}
.btn-lacak:active { transform: scale(.95); }

/* empty state */
.no-order {
  background: #fff;
  border-radius: var(--r-card);
  border: 2px dashed var(--c-line);
  padding: 28px 20px;
  text-align: center;
  margin-bottom: 14px;
}
.no-order-icon {
  font-size: 2.8rem;
  margin-bottom: 10px;
  display: block;
}
.no-order-txt {
  font-size: .8rem;
  font-weight: 700;
  color: var(--c-ink-soft);
  line-height: 1.5;
  margin-bottom: 14px;
}

/* ═══════════════════════════════════════════════
   SECTION LABEL
═══════════════════════════════════════════════ */
.sec-label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 18px 0 10px;
}
.sec-title {
  font-family: 'Fredoka One', cursive;
  font-size: .95rem;
  color: var(--c-ink);
  letter-spacing: .2px;
}
.sec-link {
  font-size: .7rem;
  font-weight: 800;
  color: var(--c-ocean-mid);
  text-decoration: none;
  letter-spacing: .2px;
}
.sec-link:hover { text-decoration: underline; }

/* ═══════════════════════════════════════════════
   STATS ROW — 2 kartu saja, tidak cramped
═══════════════════════════════════════════════ */
.stats-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-bottom: 14px;
}
.stat-card {
  background: #fff;
  border: 1.5px solid var(--c-line);
  border-radius: var(--r-card);
  padding: 14px 16px;
  box-shadow: 0 2px 8px rgba(0,47,92,.04);
  position: relative;
  overflow: hidden;
}
.stat-card::after {
  content: '';
  position: absolute;
  bottom: -10px; right: -10px;
  width: 56px; height: 56px;
  border-radius: 50%;
  background: rgba(0,119,182,.05);
}
.stat-ico {
  font-size: 1.3rem;
  margin-bottom: 8px;
  display: block;
}
.stat-num {
  font-family: 'Fredoka One', cursive;
  font-size: 1.8rem;
  color: var(--c-ocean-mid);
  line-height: 1;
}
.stat-lbl {
  font-size: .65rem;
  font-weight: 900;
  color: var(--c-ink-soft);
  text-transform: uppercase;
  letter-spacing: .7px;
  margin-top: 4px;
}

/* ═══════════════════════════════════════════════
   PROMO BANNER
═══════════════════════════════════════════════ */
.promo-banner {
  background: linear-gradient(135deg,
    var(--c-ocean-deep) 0%,
    var(--c-ocean-mid) 100%
  );
  border-radius: var(--r-card);
  padding: 18px 20px;
  margin-bottom: 14px;
  display: flex;
  align-items: center;
  gap: 14px;
  text-decoration: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0,47,92,.2);
  transition: transform .15s;
}
.promo-banner:active { transform: scale(.97); }
/* deco circle */
.promo-banner::after {
  content: '';
  position: absolute;
  width: 130px; height: 130px;
  border-radius: 50%;
  background: rgba(255,255,255,.06);
  top: -40px; right: -30px;
  pointer-events: none;
}
.promo-tag {
  background: var(--c-fire);
  color: #fff;
  font-family: 'Fredoka One', cursive;
  font-size: 1.7rem;
  padding: 8px 14px;
  border-radius: 14px;
  flex-shrink: 0;
  box-shadow: 0 4px 14px rgba(255,107,53,.4);
}
.promo-texts { flex: 1; }
.promo-title {
  font-family: 'Fredoka One', cursive;
  font-size: 1rem;
  color: #fff;
  line-height: 1.2;
  margin-bottom: 3px;
}
.promo-sub {
  font-size: .68rem;
  font-weight: 700;
  color: rgba(144,224,239,.88);
}
.promo-arr {
  color: rgba(255,255,255,.4);
  font-size: 1.4rem;
  flex-shrink: 0;
}

/* ═══════════════════════════════════════════════
   AKSI CEPAT — icon grid 2x2
═══════════════════════════════════════════════ */
.quick-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 8px;
  margin-bottom: 14px;
}
.quick-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  text-decoration: none;
  padding: 12px 4px;
  background: #fff;
  border: 1.5px solid var(--c-line);
  border-radius: 16px;
  transition: border-color .2s, box-shadow .2s;
  box-shadow: 0 1px 4px rgba(0,47,92,.04);
}
.quick-item:active {
  border-color: var(--c-ocean-mid);
  box-shadow: 0 0 0 3px rgba(0,119,182,.1);
}
.quick-ico {
  width: 40px; height: 40px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.qi-blue   { background: rgba(0,116,180,.1); }
.qi-orange { background: rgba(255,107,53,.1); }
.qi-green  { background: rgba(0,196,140,.1); }
.qi-sky    { background: rgba(0,180,216,.1); }
.quick-lbl {
  font-size: .62rem;
  font-weight: 900;
  color: var(--c-ink-mid);
  text-align: center;
  line-height: 1.2;
  letter-spacing: .2px;
}

/* ═══════════════════════════════════════════════
   RIWAYAT PESANAN
═══════════════════════════════════════════════ */
.history-card {
  background: #fff;
  border: 1.5px solid var(--c-line);
  border-radius: var(--r-card);
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,47,92,.04);
  margin-bottom: 14px;
}
.hist-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 16px;
  border-bottom: 1px solid var(--c-line-soft);
  text-decoration: none;
  color: inherit;
  transition: background .15s;
}
.hist-item:last-child { border-bottom: none; }
.hist-item:active { background: var(--c-surface); }
.hist-ico {
  width: 42px; height: 42px;
  border-radius: 13px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.1rem;
  flex-shrink: 0;
}
.hist-texts { flex: 1; min-width: 0; }
.hist-name {
  font-size: .85rem;
  font-weight: 800;
  color: var(--c-ink);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.hist-code {
  font-size: .68rem;
  font-weight: 700;
  color: var(--c-ink-soft);
  margin-top: 2px;
}
.hist-done-badge {
  display: inline-block;
  margin-top: 4px;
  font-size: .58rem;
  font-weight: 900;
  background: rgba(0,196,140,.1);
  color: #047857;
  padding: 2px 8px;
  border-radius: 99px;
  border: 1px solid rgba(0,196,140,.22);
  letter-spacing: .3px;
}
.hist-right { text-align: right; flex-shrink: 0; }
.hist-price {
  font-family: 'Fredoka One', cursive;
  font-size: .95rem;
  color: var(--c-ocean-mid);
}
.hist-date {
  font-size: .62rem;
  font-weight: 700;
  color: var(--c-ink-soft);
  margin-top: 2px;
}

/* empty history */
.hist-empty {
  padding: 24px;
  text-align: center;
  font-size: .78rem;
  font-weight: 700;
  color: var(--c-ink-soft);
}

/* ═══════════════════════════════════════════════
   BOTTOM NAV
═══════════════════════════════════════════════ */
.bot-nav {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  height: var(--nav-h);
  background: rgba(255,255,255,.96);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border-top: 1px solid var(--c-line);
  z-index: 100;
  padding-bottom: env(safe-area-inset-bottom, 0px);
}
.bn-inner {
  max-width: 520px;
  margin: 0 auto;
  height: 100%;
  display: flex;
  align-items: center;
}
.bn-item {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 3px;
  text-decoration: none;
  cursor: pointer;
  padding: 4px 0;
  -webkit-tap-highlight-color: transparent;
  position: relative;
}
.bn-icon {
  width: 24px; height: 24px;
  display: flex; align-items: center; justify-content: center;
  color: var(--c-ink-soft);
  transition: color .2s;
}
.bn-icon.on { color: var(--c-ocean-mid); }
.bn-label {
  font-size: .58rem;
  font-weight: 800;
  color: var(--c-ink-soft);
  letter-spacing: .3px;
  text-transform: uppercase;
}
.bn-label.on { color: var(--c-ocean-mid); }
/* dot indicator di bawah icon aktif */
.bn-dot {
  width: 4px; height: 4px;
  background: var(--c-ocean-mid);
  border-radius: 50%;
  margin-top: -1px;
}

/* badge notif */
.notif-badge {
  position: absolute;
  top: 2px; right: calc(50% - 22px);
  min-width: 16px; height: 16px;
  background: var(--c-fire);
  color: #fff;
  font-size: .55rem;
  font-weight: 900;
  border-radius: 99px;
  display: flex; align-items: center; justify-content: center;
  border: 1.5px solid #fff;
  padding: 0 4px;
}

/* FAB order — pusat nav */
.bn-fab {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 3px;
  text-decoration: none;
  cursor: pointer;
}
.bn-fab-btn {
  width: 50px; height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--c-fire) 0%, var(--c-fire-soft) 100%);
  display: flex; align-items: center; justify-content: center;
  margin-top: -22px;
  box-shadow:
    0 6px 20px rgba(255,107,53,.45),
    0 2px 6px rgba(255,107,53,.2);
  transition: transform .15s, box-shadow .15s;
  flex-shrink: 0;
}
.bn-fab:active .bn-fab-btn {
  transform: scale(.92);
  box-shadow: 0 3px 10px rgba(255,107,53,.3);
}
.bn-fab-lbl {
  font-size: .55rem;
  font-weight: 900;
  color: var(--c-fire);
  text-transform: uppercase;
  letter-spacing: .4px;
}

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (min-width: 560px) {
  .page-body { padding: 0 24px; }
}

/* ═══════════════════════════════════════════════
   ENTRANCE ANIMATIONS
═══════════════════════════════════════════════ */
.js-reveal {
  opacity: 0;
  transform: translateY(22px);
}
</style>
</head>
<body>

{{-- ══════════════════════════════════════
     HEADER
══════════════════════════════════════ --}}
<header class="hd" aria-label="Header dashboard">

  {{-- Gelembung dekoratif ambient --}}
  <div class="hd-bubble" style="width:100px;height:100px;top:-40px;right:24px;opacity:.45;"></div>
  <div class="hd-bubble" style="width:48px;height:48px;top:22px;right:140px;opacity:.3;"></div>
  <div class="hd-bubble" style="width:28px;height:28px;top:60px;left:80px;opacity:.25;"></div>

  <div class="hd-inner">
    {{-- Topbar --}}
    <div class="hd-topbar">
      <div class="hd-logo">
        {{-- Drum SVG mini --}}
        <svg width="32" height="32" viewBox="0 0 148 148" fill="none" aria-hidden="true">
          <circle cx="74" cy="74" r="70" fill="rgba(255,255,255,.07)" stroke="rgba(255,255,255,.18)" stroke-width="1.5"/>
          <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.14)" stroke="rgba(255,255,255,.35)" stroke-width="1.8"/>
          <rect x="16" y="26" width="116" height="30" rx="16" fill="rgba(255,255,255,.2)"/>
          <circle cx="36" cy="41" r="7" fill="#FF6B35"/>
          <circle cx="56" cy="41" r="7" fill="#00C48C"/>
          <circle cx="74" cy="90" r="32" fill="rgba(0,100,160,.55)" stroke="rgba(255,255,255,.45)" stroke-width="2.5"/>
          <circle cx="74" cy="90" r="22" fill="rgba(255,255,255,.16)" stroke="rgba(255,255,255,.6)" stroke-width="2.5"/>
          <path d="M64 88 Q74 78 84 88 L81 100 H67Z" fill="white" opacity=".9"/>
        </svg>
        <div>
          <span class="hd-logo-text">Azka Laundry</span>
          <span class="hd-logo-sub">SIMALUN</span>
        </div>
      </div>

      <div class="hd-icons">
        <a href="{{ route('order.create') }}" class="hd-icon-btn" title="Pesan baru" aria-label="Buat pesanan baru">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
          </svg>
        </a>
        <a href="{{ route('customer.notifications') }}" class="hd-icon-btn" title="Notifikasi" aria-label="Notifikasi">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
          </svg>
          @if(isset($unreadNotif) && $unreadNotif > 0)
            <span class="notif-pip" aria-label="{{ $unreadNotif }} notifikasi belum dibaca"></span>
          @endif
        </a>
      </div>
    </div>

    {{-- Greeting --}}
    <p class="hd-greet">Selamat datang kembali ☀️</p>
    <h1 class="hd-name">Halo, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
    <p class="hd-slogan">"Budayakan malas nyuci, itu pekerjaan kami."</p>
  </div>

  {{-- Wave SVG — menyambung ke konten --}}
  <svg class="hd-wave" viewBox="0 0 414 52" preserveAspectRatio="none" aria-hidden="true">
    <path fill="#f2f8fd"
      d="M0,28 C80,52 160,8 240,28 C300,44 360,12 414,24 L414,52 L0,52Z"/>
  </svg>
</header>

{{-- ══════════════════════════════════════
     PAGE BODY
══════════════════════════════════════ --}}
<main class="page-body" role="main">

  {{-- ── CTA JEMPUT (hero utama — 80% pelanggan jemput antar!) ── --}}
  <a href="{{ route('order.create') }}" class="cta-jemput js-reveal" aria-label="Pesan jemput sekarang">
    <div class="cta-drum" aria-hidden="true">
      <svg width="28" height="28" viewBox="0 0 148 148" fill="none">
        <rect x="16" y="26" width="116" height="106" rx="18" fill="rgba(255,255,255,.25)" stroke="rgba(255,255,255,.5)" stroke-width="2"/>
        <rect x="16" y="26" width="116" height="30" rx="16" fill="rgba(255,255,255,.3)"/>
        <circle cx="74" cy="90" r="30" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.6)" stroke-width="2.5"/>
        <path d="M64 88 Q74 78 84 88 L81 100 H67Z" fill="white" opacity=".95"/>
      </svg>
    </div>
    <div class="cta-texts">
      <span class="cta-label">Pesan Jemput Sekarang</span>
      <span class="cta-sub">Driver kami langsung ke depan pintumu</span>
    </div>
    <div class="cta-arr" aria-hidden="true">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12h14M12 5l7 7-7 7"/>
      </svg>
    </div>
  </a>

  {{-- ── PESANAN AKTIF ── --}}
  @if(isset($pesananAktif) && $pesananAktif)
  @php
    $statusCfg = [
      'menunggu'   => ['class'=>'sp-pickup',  'label'=>'Menunggu Kurir',    'dot'=>true],
      'dijemput'   => ['class'=>'sp-pickup',  'label'=>'Kurir Menjemput',   'dot'=>true],
      'dicuci'     => ['class'=>'sp-washing', 'label'=>'Sedang Dicuci',     'dot'=>true],
      'disetrika'  => ['class'=>'sp-washing', 'label'=>'Sedang Disetrika',  'dot'=>true],
      'siap'       => ['class'=>'sp-ready',   'label'=>'Siap Diantar',      'dot'=>true],
      'dikirim'    => ['class'=>'sp-ready',   'label'=>'Kurir Mengantar',   'dot'=>true],
      'selesai'    => ['class'=>'sp-done',    'label'=>'Selesai',           'dot'=>false],
    ];
    $st    = $statusCfg[$pesananAktif->status] ?? ['class'=>'sp-washing','label'=>ucfirst($pesananAktif->status),'dot'=>true];
    $steps = [
      ['key'=>'dijemput',   'icon'=>'📦', 'label'=>"Dijemput"],
      ['key'=>'dicuci',     'icon'=>'🫧', 'label'=>"Dicuci"],
      ['key'=>'siap',       'icon'=>'✅', 'label'=>"Siap"],
      ['key'=>'dikirim',    'icon'=>'🛵', 'label'=>"Diantar"],
      ['key'=>'selesai',    'icon'=>'🎉', 'label'=>"Selesai"],
    ];
    $statusOrder = ['menunggu','dijemput','dicuci','disetrika','siap','dikirim','selesai'];
    $curIdx = array_search($pesananAktif->status, $statusOrder) ?: 0;
  @endphp

  <div class="js-reveal">
    <div class="sec-label">
      <span class="sec-title">Pesanan Aktif</span>
      <a href="{{ route('customer.orders') }}" class="sec-link">Lihat semua →</a>
    </div>

    <div class="active-hero" role="region" aria-label="Status pesanan aktif">
      <div class="ah-top">
        <div>
          <div class="ah-order-id">#{{ strtoupper($pesananAktif->order_code) }}</div>
          <div class="ah-service">
            {{ $pesananAktif->service->name ?? 'Cuci Kiloan' }}
            · {{ $pesananAktif->weight_estimate ?? '-' }} kg
          </div>
        </div>
        <span class="status-pill {{ $st['class'] }}" role="status">
          @if($st['dot'])<span class="live-dot" aria-hidden="true"></span>@endif
          {{ $st['label'] }}
        </span>
      </div>

      {{-- Stepper --}}
      <div class="stepper" role="list" aria-label="Progress status cucian">
        @foreach($steps as $i => $step)
          @php
            $stepIdx  = array_search($step['key'], $statusOrder) ?: 0;
            $isDone   = $curIdx > $stepIdx;
            $isActive = $curIdx == $stepIdx;
            $dotCls   = $isDone ? 'done' : ($isActive ? 'active' : '');
            $lblCls   = $isDone ? 'done' : ($isActive ? 'active' : '');
          @endphp
          <div class="st-item" role="listitem">
            <div class="st-dot {{ $dotCls }}" aria-label="{{ $step['label'] }}: {{ $isDone ? 'selesai' : ($isActive ? 'sedang berlangsung' : 'menunggu') }}">
              @if($isDone)
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              @elseif($isActive)
                <span style="font-size:.65rem">{{ $i+1 }}</span>
              @else
                <span style="font-size:.65rem">{{ $i+1 }}</span>
              @endif
            </div>
            <div class="st-lbl {{ $lblCls }}">{{ $step['label'] }}</div>
          </div>
          @if(!$loop->last)
            <div class="st-line {{ $isDone ? 'done' : '' }}" aria-hidden="true"></div>
          @endif
        @endforeach
      </div>

      <div class="ah-footer">
        <div class="ah-meta">
          <div class="ah-meta-item">
            <div class="ah-meta-lbl">Total</div>
            <div class="ah-meta-val">Rp {{ number_format($pesananAktif->total_cost ?? 0, 0, ',', '.') }}</div>
          </div>
          <div class="ah-meta-item">
            <div class="ah-meta-lbl">Layanan</div>
            <div class="ah-meta-val">
              {{ $pesananAktif->service->name ?? 'Reguler' }}
            </div>
          </div>
        </div>
        <a href="{{ route('customer.order.detail', $pesananAktif->id) }}" class="btn-lacak" aria-label="Lacak pesanan {{ $pesananAktif->order_code }}">
          Lacak
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M5 12h14M12 5l7 7-7 7"/>
          </svg>
        </a>
      </div>
    </div>
  </div>

  @else

  {{-- Empty state --}}
  <div class="js-reveal">
    <div class="sec-label">
      <span class="sec-title">Pesanan Aktif</span>
    </div>
    <div class="no-order" role="region" aria-label="Tidak ada pesanan aktif">
      <span class="no-order-icon" aria-hidden="true">🧺</span>
      <p class="no-order-txt">Belum ada cucian diproses.<br>Yuk, buat harimu lebih ringan!</p>
      <a href="{{ route('order.create') }}" class="btn-lacak" style="display:inline-flex;margin:0 auto;">
        Pesan Sekarang
      </a>
    </div>
  </div>
  @endif

  {{-- ── STATISTIK ── --}}
  <div class="js-reveal">
    <div class="stats-row">
      <div class="stat-card">
        <span class="stat-ico" aria-hidden="true">📦</span>
        <div class="stat-num">{{ $totalPesanan ?? 0 }}</div>
        <div class="stat-lbl">Total Pesanan</div>
      </div>
      <div class="stat-card">
        <span class="stat-ico" aria-hidden="true">✅</span>
        <div class="stat-num">{{ $totalSelesai ?? 0 }}</div>
        <div class="stat-lbl">Selesai</div>
      </div>
    </div>
  </div>

  {{-- ── PROMO BANNER ── --}}
  <a href="{{ route('customer.orders') }}" class="promo-banner js-reveal" aria-label="Promo diskon 20% member baru">
    <div class="promo-tag" aria-hidden="true">20%</div>
    <div class="promo-texts">
      <div class="promo-title">Diskon Member Baru!</div>
      <div class="promo-sub">Berlaku s/d 31 Mei 2026 · Kode: WELCOME20</div>
    </div>
    <div class="promo-arr" aria-hidden="true">›</div>
  </a>

  {{-- ── AKSI CEPAT ── --}}
  <div class="js-reveal">
    <div class="sec-label">
      <span class="sec-title">Aksi Cepat</span>
    </div>
    <div class="quick-grid" role="navigation" aria-label="Menu aksi cepat">
      <a href="{{ route('order.create') }}" class="quick-item" aria-label="Pesan baru">
        <div class="quick-ico qi-orange" aria-hidden="true">🆕</div>
        <span class="quick-lbl">Pesan Baru</span>
      </a>
      <a href="{{ route('customer.orders') }}" class="quick-item" aria-label="Riwayat pesanan">
        <div class="quick-ico qi-blue" aria-hidden="true">📋</div>
        <span class="quick-lbl">Riwayat</span>
      </a>
      <a href="{{ route('customer.addresses.index') }}" class="quick-item" aria-label="Alamat tersimpan">
        <div class="quick-ico qi-green" aria-hidden="true">📍</div>
        <span class="quick-lbl">Alamat</span>
      </a>
      <a href="{{ route('customer.help') }}" class="quick-item" aria-label="Pusat bantuan">
        <div class="quick-ico qi-sky" aria-hidden="true">🆘</div>
        <span class="quick-lbl">Bantuan</span>
      </a>
    </div>
  </div>

  {{-- ── RIWAYAT PESANAN ── --}}
  <div class="js-reveal">
    <div class="sec-label">
      <span class="sec-title">Riwayat Pesanan</span>
      <a href="{{ route('customer.orders') }}" class="sec-link">Semua →</a>
    </div>
    <div class="history-card">
      @forelse(($riwayat ?? collect()) as $order)
      @php
        $svcSlug  = $order->service->slug ?? 'reguler';
        $icons    = ['reguler'=>'🧺','express'=>'⚡','prioritas'=>'💎'];
        $bgColors = ['reguler'=>'qi-blue','express'=>'qi-orange','prioritas'=>'qi-green'];
        $ico      = $icons[$svcSlug] ?? '🫧';
        $bgCls    = $bgColors[$svcSlug] ?? 'qi-blue';
      @endphp
      <a href="{{ route('customer.order.detail', $order->id) }}" class="hist-item" aria-label="Detail pesanan {{ $order->order_code }}">
        <div class="hist-ico {{ $bgCls }}" aria-hidden="true">{{ $ico }}</div>
        <div class="hist-texts">
          <div class="hist-name">{{ $order->service->name ?? 'Cuci Kiloan' }}</div>
          <div class="hist-code">#{{ strtoupper($order->order_code) }} · {{ $order->weight_actual ?? $order->weight_estimate }} kg</div>
          <span class="hist-done-badge">✓ Selesai</span>
        </div>
        <div class="hist-right">
          <div class="hist-price">Rp {{ number_format($order->total_cost ?? 0, 0, ',', '.') }}</div>
          <div class="hist-date">{{ \Carbon\Carbon::parse($order->created_at)->isoFormat('D MMM YY') }}</div>
        </div>
      </a>
      @empty
      <div class="hist-empty">Belum ada riwayat pesanan.</div>
      @endforelse
    </div>
  </div>

</main>

{{-- ══════════════════════════════════════
     BOTTOM NAVIGATION
══════════════════════════════════════ --}}
<nav class="bot-nav" aria-label="Navigasi utama">
  <div class="bn-inner">

    <a href="{{ route('customer.dashboard') }}" class="bn-item" aria-label="Beranda" aria-current="page">
      <div class="bn-icon on" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
        </svg>
      </div>
      <span class="bn-label on">Beranda</span>
      <div class="bn-dot" aria-hidden="true"></div>
    </a>

    <a href="{{ route('customer.orders') }}" class="bn-item" aria-label="Daftar pesanan">
      <div class="bn-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/>
        </svg>
      </div>
      <span class="bn-label">Pesanan</span>
    </a>

    {{-- FAB Pesan — pusat nav --}}
    <a href="{{ route('order.create') }}" class="bn-fab" aria-label="Pesan jemput sekarang">
      <div class="bn-fab-btn" aria-hidden="true">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
      </div>
      <span class="bn-fab-lbl">Pesan</span>
    </a>

    <a href="{{ route('customer.notifications') }}" class="bn-item" aria-label="Notifikasi">
      <div class="bn-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/>
        </svg>
      </div>
      <span class="bn-label">Notif</span>
      @if(isset($unreadNotif) && $unreadNotif > 0)
        <span class="notif-badge" aria-label="{{ $unreadNotif }} notifikasi">{{ $unreadNotif }}</span>
      @endif
    </a>

    <a href="{{ route('customer.profile') }}" class="bn-item" aria-label="Profil saya">
      <div class="bn-icon" aria-hidden="true">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
        </svg>
      </div>
      <span class="bn-label">Profil</span>
    </a>

  </div>
</nav>

{{-- ══════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof gsap === 'undefined') {
        document.querySelectorAll('.js-reveal').forEach(function(el) {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
        return;
    }

    var reveals = document.querySelectorAll('.js-reveal');
    if (reveals.length > 0) {
        gsap.fromTo(reveals,
            { opacity: 0, y: 28 },
            {
                opacity: 1, y: 0,
                duration: .55,
                stagger: .1,
                ease: 'power3.out',
                delay: .1,
            }
        );
    }

    (function spawnHeaderBubbles() {
        const header = document.querySelector('.hd');
        if (!header) return;

        const deco = [
            { w: 18, h: 18, top: '20%', right: '18%', delay: .6 },
            { w: 10, h: 10, top: '55%', right: '35%', delay: 1.1 },
            { w: 14, h: 14, top: '30%', left: '12%',  delay: .85 },
        ];
        deco.forEach(cfg => {
            const b = document.createElement('div');
            b.style.cssText = [
                'position:absolute','border-radius:50%','pointer-events:none',
                `width:${cfg.w}px`, `height:${cfg.h}px`,
                'background:radial-gradient(circle at 30% 28%,rgba(255,255,255,.7),rgba(255,255,255,.06))',
                'border:1px solid rgba(255,255,255,.3)',
                cfg.top   ? `top:${cfg.top}`   : '',
                cfg.right ? `right:${cfg.right}` : '',
                cfg.left  ? `left:${cfg.left}`  : '',
                'opacity:0',
            ].join(';');
            header.appendChild(b);
            gsap.to(b, {
                opacity: .7, y: -12,
                duration: 2.5 + Math.random(),
                ease: 'sine.inOut',
                yoyo: true, repeat: -1,
                delay: cfg.delay,
            });
            gsap.fromTo(b, { opacity: 0 }, { opacity: .7, duration: .5, delay: cfg.delay });
        });
    })();

    const drumSvg = document.querySelector('.hd-logo svg');
    if (drumSvg) {
        gsap.to(drumSvg, {
            rotation: 360,
            duration: 24,
            ease: 'none',
            repeat: -1,
            transformOrigin: '50% 50%',
        });
    }

    const activeHero = document.querySelector('.active-hero');
    if (activeHero) {
        gsap.to(activeHero, {
            boxShadow: '0 12px 36px rgba(0,119,182,.15), 0 2px 6px rgba(0,47,92,.06)',
            duration: 2,
            yoyo: true,
            repeat: -1,
            ease: 'sine.inOut',
        });
    }

    const ctaBtn = document.querySelector('.cta-jemput');
    if (ctaBtn) {
        const ctaDrum = ctaBtn.querySelector('svg');
        if (ctaDrum) {
            ctaBtn.addEventListener('touchstart', function () {
                gsap.to(ctaDrum, { rotation: '+=45', duration: .25, ease: 'power2.out' });
            }, { passive: true });
        }
    }

    document.querySelectorAll('.bn-item, .bn-fab').forEach(el => {
        el.addEventListener('touchstart', function () {
            gsap.to(this, { scale: .91, duration: .09, ease: 'power2.out' });
        }, { passive: true });
        el.addEventListener('touchend', function () {
            gsap.to(this, { scale: 1, duration: .22, ease: 'back.out(2.5)' });
        }, { passive: true });
    });

    const wave = document.querySelector('.hd-wave path');
    if (wave) {
        gsap.to(wave, {
            attr: {
                d: 'M0,20 C80,48 160,4 240,26 C300,42 360,8 414,22 L414,52 L0,52Z'
            },
            duration: 3.5,
            ease: 'sine.inOut',
            yoyo: true,
            repeat: -1,
        });
    }
});
</script>

</body>
</html>