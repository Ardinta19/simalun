@if ($paginator->hasPages())
<nav role="navigation" aria-label="Halaman" style="display:flex;align-items:center;justify-content:center;gap:6px;padding:12px 0;">
    @if ($paginator->onFirstPage())
        <span style="padding:8px 14px;border-radius:10px;font-size:.82rem;font-weight:800;color:#8899aa;background:#f1f5f9;border:1.5px solid #ddeeff;cursor:default;">Sebelumnya</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:8px 14px;border-radius:10px;font-size:.82rem;font-weight:800;color:#0077b6;background:#e0f4ff;border:1.5px solid #bae6fd;text-decoration:none;">Sebelumnya</a>
    @endif

    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:8px 10px;font-size:.82rem;font-weight:800;color:#8899aa;">{{ $element }}</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="padding:8px 12px;border-radius:10px;font-size:.82rem;font-weight:900;color:#fff;background:#0077b6;min-width:36px;text-align:center;display:inline-block;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:8px 12px;border-radius:10px;font-size:.82rem;font-weight:800;color:#1a2332;background:#fff;border:1.5px solid #ddeeff;text-decoration:none;min-width:36px;text-align:center;display:inline-block;">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:8px 14px;border-radius:10px;font-size:.82rem;font-weight:800;color:#0077b6;background:#e0f4ff;border:1.5px solid #bae6fd;text-decoration:none;">Berikutnya</a>
    @else
        <span style="padding:8px 14px;border-radius:10px;font-size:.82rem;font-weight:800;color:#8899aa;background:#f1f5f9;border:1.5px solid #ddeeff;cursor:default;">Berikutnya</span>
    @endif
</nav>
@endif
