<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<title>Notifikasi Kurir – Azka Laundry</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--blue-sky:#e0f4ff;--orange:#FF6B35;--green:#00C48C;--surface:#f4f8fc;--card:#ffffff;--ink:#1a2332;--ink-mid:#3d5066;--ink-lt:#8899aa;--border:#ddeeff;--radius:20px;--radius-sm:12px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Plus Jakarta Sans',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;padding-bottom:calc(80px + env(safe-area-inset-bottom,0px));overflow-x:hidden;}

.page-header{background:linear-gradient(135deg,var(--blue-dark) 0%,var(--blue-mid) 100%);padding:max(env(safe-area-inset-top,0px),16px) 20px 24px;position:sticky;top:0;z-index:100;}
.header-row{display:flex;align-items:center;gap:12px;max-width:520px;margin:0 auto;}
.btn-back{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:white;text-decoration:none;flex-shrink:0;}
.btn-back svg{width:18px;height:18px;}
.header-title{flex:1;font-weight:800;font-size:1.3rem;color:white;}

.page-body{max-width:520px;margin:0 auto;padding:16px;}

.date-label{font-size:.7rem;font-weight:900;color:var(--ink-lt);text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px;display:flex;align-items:center;gap:8px;}
.date-label::after{content:'';flex:1;height:1px;background:var(--border);}
.date-group{margin-bottom:20px;}

.notif-card{background:white;border-radius:var(--radius-sm);border:1.5px solid var(--border);padding:14px;margin-bottom:8px;display:flex;align-items:flex-start;gap:12px;text-decoration:none;color:inherit;transition:background .15s;position:relative;overflow:hidden;}
.notif-card:active{background:#f8fafc;}
.notif-card.unread{background:linear-gradient(90deg,rgba(0,119,182,.04) 0%,white 100%);border-color:rgba(0,119,182,.25);}
.notif-card.unread::before{content:'';position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--blue-mid);border-radius:99px 0 0 99px;}
.notif-icon{width:42px;height:42px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.notif-body{flex:1;min-width:0;}
.notif-title{font-size:.85rem;font-weight:900;color:var(--ink);line-height:1.3;margin-bottom:3px;}
.notif-card.unread .notif-title{color:var(--blue-dark);}
.notif-msg{font-size:.75rem;font-weight:700;color:var(--ink-lt);line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.notif-time{font-size:.65rem;font-weight:800;color:var(--ink-lt);margin-top:5px;}
.notif-card.unread .notif-time{color:var(--blue-mid);}
.unread-dot{width:8px;height:8px;border-radius:50%;background:var(--orange);flex-shrink:0;margin-top:4px;}

.empty-state{text-align:center;padding:60px 20px;}
.empty-icon{font-size:3rem;margin-bottom:14px;display:block;opacity:.5;}
.empty-title{font-weight:800;font-size:1.1rem;color:var(--ink-mid);margin-bottom:6px;}
.empty-sub{font-size:.8rem;font-weight:700;color:var(--ink-lt);line-height:1.5;}


</style>
</head>
<body>

<header class="page-header">
    <div class="header-row">
        <a href="{{ route('driver.dashboard') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div class="header-title">Notifikasi</div>
    </div>
</header>

<div class="page-body">

    @php
    $notifIcons = [
        'order_created'     => ['icon' => '🧺', 'bg' => '#e0f4ff'],
        'pickup_assigned'   => ['icon' => '🛵', 'bg' => '#fff3ee'],
        'washing_started'   => ['icon' => '🫧', 'bg' => '#e0f4ff'],
        'ready_to_deliver'  => ['icon' => '✅', 'bg' => '#e6fff6'],
        'delivered'         => ['icon' => '🎉', 'bg' => '#e6fff6'],
        'payment_confirmed' => ['icon' => '💰', 'bg' => '#fffbeb'],
    ];
    @endphp

    @if(isset($notifications) && $notifications->count() > 0)
        @php
            $grouped = $notifications->groupBy(function($n) {
                $d = $n->created_at;
                if ($d->isToday())     return 'Hari Ini';
                if ($d->isYesterday()) return 'Kemarin';
                return $d->translatedFormat('d F Y');
            });
        @endphp

        @foreach($grouped as $dateLabel => $group)
        <div class="date-group">
            <div class="date-label">{{ $dateLabel }}</div>
            @foreach($group as $notif)
            @php
                $type    = $notif->data['type'] ?? 'system';
                $icon    = $notifIcons[$type] ?? ['icon' => '🔔', 'bg' => '#f8fafc'];
                $orderId = $notif->data['order_id'] ?? null;
                $href    = $orderId ? route('driver.orders.show', ['order' => $orderId, 'back' => route('driver.notifications')]) : '#';
                $isUnread = is_null($notif->read_at);
            @endphp
            <a href="{{ $href }}" class="notif-card {{ $isUnread ? 'unread' : '' }}">
                <div class="notif-icon" style="background:{{ $icon['bg'] }}">{{ $icon['icon'] }}</div>
                <div class="notif-body">
                    <div class="notif-title">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                    <div class="notif-msg">{{ $notif->data['message'] ?? '-' }}</div>
                    <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @if($isUnread)<div class="unread-dot"></div>@endif
            </a>
            @endforeach
        </div>
        @endforeach

        <div style="padding:8px 0;display:flex;justify-content:center;">
            {{ $notifications->links() }}
        </div>
    @else
    <div class="empty-state">
        <span class="empty-icon">🔔</span>
        <div class="empty-title">Belum Ada Notifikasi</div>
        <div class="empty-sub">Info penugasan dan update pesanan akan muncul di sini.</div>
    </div>
    @endif

</div>

@include('layouts.component.driver._navbar_driver', ['active' => 'tugas'])

<script>
document.addEventListener('DOMContentLoaded', function() {
    gsap.from('.page-header', { opacity: 0, y: -16, duration: 0.4, ease: 'power2.out' });
    gsap.from('.date-group', { opacity: 0, y: 16, duration: 0.4, stagger: 0.1, ease: 'power2.out', delay: 0.1 });
    gsap.from('.notif-card', { opacity: 0, x: -20, duration: 0.4, stagger: 0.06, ease: 'power2.out', delay: 0.15 });
});
</script>
</body>
</html>