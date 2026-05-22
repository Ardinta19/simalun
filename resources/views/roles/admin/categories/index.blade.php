<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    margin-bottom: 12px;
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
</style>
</head>
<body>
@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])

<div class="page-header">
    <x-back-button fallback="admin.dashboard" style="hero" />
    <div class="page-header__title">Kategori Layanan</div>
    <button type="button" class="page-header__action" data-cat-create>+ Buat</button>
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
    </div>
    @empty
    <div class="empty">
        <div class="empty__icon">📂</div>
        <div>Belum ada kategori. Klik "+ Buat" untuk tambah kategori pertama.</div>
    </div>
    @endforelse
</div>

{{-- Modal Tambah / Edit --}}
<div class="modal-overlay" id="catModal" data-cat-overlay>
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
                <span class="form-row__hint">Dipakai walk-in form buat filter layanan kiloan vs satuan.</span>
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

<script>
(function() {
    var storeUrl = @json(route('admin.categories.store'));
    var updateBase = @json(url('admin/categories'));

    var modal = document.getElementById('catModal');
    var form = document.getElementById('catForm');
    var title = document.getElementById('catModalTitle');
    var submit = document.getElementById('catFormSubmit');
    var methodInput = document.getElementById('catFormMethod');

    function openCreate() {
        title.textContent = 'Buat Kategori';
        submit.textContent = 'Simpan Kategori';
        form.action = storeUrl;
        methodInput.value = '';

        document.getElementById('cat-name').value = '';
        document.getElementById('cat-pricing').value = 'per_kg';
        document.getElementById('cat-desc').value = '';
        document.getElementById('cat-active').checked = true;

        modal.classList.add('is-open');
    }

    function openEdit(btn) {
        title.textContent = 'Edit Kategori';
        submit.textContent = 'Simpan Perubahan';
        form.action = updateBase + '/' + btn.dataset.id;
        methodInput.value = 'PATCH';

        document.getElementById('cat-name').value = btn.dataset.name || '';
        document.getElementById('cat-pricing').value = btn.dataset.pricing || 'per_kg';
        document.getElementById('cat-desc').value = btn.dataset.description || '';
        document.getElementById('cat-active').checked = btn.dataset.active === '1';

        modal.classList.add('is-open');
    }

    function close() {
        modal.classList.remove('is-open');
    }

    document.querySelector('[data-cat-create]').addEventListener('click', openCreate);
    document.querySelectorAll('[data-cat-edit]').forEach(function(btn) {
        btn.addEventListener('click', function() { openEdit(btn); });
    });
    document.querySelector('[data-cat-close]').addEventListener('click', close);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) close();
    });
})();
</script>
</body>
</html>
