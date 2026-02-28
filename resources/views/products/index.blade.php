@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' – ELECTROZONE AKKA' : 'All Products – ELECTROZONE AKKA')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>{{ isset($category) ? ($category->icon ?? '') . ' ' . $category->name : 'All Products' }}</h1>
        <p>{{ $products->total() }} products found</p>
    </div>
</div>

<div class="container products-layout">
    <!-- Sidebar Filters -->
    <aside class="filters-sidebar">
        <form method="GET" action="{{ route('products.index') }}" id="filterForm">
            <div class="filter-group">
                <h4>Category</h4>
                @foreach($categories as $cat)
                <label class="filter-option">
                    <input type="radio" name="category" value="{{ $cat->slug }}"
                        {{ request('category') == $cat->slug ? 'checked' : '' }}>
                    <span>{{ $cat->icon ?? '' }} {{ $cat->name }}</span>
                </label>
                @endforeach
            </div>

            <div class="filter-group">
                <h4>Price Range (MAD)</h4>
                <div class="price-range-inputs">
                    <input type="number" name="price_min" placeholder="Min" value="{{ request('price_min') }}" class="input-sm">
                    <span>–</span>
                    <input type="number" name="price_max" placeholder="Max" value="{{ request('price_max') }}" class="input-sm">
                </div>
            </div>

            <div class="filter-group">
                <h4>Brand</h4>
                @foreach($brands as $brandName)
                <label class="filter-option">
                    <input type="radio" name="brand" value="{{ $brandName }}"
                        {{ request('brand') == $brandName ? 'checked' : '' }}>
                    <span>{{ $brandName }}</span>
                </label>
                @endforeach
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button>
                <a href="{{ route('products.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>
    </aside>

    <!-- Products Area -->
    <div class="products-area">
        <!-- Sort bar -->
        <div class="sort-bar">
            @if(isset($query))
                <span>Results for: <strong>"{{ $query }}"</strong></span>
            @endif
            <div class="sort-options">
                <label>Sort by:</label>
                <select name="sort" onchange="applySortFilter(this.value)" class="sort-select">
                    <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Newest</option>
                    <option value="price_asc"       {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc"      {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="name_asc"        {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name A–Z</option>
                </select>
            </div>
        </div>

        @if($products->count())
        <div class="products-grid">
            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="pagination-wrap">
            {{ $products->links('components.pagination') }}
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
            <h3>No products found</h3>
            <p>Try adjusting your filters or search terms.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">Clear Filters</a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function applySortFilter(value) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', value);
    window.location.href = url.toString();
}
</script>
@endpush
