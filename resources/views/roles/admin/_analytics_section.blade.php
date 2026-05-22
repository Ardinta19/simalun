{{--
    Section "Analitik 30 Hari" untuk dashboard admin.

    Berisi 4 widget yang membantu admin baca tren tanpa buka halaman lain:
      - Top 5 layanan (bar visual sederhana)
      - Distribusi jam pickup (pagi / siang / sore)
      - Top 5 customer aktif
      - Rating rata-rata + ulasan terbaru
      - Counter voucher aktif

    Variabel yang dibutuhkan dari controller:
      $topServices, $pickupBuckets, $topCustomers,
      $ratingStats, $ulasanTerbaru, $voucherAktif
--}}

@php
    $maxServiceOrder = $topServices->max('total_order') ?: 1;
    $maxPickup = max($pickupBuckets) ?: 1;

    $pickupLabels = [
        'pagi' => 'Pagi',
        'siang' => 'Siang',
        'sore' => 'Sore',
    ];

    $avgRating = $ratingStats?->avg_rating ? (float) $ratingStats->avg_rating : null;
    $totalRating = $ratingStats?->total_rating ?? 0;
@endphp

<style>
.analytics-grid {
    display: grid;
    gap: 12px;
    margin-top: 6px;
}
.analytics-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: 16px;
    box-shadow: var(--shadow);
}
.analytics-card__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.analytics-card__title {
    font-weight: 800;
    font-size: 0.92rem;
    color: var(--ink);
}
.analytics-card__hint {
    font-size: 0.66rem;
    font-weight: 700;
    color: var(--ink-lt);
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.analytics-empty {
    padding: 18px 8px;
    text-align: center;
    color: var(--ink-lt);
    font-size: 0.78rem;
}

/* ── Bar list ─────────────────────────── */
.bar-row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 4px 10px;
    align-items: baseline;
    margin-bottom: 10px;
}
.bar-row:last-child { margin-bottom: 2px; }
.bar-row__label {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.bar-row__value {
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--ink-mid);
}
.bar-row__track {
    grid-column: 1 / -1;
    height: 8px;
    background: var(--surface);
    border-radius: 4px;
    overflow: hidden;
}
.bar-row__fill {
    height: 100%;
    background: linear-gradient(90deg, var(--blue) 0%, #38bdf8 100%);
    border-radius: 4px;
}

/* ── Pickup distribution: 3 batang vertikal ── */
.pickup-bars {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 14px;
    align-items: end;
    height: 110px;
}
.pickup-bar {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    height: 100%;
}
.pickup-bar__column {
    width: 100%;
    background: linear-gradient(180deg, var(--teal) 0%, #2dd4bf 100%);
    border-radius: 8px 8px 0 0;
    min-height: 4px;
    transition: height 280ms ease;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-top: 4px;
}
.pickup-bar__count {
    font-size: 0.7rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
}
.pickup-bar__label {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--ink-mid);
}

/* ── Customer list ─────────────────────── */
.cust-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px dashed var(--border);
}
.cust-row:last-child { border-bottom: none; }
.cust-row__avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--blue-lt);
    color: var(--blue-dark);
    font-weight: 800;
    font-size: 0.8rem;
    display: grid;
    place-items: center;
    flex-shrink: 0;
}
.cust-row__info { flex: 1; min-width: 0; }
.cust-row__name {
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--ink);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.cust-row__phone {
    font-size: 0.7rem;
    color: var(--ink-lt);
}
.cust-row__count {
    text-align: right;
    flex-shrink: 0;
}
.cust-row__count-num {
    font-size: 0.86rem;
    font-weight: 800;
    color: var(--blue-dark);
}
.cust-row__count-label {
    font-size: 0.66rem;
    color: var(--ink-lt);
    font-weight: 600;
}

/* ── Rating block ─────────────────────── */
.rating-summary {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 12px;
    border-bottom: 1px dashed var(--border);
    margin-bottom: 12px;
}
.rating-summary__avg {
    font-size: 2rem;
    font-weight: 800;
    color: var(--orange);
    line-height: 1;
}
.rating-summary__stars {
    color: var(--orange);
    font-size: 1rem;
    line-height: 1;
}
.rating-summary__total {
    font-size: 0.72rem;
    color: var(--ink-mid);
    margin-top: 2px;
}
.review-item {
    padding: 6px 0;
}
.review-item + .review-item {
    border-top: 1px dashed var(--border);
    padding-top: 10px;
    margin-top: 8px;
}
.review-item__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 4px;
}
.review-item__name {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--ink);
}
.review-item__rate {
    color: var(--orange);
    font-size: 0.85rem;
    letter-spacing: 1px;
}
.review-item__comment {
    font-size: 0.76rem;
    color: var(--ink-mid);
    line-height: 1.4;
}
.review-item__order {
    font-size: 0.64rem;
    color: var(--ink-lt);
    margin-top: 4px;
    font-family: 'Courier New', monospace;
}

/* ── Voucher pill ─────────────────────── */
.voucher-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 10px;
    background: #fff7ed;
    color: #c2410c;
    border-radius: 999px;
    font-size: 0.74rem;
    font-weight: 800;
}
</style>

<section class="orders-section js-in" style="margin-top: 18px;">
    <div class="section__head" style="display:flex; align-items:center; justify-content:space-between;">
        <span class="section__title">Analitik 30 Hari</span>
        @if($voucherAktif > 0)
            <span class="voucher-pill">🎟️ {{ $voucherAktif }} voucher aktif</span>
        @endif
    </div>

    <div class="analytics-grid">
        {{-- Top Layanan --}}
        <div class="analytics-card">
            <div class="analytics-card__head">
                <span class="analytics-card__title">Layanan Terlaris</span>
                <span class="analytics-card__hint">Top 5</span>
            </div>
            @forelse($topServices as $svc)
                <div class="bar-row">
                    <span class="bar-row__label">{{ $svc->service?->name ?? 'Layanan dihapus' }}</span>
                    <span class="bar-row__value">{{ $svc->total_order }} pesanan</span>
                    <div class="bar-row__track">
                        <div class="bar-row__fill" style="width: {{ ($svc->total_order / $maxServiceOrder) * 100 }}%;"></div>
                    </div>
                </div>
            @empty
                <div class="analytics-empty">Belum ada pesanan dalam 30 hari terakhir.</div>
            @endforelse
        </div>

        {{-- Distribusi Jam Pickup --}}
        <div class="analytics-card">
            <div class="analytics-card__head">
                <span class="analytics-card__title">Jam Pickup Terpadat</span>
                <span class="analytics-card__hint">Bantu jadwal kurir</span>
            </div>
            @if(array_sum($pickupBuckets) > 0)
                <div class="pickup-bars">
                    @foreach($pickupBuckets as $slot => $count)
                        <div class="pickup-bar">
                            <div class="pickup-bar__column" style="height: {{ ($count / $maxPickup) * 95 }}%;">
                                @if($count > 0)
                                    <span class="pickup-bar__count">{{ $count }}</span>
                                @endif
                            </div>
                            <span class="pickup-bar__label">{{ $pickupLabels[$slot] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="analytics-empty">Belum ada data pickup.</div>
            @endif
        </div>

        {{-- Top Customer --}}
        <div class="analytics-card">
            <div class="analytics-card__head">
                <span class="analytics-card__title">Customer Paling Aktif</span>
                <span class="analytics-card__hint">Top 5</span>
            </div>
            @forelse($topCustomers as $row)
                @php
                    $name = $row->customer?->name ?? 'Customer dihapus';
                    $initial = mb_strtoupper(mb_substr($name, 0, 1));
                @endphp
                <div class="cust-row">
                    <div class="cust-row__avatar">{{ $initial }}</div>
                    <div class="cust-row__info">
                        <div class="cust-row__name">{{ $name }}</div>
                        <div class="cust-row__phone">{{ $row->customer?->phone ?? '-' }}</div>
                    </div>
                    <div class="cust-row__count">
                        <div class="cust-row__count-num">{{ $row->total_order }}</div>
                        <div class="cust-row__count-label">pesanan</div>
                    </div>
                </div>
            @empty
                <div class="analytics-empty">Belum ada customer aktif dalam 30 hari terakhir.</div>
            @endforelse
        </div>

        {{-- Rating & Ulasan --}}
        <div class="analytics-card">
            <div class="analytics-card__head">
                <span class="analytics-card__title">Kepuasan Pelanggan</span>
                <span class="analytics-card__hint">Dari rating customer</span>
            </div>

            @if($avgRating !== null)
                <div class="rating-summary">
                    <div class="rating-summary__avg">{{ number_format($avgRating, 1) }}</div>
                    <div>
                        <div class="rating-summary__stars">
                            @for($i = 1; $i <= 5; $i++)
                                {!! $i <= round($avgRating) ? '★' : '<span style="color:var(--border);">★</span>' !!}
                            @endfor
                        </div>
                        <div class="rating-summary__total">dari {{ $totalRating }} ulasan</div>
                    </div>
                </div>

                @forelse($ulasanTerbaru as $u)
                    <div class="review-item">
                        <div class="review-item__head">
                            <span class="review-item__name">{{ $u->customer?->name ?? 'Customer' }}</span>
                            <span class="review-item__rate">
                                @for($i = 1; $i <= 5; $i++){{ $i <= $u->rating ? '★' : '☆' }}@endfor
                            </span>
                        </div>
                        @if($u->comment)
                            <div class="review-item__comment">"{{ $u->comment }}"</div>
                        @endif
                        <div class="review-item__order">#{{ $u->order?->order_code }}</div>
                    </div>
                @empty
                @endforelse
            @else
                <div class="analytics-empty">Belum ada rating yang masuk.</div>
            @endif
        </div>
    </div>
</section>
