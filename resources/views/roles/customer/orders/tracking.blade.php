<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Lacak Pesanan – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
    --blue-dark:  #002f5c;
    --blue-mid:   #0077b6;
    --blue-light: #00b4d8;
    --orange:     #FF6B35;
    --green:      #00C48C;
    --surface:    #f4f8fc;
    --card:       #ffffff;
    --ink:        #1a2332;
    --ink-mid:    #3d5066;
    --ink-lt:     #8899aa;
    --border:     #ddeeff;
    --radius:     20px;
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
html, body { height: 100%; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    color: var(--ink);
    overflow-x: hidden;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
}

/* ── MAP ──────────────────────────────── */
#map-container {
    position: relative;
    width: 100%;
    height: 52vh;
    min-height: 300px;
    background: #e5e7eb;
}
#tracking-map {
    width: 100%; height: 100%;
}

/* Back button over map */
.map-back {
    position: absolute;
    top: max(env(safe-area-inset-top, 0px), 16px);
    left: 16px;
    z-index: 500;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: white;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; color: var(--ink);
    border: 1.5px solid var(--border);
}

/* Recenter button */
.map-recenter {
    position: absolute;
    bottom: 16px; right: 16px;
    z-index: 500;
    width: 40px; height: 40px;
    border-radius: 50%;
    background: white;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; border: 1.5px solid var(--border);
    font-size: 1.1rem;
}

/* Status chip on map */
.map-status-chip {
    position: absolute;
    top: max(env(safe-area-inset-top, 0px), 16px);
    left: 50%; transform: translateX(-50%);
    z-index: 500;
    background: white;
    border-radius: 99px;
    padding: 7px 16px;
    display: flex; align-items: center; gap: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    border: 1.5px solid var(--border);
    white-space: nowrap;
}
.map-status-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: var(--orange);
    animation: pls 1.5s ease-in-out infinite;
}
@keyframes pls {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.5; transform:scale(0.8); }
}
.map-status-label {
    font-size: 0.75rem; font-weight: 900; color: var(--ink);
}

/* ── SLIDE-UP PANEL ───────────────────── */
.panel {
    position: relative;
    z-index: 10;
    background: var(--surface);
    border-radius: 24px 24px 0 0;
    margin-top: -24px;
}
.panel-handle {
    width: 40px; height: 4px;
    background: var(--border);
    border-radius: 99px;
    margin: 12px auto 8px;
}
.panel-inner {
    padding: 0 16px;
    max-width: 520px;
    margin: 0 auto;
}

/* ── ETA CARD ─────────────────────────── */
.eta-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 16px 20px;
    margin-bottom: 14px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
}
.eta-icon {
    width: 52px; height: 52px; border-radius: 16px;
    background: linear-gradient(135deg, var(--blue-mid), var(--blue-light));
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; flex-shrink: 0;
}
.eta-info { flex: 1; }
.eta-label { font-size: 0.7rem; font-weight: 800; color: var(--ink-lt); letter-spacing: 0.5px; text-transform: uppercase; }
.eta-value { font-weight: 800; font-size: 1.4rem; color: var(--blue-dark); line-height: 1; margin-top: 2px; }
.eta-sub   { font-size: 0.72rem; font-weight: 700; color: var(--ink-lt); margin-top: 3px; }
.eta-badge {
    background: var(--green);
    color: white;
    border-radius: 99px;
    padding: 5px 12px;
    font-size: 0.68rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}

/* ── DRIVER INFO ──────────────────────── */
.driver-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 16px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
    display: flex; align-items: center; gap: 14px;
}
.driver-avatar {
    width: 50px; height: 50px; border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    object-fit: cover; flex-shrink: 0;
}
.driver-info { flex: 1; }
.driver-name { font-weight: 900; font-size: 0.95rem; color: var(--ink); }
.driver-meta { font-size: 0.72rem; font-weight: 700; color: var(--ink-lt); margin-top: 2px; }
.driver-rating {
    display: inline-flex; align-items: center; gap: 4px;
    background: #fffbeb; border-radius: 99px;
    padding: 3px 8px; margin-top: 5px;
    font-size: 0.68rem; font-weight: 900; color: #92400e;
}
.driver-actions { display: flex; gap: 8px; }
.da-btn {
    width: 40px; height: 40px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    text-decoration: none; font-size: 1rem;
    transition: transform 0.15s;
}
.da-btn:active { transform: scale(0.9); }
.da-btn.wa   { background: #dcfce7; }
.da-btn.call { background: var(--border); }

/* ── ACTIVITY TIMELINE ────────────────── */
.activity-card {
    background: white;
    border-radius: var(--radius);
    border: 1.5px solid var(--border);
    padding: 18px;
    margin-bottom: 14px;
    box-shadow: 0 4px 16px rgba(0,47,92,0.05);
}
.activity-title {
    font-weight: 800;
    font-size: 0.9rem; color: var(--blue-dark);
    margin-bottom: 16px;
    display: flex; align-items: center; gap: 8px;
}
.activity-item {
    display: flex; gap: 14px; position: relative; padding-bottom: 18px;
}
.activity-item:last-child { padding-bottom: 0; }
.activity-item::before {
    content: '';
    position: absolute;
    left: 7px; top: 20px; bottom: 0;
    width: 2px; background: var(--border);
}
.activity-item:last-child::before { display: none; }
.activity-dot {
    width: 16px; height: 16px; border-radius: 50%;
    flex-shrink: 0; z-index: 2; margin-top: 2px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.5rem;
}
.activity-dot.current {
    background: var(--orange); border: 2.5px solid var(--orange);
    box-shadow: 0 0 0 4px rgba(255,107,53,0.15);
    animation: adot 1.5s ease-in-out infinite;
}
@keyframes adot {
    0%,100% { box-shadow: 0 0 0 0 rgba(255,107,53,0.4); }
    50%      { box-shadow: 0 0 0 8px rgba(255,107,53,0); }
}
.activity-dot.done    { background: var(--green); border: 2.5px solid var(--green); }
.activity-dot.pending { background: white; border: 2.5px solid var(--border); }
.activity-text { flex: 1; }
.at-title { font-size: 0.82rem; font-weight: 800; color: var(--ink); }
.at-title.muted { color: var(--ink-lt); font-weight: 700; }
.at-time  { font-size: 0.68rem; font-weight: 700; color: var(--ink-lt); margin-top: 2px; }
.at-time.current { color: var(--orange); font-weight: 900; }

/* ── LEAFLET CUSTOM MARKER ────────────── */
.custom-marker {
    background: none; border: none;
}
.marker-customer {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--blue-mid);
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(0,119,182,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem;
}
.marker-driver {
    width: 44px; height: 44px; border-radius: 50%;
    background: var(--orange);
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(255,107,53,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    animation: bounce 1s ease-in-out infinite;
}
@keyframes bounce {
    0%,100% { transform: translateY(0); }
    50%      { transform: translateY(-6px); }
}
</style>
</head>
<body>

{{-- MAP AREA --}}
<div id="map-container">
    {{-- Back button --}}
    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('customer.orders') }}" class="map-back">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
    </a>

    {{-- Status chip --}}
    <div class="map-status-chip">
        <span class="map-status-dot"></span>
        <span class="map-status-label">Kurir Menuju Lokasi</span>
    </div>

    {{-- Map --}}
    <div id="tracking-map"></div>

    {{-- Recenter --}}
    <div class="map-recenter" id="btn-recenter" title="Pusat ulang peta">
        📍
    </div>
</div>

{{-- SLIDE-UP PANEL --}}
<div class="panel" id="js-panel">
    <div class="panel-handle"></div>
    <div class="panel-inner">

        {{-- ETA Card --}}
        <div class="eta-card">
            <div class="eta-icon">🛵</div>
            <div class="eta-info">
                <div class="eta-label">Estimasi Tiba</div>
                <div class="eta-value">15 Mnt</div>
                <div class="eta-sub">Kurir sedang dalam perjalanan</div>
            </div>
            <div class="eta-badge">Aktif</div>
        </div>

        {{-- Driver Info --}}
        @php $driver = $order->driver ?? null; @endphp
        @if($driver)
        <div class="driver-card">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=0077b6&color=fff&size=128"
                 class="driver-avatar" alt="{{ $driver->name }}">
            <div class="driver-info">
                <div class="driver-name">{{ $driver->name }}</div>
                <div class="driver-meta">Kurir Azka Laundry</div>
                <span class="driver-rating">⭐ 4.9 • Terpercaya</span>
            </div>
            <div class="driver-actions">
                @if($driver->phone)
                <a href="https://wa.me/62{{ ltrim(preg_replace('/[^0-9]/','',$driver->phone),'0') }}?text=Halo%20Kurir%2C%20pesanan%20%23{{ $order->order_code }}"
                   target="_blank" class="da-btn wa">💬</a>
                <a href="tel:{{ $driver->phone }}" class="da-btn call">📞</a>
                @endif
            </div>
        </div>
        @else
        {{-- No driver assigned yet --}}
        <div class="driver-card" style="justify-content: center; flex-direction: column; text-align: center; gap: 8px;">
            <div style="font-size: 2rem;">⏳</div>
            <div style="font-weight: 800; color: var(--ink-mid);">Menunggu Penugasan Kurir</div>
            <div style="font-size: 0.78rem; color: var(--ink-lt); font-weight: 700;">Admin sedang memproses pesananmu</div>
        </div>
        @endif

        {{-- Activity Timeline --}}
        <div class="activity-card">
            <div class="activity-title">📋 Aktivitas Pesanan</div>

            @php
                $activities = [
                    [
                        'title'   => 'Kurir sedang dalam perjalanan ke lokasi',
                        'time'    => 'Sedang berlangsung',
                        'state'   => 'current',
                    ],
                    [
                        'title'   => 'Pesanan diterima & kurir ditugaskan',
                        'time'    => $order->updated_at?->translatedFormat('H:i') ?? '-',
                        'state'   => 'done',
                    ],
                    [
                        'title'   => 'Pesanan berhasil dibuat',
                        'time'    => $order->created_at?->translatedFormat('H:i, d M') ?? '-',
                        'state'   => 'done',
                    ],
                    [
                        'title'   => 'Cucian akan diproses',
                        'time'    => 'Menunggu',
                        'state'   => 'pending',
                    ],
                    [
                        'title'   => 'Diantar kembali ke rumah',
                        'time'    => 'Menunggu',
                        'state'   => 'pending',
                    ],
                ];
            @endphp

            @foreach($activities as $act)
            <div class="activity-item">
                <div class="activity-dot {{ $act['state'] }}"></div>
                <div class="activity-text">
                    <div class="at-title {{ $act['state'] === 'pending' ? 'muted' : '' }}">{{ $act['title'] }}</div>
                    <div class="at-time {{ $act['state'] === 'current' ? 'current' : '' }}">{{ $act['time'] }}</div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>

{{-- BOTTOM NAVBAR --}}
@include('layouts.component.customer._navbar_customer', ['active' => 'pesanan'])

@php
    $customerLat = -1.603137;
    $customerLng = 103.606789;
@endphp

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const customerLat = {{ $customerLat }};
        const customerLng = {{ $customerLng }};
        let driverLat = customerLat + 0.008;
        let driverLng = customerLng + 0.005;

        const map = L.map('tracking-map', {
            zoomControl: false,
            attributionControl: false,
        }).setView([customerLat, customerLng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
        }).addTo(map);

        const customerIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="marker-customer">🏠</div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
        });

        const driverIcon = L.divIcon({
            className: 'custom-marker',
            html: '<div class="marker-driver">🛵</div>',
            iconSize: [44, 44],
            iconAnchor: [22, 22],
        });

        L.marker([customerLat, customerLng], { icon: customerIcon })
            .addTo(map)
            .bindPopup('Lokasi Kamu');

        const driverMarker = L.marker([driverLat, driverLng], { icon: driverIcon })
            .addTo(map)
            .bindPopup('Kurir Azka Laundry');

        const routeLine = L.polyline([[driverLat, driverLng], [customerLat, customerLng]], {
            color: '#0077b6',
            weight: 3,
            opacity: 0.6,
            dashArray: '8, 6',
        }).addTo(map);

        map.fitBounds([[customerLat, customerLng], [driverLat, driverLng]], { padding: [40, 40] });

        document.getElementById('btn-recenter')?.addEventListener('click', function () {
            map.fitBounds(
                [[customerLat, customerLng], [driverMarker.getLatLng().lat, driverMarker.getLatLng().lng]],
                { padding: [40, 40] }
            );
        });

        let moveCount = 0;
        const maxMoves = 15;
        const moveInterval = setInterval(function () {
            if (moveCount >= maxMoves) {
                clearInterval(moveInterval);
                return;
            }

            driverLat += (customerLat - driverLat) * 0.08 + (Math.random() - 0.5) * 0.0005;
            driverLng += (customerLng - driverLng) * 0.08 + (Math.random() - 0.5) * 0.0005;

            driverMarker.setLatLng([driverLat, driverLng]);
            routeLine.setLatLngs([[driverLat, driverLng], [customerLat, customerLng]]);

            moveCount++;
        }, 3500);

        if (typeof gsap === 'undefined') return;

        const animate = (selector, options) => {
            const els = document.querySelectorAll(selector);
            if (els.length > 0) gsap.from(els, options);
        };

        animate('#js-panel', { y: 60, opacity: 0, duration: 0.6, ease: 'power2.out', delay: 0.3 });
        animate('.eta-card', { opacity: 0, y: 20, duration: 0.5, ease: 'power2.out', delay: 0.5 });
        animate('.driver-card', { opacity: 0, y: 20, duration: 0.5, ease: 'power2.out', delay: 0.6 });
        animate('.activity-card', { opacity: 0, y: 20, duration: 0.5, ease: 'power2.out', delay: 0.7 });
        animate('.map-status-chip', { opacity: 0, y: -10, duration: 0.4, ease: 'back.out(1.5)', delay: 0.2 });
    });
</script>

</body>
</html>