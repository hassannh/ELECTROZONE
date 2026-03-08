<div class="bg-white rounded-xl lg:rounded-2xl overflow-hidden border border-border/40 shadow-sm hover:shadow-premium group transition-all duration-300 relative flex flex-col h-full active:scale-[0.98] lg:active:scale-100">
    {{-- Badges: stacked top-left --}}
    <div class="absolute top-2.5 left-2.5 z-20 flex flex-col gap-1.5 pointer-events-none">
        @if($product->stock_quantity === 0)
            <span class="bg-dark/80 backdrop-blur-sm text-white text-[0.65rem] font-black px-2 py-0.5 rounded shadow-sm">OUT OF STOCK</span>
        @elseif($product->is_on_sale && $product->old_price)
            @php $disc = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
            <span class="bg-danger text-white text-[0.7rem] font-black px-2 py-0.5 rounded shadow-md border border-white/20">-{{ $disc }}%</span>
        @elseif($product->is_new)
            <span class="bg-primary text-white text-[0.7rem] font-black px-2 py-0.5 rounded shadow-md border border-white/20 uppercase">NEW</span>
        @endif
        @if($product->is_featured)
            <span class="bg-yellow-400 text-dark text-xs p-1 rounded-full shadow-md flex items-center justify-center w-6 h-6 border border-white/40">⭐</span>
        @endif
    </div>

    {{-- Low stock warning --}}
    @if($product->stock_quantity > 0 && $product->stock_quantity <= 5)
        <div class="absolute bottom-0 left-0 right-0 bg-linear-to-r from-orange-500 to-amber-500 text-white text-[0.6rem] lg:text-[0.68rem] font-black text-center py-1 px-2 z-10 tracking-widest uppercase">
            🔥 {{ $product->stock_quantity }} LEFT
        </div>
    @endif

    {{-- Image with hover overlay --}}
    <a href="{{ route('products.show', [$product->id, $product->slug]) }}" class="relative block bg-surface overflow-hidden aspect-[1.1] shrink-0">
        @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->path) }}"
                 alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center text-4xl bg-surface animate-pulse">📦</div>
        @endif

        {{-- Hover quick-add overlay (Desktop only) --}}
        @if($product->stock_quantity > 0)
        <div class="absolute inset-x-0 bottom-0 top-0 bg-dark/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 hidden md:flex items-center justify-center p-4">
            <form action="{{ route('cart.add') }}" method="POST" class="w-full translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-full font-bold text-sm flex items-center justify-center gap-2 hover:bg-primary-dark transition-all shadow-xl active:scale-95">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Quick Add
                </button>
            </form>
        </div>
        @endif
    </a>

    <div class="p-2.5 lg:p-4 flex flex-col flex-1">
        <div class="text-[0.6rem] lg:text-[0.72rem] font-black text-light uppercase tracking-[0.15em] mb-1">{{ $product->brand }}</div>
        <h3 class="text-xs lg:text-sm font-bold text-dark leading-snug line-clamp-2 min-h-[2rem] lg:min-h-[2.6rem] mb-2 group-hover:text-primary transition-colors tracking-tight">
            <a href="{{ route('products.show', [$product->id, $product->slug]) }}">{{ $product->name }}</a>
        </h3>

        <div class="mt-auto pt-1">
            <div class="flex items-center flex-wrap gap-1.5 mb-3">
                <span class="text-sm lg:text-lg font-black text-primary tracking-tight">{{ number_format($product->price, 2) }} <span class="text-[0.65em]">MAD</span></span>
                @if($product->old_price)
                    <span class="text-[0.65rem] lg:text-sm text-light line-through decoration-danger/30">{{ number_format($product->old_price, 2) }}</span>
                @endif
            </div>

            <div class="flex items-center gap-1.5">
                @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="w-full py-2 lg:py-2.5 bg-primary text-white text-[0.65rem] lg:text-[0.82rem] font-black rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 flex items-center justify-center gap-1.5 uppercase tracking-wider">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="hidden xs:block"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            <span>Add</span>
                        </button>
                    </form>
                @else
                    <button class="flex-1 py-2 bg-surface text-light text-[0.65rem] lg:text-[0.82rem] font-bold rounded-lg cursor-not-allowed uppercase" disabled>Stock</button>
                @endif

                <a href="{{ route('products.show', [$product->id, $product->slug]) }}" class="w-8 h-8 lg:w-10 lg:h-10 shrink-0 flex items-center justify-center rounded-lg bg-surface border border-border/40 text-dark hover:bg-white hover:text-primary hover:border-primary transition-all active:scale-90" title="View Details">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>

