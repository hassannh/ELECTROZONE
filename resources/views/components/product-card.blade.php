<div class="product-card">
    @if($product->is_on_sale && $product->old_price)
        <div class="badge badge-sale">SALE</div>
    @elseif($product->is_new)
        <div class="badge badge-new">NEW</div>
    @endif

    @if($product->stock_quantity === 0)
        <div class="badge badge-out">Out of Stock</div>
    @endif

    <a href="{{ route('products.show', [$product->id, $product->slug]) }}" class="product-card-img-wrap">
        @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->path) }}" 
                 alt="{{ $product->name }}" 
                 class="product-card-img" 
                 loading="lazy">
        @else
            <div class="product-card-placeholder">📦</div>
        @endif
    </a>

    <div class="product-card-body">
        <div class="product-brand">{{ $product->brand }}</div>
        <h3 class="product-name">
            <a href="{{ route('products.show', [$product->id, $product->slug]) }}">{{ $product->name }}</a>
        </h3>
        <p class="product-short-desc">{{ Str::limit($product->short_description, 80) }}</p>

        <div class="product-price-row">
            <div class="price-group">
                <span class="price-current">{{ number_format($product->price, 2) }} MAD</span>
                @if($product->old_price)
                    <span class="price-old">{{ number_format($product->old_price, 2) }} MAD</span>
                @endif
            </div>
        </div>

        <div class="product-card-footer">
            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-accent btn-sm add-cart-btn">
                        🛒 Add to Cart
                    </button>
                </form>
            @else
                <button class="btn btn-disabled btn-sm" disabled>Out of Stock</button>
            @endif
            <a href="{{ route('products.show', [$product->id, $product->slug]) }}" class="btn btn-ghost btn-sm">View</a>
        </div>
    </div>
</div>
