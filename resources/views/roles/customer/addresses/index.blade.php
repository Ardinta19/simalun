<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Alamat Tersimpan — Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
  max-width: 520px; margin: 0 auto;
  display: grid; grid-template-columns: 36px 1fr auto;
  gap: 12px; align-items: center;
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
.btn-add {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: var(--brand-primary);
  color: #fff;
  text-decoration: none;
  padding: 8px 14px;
  border-radius: 99px;
  font-size: 0.78rem;
  font-weight: 600;
  transition: background .15s;
}
.btn-add:hover { background: var(--brand-deep); }
.btn-add svg { width: 14px; height: 14px; }

.page {
  max-width: 520px; margin: 0 auto;
  padding: 20px 16px;
}

.alert {
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 0.84rem;
  font-weight: 600;
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}
.alert svg { width: 16px; height: 16px; flex-shrink: 0; }
.alert--ok { background: rgba(22,163,74,.08); border: 1px solid rgba(22,163,74,.25); color: var(--ok); }
.alert--err { background: rgba(220,38,38,.06); border: 1px solid rgba(220,38,38,.25); color: var(--danger); }

/* Address card */
.addr {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 10px;
  position: relative;
}
.addr.is-primary { border-color: var(--brand-primary); }
.addr.is-primary::before {
  content: '';
  position: absolute;
  top: -1px; left: 16px; right: 16px;
  height: 3px;
  background: var(--brand-primary);
  border-radius: 0 0 3px 3px;
}
.addr__head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 8px;
}
.addr__label {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--ink);
  letter-spacing: -0.01em;
  display: flex;
  align-items: center;
  gap: 8px;
}
.addr__label svg { width: 16px; height: 16px; color: var(--brand-primary); }
.badge-primary {
  font-size: 0.65rem;
  font-weight: 700;
  background: rgba(0,119,182,.1);
  color: var(--brand-primary);
  padding: 2px 8px;
  border-radius: 99px;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}
.addr__recipient {
  font-size: 0.8rem;
  color: var(--ink-3);
  margin-bottom: 6px;
}
.addr__text {
  font-size: 0.86rem;
  color: var(--ink-2);
  line-height: 1.5;
}
.addr__zone {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.72rem;
  font-weight: 600;
  color: var(--ink-3);
  background: var(--line-2);
  padding: 4px 10px;
  border-radius: 99px;
  margin-top: 10px;
}
.addr__zone strong { color: var(--brand-primary); }

/* Actions */
.addr__actions {
  display: flex;
  align-items: center;
  gap: 6px;
  margin-top: 12px;
  padding-top: 12px;
  border-top: 1px solid var(--line-2);
}
.addr__btn {
  background: var(--card);
  border: 1px solid var(--line);
  color: var(--ink-2);
  padding: 7px 14px;
  border-radius: 99px;
  font-size: 0.76rem;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  font-family: inherit;
  transition: all .12s;
}
.addr__btn:hover { background: var(--line-2); }
.addr__btn--primary { color: var(--brand-primary); border-color: rgba(0,119,182,.3); }
.addr__btn--primary:hover { background: rgba(0,119,182,.06); }
.addr__btn--danger { color: var(--danger); border-color: rgba(220,38,38,.25); }
.addr__btn--danger:hover { background: rgba(220,38,38,.04); }

/* Empty */
.empty {
  background: var(--card);
  border: 1px dashed var(--line);
  border-radius: 14px;
  padding: 56px 24px;
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
}
.empty__sub {
  font-size: 0.84rem;
  color: var(--ink-3);
  line-height: 1.5;
  margin-bottom: 18px;
  max-width: 28ch;
  margin-left: auto;
  margin-right: auto;
}
.btn-cta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: var(--brand-primary);
  color: #fff;
  padding: 11px 20px;
  border-radius: 99px;
  font-size: 0.86rem;
  font-weight: 600;
  text-decoration: none;
}
.btn-cta:hover { background: var(--brand-deep); }
.btn-cta svg { width: 16px; height: 16px; }
</style>
</head>
<body>

<div class="app-bar">
  <div class="app-bar__row">
    <a href="{{ route('customer.profile') }}" class="icon-btn" aria-label="Kembali">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div class="app-bar__title">Alamat Tersimpan</div>
    <a href="{{ route('customer.addresses.create') }}" class="btn-add">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Tambah
    </a>
  </div>
</div>

<main class="page">

  @if(session('success'))
    <div class="alert alert--ok">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert--err">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      {{ session('error') }}
    </div>
  @endif

  @if(isset($alamat) && $alamat->count() > 0)
    @foreach($alamat as $addr)
      <div class="addr {{ $addr->is_primary ? 'is-primary' : '' }}" data-reveal>
        <div class="addr__head">
          <div class="addr__label">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            {{ $addr->label }}
          </div>
          @if($addr->is_primary)
            <span class="badge-primary">Utama</span>
          @endif
        </div>
        <div class="addr__recipient">
          {{ $addr->recipient_name }}{{ $addr->phone ? ' · '.$addr->phone : '' }}
        </div>
        <div class="addr__text">{{ $addr->full_address }}</div>
        @if($addr->zone)
          <div class="addr__zone">
            Zona <strong>{{ $addr->zone }}</strong>
            @if($addr->distance_km) · {{ $addr->distance_km }} km @endif
          </div>
        @endif

        <div class="addr__actions">
          <a href="{{ route('customer.addresses.edit', $addr) }}" class="addr__btn">Ubah</a>
          @if(!$addr->is_primary)
            <form method="POST" action="{{ route('customer.addresses.set-primary', $addr) }}" style="display:inline;">
              @csrf @method('PATCH')
              <button type="submit" class="addr__btn addr__btn--primary">Jadikan utama</button>
            </form>
          @endif
          <form method="POST" action="{{ route('customer.addresses.destroy', $addr) }}" style="display:inline;margin-left:auto;"
                onsubmit="return confirm('Hapus alamat ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="addr__btn addr__btn--danger">Hapus</button>
          </form>
        </div>
      </div>
    @endforeach
  @else
    <div class="empty">
      <div class="empty__icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>
        </svg>
      </div>
      <div class="empty__title">Belum ada alamat tersimpan</div>
      <div class="empty__sub">Tambahkan alamat agar proses jemput-antar lebih cepat tanpa perlu mengetik ulang.</div>
      <a href="{{ route('customer.addresses.create') }}" class="btn-cta">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Alamat
      </a>
    </div>
  @endif

</main>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', () => {
  if (typeof gsap === 'undefined') return;
  gsap.from('[data-reveal]', {
    y: 10,
    opacity: 0,
    duration: 0.4,
    ease: 'power2.out',
    stagger: 0.06
  });
});
</script>
</body>
</html>
