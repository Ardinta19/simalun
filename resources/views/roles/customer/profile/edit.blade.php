<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Edit Profil</title>
@include('layouts.component.customer._head_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root {
        --blue-dark:#002f5c; --blue-mid:#0077b6; --blue-light:#00b4d8;
        --blue-sky:#e0f4ff; --orange:#FF6B35; --green:#00C48C; --red:#ef4444;
        --ink:#1a2332; --ink-mid:#3d5066; --ink-lt:#8899aa;
        --surface:#f4f8fc; --border:#ddeeff; --radius:16px; --radius-sm:10px;
    }
    *,*::before,*::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
    body {
        font-family:'Plus Jakarta Sans', sans-serif;
        background:var(--surface);
        color:var(--ink);
        min-height:100vh;
        padding-bottom:calc(80px + env(safe-area-inset-bottom, 0px));
        overflow-x:hidden;
    }

    .page-header {
        background:linear-gradient(135deg, var(--blue-dark) 0%, var(--blue-mid) 100%);
        padding:max(env(safe-area-inset-top, 0px), 16px) 20px 24px;
        position:sticky;
        top:0;
        z-index:100;
    }
    .header-row {
        display:flex;
        align-items:center;
        gap:12px;
        max-width:520px;
        margin:0 auto;
    }
    .btn-back {
        width:38px; height:38px;
        border-radius:50%;
        background:rgba(255,255,255,.15);
        border:1.5px solid rgba(255,255,255,.25);
        display:flex; align-items:center; justify-content:center;
        color:#fff;
        text-decoration:none;
        flex-shrink:0;
    }
    .btn-back svg { width:18px; height:18px; }
    .header-title {
        flex:1;
        font-weight:800;
        font-size:1.3rem;
        color:#fff;
    }
    .header-sub {
        font-size:.68rem;
        font-weight:800;
        color:rgba(255,255,255,.65);
        margin-top:2px;
        letter-spacing:.5px;
    }

    .page-body { max-width:520px; margin:0 auto; padding:16px; }

    .alert {
        border-radius:var(--radius-sm);
        padding:12px 14px;
        font-size:.85rem;
        font-weight:700;
        margin-bottom:14px;
    }
    .alert-success {
        background:#e6fff6;
        color:#047857;
        border:1.5px solid rgba(0,196,140,.3);
    }
    .alert-error {
        background:#fff1f1;
        color:#b91c1c;
        border:1.5px solid #fecaca;
    }

    .section-card {
        background:#fff;
        border-radius:var(--radius);
        border:1.5px solid var(--border);
        margin-bottom:14px;
        overflow:hidden;
        box-shadow:0 2px 12px rgba(0,47,92,.05);
    }
    .section-head {
        display:flex;
        align-items:center;
        gap:10px;
        padding:14px 16px;
        border-bottom:1.5px solid var(--border);
        background:linear-gradient(90deg, rgba(0,119,182,.04) 0%, transparent 100%);
    }
    .section-num {
        width:28px; height:28px;
        border-radius:50%;
        background:var(--blue-mid);
        color:#fff;
        font-weight:800;
        font-size:.88rem;
        display:flex; align-items:center; justify-content:center;
        flex-shrink:0;
    }
    .section-title {
        font-weight:800;
        font-size:.98rem;
        color:var(--blue-dark);
    }
    .section-body { padding:16px; display:flex; flex-direction:column; gap:14px; }

    .avatar-wrap {
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:10px;
        margin-bottom:6px;
    }
    .avatar-preview {
        width:96px; height:96px;
        border-radius:50%;
        object-fit:cover;
        border:3px solid var(--blue-sky);
        background:var(--blue-sky);
    }
    .avatar-action {
        font-size:.78rem;
        font-weight:800;
        color:var(--blue-mid);
        cursor:pointer;
        background:var(--blue-sky);
        padding:.45rem 1rem;
        border-radius:99px;
    }
    .avatar-action input { display:none; }

    .field-label {
        font-size:.72rem;
        font-weight:900;
        color:var(--ink-mid);
        letter-spacing:.4px;
        text-transform:uppercase;
        margin-bottom:6px;
    }
    .field-label .req { color:var(--red); }
    .field-input {
        width:100%;
        padding:12px 14px;
        border:1.5px solid var(--border);
        border-radius:var(--radius-sm);
        font-family:'Plus Jakarta Sans', sans-serif;
        font-size:.93rem;
        font-weight:600;
        color:var(--ink);
        background:#fff;
        outline:none;
        transition:border-color .2s, box-shadow .2s;
    }
    .field-input:focus {
        border-color:var(--blue-mid);
        box-shadow:0 0 0 3px rgba(0,119,182,.12);
    }
    .field-input.is-error {
        border-color:var(--red);
        box-shadow:0 0 0 3px rgba(239,68,68,.1);
    }
    .field-input::placeholder { color:var(--ink-lt); font-weight:600; }
    textarea.field-input { resize:none; min-height:80px; line-height:1.5; }
    .field-error {
        font-size:.72rem;
        font-weight:800;
        color:var(--red);
        margin-top:5px;
    }
    .field-hint {
        font-size:.7rem;
        font-weight:700;
        color:var(--ink-lt);
        margin-top:4px;
    }

    .btn-submit {
        width:100%;
        padding:13px;
        background:linear-gradient(135deg, var(--orange) 0%, #ff8c5a 100%);
        color:#fff;
        font-weight:900;
        font-size:.95rem;
        border:none;
        border-radius:99px;
        cursor:pointer;
        box-shadow:0 6px 18px rgba(255,107,53,.35);
        transition:transform .15s;
    }
    .btn-submit.secondary {
        background:linear-gradient(135deg, var(--blue-mid) 0%, var(--blue-light) 100%);
        box-shadow:0 6px 18px rgba(0,119,182,.3);
    }
    .btn-submit:active { transform:scale(.97); }

    .btn-danger {
        width:100%;
        padding:11px;
        background:transparent;
        color:var(--red);
        border:1.5px solid var(--red);
        border-radius:99px;
        font-weight:800;
        font-size:.85rem;
        cursor:pointer;
        transition:all .2s;
    }
    .btn-danger:hover { background:var(--red); color:#fff; }
</style>
</head>
<body>

<header class="page-header">
    <div class="header-row">
        <a href="{{ route('customer.profile') }}" class="btn-back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
        </a>
        <div>
            <div class="header-title">Edit Profil</div>
            <div class="header-sub">Perbarui data pribadi & alamat utama</div>
        </div>
    </div>
</header>

<div class="page-body">

    @if(session('status') === 'profile-updated')
        <div class="alert alert-success">Profil berhasil diperbarui.</div>
    @elseif(session('status') === 'address-saved')
        <div class="alert alert-success">Alamat utama berhasil disimpan.</div>
    @elseif(session('status') === 'password-updated')
        <div class="alert alert-success">Password berhasil diubah.</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            Periksa kembali isian berikut:
            @foreach($errors->all() as $e) <br>• {{ $e }} @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="section-card">
            <div class="section-head">
                <div class="section-num">1</div>
                <div class="section-title">Data Pribadi</div>
            </div>
            <div class="section-body">
                <div class="avatar-wrap">
                    <img id="avatar-preview" class="avatar-preview"
                         src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=e0f4ff&color=0077b6&size=200' }}"
                         alt="Foto profil">
                    <label class="avatar-action">
                        Ubah Foto
                        <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" id="avatar-input">
                    </label>
                    <div class="field-hint">Maksimal 2 MB · format JPG, PNG, atau WebP</div>
                </div>

                @if($user->avatar)
                <div style="text-align:center; margin-top:4px;">
                    <button type="button" class="btn-danger" style="width:auto; padding:8px 18px; font-size:.78rem;" id="btn-delete-avatar">Hapus Foto Profil</button>
                </div>
                @endif

                <div>
                    <div class="field-label">Nama Lengkap <span class="req">*</span></div>
                    <input type="text" name="name"
                           class="field-input {{ $errors->has('name') ? 'is-error' : '' }}"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Email <span style="font-weight:600;color:var(--ink-lt)">(opsional)</span></div>
                    <input type="email" name="email"
                           class="field-input {{ $errors->has('email') ? 'is-error' : '' }}"
                           value="{{ old('email', $user->email) }}"
                           placeholder="Boleh dikosongkan">
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Nomor HP / WhatsApp <span class="req">*</span></div>
                    <input type="text" name="phone"
                           class="field-input"
                           value="{{ old('phone', $user->phone) }}"
                           placeholder="08xxxxxxxxxx" required>
                </div>

                <button type="submit" class="btn-submit">Simpan Data Pribadi</button>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('profile.primary-address') }}">
        @csrf

        <div class="section-card">
            <div class="section-head">
                <div class="section-num">2</div>
                <div class="section-title">{{ $primaryAddress ? 'Edit Alamat Utama' : 'Tambah Alamat Utama' }}</div>
            </div>
            <div class="section-body">

                <div>
                    <div class="field-label">Label <span class="req">*</span></div>
                    <input type="text" name="label"
                           class="field-input {{ $errors->has('label') ? 'is-error' : '' }}"
                           value="{{ old('label', $primaryAddress->label ?? 'Rumah') }}"
                           placeholder="Rumah, Kantor, Kos..."
                           required>
                </div>

                <div>
                    <div class="field-label">Nama Penerima <span class="req">*</span></div>
                    <input type="text" name="recipient_name"
                           class="field-input {{ $errors->has('recipient_name') ? 'is-error' : '' }}"
                           value="{{ old('recipient_name', $primaryAddress->recipient_name ?? $user->name) }}"
                           required>
                </div>

                <div>
                    <div class="field-label">No. HP Penerima</div>
                    <input type="text" name="phone"
                           class="field-input"
                           value="{{ old('phone', $primaryAddress->phone ?? $user->phone) }}"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div>
                    <div class="field-label">Alamat Lengkap <span class="req">*</span></div>
                    <textarea name="full_address"
                              class="field-input {{ $errors->has('full_address') ? 'is-error' : '' }}"
                              placeholder="Jalan, nomor rumah, RT/RW, kelurahan, kecamatan"
                              required>{{ old('full_address', $primaryAddress->full_address ?? '') }}</textarea>
                </div>

                <div>
                    <div class="field-label">Jarak dari Laundry (km)</div>
                    <input type="number" name="distance_km"
                           class="field-input"
                           step="0.1" min="0" max="50"
                           value="{{ old('distance_km', $primaryAddress->distance_km ?? '') }}"
                           placeholder="Contoh: 3.5">
                    <div class="field-hint">Zona ditentukan otomatis: 0–3 km (A), 3–7 km (B), &gt;7 km (C)</div>
                </div>

                <div>
                    <div class="field-label">Catatan (opsional)</div>
                    <input type="text" name="notes"
                           class="field-input"
                           value="{{ old('notes', $primaryAddress->notes ?? '') }}"
                           placeholder="Patokan, warna pagar, lantai">
                </div>

                <button type="submit" class="btn-submit secondary">
                    {{ $primaryAddress ? 'Simpan Perubahan Alamat' : 'Simpan Alamat Utama' }}
                </button>
            </div>
        </div>
    </form>

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="section-card">
            <div class="section-head">
                <div class="section-num">3</div>
                <div class="section-title">Ganti Password</div>
            </div>
            <div class="section-body">
                <div>
                    <div class="field-label">Password Saat Ini</div>
                    <input type="password" name="current_password" class="field-input" autocomplete="current-password" placeholder="••••••••">
                    @error('current_password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Password Baru</div>
                    <input type="password" name="password" class="field-input" autocomplete="new-password" placeholder="Minimal 8 karakter">
                    @error('password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div>
                    <div class="field-label">Konfirmasi Password Baru</div>
                    <input type="password" name="password_confirmation" class="field-input" autocomplete="new-password" placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn-submit secondary">Perbarui Password</button>
            </div>
        </div>
    </form>

    @if($user->role === 'customer')
        <div class="section-card">
            <div class="section-head" style="background:#fff5f5;">
                <div class="section-num" style="background:var(--red);">!</div>
                <div class="section-title" style="color:var(--red);">Hapus Akun</div>
            </div>
            <div class="section-body">
                <div class="field-hint" style="margin:0;">
                    Akun yang dihapus tidak bisa dipulihkan. Semua data pesanan akan ikut terhapus.
                </div>
                <form method="POST" action="{{ route('profile.destroy') }}" id="form-delete-account">
                    @csrf
                    @method('delete')
                    <div>
                        <div class="field-label">Konfirmasi Password <span class="req">*</span></div>
                        <input type="password" name="password" class="field-input" required placeholder="Masukkan password Anda">
                        @error('password') <div class="field-error">{{ $message }}</div> @enderror
                    </div>
                    <button type="button" class="btn-danger" style="margin-top:12px;" id="btn-delete-account">Hapus Akun Permanen</button>
                </form>
            </div>
        </div>
    @endif

</div>

@include('layouts.component.customer._confirm_modal')

<form method="POST" action="{{ route('profile.avatar.delete') }}" id="form-delete-avatar" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
(function() {
    var avatarInput = document.getElementById('avatar-input');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showConfirmModal({
                    title: 'File Terlalu Besar',
                    message: 'Ukuran foto maksimal 2 MB. Silakan pilih foto yang lebih kecil.',
                    confirmText: 'Mengerti',
                    cancelText: 'Tutup',
                    type: 'warning',
                    onConfirm: function() {}
                });
                event.target.value = '';
                return;
            }

            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    var btnDeleteAvatar = document.getElementById('btn-delete-avatar');
    if (btnDeleteAvatar) {
        btnDeleteAvatar.addEventListener('click', function() {
            showConfirmModal({
                title: 'Hapus Foto Profil?',
                message: 'Foto profil kamu akan dihapus dan diganti dengan avatar default.',
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal',
                type: 'warning',
                onConfirm: function() {
                    document.getElementById('form-delete-avatar').submit();
                }
            });
        });
    }

    var btnDelete = document.getElementById('btn-delete-account');
    if (btnDelete) {
        btnDelete.addEventListener('click', function() {
            var passwordInput = document.querySelector('#form-delete-account input[name="password"]');
            if (!passwordInput || !passwordInput.value) {
                passwordInput.focus();
                return;
            }
            showConfirmModal({
                title: 'Hapus Akun Permanen?',
                message: 'Semua data kamu termasuk pesanan dan alamat akan dihapus. Tindakan ini tidak bisa dibatalkan.',
                confirmText: 'Ya, Hapus Akun',
                cancelText: 'Batal',
                type: 'danger',
                onConfirm: function() {
                    document.getElementById('form-delete-account').submit();
                }
            });
        });
    }
})();
</script>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

</body>
</html>
