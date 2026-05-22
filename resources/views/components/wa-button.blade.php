{{--
    Tombol "Hubungi via WhatsApp"

    Props:
        phone   — string nomor (akan dinormalisasi). Wajib.
        message — string pesan pre-fill (opsional).
        label   — teks tombol (default "Hubungi via WA").
        variant — "inline" (kecil, untuk list) atau "block" (besar, untuk detail).
                  Default "inline".

    Kalau nomor kosong / invalid komponen ini tidak akan render apapun
    supaya halaman tidak menampilkan tombol mati.
--}}

@props([
    'phone' => null,
    'message' => '',
    'label' => 'Hubungi via WA',
    'variant' => 'inline',
])

@php
    $waUrl = \App\Support\WhatsApp::link($phone, $message);
@endphp

@if ($waUrl)
    @if ($variant === 'block')
        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
           class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-2">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M19.05 4.91A10 10 0 0 0 12 2a10 10 0 0 0-8.5 15.18L2 22l4.93-1.46A10 10 0 0 0 22 12a10 10 0 0 0-2.95-7.09Zm-7.05 15.4a8.25 8.25 0 0 1-4.2-1.16l-.3-.18-2.93.86.87-2.85-.2-.31a8.27 8.27 0 1 1 6.76 3.64Zm4.52-6.18c-.25-.13-1.47-.72-1.7-.81-.23-.09-.4-.13-.56.13-.16.25-.65.81-.79.97-.15.16-.3.18-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.48-1.39-1.73-.15-.25-.02-.39.11-.51.11-.11.25-.3.38-.45.13-.15.16-.25.25-.42.08-.16.04-.31-.02-.44-.06-.13-.56-1.34-.77-1.84-.2-.49-.41-.42-.56-.43h-.48c-.16 0-.42.06-.65.31-.22.25-.85.83-.85 2.03 0 1.2.87 2.36.99 2.52.13.16 1.71 2.62 4.16 3.67.58.25 1.04.4 1.39.51.59.19 1.12.16 1.55.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.28Z"/>
            </svg>
            <span>{{ $label }}</span>
        </a>
    @else
        <a href="{{ $waUrl }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100 hover:text-emerald-800">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M19.05 4.91A10 10 0 0 0 12 2a10 10 0 0 0-8.5 15.18L2 22l4.93-1.46A10 10 0 0 0 22 12a10 10 0 0 0-2.95-7.09Zm-7.05 15.4a8.25 8.25 0 0 1-4.2-1.16l-.3-.18-2.93.86.87-2.85-.2-.31a8.27 8.27 0 1 1 6.76 3.64Zm4.52-6.18c-.25-.13-1.47-.72-1.7-.81-.23-.09-.4-.13-.56.13-.16.25-.65.81-.79.97-.15.16-.3.18-.55.06-.25-.13-1.05-.39-2-1.24-.74-.66-1.24-1.48-1.39-1.73-.15-.25-.02-.39.11-.51.11-.11.25-.3.38-.45.13-.15.16-.25.25-.42.08-.16.04-.31-.02-.44-.06-.13-.56-1.34-.77-1.84-.2-.49-.41-.42-.56-.43h-.48c-.16 0-.42.06-.65.31-.22.25-.85.83-.85 2.03 0 1.2.87 2.36.99 2.52.13.16 1.71 2.62 4.16 3.67.58.25 1.04.4 1.39.51.59.19 1.12.16 1.55.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.15-1.18-.06-.1-.23-.16-.48-.28Z"/>
            </svg>
            <span>{{ $label }}</span>
        </a>
    @endif
@endif
