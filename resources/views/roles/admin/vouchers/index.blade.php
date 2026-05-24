<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Voucher – Azka Laundry</title>
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
    --green: #059669;
    --green-lt: #ecfdf5;
    --red: #dc2626;
    --red-lt: #fef2f2;
    --orange: #f59e0b;
    --orange-lt: #fff7ed;
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
    padding: 18px 18px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.page-header__title {
    font-size: 1.05rem;
    font-weight: 800;
    flex: 1;
}
.page-header__action {
    background: rgba(255, 255, 255, 0.18);
    border: none;
    color: #fff;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: .8rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
}
.page-header__action:hover { background: rgba(255, 255, 255, 0.26); }

.container { padding: 16px; }

.flash {
    padding: 12px 14px;
    border-radius: 12px;
    font-size: .85rem;
    font-weight: 600;
    margin-bottom: 12px;
}
.flash--ok  { background: var(--green-lt); color: var(--green); }
.flash--err { background: var(--red-lt);   color: var(--red); }

.voucher-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 12px;
}
.voucher-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 8px;
}
.voucher-code {
    font-family: 'Courier New', monospace;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--blue-dark);
    letter-spacing: 0.5px;
}
.voucher-status {
    font-size: .68rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.voucher-status--on  { background: var(--green-lt); color: var(--green); }
.voucher-status--off { background: #f1f5f9;         color: var(--ink-lt); }

.voucher-desc {
    font-size: .82rem;
    color: var(--ink-mid);
    margin-bottom: 10px;
}
.voucher-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px 14px;
    font-size: .74rem;
    margin-bottom: 12px;
}
.voucher-meta__item span:first-child {
    color: var(--ink-lt);
    font-weight: 600;
    display: block;
    font-size: .68rem;
}
.voucher-meta__item span:last-child {
    color: var(--ink);
    font-weight: 700;
}
.voucher-actions {
    display: flex;
    gap: 8px;
    border-top: 1px dashed var(--border);
    padding-top: 12px;
}
.voucher-btn {
    flex: 1;
    border: none;
    background: var(--blue-lt);
    color: var(--blue-dark);
    padding: 9px 10px;
    border-radius: 10px;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
}
.voucher-btn--danger { background: var(--red-lt); color: var(--red); }
.voucher-btn:hover { opacity: .85; }

.empty {
    text-align: center;
    padding: 40px 20px;
    color: var(--ink-lt);
}
.empty__icon { font-size: 2.5rem; margin-bottom: 8px; }
</style>
</head>
<body>
@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])

<div class="page-header">
    <x-back-button fallback="admin.dashboard" style="hero" />
    <div class="page-header__title">Voucher Promo</div>
    <a href="{{ route('admin.vouchers.create') }}" class="page-header__action">+ {{ __('ui.buttons.create') }}</a>
</div>

<div class="container">
    @if(session('success'))
        <div class="flash flash--ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash--err">{{ session('error') }}</div>
    @endif

    @forelse($vouchers as $v)
    <div class="voucher-card">
        <div class="voucher-card__head">
            <div class="voucher-code">{{ $v->code }}</div>
            <span class="voucher-status {{ $v->is_active ? 'voucher-status--on' : 'voucher-status--off' }}">
                {{ $v->is_active ? __('ui.states.active') : __('ui.states.inactive') }}
            </span>
        </div>

        <div class="voucher-desc">{{ $v->description }}</div>

        <div class="voucher-meta">
            <div class="voucher-meta__item">
                <span>Potongan</span>
                <span>{{ $v->display_value }}</span>
            </div>
            <div class="voucher-meta__item">
                <span>Min. Order</span>
                <span>{{ $v->min_order > 0 ? 'Rp '.number_format($v->min_order, 0, ',', '.') : 'Tanpa minimum' }}</span>
            </div>
            <div class="voucher-meta__item">
                <span>Pemakaian</span>
                <span>{{ $v->used_count }}{{ $v->usage_limit ? ' / '.$v->usage_limit : '' }}</span>
            </div>
            <div class="voucher-meta__item">
                <span>Berlaku sampai</span>
                <span>{{ $v->valid_until ? $v->valid_until->translatedFormat('d M Y') : 'Tanpa batas' }}</span>
            </div>
        </div>

        <div class="voucher-actions">
            <form method="POST" action="{{ route('admin.vouchers.toggle', $v) }}" style="flex: 1;">
                @csrf @method('PATCH')
                <button type="submit" class="voucher-btn">
                    {{ $v->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @if($v->used_count === 0)
                <form method="POST" action="{{ route('admin.vouchers.destroy', $v) }}" style="flex: 1;"
                      onsubmit="return confirm('Hapus voucher {{ $v->code }}?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="voucher-btn voucher-btn--danger">{{ __('ui.buttons.delete') }}</button>
                </form>
            @endif
        </div>
    </div>
    @empty
    <div class="empty">
        <div class="empty__icon">🎟️</div>
        <div>Belum ada voucher. Klik "+ Buat" untuk tambah voucher pertama.</div>
    </div>
    @endforelse

    {{ $vouchers->links() }}
</div>
</body>
</html>
