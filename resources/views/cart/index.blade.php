@extends('layouts.app')

@section('title', 'Your Cart – ELECTROZONE AKKA')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12 border-b border-border/50 pb-8">
        <h1 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase">🛒 Your Cart</h1>
        <a href="{{ route('products.index') }}" class="px-6 py-2.5 bg-surface border-2 border-border/50 rounded-xl font-bold text-sm text-dark hover:border-primary hover:text-primary transition-all">← Continue Shopping</a>
    </div>

    @if(empty($cart))
    <div class="flex flex-col items-center justify-center py-24 text-center">
        <div class="w-32 h-32 bg-surface rounded-full flex items-center justify-center text-6xl mb-8">🛒</div>
        <h2 class="text-2xl lg:text-3xl font-black text-dark uppercase mb-4">Your cart is empty</h2>
        <p class="text-light font-medium mb-10 max-w-md">Looks like you haven't added anything yet. Let's change that and find some awesome electronics!</p>
        <a href="{{ route('products.index') }}" class="px-10 py-4 bg-primary text-white rounded-xl font-black text-lg shadow-xl hover:bg-primary-dark transition-all active:scale-95">Browse Products</a>
    </div>
    @else
    <form action="{{ route('cart.update') }}" method="POST" id="bulk-cart-form">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-[1fr_380px] gap-12 items-start">
            <!-- Cart Items -->
            <div class="flex flex-col gap-6">
                @foreach($cart as $id => $item)
                <div class="bg-white border border-border/50 rounded-3xl p-4 lg:p-6 flex flex-col sm:flex-row items-center gap-6 shadow-sm hover:shadow-md transition-shadow relative" data-id="{{ $id }}" data-price="{{ $item['price'] }}">
                    <div class="w-24 h-24 lg:w-32 lg:h-32 bg-surface rounded-2xl overflow-hidden shrink-0 border border-border/30">
                        @if($item['image'])
                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-contain">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-4xl">📦</div>
                        @endif
                    </div>
                    
                    <div class="flex-1 flex flex-col sm:flex-row items-center gap-6 w-full">
                        <div class="flex-1 text-center sm:text-start">
                            <div class="text-[0.65rem] font-black text-light uppercase tracking-widest mb-1">{{ $item['brand'] }}</div>
                            <h3 class="text-lg font-bold text-dark leading-tight">{{ $item['name'] }}</h3>
                            <div class="text-primary font-black mt-2"><span class="price-val">{{ number_format($item['price'], 2) }}</span> MAD</div>
                        </div>

                        <div class="flex items-center gap-8 shrink-0">
                            <div class="flex items-center bg-surface border border-border/50 rounded-xl p-1">
                                <button type="button" onclick="adjustCartQtyBulk(this, -1)" class="w-8 h-8 flex items-center justify-center font-bold hover:bg-white rounded-lg transition-colors">−</button>
                                <input type="number" 
                                       name="quantities[{{ $id }}]" 
                                       value="{{ $item['quantity'] }}" 
                                       min="1" 
                                       class="w-10 text-center font-black bg-transparent outline-none qty-input-bulk" 
                                       onchange="updateCartUI()">
                                <button type="button" onclick="adjustCartQtyBulk(this, 1)" class="w-8 h-8 flex items-center justify-center font-bold hover:bg-white rounded-lg transition-colors">+</button>
                            </div>
                            
                            <div class="text-lg font-black text-dark min-w-[120px] text-end">
                                <span class="item-sub-val">{{ number_format($item['price'] * $item['quantity'], 2) }}</span> <span class="text-[0.6em]">MAD</span>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="absolute top-4 right-4 lg:top-6 lg:right-6 w-8 h-8 flex items-center justify-center bg-surface text-light hover:bg-danger/10 hover:text-danger rounded-full transition-all" onclick="removeItem('{{ $id }}')" title="Remove">✕</button>
                </div>
                @endforeach

                <div class="flex justify-start mt-4">
                    <button type="submit" class="hidden items-center gap-2 px-6 py-3 bg-white border-2 border-primary text-primary rounded-xl font-bold hover:bg-primary hover:text-white transition-all shadow-lg active:scale-95" id="update-cart-btn">
                        🔄 Update Cart
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="bg-dark text-white rounded-[2.5rem] p-8 lg:p-10 shadow-2xl sticky top-28">
                <h3 class="text-2xl font-black uppercase tracking-tighter mb-8 border-b border-white/10 pb-4">Order Summary</h3>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center justify-between text-white/60 font-bold">
                        <span>Subtotal</span>
                        <span id="js-subtotal" class="text-white">{{ number_format($total, 2) }} MAD</span>
                    </div>
                    <div class="flex items-center justify-between text-white/60 font-bold">
                        <span>Shipping</span>
                        <span id="js-shipping" class="text-white">{{ $total >= 500 ? 'FREE' : '30.00 MAD' }}</span>
                    </div>
                    
                    <div class="p-4 bg-white/5 rounded-2xl border border-white/10 animate-pulse" id="js-shipping-hint" {!! $total >= 500 ? 'style="display:none"' : '' !!}>
                        <p class="text-xs font-bold text-center">
                            Add <span id="js-more-amount" class="text-accent">{{ number_format(500 - $total, 2) }}</span> MAD more to get <span class="text-accent underline">FREE SHIPPING</span>!
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-white/20 mb-10">
                    <span class="text-lg font-bold uppercase tracking-widest text-white/50">Total</span>
                    <span id="js-total" class="text-3xl font-black text-white tracking-tight">{{ number_format($total >= 500 ? $total : $total + 30, 2) }} <span class="text-[0.5em] text-white/40">MAD</span></span>
                </div>

                <button type="submit" name="checkout" value="1" class="w-full py-5 bg-primary text-white rounded-2xl font-black text-xl hover:bg-primary-dark transition-all shadow-xl hover:shadow-primary/30 active:scale-95 flex items-center justify-center gap-3 group">
                    Proceed to Checkout <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform rtl:rotate-180"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                </button>
                
                <div class="mt-8 flex items-center justify-center gap-2 text-white/30 text-[0.65rem] font-black uppercase tracking-widest">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    SECURE CHECKOUT
                </div>
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
    const container = btn.closest('.flex');
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
    const items = document.querySelectorAll('[data-id]');
    const updateBtn = document.getElementById('update-cart-btn');
    
    if(updateBtn) updateBtn.classList.remove('hidden');
    if(updateBtn) updateBtn.classList.add('flex');

    items.forEach(item => {
        const id = item.dataset.id;
        if (!id) return;
        const price = parseFloat(item.dataset.price);
        const qty = parseInt(item.querySelector('.qty-input-bulk').value) || 1;
        const itemSubtotal = price * qty;
        subtotal += itemSubtotal;
        
        const subDisplay = item.querySelector('.item-sub-val');
        if(subDisplay) subDisplay.textContent = formatMoney(itemSubtotal);
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
</script>
@endpush
