<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buat Voucher – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --blue: #0077b6;
    --blue-dark: #002f5c;
    --blue-lt: #e8f4fd;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-mid: #4a5568;
    --ink-lt: #8896a6;
    --border: #e2e8f0;
    --red: #dc2626;
    --red-lt: #fef2f2;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--surface);
    color: var(--ink);
    padding-bottom: 100px;
    min-height: 100vh;
}
.page-header {
    background: var(--blue);
    color: #fff;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.page-header__title { font-size: 1.05rem; font-weight: 800; }
.container { padding: 16px; }
.form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px;
}
.form-row { margin-bottom: 14px; }
.form-row__label {
    display: block;
    font-size: .78rem;
    font-weight: 700;
    color: var(--ink-mid);
    margin-bottom: 6px;
}
.form-row__hint {
    display: block;
    font-size: .68rem;
    color: var(--ink-lt);
    margin-top: 4px;
}
.input,
.select,
.textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-family: inherit;
    font-size: .88rem;
    color: var(--ink);
    background: #fff;
}
.input:focus,
.select:focus,
.textarea:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
}
.input--mono {
    font-family: 'Courier New', monospace;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
}
.row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.error {
    color: var(--red);
    font-size: .72rem;
    margin-top: 4px;
    font-weight: 600;
}
.toggle-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: var(--blue-lt);
    border-radius: 10px;
}
.toggle-row input { width: 16px; height: 16px; accent-color: var(--blue); }
.btn-submit {
    width: 100%;
    background: var(--blue);
    color: #fff;
    padding: 13px 16px;
    border: none;
    border-radius: 12px;
    font-size: .9rem;
    font-weight: 800;
    cursor: pointer;
    margin-top: 14px;
}
.btn-submit:hover { background: var(--blue-dark); }
</style>
</head>
<body>
<div class="page-header">
    <x-back-button fallback="admin.vouchers.index" style="hero" />
    <div class="page-header__title">Buat Voucher Baru</div>
</div>

<div class="container">
    <form method="POST" action="{{ route('admin.vouchers.store') }}" class="form-card">
        @csrf

        <div class="form-row">
            <label class="form-row__label" for="code">Kode Voucher</label>
            <input type="text" name="code" id="code" class="input input--mono"
                   value="{{ old('code') }}" required maxlength="30"
                   placeholder="WELCOME10">
            <span class="form-row__hint">Akan disimpan UPPERCASE. Customer tinggal masukin kode ini di form pemesanan.</span>
            @error('code')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <label class="form-row__label" for="description">Keterangan</label>
            <input type="text" name="description" id="description" class="input"
                   value="{{ old('description') }}" required maxlength="200"
                   placeholder="Diskon 10% untuk customer baru">
            @error('description')<div class="error">{{ $message }}</div>@enderror
        </div>

        <div class="row-2">
            <div class="form-row">
                <label class="form-row__label" for="type">Jenis</label>
                <select name="type" id="type" class="select" required>
                    <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Persen (%)</option>
                    <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                </select>
                @error('type')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <label class="form-row__label" for="value">Nilai</label>
                <input type="number" name="value" id="value" class="input"
                       value="{{ old('value') }}" required min="1">
                <span class="form-row__hint">10 untuk 10%, atau 5000 untuk Rp 5.000.</span>
                @error('value')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row-2">
            <div class="form-row">
                <label class="form-row__label" for="min_order">Min. Order (Rp)</label>
                <input type="number" name="min_order" id="min_order" class="input"
                       value="{{ old('min_order', 0) }}" min="0">
                @error('min_order')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <label class="form-row__label" for="max_discount">Maks. Diskon (Rp)</label>
                <input type="number" name="max_discount" id="max_discount" class="input"
                       value="{{ old('max_discount') }}" min="1"
                       placeholder="Opsional, hanya untuk persen">
                <span class="form-row__hint">Cap diskon maksimum, hanya berlaku untuk jenis persen.</span>
                @error('max_discount')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row-2">
            <div class="form-row">
                <label class="form-row__label" for="valid_from">Berlaku Dari</label>
                <input type="date" name="valid_from" id="valid_from" class="input"
                       value="{{ old('valid_from') }}">
                @error('valid_from')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <label class="form-row__label" for="valid_until">Berlaku Sampai</label>
                <input type="date" name="valid_until" id="valid_until" class="input"
                       value="{{ old('valid_until') }}">
                @error('valid_until')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-row">
            <label class="form-row__label" for="usage_limit">Batas Pemakaian</label>
            <input type="number" name="usage_limit" id="usage_limit" class="input"
                   value="{{ old('usage_limit') }}" min="1"
                   placeholder="Opsional, kosongkan kalau tanpa batas">
            <span class="form-row__hint">Total pemakaian gabungan dari semua customer.</span>
            @error('usage_limit')<div class="error">{{ $message }}</div>@enderror
        </div>

        <label class="toggle-row">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span style="font-size: .85rem; font-weight: 600;">Langsung aktifkan setelah dibuat</span>
        </label>

        <button type="submit" class="btn-submit">Simpan Voucher</button>
    </form>
</div>
</body>
</html>
