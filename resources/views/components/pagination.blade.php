@if ($paginator->hasPages())
<nav class="pagination-nav" aria-label="Pagination">
    <div class="pagination-info">
        {{ __('store.showing') ?? 'Showing' }}
        <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
        {{ __('store.of') ?? 'of' }}
        <strong>{{ $paginator->total() }}</strong>
    </div>

    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link page-prev" aria-disabled="true">
                    @if(session('is_rtl', true)) › @else ‹ @endif
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link page-prev" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    @if(session('is_rtl', true)) › @else ‹ @endif
                </a>
            </li>
        @endif

        {{-- Page Number Links --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link page-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    @if(session('is_rtl', true)) ‹ @else › @endif
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link page-next" aria-disabled="true">
                    @if(session('is_rtl', true)) ‹ @else › @endif
                </span>
            </li>
        @endif
    </ul>
</nav>
@endif
