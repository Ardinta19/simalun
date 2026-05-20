<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Profil Saya</title>
@include('layouts.component.customer._head_meta')
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root {
        --blue-dark:#002f5c; --blue-mid:#0077b6; --blue-light:#00b4d8;
        --blue-sky:#e0f4ff; --orange:#FF6B35; --green:#00C48C;
        --ink:#1a2332; --ink-mid:#3d5066; --ink-lt:#8899aa;
        --surface:#f4f8fc; --border:#ddeeff; --radius:16px; --nav-h:72px;
    }
    *,*::before,*::after { margin:0; padding:0; box-sizing:border-box; -webkit-tap-highlight-color:transparent; }
    body {
        font-family:'Nunito', sans-serif;
        background:var(--surface);
        color:var(--ink);
        min-height:100vh;
        padding-bottom:80px;
    }

    .top-header {
        background:linear-gradient(145deg, var(--blue-dark) 0%, var(--blue-mid) 60%, var(--blue-light) 100%);
        position:relative;
        overflow:hidden;
    }
    .top-header::before {
        content:'';
        position:absolute;
        width:220px; height:220px;
        border-radius:50%;
        background:rgba(255,255,255,.06);
        top:-80px; right:-60px;
    }
    .header-inner {
        position:relative;
        z-index:1;
        padding:max(env(safe-area-inset-top, 0px), 20px) 20px 20px;
        max-width:520px;
        margin:0 auto;
    }
    .header-top { display:flex; align-items:center; gap:12px; }
    .back-btn {
        width:38px; height:38px;
        border-radius:50%;
        background:rgba(255,255,255,.15);
        border:1.5px solid rgba(255,255,255,.25);
        display:flex; align-items:center; justify-content:center;
        color:#fff;
        text-decoration:none;
        font-size:1.1rem;
        flex-shrink:0;
    }
    .header-title {
        font-family:'Fredoka One', cursive;
        font-size:1.2rem;
        color:#fff;
        flex:1;
    }
    .header-wave { display:block; width:100%; margin-bottom:-2px; }

    .page-body { max-width:520px; margin:0 auto; padding:16px 16px 8px; }

    .avatar-card {
        background:#fff;
        border:1.5px solid var(--border);
        border-radius:var(--radius);
        padding:24px 20px;
        text-align:center;
        margin-bottom:16px;
        box-shadow:0 2px 8px rgba(0,47,92,.06);
    }
    .avatar-wrap {
        width:80px; height:80px;
        border-radius:50%;
        background:var(--blue-sky);
        border:3px solid var(--border);
        margin:0 auto 12px;
        display:flex; align-items:center; justify-content:center;
        font-size:2rem;
        overflow:hidden;
    }
    .avatar-wrap img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .profile-name { font-family:'Fredoka One', cursive; font-size:1.3rem; color:var(--ink); }
    .profile-role {
        font-size:.75rem;
        font-weight:800;
        color:var(--ink-lt);
        letter-spacing:.5px;
        text-transform:uppercase;
        margin-top:4px;
    }
    .edit-btn {
        display:inline-flex;
        align-items:center;
        gap:.4rem;
        background:var(--blue-mid);
        color:#fff;
        font-weight:800;
        font-size:.82rem;
        border:none;
        border-radius:99px;
        padding:.5rem 1.2rem;
        text-decoration:none;
        margin-top:14px;
        transition:background .2s;
    }
    .edit-btn:hover { background:var(--blue-dark); }

    .section-card {
        background:#fff;
        border:1.5px solid var(--border);
        border-radius:var(--radius);
        margin-bottom:16px;
        box-shadow:0 2px 8px rgba(0,47,92,.06);
        overflow:hidden;
    }
    .section-head {
        display:flex;
        align-items:center;
        justify-content:space-between;
        padding:14px 16px 10px;
        gap:8px;
    }
    .section-title {
        font-family:'Fredoka One', cursive;
        font-size:.95rem;
        color:var(--blue-dark);
        display:flex;
        align-items:center;
        gap:8px;
    }
    .section-action {
        font-size:.72rem;
        font-weight:800;
        color:var(--blue-mid);
        text-decoration:none;
        letter-spacing:.3px;
    }
    .section-action:hover { text-decoration:underline; }

    .info-row {
        display:flex;
        align-items:center;
        gap:12px;
        padding:14px 16px;
        border-top:1px solid var(--border);
    }
    .info-icon {
        width:36px; height:36px;
        border-radius:10px;
        background:var(--blue-sky);
        display:flex; align-items:center; justify-content:center;
        font-size:.95rem;
        flex-shrink:0;
    }
    .info-body { flex:1; min-width:0; }
    .info-label {
        font-size:.68rem;
        font-weight:800;
        color:var(--ink-lt);
        text-transform:uppercase;
        letter-spacing:.3px;
    }
    .info-value {
        font-size:.92rem;
        font-weight:700;
        color:var(--ink);
        margin-top:2px;
    }
    .info-value.truncate {
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }

    .address-body {
        padding:0 16px 14px;
    }
    .address-text {
        font-size:.85rem;
        font-weight:600;
        color:var(--ink-mid);
        line-height:1.5;
    }
    .address-meta {
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        margin-top:8px;
    }
    .meta-chip {
        font-size:.7rem;
        font-weight:800;
        color:var(--ink-lt);
        background:var(--surface);
        border:1px solid var(--border);
        border-radius:99px;
        padding:3px 9px;
    }
    .meta-chip.zone-a { color:#047857; background:#e6fff6; border-color:rgba(0,196,140,.25); }
    .meta-chip.zone-b { color:#0369a1; background:#e0f4ff; border-color:rgba(0,119,182,.25); }
    .meta-chip.zone-c { color:#9a3412; background:#fff3ee; border-color:rgba(255,107,53,.25); }

    .empty-address {
        padding:18px 16px 18px;
        text-align:center;
    }
    .empty-address p {
        font-size:.85rem;
        font-weight:700;
        color:var(--ink-lt);
        margin-bottom:12px;
    }
    .btn-add-addr {
        display:inline-flex;
        align-items:center;
        gap:6px;
        background:var(--orange);
        color:#fff;
        text-decoration:none;
        font-weight:800;
        font-size:.82rem;
        padding:.55rem 1.1rem;
        border-radius:99px;
        box-shadow:0 4px 12px rgba(255,107,53,.3);
    }

    .danger-card {
        background:#fff;
        border:1.5px solid #fee2e2;
        border-radius:var(--radius);
        padding:14px 16px;
        margin-bottom:16px;
    }
    .danger-title {
        font-size:.82rem;
        font-weight:800;
        color:#ef4444;
        margin-bottom:10px;
    }
    .btn-logout {
        display:flex;
        align-items:center;
        justify-content:center;
        gap:.5rem;
        width:100%;
        padding:.65rem;
        border-radius:99px;
        border:1.5px solid #ef4444;
        background:transparent;
        color:#ef4444;
        font-weight:800;
        font-size:.88rem;
        cursor:pointer;
        transition:all .2s;
    }
    .btn-logout:hover { background:#ef4444; color:#fff; }

    .alert-success {
        background:#e6fff6;
        border:1.5px solid var(--green);
        border-radius:var(--radius);
        padding:12px 16px;
        margin-bottom:14px;
        font-size:.85rem;
        font-weight:700;
        color:#047857;
    }
</style>
</head>
<body>

<div class="top-header">
    <div class="header-inner">
        <div class="header-top">
            <a href="{{ route('customer.dashboard') }}" class="back-btn" aria-label="Kembali">‹</a>
            <div class="header-title">Profil Saya</div>
        </div>
    </div>
    <svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;">
        <path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/>
    </svg>
</div>

<div class="page-body">

    @if(session('status') === 'profile-updated')
        <div class="alert-success">Profil berhasil diperbarui.</div>
    @elseif(session('status') === 'address-saved')
        <div class="alert-success">Alamat utama berhasil disimpan.</div>
    @endif

    <div class="avatar-card">
        <div class="avatar-wrap">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}" alt="Foto profil">
            @else
                <span>👤</span>
            @endif
        </div>
        <div class="profile-name">{{ $user->name }}</div>
        <div class="profile-role">{{ ucfirst($user->role) }}</div>
        <a href="{{ route('profile.edit') }}" class="edit-btn" style="margin-top:14px;">Edit Profil</a>
    </div>

    <div class="section-card">
        <div class="section-head">
            <div class="section-title">Informasi Akun</div>
        </div>

        <div class="info-row">
            <div class="info-icon">@</div>
            <div class="info-body">
                <div class="info-label">Email</div>
                <div class="info-value truncate">{{ $user->email ?: '-' }}</div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-icon">#</div>
            <div class="info-body">
                <div class="info-label">Nomor HP</div>
                <div class="info-value">{{ $user->phone ?: 'Belum diisi' }}</div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-icon">·</div>
            <div class="info-body">
                <div class="info-label">Bergabung Sejak</div>
                <div class="info-value">{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="section-card">
        <div class="section-head">
            <div class="section-title">Alamat Utama</div>
            <a href="{{ route('customer.addresses.index') }}" class="section-action">Kelola Semua →</a>
        </div>

        @if($primaryAddress)
            <div class="address-body">
                <div class="address-text">
                    <strong>{{ $primaryAddress->label }}</strong> · {{ $primaryAddress->recipient_name }}<br>
                    {{ $primaryAddress->full_address }}
                    @if($primaryAddress->notes)
                        <br><em style="color:var(--ink-lt);">Catatan: {{ $primaryAddress->notes }}</em>
                    @endif
                </div>
                <div class="address-meta">
                    @if($primaryAddress->phone)
                        <span class="meta-chip">{{ $primaryAddress->phone }}</span>
                    @endif
                    @if($primaryAddress->zone)
                        <span class="meta-chip zone-{{ strtolower($primaryAddress->zone) }}">Zona {{ $primaryAddress->zone }}</span>
                    @endif
                    @if($primaryAddress->distance_km)
                        <span class="meta-chip">{{ $primaryAddress->distance_km }} km</span>
                    @endif
                </div>
            </div>

            <div style="display:flex; gap:8px; padding:0 16px 14px;">
                <a href="{{ route('customer.addresses.edit', $primaryAddress) }}"
                   style="flex:1; text-align:center; padding:.55rem; border-radius:10px; background:var(--blue-sky); color:var(--blue-mid); font-weight:800; font-size:.78rem; text-decoration:none;">
                    Edit Alamat Utama
                </a>
                <a href="{{ route('customer.addresses.create') }}"
                   style="flex:1; text-align:center; padding:.55rem; border-radius:10px; background:var(--surface); color:var(--ink-mid); font-weight:800; font-size:.78rem; text-decoration:none; border:1px solid var(--border);">
                    Tambah Baru
                </a>
            </div>
        @else
            <div class="empty-address">
                <p>Belum ada alamat utama. Tambahkan agar bisa langsung dipakai saat pesan.</p>
                <a href="{{ route('customer.addresses.create') }}" class="btn-add-addr">+ Tambah Alamat Utama</a>
            </div>
        @endif

        @if(($totalAddresses ?? 0) > 1)
            <div style="padding:10px 16px 14px; border-top:1px solid var(--border); font-size:.75rem; font-weight:700; color:var(--ink-lt);">
                Total alamat tersimpan: <strong style="color:var(--blue-mid);">{{ $totalAddresses }}</strong>
            </div>
        @endif
    </div>

    <div class="danger-card">
        <div class="danger-title">Akun</div>
        <form method="POST" action="{{ route('logout') }}" id="form-logout">
            @csrf
            <button type="button" class="btn-logout" id="btn-logout">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

</div>

@include('layouts.component.customer._confirm_modal')

<script>
(function() {
    var btnLogout = document.getElementById('btn-logout');
    if (btnLogout) {
        btnLogout.addEventListener('click', function() {
            showConfirmModal({
                title: 'Keluar dari Akun?',
                message: 'Kamu akan keluar dari sesi ini. Yakin ingin melanjutkan?',
                confirmText: 'Ya, Keluar',
                cancelText: 'Batal',
                type: 'warning',
                onConfirm: function() {
                    document.getElementById('form-logout').submit();
                }
            });
        });
    }
})();
</script>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

</body>
</html>
