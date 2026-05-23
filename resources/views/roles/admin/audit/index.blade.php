<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Audit Trail – Azka Laundry</title>
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
    --orange: #c2410c;
    --orange-lt: #fff7ed;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Plus Jakarta Sans', sans-serif;
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
.container { padding: 16px; max-width: 720px; margin: 0 auto; }

.filter-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 14px;
}
.filter-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}
.filter-grid--full {
    grid-template-columns: 1fr;
    margin-top: 10px;
}
.filter-label {
    font-size: .68rem;
    font-weight: 700;
    color: var(--ink-mid);
    margin-bottom: 4px;
    display: block;
    text-transform: uppercase;
    letter-spacing: .4px;
}
.filter-input,
.filter-select {
    width: 100%;
    padding: 9px 11px;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-family: inherit;
    font-size: .82rem;
    background: #fff;
}
.filter-actions {
    display: flex;
    gap: 8px;
    margin-top: 10px;
}
.btn {
    padding: 9px 14px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 700;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-family: inherit;
}
.btn--primary { background: var(--blue); color: #fff; }
.btn--ghost { background: var(--blue-lt); color: var(--blue-dark); }

.action-tags {
    display: flex; flex-wrap: wrap; gap: 6px;
    margin-bottom: 14px;
}
.action-tag {
    background: #fff;
    border: 1px solid var(--border);
    color: var(--ink-mid);
    padding: 5px 10px;
    border-radius: 99px;
    font-size: .68rem;
    font-weight: 700;
    text-decoration: none;
}
.action-tag--active {
    background: var(--blue);
    color: #fff;
    border-color: var(--blue);
}
.action-tag__count {
    opacity: .7;
    margin-left: 4px;
}

.log-row {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 8px;
}
.log-row__head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
    margin-bottom: 4px;
}
.log-row__action {
    font-size: .78rem;
    font-weight: 800;
    color: var(--blue-dark);
    font-family: 'Courier New', monospace;
    background: var(--blue-lt);
    padding: 3px 8px;
    border-radius: 6px;
}
.log-row__time {
    font-size: .68rem;
    color: var(--ink-lt);
    font-weight: 600;
    flex-shrink: 0;
}
.log-row__summary {
    font-size: .85rem;
    color: var(--ink);
    margin-bottom: 6px;
    line-height: 1.4;
}
.log-row__meta {
    font-size: .7rem;
    color: var(--ink-lt);
    font-weight: 600;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.log-row__meta b { color: var(--ink-mid); font-weight: 700; }

.log-diff {
    margin-top: 8px;
    padding: 8px 10px;
    background: #f8fafc;
    border-radius: 6px;
    font-size: .72rem;
    font-family: 'Courier New', monospace;
    overflow-x: auto;
}
.log-diff__row { display: flex; gap: 8px; margin-bottom: 2px; }
.log-diff__key { color: var(--ink-lt); font-weight: 700; min-width: 110px; }
.log-diff__before { color: var(--red); }
.log-diff__after { color: var(--green); font-weight: 700; }
.log-diff__arrow { color: var(--ink-lt); }

.empty {
    text-align: center;
    padding: 50px 20px;
    color: var(--ink-lt);
}
.empty__icon { font-size: 3rem; margin-bottom: 10px; }

.pagination-wrap { margin-top: 14px; display: flex; justify-content: center; }
.pagination-wrap nav { display: flex; gap: 6px; }
.pagination-wrap a, .pagination-wrap span {
    padding: 6px 11px; border-radius: 6px;
    font-size: .8rem; font-weight: 700;
    background: #fff; border: 1px solid var(--border);
    color: var(--ink-mid); text-decoration: none;
}
.pagination-wrap span[aria-current="page"] { background: var(--blue); color: #fff; border-color: var(--blue); }
</style>
</head>
<body>
@include('layouts.component.admin._navbar_admin', ['active' => 'beranda'])

<div class="page-header">
    <x-back-button fallback="admin.dashboard" style="hero" />
    <div class="page-header__title">Audit Trail</div>
</div>

<div class="container">

    {{-- Quick filter per action --}}
    @if($actionGroups->isNotEmpty())
    <div class="action-tags">
        <a href="{{ route('admin.audit.index') }}"
           class="action-tag {{ ! request('action') ? 'action-tag--active' : '' }}">
            Semua <span class="action-tag__count">({{ $logs->total() }})</span>
        </a>
        @foreach($actionGroups as $group)
            @php $prefix = explode('.', $group->action)[0]; @endphp
            @if($loop->first || $group->action !== $loop->parent[$loop->index - 1]->action)
            <a href="{{ route('admin.audit.index', ['action' => $prefix]) }}"
               class="action-tag {{ request('action') === $prefix ? 'action-tag--active' : '' }}">
                {{ $prefix }}<span class="action-tag__count">({{ $group->total }})</span>
            </a>
            @endif
        @endforeach
    </div>
    @endif

    {{-- Filter form --}}
    <form method="GET" class="filter-card">
        <div class="filter-grid">
            <div>
                <label class="filter-label" for="actor">Aktor</label>
                <select name="actor_id" id="actor" class="filter-select">
                    <option value="">Semua aktor</option>
                    @foreach($actors as $actor)
                    <option value="{{ $actor->id }}" {{ (string) request('actor_id') === (string) $actor->id ? 'selected' : '' }}>
                        {{ $actor->name }} ({{ $actor->role }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="filter-label" for="action-filter">Action</label>
                <input type="text" name="action" id="action-filter" class="filter-input"
                       value="{{ request('action') }}"
                       placeholder="contoh: voucher, order.">
            </div>
            <div>
                <label class="filter-label" for="date-from">Dari Tanggal</label>
                <input type="date" name="date_from" id="date-from" class="filter-input" value="{{ request('date_from') }}">
            </div>
            <div>
                <label class="filter-label" for="date-to">Sampai Tanggal</label>
                <input type="date" name="date_to" id="date-to" class="filter-input" value="{{ request('date_to') }}">
            </div>
        </div>
        <div class="filter-actions">
            <button type="submit" class="btn btn--primary">Terapkan</button>
            <a href="{{ route('admin.audit.index') }}" class="btn btn--ghost">Reset</a>
        </div>
    </form>

    {{-- List --}}
    @forelse($logs as $log)
    <div class="log-row">
        <div class="log-row__head">
            <span class="log-row__action">{{ $log->action }}</span>
            <span class="log-row__time">{{ $log->created_at->format('d/m/Y H:i') }}</span>
        </div>

        @if($log->summary)
        <div class="log-row__summary">{{ $log->summary }}</div>
        @endif

        <div class="log-row__meta">
            <span><b>Aktor:</b> {{ $log->actor?->name ?? 'Sistem' }}</span>
            @if($log->ip)
            <span><b>IP:</b> {{ $log->ip }}</span>
            @endif
            @if($log->auditable_type)
            <span><b>Target:</b> {{ class_basename($log->auditable_type) }}#{{ $log->auditable_id }}</span>
            @endif
        </div>

        @if($log->before || $log->after)
        <div class="log-diff">
            @php
                $beforeArr = is_array($log->before) ? $log->before : [];
                $afterArr  = is_array($log->after) ? $log->after : [];
                $keys = array_unique(array_merge(array_keys($beforeArr), array_keys($afterArr)));
            @endphp
            @foreach($keys as $key)
                @php
                    $b = $beforeArr[$key] ?? null;
                    $a = $afterArr[$key] ?? null;
                    if (is_array($b)) $b = json_encode($b);
                    if (is_array($a)) $a = json_encode($a);
                @endphp
                @if($b !== $a)
                <div class="log-diff__row">
                    <span class="log-diff__key">{{ $key }}:</span>
                    @if($b !== null)
                        <span class="log-diff__before">{{ Str::limit((string) $b, 60) }}</span>
                        <span class="log-diff__arrow">→</span>
                    @endif
                    @if($a !== null)
                        <span class="log-diff__after">{{ Str::limit((string) $a, 60) }}</span>
                    @else
                        <span class="log-diff__after">(dihapus)</span>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
        @endif
    </div>
    @empty
    <div class="empty">
        <div class="empty__icon">📋</div>
        <div>Belum ada catatan audit yang cocok.</div>
        @if(request()->hasAny(['actor_id','action','date_from','date_to']))
            <a href="{{ route('admin.audit.index') }}" class="btn btn--ghost" style="margin-top:14px;display:inline-block;">Reset Filter</a>
        @endif
    </div>
    @endforelse

    @if($logs->hasPages())
    <div class="pagination-wrap">
        {{ $logs->links() }}
    </div>
    @endif

</div>
</body>
</html>
