@extends('layouts.app')

@section('title', $product->name . ' – ELECTROZONE AKKA')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        @if($product->category)
        <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a>
        <span>/</span>
        @endif
        <span>{{ Str::limit($product->name, 40) }}</span>
    </nav>

    <div class="pdp-layout">
        <!-- Gallery -->
        <div class="pdp-gallery">
            <div class="gallery-main">
                @php $images = $product->images; @endphp
                @if($images->count())
                    <img id="mainImg" src="{{ asset('storage/' . $images->first()->path) }}" alt="{{ $product->name }}" class="gallery-main-img">
                @else
                    <div class="gallery-placeholder">📦</div>
                @endif
            </div>
            @if($images->count() > 1)
            <div class="gallery-thumbs">
                @foreach($images as $img)
                <img src="{{ asset('storage/' . $img->path) }}" 
                     alt="{{ $product->name }}" 
                     class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                     onclick="switchImage(this, '{{ asset('storage/' . $img->path) }}')">
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="pdp-info">
            <div class="pdp-brand">{{ $product->brand }}</div>
            <h1 class="pdp-title">{{ $product->name }}</h1>

            <div class="pdp-badges">
                @if($product->is_new)<span class="badge badge-new">NEW</span>@endif
                @if($product->is_on_sale)<span class="badge badge-sale">ON SALE</span>@endif
                @if($product->is_featured)<span class="badge badge-featured">⭐ FEATURED</span>@endif
            </div>

            <div class="pdp-price-block">
                <span class="pdp-price">{{ number_format($product->price, 2) }} MAD</span>
                @if($product->old_price)
                <span class="pdp-old-price">{{ number_format($product->old_price, 2) }} MAD</span>
                @php $discount = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                <span class="pdp-discount">–{{ $discount }}%</span>
                @endif
            </div>

            <p class="pdp-short-desc">{{ $product->short_description }}</p>

            <div class="stock-status {{ $product->isInStock() ? 'in-stock' : 'out-stock' }}">
                @if($product->isInStock())
                    ✅ In Stock ({{ $product->stock_quantity }} available)
                @else
                    ❌ Out of Stock
                @endif
            </div>

            @if($product->isInStock())
            <form action="{{ route('cart.add') }}" method="POST" class="pdp-add-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="qty-selector">
                    <label>Quantity:</label>
                    <div class="qty-control">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qtyInput" value="1" min="1" max="{{ $product->stock_quantity }}">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div class="pdp-btns">
                    <button type="submit" class="btn btn-accent btn-lg">🛒 Add to Cart</button>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline btn-lg">View Cart</a>
                </div>
            </form>
            @else
            <button class="btn btn-disabled btn-lg" disabled>Out of Stock</button>
            @endif

            <!-- Features -->
            @if($product->features)
            <div class="pdp-features">
                <h4>✨ Key Features</h4>
                <ul>
                    @foreach($product->features as $feature)
                    <li>{{ $feature }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>

    <!-- Tabs: Description + Specs -->
    <div class="pdp-tabs">
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab(event, 'desc')">Description</button>
            @if($product->specifications)
            <button class="tab-btn" onclick="switchTab(event, 'specs')">Specifications</button>
            @endif
        </div>

        <div id="tab-desc" class="tab-content active">
            <div class="product-description">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>

        @if($product->specifications)
        <div id="tab-specs" class="tab-content">
            <table class="specs-table">
                @foreach($product->specifications as $key => $value)
                <tr>
                    <th>{{ $key }}</th>
                    <td>{{ $value }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif
    </div>

    <!-- Related Products -->
    @if($related->count())
    <div class="pdp-related">
        <h2>You Might Also Like</h2>
        <div class="products-grid">
            @foreach($related as $rel)
                @include('components.product-card', ['product' => $rel])
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function switchImage(el, src) {
    document.getElementById('mainImg').src = src;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
}

function changeQty(delta) {
    const input = document.getElementById('qtyInput');
    const max = parseInt(input.max);
    const newVal = Math.min(max, Math.max(1, parseInt(input.value) + delta));
    input.value = newVal;
}

function switchTab(event, tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    event.target.classList.add('active');
    document.getElementById('tab-' + tabId).classList.add('active');
}
</script>
@endpush
