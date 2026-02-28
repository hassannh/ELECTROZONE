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
    <form action="{{ route('cart.update') }}" method="POST" id="bulk-cart-form">
        @csrf @method('PUT')
        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach($cart as $id => $item)
                <div class="cart-item" data-id="{{ $id }}" data-price="{{ $item['price'] }}">
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
                        <div class="cart-item-price"><span class="price-val">{{ number_format($item['price'], 2) }}</span> MAD</div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="qty-control-sm">
                            <button type="button" onclick="adjustCartQtyBulk(this, -1)">−</button>
                            <input type="number" 
                                   name="quantities[{{ $id }}]" 
                                   value="{{ $item['quantity'] }}" 
                                   min="1" 
                                   class="qty-input-bulk" 
                                   onchange="updateCartUI()">
                            <button type="button" onclick="adjustCartQtyBulk(this, 1)">+</button>
                        </div>
                        <div class="cart-item-sub"><span class="item-sub-val">{{ number_format($item['price'] * $item['quantity'], 2) }}</span> MAD</div>
                    </div>
                    <!-- Remove Button (separate form because it's a DELETE request) -->
                    <button type="button" class="btn-remove" onclick="removeItem('{{ $id }}')" title="Remove">✕</button>
                </div>
                @endforeach

                <div class="cart-actions-footer">
                    <button type="submit" class="btn btn-ghost btn-update-cart" id="update-cart-btn" style="display: none;">
                        🔄 Update Cart
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="js-subtotal">{{ number_format($total, 2) }} MAD</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="js-shipping">{{ $total >= 500 ? 'FREE' : '30.00 MAD' }}</span>
                </div>
                <div class="free-shipping-hint" id="js-shipping-hint" {!! $total >= 500 ? 'style="display:none"' : '' !!}>
                    Add <span id="js-more-amount">{{ number_format(500 - $total, 2) }}</span> MAD more to get free shipping!
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="js-total">{{ number_format($total >= 500 ? $total : $total + 30, 2) }} MAD</span>
                </div>
                <!-- Submit the bulk form before checking out to ensure quantities are saved -->
                <button type="submit" name="checkout" value="1" class="btn btn-accent btn-lg btn-block">
                    Proceed to Checkout →
                </button>
                <div class="payment-note">🔒 Secure checkout – Your data is protected</div>
            </div>
        </div>
    </form>

    <!-- Hidden forms for removal -->
    @foreach($cart as $id => $item)
    <form id="remove-form-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST" style="display: none;">
        @csrf @method('DELETE')
    </form>
    @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script>
function formatMoney(amount) {
    return new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(amount);
}

function adjustCartQtyBulk(btn, delta) {
    const container = btn.closest('.qty-control-sm');
    const input = container.querySelector('input');
    const newVal = Math.max(1, parseInt(input.value) + delta);
    input.value = newVal;
    updateCartUI();
}

function removeItem(id) {
    if (confirm('Remove this item?')) {
        document.getElementById('remove-form-' + id).submit();
    }
}

function updateCartUI() {
    let subtotal = 0;
    const items = document.querySelectorAll('.cart-item');
    const updateBtn = document.getElementById('update-cart-btn');
    
    // Show update button once user interacts
    if(updateBtn) updateBtn.style.display = 'inline-flex';

    items.forEach(item => {
        const id = item.dataset.id;
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.querySelector('.qty-input-bulk').value) || 1;
        const itemSubtotal = price * qty;
        subtotal += itemSubtotal;
        
        item.querySelector('.item-sub-val').textContent = formatMoney(itemSubtotal);
    });

    const shipping = subtotal >= 500 ? 0 : 30;
    const total = subtotal + shipping;

    document.getElementById('js-subtotal').textContent = formatMoney(subtotal) + ' MAD';
    document.getElementById('js-shipping').textContent = shipping === 0 ? 'FREE' : '30.00 MAD';
    document.getElementById('js-total').textContent = formatMoney(total) + ' MAD';

    const hint = document.getElementById('js-shipping-hint');
    if (hint) {
        if (subtotal >= 500) {
            hint.style.display = 'none';
        } else {
            hint.style.display = 'block';
            document.getElementById('js-more-amount').textContent = formatMoney(500 - subtotal);
        }
    }
}

// Initial calculation to ensure formatting is consistent
document.addEventListener('DOMContentLoaded', () => {
    // updateCartUI(); // No need to trigger on load unless we expect differences
});
</script>
@endpush
