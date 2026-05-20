<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Order Walk-in – Azka Laundry</title>
@include('layouts.component.customer._head_meta')
<style>
:root {
    --primary: #0d6fb8;
    --primary-dark: #002f5c;
    --primary-light: #e0f4ff;
    --accent: #FF6B35;
    --accent-light: #fff3ee;
    --success: #059669;
    --success-light: #ecfdf5;
    --danger: #dc2626;
    --surface: #f4f8fc;
    --card: #ffffff;
    --ink: #1a2332;
    --ink-secondary: #475569;
    --ink-muted: #94a3b8;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --radius: 14px;
    --radius-sm: 10px;
    --shadow-sm: 0 1px 3px rgba(0,0,0,.06);
    --shadow-md: 0 4px 12px rgba(0,47,92,.08);
}
*, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--surface);
    color: var(--ink);
    min-height: 100vh;
    padding-bottom: calc(80px + env(safe-area-inset-bottom, 0px));
    overflow-x: hidden;
}

/* Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    padding: max(env(safe-area-inset-top, 0px), 16px) 20px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
}
.page-header__inner {
    display: flex;
    align-items: center;
    gap: 12px;
    max-width: 520px;
    margin: 0 auto;
}
.page-header__back {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    text-decoration: none;
    flex-shrink: 0;
}
.page-header__back svg { width: 18px; height: 18px; }
.page-header__title { font-weight: 800; font-size: 1.1rem; color: #fff; }
.page-header__subtitle { font-size: .66rem; font-weight: 600; color: rgba(255,255,255,.6); margin-top: 1px; }

/* Body */
.page-body { max-width: 520px; margin: 0 auto; padding: 14px 16px; }

/* Alert */
.page-alert {
    padding: 11px 14px;
    border-radius: var(--radius-sm);
    font-size: .8rem;
    font-weight: 700;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.page-alert--success { background: var(--success-light); color: var(--success); border: 1px solid rgba(5,150,105,.2); }
.page-alert--error { background: #fef2f2; color: var(--danger); border: 1px solid rgba(220,38,38,.15); }

/* Form Card */
.form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow-md);
}
.form-card__head {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-card__icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    background: var(--accent-light);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.form-card__icon svg { width: 16px; height: 16px; color: var(--accent); }
.form-card__title { font-weight: 700; font-size: .88rem; color: var(--ink); }
.form-card__body { padding: 16px; }

/* Form fields */
.form-group {
    margin-bottom: 14px;
}
.form-label {
    font-size: .7rem;
    font-weight: 700;
    color: var(--ink-secondary);
    margin-bottom: 6px;
    display: block;
}
.form-label__req { color: var(--danger); }
.form-input {
    width: 100%;
    padding: 11px 13px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    font-size: .85rem;
    color: var(--ink);
    background: #fff;
    outline: none;
    transition: border-color .2s;
}
.form-input:focus { border-color: var(--primary); }
.form-input::placeholder { color: var(--ink-muted); font-weight: 500; }

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

/* Item lines section */
.items-section {
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 12px;
    margin-bottom: 14px;
}
.items-section__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}
.items-section__title {
    font-size: .72rem;
    font-weight: 700;
    color: var(--ink-secondary);
}
.items-section__hint {
    font-size: .66rem;
    font-weight: 600;
    color: var(--ink-muted);
}
.items-wrap {
    display: grid;
    gap: 8px;
}
.item-row {
    display: grid;
    grid-template-columns: 1fr 70px 36px;
    gap: 6px;
    align-items: center;
}
.item-remove {
    height: 36px;
    border: 1.5px solid rgba(220,38,38,.2);
    background: #fef2f2;
    color: var(--danger);
    border-radius: 8px;
    font-weight: 900;
    font-size: 1rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.item-remove:disabled { opacity: .4; cursor: not-allowed; }
.btn-add-item {
    margin-top: 8px;
    padding: 9px 12px;
    border: 1.5px dashed rgba(13,111,184,.35);
    background: var(--primary-light);
    color: var(--primary);
    border-radius: var(--radius-sm);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .74rem;
    cursor: pointer;
    width: 100%;
    transition: background .12s;
}
.btn-add-item:active { background: #cce9fa; }

/* Submit */
.form-submit {
    width: 100%;
    padding: 13px;
    border: none;
    border-radius: var(--radius-sm);
    background: var(--accent);
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(255,107,53,.3);
    transition: transform .12s;
}
.form-submit:active { transform: scale(.97); }
</style>
</head>
<body>

<header class="page-header">
    <div class="page-header__inner">
        <a href="{{ route('dashboard.admin') }}" class="page-header__back" aria-label="Kembali">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
        </a>
        <div>
            <div class="page-header__title">Order Walk-in</div>
            <div class="page-header__subtitle">Tambah pesanan pelanggan langsung</div>
        </div>
    </div>
</header>

<div class="page-body">

    @if(session('status'))
        <div class="page-alert page-alert--success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('status') }}
        </div>
    @endif
    @if($errors->any())
        <div class="page-alert page-alert--error">{{ $errors->first() }}</div>
    @endif

    <div class="form-card">
        <div class="form-card__head">
            <div class="form-card__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
            </div>
            <div class="form-card__title">Data Pelanggan & Order</div>
        </div>
        <div class="form-card__body">
            <form method="POST" action="{{ route('admin.orders.walk-in.store') }}" id="walkin-form">
                @csrf

                <div class="form-group">
                    <label class="form-label">Nama Pelanggan <span class="form-label__req">*</span></label>
                    <input name="customer_name" type="text" placeholder="Nama lengkap" required class="form-input" value="{{ old('customer_name') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP (opsional)</label>
                    <input name="customer_phone" type="text" placeholder="08xxxxxxxxxx" class="form-input" value="{{ old('customer_phone') }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Layanan <span class="form-label__req">*</span></label>
                    <select name="service_id" id="walkin-service-main" required class="form-input">
                        <option value="">Pilih layanan</option>
                        @foreach(($daftarLayanan ?? collect()) as $layanan)
                            @if(($layanan->pricing_model ?? 'per_kg') === 'per_kg')
                            <option value="{{ $layanan->id }}" data-category-id="{{ $layanan->service_category_id }}">{{ $layanan->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="form-row form-group">
                    <div>
                        <label class="form-label">Berat (kg) <span class="form-label__req">*</span></label>
                        <input name="weight_estimate" type="number" min="0.5" max="50" step="0.1" placeholder="Contoh: 3.5" required class="form-input" value="{{ old('weight_estimate') }}">
                    </div>
                    <div>
                        <label class="form-label">Slot Proses <span class="form-label__req">*</span></label>
                        <select name="pickup_time" required class="form-input">
                            <option value="pagi">Pagi</option>
                            <option value="siang">Siang</option>
                            <option value="sore">Sore</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan (opsional)</label>
                    <input name="notes" type="text" placeholder="Catatan khusus" class="form-input" value="{{ old('notes') }}">
                </div>

                {{-- Item Lines --}}
                <div class="items-section">
                    <div class="items-section__head">
                        <span class="items-section__title">Item Satuan (opsional)</span>
                        <span class="items-section__hint">Tambah sesuai kebutuhan</span>
                    </div>

                    <div id="walkin-items-wrap" class="items-wrap"></div>

                    <button type="button" id="walkin-add-item" class="btn-add-item">+ Tambah Item Satuan</button>

                    <template id="walkin-item-template">
                        <div class="item-row" data-item-row>
                            <select class="form-input" data-item-service>
                                <option value="">Pilih item</option>
                                @foreach(($daftarLayananItem ?? collect()) as $layananItem)
                                <option value="{{ $layananItem->id }}" data-category-id="{{ $layananItem->service_category_id }}">{{ $layananItem->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" min="1" max="999" placeholder="Qty" class="form-input" data-item-qty>
                            <button type="button" class="item-remove" data-item-remove>&times;</button>
                        </div>
                    </template>
                </div>

                <button type="submit" class="form-submit">Buat Order Walk-in</button>
            </form>
        </div>
    </div>

</div>

@include('layouts.component.admin._navbar_admin', ['active' => 'pesanan'])
@include('layouts.component._form_loading')

<script>
(function () {
    var mainSelect = document.getElementById('walkin-service-main');
    var itemsWrap = document.getElementById('walkin-items-wrap');
    var addItemBtn = document.getElementById('walkin-add-item');
    var itemTemplate = document.getElementById('walkin-item-template');
    if (!mainSelect || !itemsWrap || !addItemBtn || !itemTemplate) return;

    function sinkronKategoriItem() {
        var selected = mainSelect.options[mainSelect.selectedIndex];
        var categoryId = selected ? (selected.getAttribute('data-category-id') || '') : '';
        var itemSelects = itemsWrap.querySelectorAll('[data-item-service]');

        itemSelects.forEach(function(selectEl) {
            var options = selectEl.options;
            for (var i = 0; i < options.length; i++) {
                if (i === 0) { options[i].hidden = false; continue; }
                var optCat = options[i].getAttribute('data-category-id') || '';
                var show = !categoryId || (String(optCat) === String(categoryId));
                options[i].hidden = !show;
                if (!show && options[i].selected) { selectEl.selectedIndex = 0; }
            }
        });
    }

    function reindexItemNames() {
        var rows = itemsWrap.querySelectorAll('[data-item-row]');
        rows.forEach(function(row, i) {
            var service = row.querySelector('[data-item-service]');
            var qty = row.querySelector('[data-item-qty]');
            if (service) service.name = 'item_lines[' + i + '][service_id]';
            if (qty) qty.name = 'item_lines[' + i + '][qty]';
        });
        var removeButtons = itemsWrap.querySelectorAll('[data-item-remove]');
        removeButtons.forEach(function(btn) { btn.disabled = rows.length <= 1; });
    }

    function addItemRow() {
        var node = itemTemplate.content.cloneNode(true);
        var row = node.querySelector('[data-item-row]');
        var removeBtn = node.querySelector('[data-item-remove]');
        removeBtn.addEventListener('click', function() {
            row.remove();
            reindexItemNames();
        });
        itemsWrap.appendChild(node);
        reindexItemNames();
        sinkronKategoriItem();
    }

    addItemBtn.addEventListener('click', addItemRow);
    mainSelect.addEventListener('change', sinkronKategoriItem);

    addItemRow();
    sinkronKategoriItem();
})();
</script>

</body>
</html>
