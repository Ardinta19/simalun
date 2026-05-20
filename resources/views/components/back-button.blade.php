{{--
    Smart Back Button Component
    Usage:
        <x-back-button fallback="customer.orders" />
        <x-back-button fallback="admin.orders" style="hero" />
        <x-back-button fallback="driver.orders" style="text" label="Kembali ke Pesanan" />
--}}

@if($style === 'hero')
{{-- Tombol bulat transparan putih — untuk di atas hero/gradient header --}}
<a href="{{ $url }}" class="back-btn back-btn--hero" aria-label="{{ $label }}">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
</a>

@elseif($style === 'text')
{{-- Link text biasa --}}
<a href="{{ $url }}" class="back-btn back-btn--text">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
    <span>{{ $label }}</span>
</a>

@else
{{-- Default: tombol solid kecil --}}
<a href="{{ $url }}" class="back-btn back-btn--default" aria-label="{{ $label }}">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="15 18 9 12 15 6"/>
    </svg>
    <span>{{ $label }}</span>
</a>
@endif
