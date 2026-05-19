<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alamat Saya – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue:#0077b6;--blue-dark:#002f5c;--bg:#f4f8fc;--card:#fff;--ink:#1a2332;--muted:#64748b;--line:#ddeeff;--green:#00C48C;--red:#ef4444;--r:16px}
*{box-sizing:border-box}body{margin:0;font-family:'Nunito',sans-serif;background:var(--bg);color:var(--ink)}
.wrap{max-width:860px;margin:0 auto;padding:18px}
.top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px}
.h1{font-family:'Fredoka One',cursive;color:var(--blue-dark);font-size:1.25rem}
.btn{border:1.5px solid var(--line);background:#fff;color:var(--ink);border-radius:12px;padding:.55rem .8rem;font-weight:800;text-decoration:none;cursor:pointer;transition:.18s}
.btn:hover{transform:translateY(-1px)}
.card{background:var(--card);border:1.5px solid var(--line);border-radius:var(--r);padding:14px;margin-bottom:12px;box-shadow:0 4px 14px rgba(0,47,92,.05)}
.card-title{font-weight:900;margin-bottom:10px;color:var(--blue-dark)}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.label{font-size:.72rem;font-weight:800;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;margin-bottom:4px}
.input{width:100%;border:1.5px solid var(--line);border-radius:10px;padding:.65rem .7rem;font:600 .92rem 'Nunito',sans-serif}
textarea.input{min-height:72px;resize:vertical}
.actions{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
.btn-primary{background:var(--blue);color:#fff;border-color:var(--blue)}
.btn-danger{background:#fff5f5;color:var(--red);border-color:#fecaca}
.badge{display:inline-block;background:#ecfeff;color:#0e7490;border:1px solid #bae6fd;border-radius:999px;padding:.15rem .5rem;font-size:.72rem;font-weight:900}
.badge-muted{display:inline-block;background:#f1f5f9;color:#334155;border:1px solid #cbd5e1;border-radius:999px;padding:.15rem .5rem;font-size:.72rem;font-weight:900;margin-left:6px}
.row{display:flex;justify-content:space-between;align-items:flex-start;gap:10px}
.err{font-size:.75rem;color:var(--red);font-weight:700;margin-top:4px}
.input.is-error{border-color:#fca5a5;background:#fff5f5}
.head-actions{display:flex;gap:8px;align-items:center}
.ok{background:#ecfdf5;border:1.5px solid #bbf7d0;color:#166534;border-radius:10px;padding:.65rem .75rem;font-weight:800;margin-bottom:10px}
.meta{font-size:.78rem;color:var(--muted);margin-top:4px}
@media(max-width:700px){.grid{grid-template-columns:1fr}.wrap{padding:14px}.btn{padding:.5rem .72rem}}
</style>
</head>
<body>
<div class="wrap">
  <div class="top">
    <div class="h1">Alamat Saya</div>
    @php
  $role = auth()->user()->role;
  $dashboardRoute = $role === 'admin' ? 'dashboard.admin' : ($role === 'driver' ? 'dashboard.driver' : 'customer.dashboard');
@endphp
<a class="btn" href="{{ route($dashboardRoute) }}" data-smart-back>← Kembali</a>
  </div>

  @if(session('status'))<div class="ok">{{ session('status') }}</div>@endif

  <div class="card">
    <div class="card-title">Tambah Alamat Baru</div>
    <div class="meta" style="margin-bottom:10px">Alamat ini bisa dipakai ulang saat membuat pesanan berikutnya.</div>
    <form method="POST" action="{{ route('customer.addresses.store') }}">
      @csrf
      <div class="grid">
        <div><div class="label">Label</div><input class="input {{ $errors->has('label') ? 'is-error' : '' }}" name="label" value="{{ old('label','') }}" placeholder="Contoh: Rumah, Kos, Kantor">@error('label')<div class="err">{{ $message }}</div>@enderror</div>
        <div><div class="label">Nama Penerima</div><input class="input {{ $errors->has('recipient_name') ? 'is-error' : '' }}" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>@error('recipient_name')<div class="err">{{ $message }}</div>@enderror</div>
        <div><div class="label">No HP</div><input class="input {{ $errors->has('phone') ? 'is-error' : '' }}" name="phone" value="{{ old('phone') }}">@error('phone')<div class="err">{{ $message }}</div>@enderror</div>
        <div><div class="label">Zona</div><select class="input {{ $errors->has('zone') ? 'is-error' : '' }}" name="zone"><option value="">Pilih zona</option><option value="A" {{ old('zone')==='A'?'selected':'' }}>A</option><option value="B" {{ old('zone')==='B'?'selected':'' }}>B</option><option value="C" {{ old('zone')==='C'?'selected':'' }}>C</option></select>@error('zone')<div class="err">{{ $message }}</div>@enderror</div>
      </div>
      <div style="margin-top:10px"><div class="label">Alamat Lengkap</div><textarea class="input {{ $errors->has('full_address') ? 'is-error' : '' }}" name="full_address" required>{{ old('full_address') }}</textarea>@error('full_address')<div class="err">{{ $message }}</div>@enderror</div>
      <div style="margin-top:10px"><div class="label">Patokan</div><input class="input {{ $errors->has('notes') ? 'is-error' : '' }}" name="notes" value="{{ old('notes') }}">@error('notes')<div class="err">{{ $message }}</div>@enderror</div>
      <label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-weight:800"><input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}> Jadikan alamat utama</label>
      <div class="actions" style="margin-top:10px"><button class="btn btn-primary" type="submit">Simpan Alamat</button></div>
    </form>
  </div>

  @foreach($addresses as $address)
  <div class="card">
    <div class="row" style="margin-bottom:8px">
      <div>
        <div style="font-weight:900">{{ $address->label }} @if($address->is_primary)<span class="badge">Utama</span>@endif @if($address->last_used_at)<span class="badge-muted">Terakhir dipakai {{ $address->last_used_at->diffForHumans() }}</span>@endif</div>
        <div class="meta">{{ $address->recipient_name }} @if($address->phone)• {{ $address->phone }}@endif @if($address->zone)• Zona {{ $address->zone }}@endif</div>
      </div>
    </div>
    @php($bag = 'updateAddress_'.$address->id)
    <form method="POST" action="{{ route('customer.addresses.update', $address) }}">
      @csrf
      @method('PUT')
      <div class="grid">
        <div><div class="label">Label</div><input class="input {{ $errors->getBag($bag)->has('label') ? 'is-error' : '' }}" name="label" value="{{ old('label', $address->label) }}">@if($errors->getBag($bag)->has('label'))<div class="err">{{ $errors->getBag($bag)->first('label') }}</div>@endif</div>
        <div><div class="label">Nama Penerima</div><input class="input {{ $errors->getBag($bag)->has('recipient_name') ? 'is-error' : '' }}" name="recipient_name" value="{{ old('recipient_name', $address->recipient_name) }}" required>@if($errors->getBag($bag)->has('recipient_name'))<div class="err">{{ $errors->getBag($bag)->first('recipient_name') }}</div>@endif</div>
        <div><div class="label">No HP</div><input class="input {{ $errors->getBag($bag)->has('phone') ? 'is-error' : '' }}" name="phone" value="{{ old('phone', $address->phone) }}">@if($errors->getBag($bag)->has('phone'))<div class="err">{{ $errors->getBag($bag)->first('phone') }}</div>@endif</div>
        <div><div class="label">Zona</div><select class="input {{ $errors->getBag($bag)->has('zone') ? 'is-error' : '' }}" name="zone"><option value="">Pilih zona</option><option value="A" {{ old('zone', $address->zone)==='A'?'selected':'' }}>A</option><option value="B" {{ old('zone', $address->zone)==='B'?'selected':'' }}>B</option><option value="C" {{ old('zone', $address->zone)==='C'?'selected':'' }}>C</option></select>@if($errors->getBag($bag)->has('zone'))<div class="err">{{ $errors->getBag($bag)->first('zone') }}</div>@endif</div>
      </div>
      <div style="margin-top:10px"><div class="label">Alamat Lengkap</div><textarea class="input {{ $errors->getBag($bag)->has('full_address') ? 'is-error' : '' }}" name="full_address" required>{{ old('full_address', $address->full_address) }}</textarea>@if($errors->getBag($bag)->has('full_address'))<div class="err">{{ $errors->getBag($bag)->first('full_address') }}</div>@endif</div>
      <div style="margin-top:10px"><div class="label">Patokan</div><input class="input {{ $errors->getBag($bag)->has('notes') ? 'is-error' : '' }}" name="notes" value="{{ old('notes', $address->notes) }}">@if($errors->getBag($bag)->has('notes'))<div class="err">{{ $errors->getBag($bag)->first('notes') }}</div>@endif</div>
      <label style="display:flex;align-items:center;gap:8px;margin-top:10px;font-weight:800"><input type="checkbox" name="is_primary" value="1" {{ old('is_primary', $address->is_primary) ? 'checked' : '' }}> Jadikan alamat utama</label>
      <div class="actions" style="margin-top:10px">
        <button class="btn btn-primary" type="submit">Update</button>
      </div>
    </form>
    <form method="POST" action="{{ route('customer.addresses.destroy', $address) }}" style="margin-top:8px"
          data-confirm="Alamat ini akan dihapus permanen dan tidak bisa dikembalikan."
          data-confirm-title="Hapus Alamat?"
          data-confirm-ok="Ya, Hapus"
          data-confirm-danger="true">
      @csrf
      @method('DELETE')
      <button class="btn btn-danger" type="submit">Hapus</button>
      @if($address->is_primary)
        <span class="badge-muted">Jika dihapus, alamat berikutnya jadi utama otomatis</span>
      @endif
    </form>
  </div>
  @endforeach
</div>

@include('layouts.component.customer._navbar_customer', ['active' => 'profil'])

</body>
</html>
