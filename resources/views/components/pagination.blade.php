@if ($paginator->hasPages())
<nav class="flex flex-col sm:flex-row items-center justify-between gap-6 py-10" aria-label="Pagination">
    <div class="text-[0.75rem] font-black text-light uppercase tracking-[0.1em]">
        {{ __('store.showing') ?? 'Showing' }}
        <span class="text-dark underline decoration-primary/30 underline-offset-4">{{ $paginator->firstItem() }}</span> – <span class="text-dark underline decoration-primary/30 underline-offset-4">{{ $paginator->lastItem() }}</span>
        {{ __('store.of') ?? 'of' }}
        <span class="text-dark">{{ $paginator->total() }}</span>
    </div>

    <ul class="flex items-center gap-1.5">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface text-border cursor-not-allowed border border-border/50">
                <span class="text-xl leading-none">@if(session('is_rtl', true)) › @else ‹ @endif</span>
            </li>
        @else
            <li>
                <a class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-dark border border-border/50 hover:border-primary hover:text-primary transition-all shadow-sm active:scale-90" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
                    <span class="text-xl leading-none">@if(session('is_rtl', true)) › @else ‹ @endif</span>
                </a>
            </li>
        @endif

        {{-- Page Number Links --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface text-light font-bold text-sm border border-border/50">
                    <span>{{ $element }}</span>
                </li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="w-10 h-10 flex items-center justify-center rounded-xl bg-primary text-white font-black text-sm shadow-lg shadow-primary/20" aria-current="page">
                            <span>{{ $page }}</span>
                        </li>
                    @else
                        <li>
                            <a class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-dark font-bold text-sm border border-border/50 hover:border-primary hover:text-primary transition-all shadow-sm active:scale-95" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li>
                <a class="w-10 h-10 flex items-center justify-center rounded-xl bg-white text-dark border border-border/50 hover:border-primary hover:text-primary transition-all shadow-sm active:scale-90" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
                    <span class="text-xl leading-none">@if(session('is_rtl', true)) ‹ @else › @endif</span>
                </a>
            </li>
        @else
            <li class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface text-border cursor-not-allowed border border-border/50">
                <span class="text-xl leading-none">@if(session('is_rtl', true)) ‹ @else › @endif</span>
            </li>
        @endif
    </ul>
</nav>
@endif
