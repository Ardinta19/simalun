{{--
    Rating & ulasan setelah pesanan selesai.

    Dua mode:
      1. Sudah pernah rating  → tampil read-only (bintang + komentar) sebagai
         pengingat ke customer apa yang dia tulis dulu.
      2. Belum rating         → form: 5 bintang radio + textarea opsional.
                                Submit ke route('customer.order.rating').
--}}
<style>
.rating-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 18px 18px 16px;
    margin-bottom: 14px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, .04);
}
.rating-card__head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.rating-card__icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #fff7ed;
    color: #f59e0b;
    display: grid;
    place-items: center;
    font-size: 1.1rem;
}
.rating-card__title {
    font-weight: 800;
    font-size: .95rem;
    color: var(--ink, #1a1a2e);
}
.rating-card__sub {
    font-size: .72rem;
    color: var(--ink-lt, #8896a6);
    margin-top: 1px;
}
.rating-stars {
    display: flex;
    gap: 6px;
    margin: 6px 0 12px;
}
.rating-stars input { display: none; }
.rating-stars label {
    cursor: pointer;
    font-size: 1.8rem;
    color: #cbd5e1;
    transition: color 80ms ease, transform 120ms ease;
    line-height: 1;
}
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #cbd5e1;
}
/* hover mengisi dari kanan ke kiri pakai trik direction:rtl */
.rating-stars { direction: rtl; justify-content: flex-end; }
.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #f59e0b;
    transform: scale(1.05);
}
.rating-textarea {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    font-family: inherit;
    font-size: .85rem;
    resize: vertical;
    min-height: 70px;
    background: #f8fafc;
}
.rating-textarea:focus {
    outline: none;
    border-color: #0b5394;
    background: #fff;
}
.rating-submit {
    width: 100%;
    border: none;
    background: #0b5394;
    color: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    font-weight: 700;
    font-size: .9rem;
    cursor: pointer;
    transition: background 120ms ease, transform 120ms ease;
}
.rating-submit:hover { background: #093e6e; }
.rating-submit:active { transform: scale(.98); }

.rating-existing {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 1.4rem;
    line-height: 1;
}
.rating-existing__star { color: #f59e0b; }
.rating-existing__star--off { color: #e2e8f0; }
.rating-existing__comment {
    margin-top: 10px;
    padding: 10px 12px;
    background: #f8fafc;
    border-radius: 10px;
    font-size: .82rem;
    color: var(--ink-mid, #4a5568);
    line-height: 1.5;
}
.rating-existing__date {
    margin-top: 6px;
    font-size: .68rem;
    color: var(--ink-lt, #8896a6);
}

.rating-error {
    margin-top: 6px;
    font-size: .75rem;
    color: #dc2626;
}
.rating-status {
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 10px;
    font-size: .78rem;
    font-weight: 600;
}
.rating-status--ok { background: #ecfdf5; color: #047857; }
.rating-status--err { background: #fef2f2; color: #b91c1c; }
</style>

<div class="rating-card js-section">
    @if(session('success'))
        <div class="rating-status rating-status--ok">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rating-status rating-status--err">{{ session('error') }}</div>
    @endif

    @if($order->rating)
        {{-- Sudah pernah rating: read-only --}}
        <div class="rating-card__head">
            <div class="rating-card__icon">⭐</div>
            <div>
                <div class="rating-card__title">Rating Kamu</div>
                <div class="rating-card__sub">Terima kasih sudah memberi ulasan.</div>
            </div>
        </div>

        <div class="rating-existing">
            @for($i = 1; $i <= 5; $i++)
                <span class="rating-existing__star {{ $i <= $order->rating->rating ? '' : 'rating-existing__star--off' }}">★</span>
            @endfor
        </div>

        @if($order->rating->comment)
            <div class="rating-existing__comment">"{{ $order->rating->comment }}"</div>
        @endif

        <div class="rating-existing__date">
            Dikirim {{ $order->rating->created_at->translatedFormat('d M Y, H:i') }}
        </div>
    @else
        {{-- Belum rating: form input --}}
        <div class="rating-card__head">
            <div class="rating-card__icon">⭐</div>
            <div>
                <div class="rating-card__title">Bagaimana pengalamanmu?</div>
                <div class="rating-card__sub">Beri rating untuk pesanan #{{ $order->order_code }}.</div>
            </div>
        </div>

        <form method="POST" action="{{ route('customer.order.rating', $order) }}">
            @csrf

            {{--
              Bintang pakai radio + label.
              Urutan 5→1 di markup karena CSS pakai direction:rtl supaya hover
              "mengisi" dari kiri ke kanan secara visual.
            --}}
            <div class="rating-stars">
                @foreach([5, 4, 3, 2, 1] as $val)
                    <input type="radio" name="rating" id="rating-{{ $val }}" value="{{ $val }}"
                           {{ old('rating') == $val ? 'checked' : '' }}>
                    <label for="rating-{{ $val }}" title="{{ $val }} bintang">★</label>
                @endforeach
            </div>
            @error('rating')
                <div class="rating-error">{{ $message }}</div>
            @enderror

            <textarea name="comment" class="rating-textarea" maxlength="500"
                      placeholder="Cerita sedikit pengalamanmu (opsional)...">{{ old('comment') }}</textarea>
            @error('comment')
                <div class="rating-error">{{ $message }}</div>
            @enderror

            <button type="submit" class="rating-submit" style="margin-top: 12px;">
                Kirim Ulasan
            </button>
        </form>
    @endif
</div>
