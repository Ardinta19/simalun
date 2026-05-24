<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Kategori Layanan – Azka Laundry</title>
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
    font-family: inherit;
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

.cat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 14px;
}
.cat-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    margin-bottom: 6px;
}
.cat-name {
    font-size: 1rem;
    font-weight: 800;
    color: var(--blue-dark);
}
.cat-status {
    font-size: .68rem;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.cat-status--on  { background: var(--green-lt); color: var(--green); }
.cat-status--off { background: #f1f5f9;         color: var(--ink-lt); }

.cat-desc {
    font-size: .82rem;
    color: var(--ink-mid);
    margin-bottom: 10px;
}
.cat-meta {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px 14px;
    font-size: .74rem;
    margin-bottom: 12px;
}
.cat-meta__item span:first-child {
    color: var(--ink-lt);
    font-weight: 600;
    display: block;
    font-size: .68rem;
}
.cat-meta__item span:last-child {
    color: var(--ink);
    font-weight: 700;
}
.cat-actions {
    display: flex;
    gap: 8px;
    border-top: 1px dashed var(--border);
    padding-top: 12px;
    margin-bottom: 12px;
}
.cat-btn {
    flex: 1;
    border: none;
    background: var(--blue-lt);
    color: var(--blue-dark);
    padding: 9px 10px;
    border-radius: 10px;
    font-size: .78rem;
    font-weight: 700;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    font-family: inherit;
}
.cat-btn--danger { background: var(--red-lt); color: var(--red); }
.cat-btn:hover { opacity: .85; }

.svc-section {
    background: #f9fafb;
    border-radius: 12px;
    padding: 12px;
}
.svc-section__head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 10px;
}
.svc-section__title {
    font-size: .8rem;
    font-weight: 800;
    color: var(--ink-mid);
    text-transform: uppercase;
    letter-spacing: .5px;
}
.svc-section__add {
    background: var(--blue);
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: .72rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}
.svc-row {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 6px;
}
.svc-row:last-child { margin-bottom: 0; }
.svc-row__main {
    flex: 1;
    min-width: 0;
}
.svc-row__name {
    font-size: .88rem;
    font-weight: 800;
    color: var(--ink);
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.svc-row__name .svc-pill {
    font-size: .58rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 99px;
    text-transform: uppercase;
}
.svc-row__name .svc-pill--off { background: #f1f5f9; color: var(--ink-lt); }
.svc-row__meta {
    font-size: .72rem;
    color: var(--ink-lt);
    margin-top: 2px;
    font-weight: 600;
}
.svc-row__actions {
    display: flex;
    gap: 6px;
}
.svc-mini {
    border: none;
    background: var(--blue-lt);
    color: var(--blue-dark);
    padding: 6px 10px;
    border-radius: 8px;
    font-size: .7rem;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
}
.svc-mini--danger { background: var(--red-lt); color: var(--red); }
.svc-empty {
    text-align: center;
    color: var(--ink-lt);
    font-size: .78rem;
    padding: 14px 8px;
    font-style: italic;
}

.empty {
    text-align: center;
    padding: 40px 20px;
    color: var(--ink-lt);
}
.empty__icon { font-size: 2.5rem; margin-bottom: 8px; }

/* Modal */
.modal-overlay {
    position: fixed; inset: 0;
    background: rgba(15, 23, 42, 0.55);
    backdrop-filter: blur(2px);
    display: none;
    align-items: flex-end;
    justify-content: center;
    z-index: 1000;
    padding: 16px;
}
.modal-overlay.is-open { display: flex; }
.modal-card {
    background: #fff;
    border-radius: 18px 18px 12px 12px;
    width: 100%;
    max-width: 480px;
    padding: 18px;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-card__head {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 14px;
}
.modal-card__title {
    font-size: 1rem; font-weight: 800; color: var(--blue-dark);
}
.modal-card__close {
    background: none; border: none; color: var(--ink-lt);
    font-size: 1.4rem; line-height: 1; cursor: pointer; padding: 4px 8px;
}

.form-row { margin-bottom: 12px; }
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
.select {
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
.select:focus {
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
}
.toggle-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: var(--blue-lt);
    border-radius: 10px;
    margin-bottom: 12px;
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
    margin-top: 6px;
    font-family: inherit;
}
.btn-submit:hover { background: var(--blue-dark); }
.context-tag {
    background: var(--blue-lt);
    color: var(--blue-dark);
    padding: 8px 12px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 700;
    margin-bottom: 12px;
}
</style>
</head>
<body>
@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])

<div class="page-header">
    <x-back-button fallback="admin.dashboard" style="hero" />
    <div class="page-header__title">Kategori &amp; Layanan</div>
    <button type="button" class="page-header__action" data-cat-create>+ Kategori</button>
</div>

<div class="container">
    @if(session('success'))
        <div class="flash flash--ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash--err">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="flash flash--err">{{ $errors->first() }}</div>
    @endif

    @forelse($categories as $cat)
    <div class="cat-card">
        <div class="cat-card__head">
            <div class="cat-name">{{ $cat->name }}</div>
            <span class="cat-status {{ $cat->is_active ? 'cat-status--on' : 'cat-status--off' }}">
                {{ $cat->is_active ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>

        @if($cat->description)
            <div class="cat-desc">{{ $cat->description }}</div>
        @endif

        <div class="cat-meta">
            <div class="cat-meta__item">
                <span>Model harga</span>
                <span>{{ $cat->pricing_model === 'per_kg' ? 'Per Kg' : 'Per Item' }}</span>
            </div>
            <div class="cat-meta__item">
                <span>Jumlah layanan</span>
                <span>{{ $cat->services_count }}</span>
            </div>
        </div>

        <div class="cat-actions">
            <button type="button" class="cat-btn"
                    data-cat-edit
                    data-id="{{ $cat->id }}"
                    data-name="{{ $cat->name }}"
                    data-pricing="{{ $cat->pricing_model }}"
                    data-description="{{ $cat->description }}"
                    data-active="{{ $cat->is_active ? '1' : '0' }}">
                Edit
            </button>
            <form method="POST" action="{{ route('admin.categories.toggle', $cat) }}" style="flex: 1;">
                @csrf @method('PATCH')
                <button type="submit" class="cat-btn">
                    {{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @if($cat->services_count === 0)
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" style="flex: 1;"
                      onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="cat-btn cat-btn--danger">Hapus</button>
                </form>
            @endif
        </div>

        {{-- Layanan dalam kategori ini --}}
        <div class="svc-section">
            <div class="svc-section__head">
                <div class="svc-section__title">Layanan ({{ $cat->services_count }})</div>
                <button type="button" class="svc-section__add"
                        data-svc-create
                        data-category-id="{{ $cat->id }}"
                        data-category-name="{{ $cat->name }}"
                        data-pricing="{{ $cat->pricing_model }}">
                    + Tambah Layanan
                </button>
            </div>

            @forelse($cat->services as $svc)
            <div class="svc-row">
                <div class="svc-row__main">
                    <div class="svc-row__name">
                        {{ $svc->name }}
                        @unless($svc->is_active)
                            <span class="svc-pill svc-pill--off">Nonaktif</span>
                        @endunless
                    </div>
                    <div class="svc-row__meta">
                        Rp {{ number_format($svc->effective_unit_price, 0, ',', '.') }}/{{ $svc->unit_type }}
                        &middot; Estimasi {{ $svc->estimated_hours }} jam
                    </div>
                </div>
                <div class="svc-row__actions">
                    <button type="button" class="svc-mini"
                            data-svc-edit
                            data-id="{{ $svc->id }}"
                            data-name="{{ $svc->name }}"
                            data-unit-price="{{ $svc->effective_unit_price }}"
                            data-estimated-hours="{{ $svc->estimated_hours }}"
                            data-description="{{ $svc->description }}"
                            data-active="{{ $svc->is_active ? '1' : '0' }}"
                            data-category-name="{{ $cat->name }}"
                            data-pricing="{{ $cat->pricing_model }}">
                        Edit
                    </button>
                    <form method="POST" action="{{ route('admin.services.toggle', $svc) }}" style="display:inline;">
                        @csrf @method('PATCH')
                        <button type="submit" class="svc-mini" title="{{ $svc->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                            {{ $svc->is_active ? 'Off' : 'On' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.services.destroy', $svc) }}" style="display:inline;"
                          onsubmit="return confirm('Hapus layanan {{ $svc->name }}? Tidak bisa dipulihkan.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="svc-mini svc-mini--danger">Hapus</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="svc-empty">Belum ada layanan. Klik "+ Tambah Layanan" untuk mengisi.</div>
            @endforelse
        </div>
    </div>
    @empty
    <div class="empty">
        <div class="empty__icon">📂</div>
        <div>Belum ada kategori. Klik "+ Kategori" untuk tambah kategori pertama.</div>
    </div>
    @endforelse
</div>

{{-- Modal Tambah / Edit Kategori --}}
<div class="modal-overlay" id="catModal">
    <div class="modal-card">
        <div class="modal-card__head">
            <div class="modal-card__title" id="catModalTitle">Buat Kategori</div>
            <button type="button" class="modal-card__close" data-cat-close>&times;</button>
        </div>

        <form method="POST" id="catForm" action="{{ route('admin.categories.store') }}">
            @csrf
            <input type="hidden" name="_method" id="catFormMethod" value="">

            <div class="form-row">
                <label class="form-row__label" for="cat-name">Nama Kategori</label>
                <input type="text" name="name" id="cat-name" class="input"
                       required maxlength="80"
                       placeholder="contoh: Kiloan, Satuan, Karpet">
            </div>

            <div class="form-row">
                <label class="form-row__label" for="cat-pricing">Model Harga</label>
                <select name="pricing_model" id="cat-pricing" class="select" required>
                    <option value="per_kg">Per Kg</option>
                    <option value="per_item">Per Item</option>
                </select>
                <span class="form-row__hint">Per kg = layanan utama (single-pick). Per item = item satuan (multiple).</span>
            </div>

            <div class="form-row">
                <label class="form-row__label" for="cat-desc">Keterangan</label>
                <input type="text" name="description" id="cat-desc" class="input"
                       maxlength="200"
                       placeholder="Opsional, untuk catatan internal">
            </div>

            <label class="toggle-row">
                <input type="checkbox" name="is_active" id="cat-active" value="1" checked>
                <span style="font-size: .85rem; font-weight: 600;">Kategori aktif</span>
            </label>

            <button type="submit" class="btn-submit" id="catFormSubmit">Simpan Kategori</button>
        </form>
    </div>
</div>

{{-- Modal Tambah / Edit Layanan --}}
<div class="modal-overlay" id="svcModal">
    <div class="modal-card">
        <div class="modal-card__head">
            <div class="modal-card__title" id="svcModalTitle">Tambah Layanan</div>
            <button type="button" class="modal-card__close" data-svc-close>&times;</button>
        </div>

        <div class="context-tag" id="svcContextTag">Kategori: -</div>

        <form method="POST" id="svcForm" action="">
            @csrf
            <input type="hidden" name="_method" id="svcFormMethod" value="">

            <div class="form-row">
                <label class="form-row__label" for="svc-name">Nama Layanan</label>
                <input type="text" name="name" id="svc-name" class="input"
                       required maxlength="120"
                       placeholder="contoh: Cuci Karpet 2x3 m">
            </div>

            <div class="form-row">
                <label class="form-row__label" for="svc-price">Harga (Rp)</label>
                <input type="number" name="unit_price" id="svc-price" class="input"
                       required min="1000" max="1000000" step="500"
                       placeholder="contoh: 50000">
                <span class="form-row__hint" id="svcPriceHint">Per kg / per item — ikut model kategori.</span>
            </div>

            <div class="form-row">
                <label class="form-row__label" for="svc-hours">Estimasi Selesai (jam)</label>
                <input type="number" name="estimated_hours" id="svc-hours" class="input"
                       required min="1" max="240"
                       placeholder="contoh: 48">
                <span class="form-row__hint">24 jam = 1 hari, 48 jam = 2 hari, 240 jam = 10 hari.</span>
            </div>

            <div class="form-row">
                <label class="form-row__label" for="svc-desc">Keterangan</label>
                <input type="text" name="description" id="svc-desc" class="input"
                       maxlength="200"
                       placeholder="Opsional">
            </div>

            <label class="toggle-row">
                <input type="checkbox" name="is_active" id="svc-active" value="1" checked>
                <span style="font-size: .85rem; font-weight: 600;">Layanan aktif</span>
            </label>

            <button type="submit" class="btn-submit" id="svcFormSubmit">Simpan Layanan</button>
        </form>
    </div>
</div>

<script>
(function() {
    var catStoreUrl = @json(route('admin.categories.store'));
    var catUpdateBase = @json(url('admin/categories'));
    var svcUpdateBase = @json(url('admin/services'));

    // ── Modal kategori ──
    var catModal = document.getElementById('catModal');
    var catForm = document.getElementById('catForm');
    var catTitle = document.getElementById('catModalTitle');
    var catSubmit = document.getElementById('catFormSubmit');
    var catMethod = document.getElementById('catFormMethod');

    function openCatCreate() {
        catTitle.textContent = 'Buat Kategori';
        catSubmit.textContent = 'Simpan Kategori';
        catForm.action = catStoreUrl;
        catMethod.value = '';
        document.getElementById('cat-name').value = '';
        document.getElementById('cat-pricing').value = 'per_kg';
        document.getElementById('cat-desc').value = '';
        document.getElementById('cat-active').checked = true;
        catModal.classList.add('is-open');
    }
    function openCatEdit(btn) {
        catTitle.textContent = 'Edit Kategori';
        catSubmit.textContent = 'Simpan Perubahan';
        catForm.action = catUpdateBase + '/' + btn.dataset.id;
        catMethod.value = 'PATCH';
        document.getElementById('cat-name').value = btn.dataset.name || '';
        document.getElementById('cat-pricing').value = btn.dataset.pricing || 'per_kg';
        document.getElementById('cat-desc').value = btn.dataset.description || '';
        document.getElementById('cat-active').checked = btn.dataset.active === '1';
        catModal.classList.add('is-open');
    }
    function closeCat() { catModal.classList.remove('is-open'); }

    document.querySelector('[data-cat-create]').addEventListener('click', openCatCreate);
    document.querySelectorAll('[data-cat-edit]').forEach(function(btn) {
        btn.addEventListener('click', function() { openCatEdit(btn); });
    });
    document.querySelector('[data-cat-close]').addEventListener('click', closeCat);
    catModal.addEventListener('click', function(e) { if (e.target === catModal) closeCat(); });

    // ── Modal layanan ──
    var svcModal = document.getElementById('svcModal');
    var svcForm = document.getElementById('svcForm');
    var svcTitle = document.getElementById('svcModalTitle');
    var svcSubmit = document.getElementById('svcFormSubmit');
    var svcMethod = document.getElementById('svcFormMethod');
    var svcContextTag = document.getElementById('svcContextTag');
    var svcPriceHint = document.getElementById('svcPriceHint');

    function setSvcPricingHint(pricing) {
        svcPriceHint.textContent = pricing === 'per_kg'
            ? 'Harga per kilogram (model kiloan).'
            : 'Harga per item (model satuan).';
    }

    function openSvcCreate(btn) {
        svcTitle.textContent = 'Tambah Layanan';
        svcSubmit.textContent = 'Simpan Layanan';
        svcForm.action = catUpdateBase + '/' + btn.dataset.categoryId + '/services';
        svcMethod.value = '';
        svcContextTag.textContent = 'Kategori: ' + btn.dataset.categoryName;
        setSvcPricingHint(btn.dataset.pricing);
        document.getElementById('svc-name').value = '';
        document.getElementById('svc-price').value = '';
        document.getElementById('svc-hours').value = '48';
        document.getElementById('svc-desc').value = '';
        document.getElementById('svc-active').checked = true;
        svcModal.classList.add('is-open');
    }
    function openSvcEdit(btn) {
        svcTitle.textContent = 'Edit Layanan';
        svcSubmit.textContent = 'Simpan Perubahan';
        svcForm.action = svcUpdateBase + '/' + btn.dataset.id;
        svcMethod.value = 'PATCH';
        svcContextTag.textContent = 'Kategori: ' + btn.dataset.categoryName;
        setSvcPricingHint(btn.dataset.pricing);
        document.getElementById('svc-name').value = btn.dataset.name || '';
        document.getElementById('svc-price').value = btn.dataset.unitPrice || '';
        document.getElementById('svc-hours').value = btn.dataset.estimatedHours || '48';
        document.getElementById('svc-desc').value = btn.dataset.description || '';
        document.getElementById('svc-active').checked = btn.dataset.active === '1';
        svcModal.classList.add('is-open');
    }
    function closeSvc() { svcModal.classList.remove('is-open'); }

    document.querySelectorAll('[data-svc-create]').forEach(function(btn) {
        btn.addEventListener('click', function() { openSvcCreate(btn); });
    });
    document.querySelectorAll('[data-svc-edit]').forEach(function(btn) {
        btn.addEventListener('click', function() { openSvcEdit(btn); });
    });
    document.querySelector('[data-svc-close]').addEventListener('click', closeSvc);
    svcModal.addEventListener('click', function(e) { if (e.target === svcModal) closeSvc(); });
})();
</script>
</body>
</html>
