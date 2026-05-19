<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<title>Tambah Pelanggan Walk-in – Azka Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root{--blue-dark:#002f5c;--blue-mid:#0077b6;--blue-light:#00b4d8;--surface:#f4f8fc;--card:#fff;--border:#ddeeff;--ink:#1a2332;--ink-lt:#8899aa;--orange:#FF6B35;--green:#00C48C;--red:#ef4444;--radius:16px;}
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent;}
body{font-family:'Nunito',sans-serif;background:var(--surface);color:var(--ink);min-height:100vh;}
.top-header{background:linear-gradient(145deg,var(--blue-dark) 0%,var(--blue-mid) 60%,var(--blue-light) 100%);position:relative;overflow:hidden;}
.header-inner{position:relative;z-index:1;padding:max(env(safe-area-inset-top,0px),20px) 20px 20px;max-width:520px;margin:0 auto;}
.header-top{display:flex;align-items:center;gap:12px;}
.back-btn{width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;font-size:1.1rem;flex-shrink:0;}
.header-title{font-family:'Fredoka One',cursive;font-size:1.2rem;color:#fff;flex:1;}
.header-sub{font-size:.72rem;font-weight:800;color:rgba(255,255,255,.75);letter-spacing:.5px;}
.header-wave{display:block;width:100%;margin-bottom:-2px;}
.page-body{max-width:520px;margin:0 auto;padding:16px;}
.card{background:#fff;border:1.5px solid var(--border);border-radius:14px;padding:12px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;}
.input{height:38px;padding:0 10px;border:1.5px solid var(--border);border-radius:10px;width:100%;font-family:'Nunito',sans-serif;font-weight:700;color:var(--ink);}
.input::placeholder{color:var(--ink-lt)}
.input-full{grid-column:1/3;}
.group{grid-column:1/3;border:1.5px solid var(--border);border-radius:10px;padding:10px;}
.group-head{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:8px;}
.group-title{font-size:.72rem;font-weight:800;color:#6b7280;}
.group-hint{font-size:.68rem;font-weight:800;color:#94a3b8;}
.items-wrap{display:grid;gap:7px;}
.item-row{display:grid;grid-template-columns:1fr 76px 40px;gap:6px;align-items:center;}
.item-remove{height:36px;border:1.5px solid #fecaca;background:#fff5f5;color:#b91c1c;border-radius:8px;font-weight:900;cursor:pointer;}
.item-remove:disabled{opacity:.45;cursor:not-allowed;}
.btn-add-item{height:34px;padding:0 10px;border:1.5px dashed rgba(0,119,182,.4);background:#f8fbff;color:#0369a1;border-radius:9px;font-weight:900;cursor:pointer;font-size:.74rem;}
.btn-main{height:38px;padding:0 12px;border:0;border-radius:10px;background:var(--orange);color:#fff;font-weight:800;grid-column:1/3;cursor:pointer;}
.alert-ok{margin-bottom:10px;background:rgba(0,196,140,.12);border:1.5px solid rgba(0,196,140,.26);color:#087f5b;padding:10px 12px;border-radius:12px;font-size:.8rem;font-weight:800;}
.alert-err{margin-bottom:10px;background:rgba(239,68,68,.1);border:1.5px solid rgba(239,68,68,.25);color:#b91c1c;padding:10px 12px;border-radius:12px;font-size:.78rem;font-weight:800;}
</style>
</head>
<body>
<div class="top-header">
  <div class="header-inner">
    <div class="header-top">
      <a href="{{ route('dashboard.admin') }}" class="back-btn" data-smart-back aria-label="Kembali">‹</a>
      <div style="flex:1">
        <div class="header-title">Tambah Pelanggan</div>
        <div class="header-sub">FORM ORDER WALK-IN</div>
      </div>
    </div>
  </div>
  <svg class="header-wave" viewBox="0 0 1440 28" preserveAspectRatio="none" style="height:28px;"><path fill="#f4f8fc" d="M0,14 C240,28 480,0 720,14 C960,28 1200,0 1440,14 L1440,28 L0,28Z"/></svg>
</div>

<div class="page-body">
  @if(session('status'))
    <div class="alert-ok">{{ session('status') }}</div>
  @endif

  @if($errors->any())
    <div class="alert-err">{{ $errors->first() }}</div>
  @endif

  <div class="card">
    <form method="POST" action="{{ route('admin.orders.walk-in.store') }}" class="form-grid">
      @csrf
      <input name="customer_name" type="text" placeholder="Nama customer" required class="input input-full">
      <input name="customer_phone" type="text" placeholder="No HP (opsional)" class="input input-full">

      <select name="service_id" id="walkin-service-main" required class="input input-full">
        <option value="">Pilih layanan</option>
        @foreach(($daftarLayanan ?? collect()) as $layanan)
          @if(($layanan->pricing_model ?? 'per_kg') === 'per_kg')
            <option value="{{ $layanan->id }}" data-category-id="{{ $layanan->service_category_id }}">{{ $layanan->name }}</option>
          @endif
        @endforeach
      </select>

      <input name="weight_estimate" type="number" min="0.5" max="50" step="0.1" placeholder="Berat (kg)" required class="input">
      <select name="pickup_time" required class="input" aria-label="Slot Proses" title="Slot Proses">
        <option value="pagi">Slot Proses: Pagi</option>
        <option value="siang">Slot Proses: Siang</option>
        <option value="sore">Slot Proses: Sore</option>
      </select>

      <input name="notes" type="text" placeholder="Catatan" class="input input-full">

      <div class="group">
        <div class="group-head">
          <div class="group-title">Item Satuan (opsional)</div>
          <div class="group-hint">Tambah sesuai kebutuhan</div>
        </div>

        <div id="walkin-items-wrap" class="items-wrap"></div>

        <button type="button" id="walkin-add-item" class="btn-add-item">+ Tambah Item Satuan</button>

        <template id="walkin-item-template">
          <div class="item-row" data-item-row>
            <select class="input" data-item-service>
              <option value="">Pilih item layanan</option>
              @foreach(($daftarLayananItem ?? collect()) as $layananItem)
                <option value="{{ $layananItem->id }}" data-category-id="{{ $layananItem->service_category_id }}">{{ $layananItem->name }}</option>
              @endforeach
            </select>
            <input type="number" min="1" max="999" placeholder="Qty" class="input" data-item-qty>
            <button type="button" class="item-remove" data-item-remove>×</button>
          </div>
        </template>
      </div>

      <button type="submit" class="btn-main">Buat Order Walk-in</button>
    </form>
  </div>
</div>

<script>
(function () {
  const mainSelect = document.getElementById('walkin-service-main');
  const itemsWrap = document.getElementById('walkin-items-wrap');
  const addItemBtn = document.getElementById('walkin-add-item');
  const itemTemplate = document.getElementById('walkin-item-template');
  if (!mainSelect || !itemsWrap || !addItemBtn || !itemTemplate) return;

  function sinkronKategoriItem() {
    const selected = mainSelect.options[mainSelect.selectedIndex];
    const categoryId = selected ? (selected.getAttribute('data-category-id') || '') : '';
    const itemSelects = Array.from(itemsWrap.querySelectorAll('[data-item-service]'));

    itemSelects.forEach((selectEl) => {
      const options = Array.from(selectEl.options);
      options.forEach((opt, idx) => {
        if (idx === 0) {
          opt.hidden = false;
          return;
        }
        const optCat = opt.getAttribute('data-category-id') || '';
        const show = !categoryId || (String(optCat) === String(categoryId));
        opt.hidden = !show;
        if (!show && opt.selected) {
          selectEl.selectedIndex = 0;
        }
      });
    });
  }

  function reindexItemNames() {
    const rows = Array.from(itemsWrap.querySelectorAll('[data-item-row]'));
    rows.forEach((row, i) => {
      const service = row.querySelector('[data-item-service]');
      const qty = row.querySelector('[data-item-qty]');
      if (service) service.name = `item_lines[${i}][service_id]`;
      if (qty) qty.name = `item_lines[${i}][qty]`;
    });

    const removeButtons = rows.map((row) => row.querySelector('[data-item-remove]')).filter(Boolean);
    removeButtons.forEach((btn) => {
      btn.disabled = rows.length <= 1;
    });
  }

  function applyCategoryFilter() {
    const categoryId = categorySelect.value;
    filterSelectOptions(mainSelect, categoryId);
    const itemSelects = Array.from(itemsWrap.querySelectorAll('[data-item-service]'));
    itemSelects.forEach((sel) => filterSelectOptions(sel, categoryId));
  }

  function addItemRow() {
    const node = itemTemplate.content.cloneNode(true);
    const row = node.querySelector('[data-item-row]');
    const removeBtn = node.querySelector('[data-item-remove]');
    removeBtn.addEventListener('click', function () {
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
