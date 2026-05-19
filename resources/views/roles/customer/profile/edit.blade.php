<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Edit Profil — Azka Laundry</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<style>
:root {
  --brand-deep:    #002f5c;
  --brand-primary: #0077b6;
  --brand-light:   #00b4d8;
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
  --warn:          #d97706;
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

/* App bar */
.app-bar {
  background: var(--card);
  border-bottom: 1px solid var(--line);
  padding: max(env(safe-area-inset-top, 0), 14px) 20px 14px;
  position: sticky; top: 0; z-index: 50;
}
.app-bar__row {
  max-width: 680px; margin: 0 auto;
  display: grid; grid-template-columns: 36px 1fr 36px;
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
  transition: background .12s;
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

/* Page */
.page {
  max-width: 680px;
  margin: 0 auto;
  padding: 24px 20px;
}

/* Alerts */
.alert {
  padding: 12px 16px;
  border-radius: 10px;
  font-size: 0.84rem;
  font-weight: 600;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.alert svg { width: 18px; height: 18px; flex-shrink: 0; }
.alert--ok { background: rgba(22,163,74,.08); border: 1px solid rgba(22,163,74,.25); color: var(--ok); }
.alert--err { background: rgba(220,38,38,.06); border: 1px solid rgba(220,38,38,.2); color: var(--danger); }

/* Avatar section */
.avatar-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 28px;
}
.avatar-wrap {
  position: relative;
  width: 96px; height: 96px;
  margin-bottom: 12px;
}
.avatar-img {
  width: 96px; height: 96px;
  border-radius: 50%;
  object-fit: cover;
  background: var(--line-2);
  border: 3px solid var(--line);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
  font-weight: 700;
  color: var(--brand-primary);
}
.avatar-img img {
  width: 100%; height: 100%;
  border-radius: 50%;
  object-fit: cover;
}
.avatar-btn {
  position: absolute;
  bottom: 2px; right: 2px;
  width: 30px; height: 30px;
  border-radius: 50%;
  background: var(--brand-primary);
  border: 2.5px solid var(--card);
  display: grid; place-items: center;
  cursor: pointer;
  color: #fff;
  transition: transform .12s;
}
.avatar-btn:hover { transform: scale(1.1); }
.avatar-btn svg { width: 14px; height: 14px; }
.avatar-hint {
  font-size: 0.76rem;
  color: var(--ink-mute);
  text-align: center;
}

/* Form sections */
.form-section {
  background: var(--card);
  border: 1px solid var(--line);
  border-radius: 14px;
  padding: 20px;
  margin-bottom: 16px;
}
.form-section__title {
  font-size: 0.92rem;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 4px;
  letter-spacing: -0.01em;
}
.form-section__sub {
  font-size: 0.78rem;
  color: var(--ink-3);
  margin-bottom: 18px;
}

/* Form fields */
.field {
  margin-bottom: 16px;
}
.field:last-child { margin-bottom: 0; }
.field__label {
  display: block;
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--ink-2);
  margin-bottom: 6px;
  letter-spacing: 0.02em;
}
.field__input {
  width: 100%;
  padding: 12px 14px;
  background: var(--bg);
  border: 1px solid var(--line);
  border-radius: 10px;
  font-family: inherit;
  font-size: 0.9rem;
  font-weight: 500;
  color: var(--ink);
  outline: none;
  transition: border-color .2s, box-shadow .2s;
}
.field__input:focus {
  border-color: var(--brand-primary);
  box-shadow: 0 0 0 3px rgba(0,119,182,.1);
  background: var(--card);
}
.field__input::placeholder {
  color: var(--ink-mute);
  font-weight: 400;
}
.field__error {
  font-size: 0.76rem;
  color: var(--danger);
  font-weight: 600;
  margin-top: 4px;
}

/* Buttons */
.btn {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  width: 100%;
  padding: 13px 20px;
  border: none;
  border-radius: 10px;
  font-family: inherit;
  font-size: 0.9rem;
  font-weight: 700;
  cursor: pointer;
  transition: background .15s, transform .1s;
  text-decoration: none;
}
.btn:active { transform: scale(0.98); }
.btn--primary {
  background: var(--brand-primary);
  color: #fff;
}
.btn--primary:hover { background: var(--brand-deep); }
.btn--outline {
  background: var(--card);
  color: var(--ink-2);
  border: 1px solid var(--line);
}
.btn--outline:hover { background: var(--line-2); }
.btn--danger {
  background: none;
  color: var(--danger);
  border: 1px solid rgba(220,38,38,.25);
  margin-top: 16px;
}
.btn--danger:hover { background: rgba(220,38,38,.04); }
.btn svg { width: 16px; height: 16px; }

/* Separator */
.divider {
  height: 1px;
  background: var(--line);
  margin: 20px 0;
}
</style>
</head>
<body>

<div class="app-bar">
  <div class="app-bar__row">
    <a href="{{ route('customer.profile') }}" class="icon-btn" aria-label="Kembali ke profil">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </a>
    <div class="app-bar__title">Edit Profil</div>
    <span></span>
  </div>
</div>

<main class="page">

  @if(session('status') === 'profile-updated')
  <div class="alert alert--ok" data-reveal>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Profil berhasil diperbarui
  </div>
  @endif

  @if(session('status') === 'password-updated')
  <div class="alert alert--ok" data-reveal>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    Password berhasil diperbarui
  </div>
  @endif

  @if($errors->any())
  <div class="alert alert--err" data-reveal>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ $errors->first() }}
  </div>
  @endif

  {{-- Profile Info Form --}}
  <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" data-reveal>
    @csrf
    @method('PATCH')

    {{-- Avatar --}}
    <div class="avatar-section">
      <div class="avatar-wrap">
        <div class="avatar-img">
          @if($user->avatar)
            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" id="avatar-preview">
          @else
            <span id="avatar-initial">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            <img src="" alt="" id="avatar-preview" style="display:none; width:100%; height:100%; border-radius:50%; object-fit:cover;">
          @endif
        </div>
        <label for="avatar-file" class="avatar-btn" title="Ubah foto profil">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </label>
        <input type="file" id="avatar-file" name="avatar" accept="image/jpeg,image/png,image/webp" style="display:none">
      </div>
      <div class="avatar-hint">Ketuk ikon kamera untuk mengganti foto</div>
    </div>

    <div class="form-section">
      <div class="form-section__title">Informasi Pribadi</div>
      <div class="form-section__sub">Nama dan kontak yang digunakan untuk pesanan</div>

      <div class="field">
        <label class="field__label" for="name">Nama Lengkap</label>
        <input type="text" id="name" name="name" class="field__input" value="{{ old('name', $user->name) }}" required autocomplete="name">
        @error('name') <div class="field__error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="field__label" for="email">Alamat Email</label>
        <input type="email" id="email" name="email" class="field__input" value="{{ old('email', $user->email) }}" required autocomplete="email">
        @error('email') <div class="field__error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="field__label" for="phone">Nomor WhatsApp</label>
        <input type="tel" id="phone" name="phone" class="field__input" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" autocomplete="tel">
        @error('phone') <div class="field__error">{{ $message }}</div> @enderror
      </div>
    </div>

    <button type="submit" class="btn btn--primary">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      Simpan Perubahan
    </button>
  </form>

  <div class="divider"></div>

  {{-- Password Form --}}
  <form method="POST" action="{{ route('password.update') }}" data-reveal>
    @csrf
    @method('PUT')

    <div class="form-section">
      <div class="form-section__title">Ubah Password</div>
      <div class="form-section__sub">Gunakan kombinasi minimal 8 karakter</div>

      <div class="field">
        <label class="field__label" for="current_password">Password Saat Ini</label>
        <input type="password" id="current_password" name="current_password" class="field__input" placeholder="Masukkan password lama" autocomplete="current-password">
        @error('current_password', 'updatePassword') <div class="field__error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="field__label" for="password">Password Baru</label>
        <input type="password" id="password" name="password" class="field__input" placeholder="Minimal 8 karakter" autocomplete="new-password">
        @error('password', 'updatePassword') <div class="field__error">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <label class="field__label" for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="field__input" placeholder="Ketik ulang password baru" autocomplete="new-password">
      </div>
    </div>

    <button type="submit" class="btn btn--outline">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Perbarui Password
    </button>
  </form>

  @if($user->role !== 'admin')
  <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan.')">
    @csrf
    @method('DELETE')
    <input type="hidden" name="password" value="">
    <button type="submit" class="btn btn--danger">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
      Hapus Akun Permanen
    </button>
  </form>
  @endif

</main>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Avatar preview
  const fileInput = document.getElementById('avatar-file');
  const preview = document.getElementById('avatar-preview');
  const initial = document.getElementById('avatar-initial');

  if (fileInput) {
    fileInput.addEventListener('change', function () {
      if (this.files && this.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
          if (initial) initial.style.display = 'none';
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }

  // GSAP entrance
  if (typeof gsap !== 'undefined') {
    gsap.from('[data-reveal]', {
      y: 12,
      opacity: 0,
      duration: 0.4,
      ease: 'power2.out',
      stagger: 0.06
    });
  }
});
</script>
</body>
</html>
