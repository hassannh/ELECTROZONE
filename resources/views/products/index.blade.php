@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' – ELECTROZONE AKKA' : 'All Products – ELECTROZONE AKKA')

@section('content')
<div class="bg-surface py-10 lg:py-16 border-b border-border/50">
    <div class="max-w-7xl mx-auto px-6">
        <h1 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase mb-2">
            {{ isset($category) ? ($category->icon ?? '') . ' ' . $category->name : 'All Products' }}
        </h1>
        <p class="text-light font-bold">{{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }} found</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-6 py-8 lg:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-10 items-start">

        {{-- Mobile Filter Toggle --}}
        <button class="lg:hidden w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-white border-2 border-border/50 rounded-xl font-black text-dark hover:border-primary transition-all active:scale-95 shadow-sm" id="mobileFilterToggle" onclick="toggleMobileFilters()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/></svg>
            Filters
            @php
                $activeFilters = array_filter([request('category'), request('brand'), request('price_min'), request('price_max')]);
            @endphp
            @if(count($activeFilters) > 0)
                <span class="w-5 h-5 flex items-center justify-center bg-primary text-white text-[10px] font-black rounded-full">{{ count($activeFilters) }}</span>
            @endif
        </button>

        {{-- Sidebar Filters --}}
        <aside class="fixed inset-y-0 left-0 w-80 bg-white z-[10001] shadow-2xl p-6 lg:relative lg:inset-auto lg:w-auto lg:z-10 lg:shadow-none lg:p-0 -translate-x-full lg:translate-x-0 transition-transform duration-300 overflow-y-auto ltr:left-0 ltr:-translate-x-full rtl:right-0 rtl:translate-x-full" id="filtersSidebar">
            <div class="flex items-center justify-between mb-8 lg:mb-6">
                <h3 class="text-xl font-black text-dark uppercase tracking-tight">Filters</h3>
                @if(count($activeFilters) > 0)
                    <a href="{{ route('products.index') }}" class="text-xs font-bold text-primary hover:underline">Clear all</a>
                @endif
                <button class="lg:hidden text-2xl" onclick="toggleMobileFilters()">✕</button>
            </div>

            <form method="GET" action="{{ route('products.index') }}" id="filterForm" class="flex flex-col gap-8">
                <div class="filter-group">
                    <h4 class="text-xs font-black text-light uppercase tracking-widest mb-4">Category</h4>
                    <div class="flex flex-col gap-2">
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface cursor-pointer transition-colors group">
                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                {{ request('category') == $cat->slug ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()"
                                class="w-4 h-4 text-primary border-border focus:ring-primary">
                            <span class="text-sm font-semibold text-dark group-hover:text-primary transition-colors">{{ $cat->icon ?? '' }} {{ $cat->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-group">
                    <h4 class="text-xs font-black text-light uppercase tracking-widest mb-4">Price Range (MAD)</h4>
                    <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-2">
                        <input type="number" name="price_min" placeholder="Min" value="{{ request('price_min') }}" class="w-full px-3 py-2 bg-surface border-2 border-transparent rounded-lg text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all">
                        <span class="text-light">-</span>
                        <input type="number" name="price_max" placeholder="Max" value="{{ request('price_max') }}" class="w-full px-3 py-2 bg-surface border-2 border-transparent rounded-lg text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all">
                    </div>
                </div>

                <div class="filter-group">
                    <h4 class="text-xs font-black text-light uppercase tracking-widest mb-4">Brand</h4>
                    <div class="flex flex-col gap-2">
                        @foreach($brands as $brandName)
                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface cursor-pointer transition-colors group">
                            <input type="radio" name="brand" value="{{ $brandName }}"
                                {{ request('brand') == $brandName ? 'checked' : '' }}
                                onchange="document.getElementById('filterForm').submit()"
                                class="w-4 h-4 text-primary border-border focus:ring-primary">
                            <span class="text-sm font-semibold text-dark group-hover:text-primary transition-colors">{{ $brandName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <button type="submit" class="w-full py-3 bg-primary text-white rounded-xl font-bold hover:bg-primary-dark transition-all shadow-lg active:scale-95">Apply Filters</button>
                    <a href="{{ route('products.index') }}" class="w-full py-3 bg-surface text-dark text-center rounded-xl font-bold hover:bg-border transition-all">Reset All</a>
                </div>
            </form>
        </aside>

        {{-- Products Area --}}
        <div class="flex-1">
            {{-- Sort bar with active filter chips --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-4 bg-white border border-border/50 rounded-2xl shadow-sm mb-8">
                <div class="flex flex-wrap items-center gap-3">
                    @if(isset($query))
                        <span class="text-sm text-dark font-medium">Results for: <strong class="text-primary">"{{ $query }}"</strong></span>
                    @else
                        <span class="text-sm text-dark font-medium"><strong>{{ $products->total() }}</strong> products</span>
                    @endif

                    {{-- Active Filter Chips --}}
                    <div class="flex flex-wrap items-center gap-2">
                        @if(request('category'))
                            <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full hover:bg-primary/20 transition-colors">
                                {{ request('category') }} <span class="text-lg leading-none">&times;</span>
                            </a>
                        @endif
                        @if(request('brand'))
                            <a href="{{ request()->fullUrlWithQuery(['brand' => null]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full hover:bg-primary/20 transition-colors">
                                {{ request('brand') }} <span class="text-lg leading-none">&times;</span>
                            </a>
                        @endif
                        @if(request('price_min') || request('price_max'))
                            <a href="{{ request()->fullUrlWithQuery(['price_min' => null, 'price_max' => null]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full hover:bg-primary/20 transition-colors">
                                {{ request('price_min', '0') }}–{{ request('price_max', '∞') }} <span class="text-lg leading-none">&times;</span>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-6 w-full md:w-auto">
                    {{-- Grid/List toggle (simplified for now but keeping UI) --}}
                    <div class="flex items-center bg-surface border border-border/50 rounded-lg p-1">
                        <button class="w-8 h-8 flex items-center justify-center rounded transition-all bg-white text-primary shadow-sm" id="gridViewBtn" onclick="setView('grid')" title="Grid view">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                        </button>
                        <button class="w-8 h-8 flex items-center justify-center rounded transition-all text-light" id="listViewBtn" onclick="setView('list')" title="List view" disabled>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
                        </button>
                    </div>

                    <div class="flex items-center gap-2 flex-1 md:flex-none">
                        <label class="text-[0.65rem] font-black text-light uppercase tracking-widest">Sort:</label>
                        <select name="sort" onchange="applySortFilter(this.value)" class="flex-1 md:flex-none bg-surface border border-transparent rounded-lg px-3 py-1.5 text-xs font-bold focus:outline-none focus:border-primary transition-all">
                            <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc"       {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price ↑</option>
                            <option value="price_desc"      {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price ↓</option>
                            <option value="name_asc"        {{ request('sort') == 'name_asc' ? 'selected' : '' }}>A–Z</option>
                        </select>
                    </div>
                </div>
            </div>

            @if($products->count())
            <div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-8" id="productsGrid">
                @foreach($products as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-12 flex justify-center">
                {{ $products->links('components.pagination') }}
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-24 h-24 bg-surface rounded-full flex items-center justify-center text-5xl mb-6">🔍</div>
                <h3 class="text-2xl font-black text-dark uppercase mb-2">No products found</h3>
                <p class="text-light font-medium mb-8">Try adjusting your filters or search terms.</p>
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-primary text-white rounded-xl font-bold shadow-lg hover:bg-primary-dark transition-all">Clear Filters</a>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Mobile filter overlay --}}
<div class="fixed inset-0 bg-black/50 z-[10000] backdrop-blur-[2px] hidden transition-opacity" id="mobileFilterOverlay" onclick="toggleMobileFilters()"></div>
@endsection

@push('scripts')
<script>
function applySortFilter(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}

function toggleMobileFilters() {
    const sidebar = document.getElementById('filtersSidebar');
    const overlay = document.getElementById('mobileFilterOverlay');
    const isRtl = document.documentElement.dir === 'rtl';
    
    if (sidebar.style.transform === 'translateX(0px)') {
        sidebar.style.transform = isRtl ? 'translateX(100%)' : 'translateX(-100%)';
        overlay.classList.add('hidden');
    } else {
        sidebar.style.transform = 'translateX(0px)';
        overlay.classList.remove('hidden');
    }
}
</script>
@endpush
