@extends('layouts.app')

@section('title', 'Your Cart – ELECTROZONE AKKA')

@section('content')
<div class="container">
    <div class="page-header-simple">
        <h1>🛒 Your Cart</h1>
        <a href="{{ route('products.index') }}" class="btn btn-ghost">← Continue Shopping</a>
    </div>

    @if(empty($cart))
    <div class="empty-state cart-empty">
        <div class="empty-icon">🛒</div>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven't added anything yet. Let's change that!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Browse Products</a>
    </div>
    @else
    <div class="cart-layout">
        <!-- Cart Items -->
        <div class="cart-items">
            @foreach($cart as $id => $item)
            <div class="cart-item">
                <div class="cart-item-img">
                    @if($item['image'])
                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                    @else
                        <div class="img-placeholder">📦</div>
                    @endif
                </div>
                <div class="cart-item-info">
                    <div class="cart-item-brand">{{ $item['brand'] }}</div>
                    <h3 class="cart-item-name">{{ $item['name'] }}</h3>
                    <div class="cart-item-price">{{ number_format($item['price'], 2) }} MAD</div>
                </div>
                <div class="cart-item-actions">
                    <form action="{{ route('cart.update') }}" method="POST" class="qty-update-form">
                        @csrf @method('PUT')
                        <input type="hidden" name="product_id" value="{{ $id }}">
                        <div class="qty-control-sm">
                            <button type="button" onclick="adjustCartQty(this, -1)">−</button>
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="qty-input" onchange="this.form.submit()">
                            <button type="button" onclick="adjustCartQty(this, 1)">+</button>
                        </div>
                    </form>
                    <div class="cart-item-sub">{{ number_format($item['price'] * $item['quantity'], 2) }} MAD</div>
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-remove" title="Remove">✕</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="cart-summary">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>{{ number_format($total, 2) }} MAD</span>
            </div>
            <div class="summary-row">
                <span>Shipping</span>
                <span>{{ $total >= 500 ? '🎉 FREE' : '30.00 MAD' }}</span>
            </div>
            @if($total < 500)
            <div class="free-shipping-hint">
                Add {{ number_format(500 - $total, 2) }} MAD more to get free shipping!
            </div>
            @endif
            <div class="summary-total">
                <span>Total</span>
                <span>{{ number_format($total >= 500 ? $total : $total + 30, 2) }} MAD</span>
            </div>
            <a href="{{ route('checkout.index') }}" class="btn btn-accent btn-lg btn-block">
                Proceed to Checkout →
            </a>
            <div class="payment-note">🔒 Secure checkout – Your data is protected</div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function adjustCartQty(btn, delta) {
    const form = btn.closest('form');
    const input = form.querySelector('input[name="quantity"]');
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
    form.submit();
}
</script>
@endpush
