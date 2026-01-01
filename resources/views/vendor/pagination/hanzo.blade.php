@if ($paginator->hasPages())
    <nav class="hz-pagination" role="navigation" aria-label="Pagination">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="hz-page hz-page--arrow is-disabled" aria-disabled="true">‹</span>
        @else
            <a class="hz-page hz-page--arrow"
               href="{{ $paginator->previousPageUrl() }}"
               rel="prev"
               aria-label="Previous page">‹</a>
        @endif

        {{-- Numbers --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="hz-page is-active" aria-current="page">{{ $page }}</span>
            @else
                {{-- Debug: {{ $url }} --}}
                <a class="hz-page" href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a class="hz-page hz-page--arrow"
               href="{{ $paginator->nextPageUrl() }}"
               rel="next"
               aria-label="Next page">›</a>
        @else
            <span class="hz-page hz-page--arrow is-disabled" aria-disabled="true">›</span>
        @endif
    </nav>
@endif
