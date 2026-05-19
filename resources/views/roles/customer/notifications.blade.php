<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Notifikasi — Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
  --danger:        #dc2626;
}
* { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  background: var(--bg);
  color: var(--ink);
  min-height: 100vh;
  padding-bottom: calc(76px + env(safe-area-inset-bottom, 0));
  -webkit-font-smoothing: antialiased;
}

.app-bar {
  background: var(--card);
  border-bottom: 1px solid var(--line);
  padding: max(env(safe-area-inset-top, 0), 14px) 20px 14px;
  position: sticky; top: 0; z-index: 50;
}
.app-bar__row {
  max-width: 680px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 36px 1fr 36px;
  gap: 12px;
  align-items: center;
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
}
.icon-btn:hover { background: var(--line-2); }
.icon-btn svg { width: 18px; height: 18px; }
.app-bar__title {
  text-align: center;
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.01em;
}

.page {
  max-width: 680px;
  margin: 0 auto;
  padding: 20px 16px;
}

.section-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 4px;
  margin-bottom: 12px;
}
.section-head__title {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--ink-mute);
  letter-spacing: 0.12em;
  text-transform: uppercase;
}
.section-head__action {
  font-size: 0.78rem;
  font-weight: 600;
  color: var(--brand-primary);
  text-decoration: none;
  cursor: pointer;
  background: none;
  border: 0;
  font-family: inherit;
}
.section-head__action:hover { text-decoration: underline; }

/* Notification list */
.list { list-style: none; }
.list-item {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 14px;
  display: grid;
  grid-template-columns: 38px 1fr;
  gap: 14px;
  text-decoration: none;
  color: inherit;
  position: relative;
  margin-bottom: 8px;
  transition: border-color .12s, transform .12s;
}
.list-item:active { transform: translateY(1px); }
.list-item:hover { border-color: var(--ink-mute); }
.list-item.is-unread {
  background: linear-gradient(0deg, rgba(0,119,182,.04), rgba(0,119,182,.04)), var(--card);
  border-color: rgba(0,119,182,.2);
}
.list-item.is-unread::before {
  content: '';
  position: absolute;
  top: 18px; right: 14px;
  width: 8px; height: 8px;
  border-radius: 50%;
  background: var(--brand-accent);
}
.li-icon {
  width: 38px; height: 38px;
  border-radius: 50%;
  background: var(--line-2);
  display: grid; place-items: center;
  color: var(--brand-primary);
  flex-shrink: 0;
}
.li-icon svg { width: 18px; height: 18px; }
.li-icon.success { background: rgba(22,163,74,.1); color: var(--ok); }
.li-icon.warn    { background: rgba(217,119,6,.1); color: var(--warn); }
.li-icon.danger  { background: rgba(220,38,38,.08); color: var(--danger); }
.li-body { min-width: 0; }
.li-title {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 2px;
  letter-spacing: -0.005em;
  padding-right: 16px;
}
.li-msg {
  font-size: 0.82rem;
  color: var(--ink-3);
  line-height: 1.5;
  margin-bottom: 6px;
}
.li-meta {
  font-size: 0.72rem;
  font-weight: 500;
  color: var(--ink-mute);
  display: flex;
  align-items: center;
  gap: 8px;
}
.li-meta__sep { width: 3px; height: 3px; border-radius: 50%; background: var(--line); }

/* Date group separator */
.day-divider {
  font-size: 0.72rem;
  font-weight: 700;
  color: var(--ink-mute);
  letter-spacing: 0.05em;
  text-transform: uppercase;
  padding: 14px 4px 6px;
}

/* Empty state */
.empty {
  background: var(--card);
  border: 1px dashed var(--line);
  border-radius: 14px;
  padding: 48px 24px;
  text-align: center;
}
.empty__icon {
  width: 56px; height: 56px;
  margin: 0 auto 14px;
  border-radius: 50%;
  background: var(--line-2);
  display: grid; place-items: center;
  color: var(--ink-mute);
}
.empty__icon svg { width: 24px; height: 24px; }
.empty__title {
  font-size: 1rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 4px;
  letter-spacing: -0.01em;
}
.empty__sub {
  font-size: 0.84rem;
  color: var(--ink-3);
  line-height: 1.5;
  max-width: 28ch;
  margin: 0 auto;
}

/* Pagination wrapper inherits Laravel's default */
.pagination-wrap { padding: 16px 0 4px; }
</style>
</head>
<body>

<div class="app-bar">
  <div class="app-bar__row">
    <a href="{{ route('customer.dashboard') }}" class="icon-btn" aria-label="Kembali">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div class="app-bar__title">Notifikasi</div>
    <span></span>
  </div>
</div>

<main class="page">

  @if($notifications->count() > 0)
    @php $unreadCount = $notifications->whereNull('read_at')->count(); @endphp

    <div class="section-head">
      <div class="section-head__title">
        @if($unreadCount > 0)
          {{ $unreadCount }} Belum Dibaca
        @else
          Semua Notifikasi
        @endif
      </div>
      @if($unreadCount > 0)
        <form method="POST" action="{{ route('customer.notifications.read-all') }}">
          @csrf
          <button type="submit" class="section-head__action">Tandai semua dibaca</button>
        </form>
      @endif
    </div>

    <ul class="list">
      @foreach($notifications as $notif)
        @php
          $status = $notif->data['status'] ?? '';
          $iconClass = 'li-icon';
          $iconSvg   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>';

          if (in_array($status, ['picked_up', 'dijemput'])) {
            $iconClass .= ' warn';
            $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="17" r="2"/><circle cx="17" cy="17" r="2"/><path d="M2 17h2l1.4-7h8.6l1.5 5H22"/></svg>';
          } elseif (in_array($status, ['washing', 'dicuci', 'disetrika'])) {
            $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="12" cy="13" r="5"/><circle cx="7" cy="7" r="1"/></svg>';
          } elseif (in_array($status, ['ready', 'siap'])) {
            $iconClass .= ' success';
            $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>';
          } elseif (in_array($status, ['done', 'selesai', 'completed'])) {
            $iconClass .= ' success';
            $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 9l-5.5 6L8 12.5"/></svg>';
          } elseif (in_array($status, ['cancelled', 'dibatalkan'])) {
            $iconClass .= ' danger';
            $iconSvg = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>';
          }

          $href = route('customer.notifications.click', $notif->id);
        @endphp

        <a href="{{ $href }}" class="list-item {{ is_null($notif->read_at) ? 'is-unread' : '' }}">
          <div class="{{ $iconClass }}">{!! $iconSvg !!}</div>
          <div class="li-body">
            <div class="li-title">{{ $notif->data['title'] ?? 'Pemberitahuan' }}</div>
            <div class="li-msg">{{ $notif->data['message'] ?? '' }}</div>
            <div class="li-meta">
              <span>{{ $notif->created_at->translatedFormat('D, j M Y') }}</span>
              <span class="li-meta__sep"></span>
              <span>{{ $notif->created_at->diffForHumans() }}</span>
            </div>
          </div>
        </a>
      @endforeach
    </ul>

    <div class="pagination-wrap">
      {{ $notifications->links() }}
    </div>

  @else
    <div class="empty">
      <div class="empty__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
          <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>
      </div>
      <div class="empty__title">Belum ada notifikasi</div>
      <div class="empty__sub">Pemberitahuan tentang pesanan, status cucian, dan promo akan muncul di sini.</div>
    </div>
  @endif

</main>

@include('layouts.component.customer._navbar_customer', ['active' => 'notif'])

<script>
document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined') return;
  gsap.from('.list-item, .empty', {
    y: 10,
    opacity: 0,
    duration: 0.4,
    ease: 'power2.out',
    stagger: 0.04
  });
});
</script>
</body>
</html>
