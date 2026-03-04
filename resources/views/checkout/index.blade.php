@extends('layouts.app')

@section('title', 'Checkout – ELECTROZONE AKKA')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 lg:py-16">
    <div class="mb-12 border-b border-border/50 pb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-3xl lg:text-5xl font-black text-dark tracking-tighter uppercase mb-4">💳 Checkout</h1>
            <div class="flex items-center gap-4 text-[0.65rem] font-black uppercase tracking-[0.2em]">
                <span class="text-primary flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-primary"></span> 1. Shipping</span>
                <span class="text-border">/</span>
                <span class="text-light">2. Confirmation</span>
            </div>
        </div>
        <a href="{{ route('cart.index') }}" class="text-sm font-bold text-primary hover:underline flex items-center gap-2">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M19 12H5m7-7-7 7 7 7"/></svg>
            Back to Cart
        </a>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-[1fr_400px] gap-12 items-start" id="checkoutForm">
        @csrf
        <!-- Shipping Form -->
        <div class="flex flex-col gap-10">
            <div class="bg-white border border-border/50 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-black text-dark uppercase tracking-tighter mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 flex items-center justify-center bg-surface rounded-xl text-xl">📦</span>
                    Shipping Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col gap-2">
                        <label for="full_name" class="text-xs font-black text-light uppercase tracking-widest">Full Name *</label>
                        <input type="text" id="full_name" name="full_name" 
                               value="{{ old('full_name') }}" 
                               class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all {{ $errors->has('full_name') ? 'border-danger bg-white' : '' }}"
                               placeholder="Ahmed El Mansouri" required>
                        @error('full_name')<span class="text-[0.65rem] font-bold text-danger uppercase tracking-tight">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="phone" class="text-xs font-black text-light uppercase tracking-widest">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all {{ $errors->has('phone') ? 'border-danger bg-white' : '' }}"
                               placeholder="+212 6XX-XXXXXX" required>
                        @error('phone')<span class="text-[0.65rem] font-bold text-danger uppercase tracking-tight">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-6">
                    <label for="email" class="text-xs font-black text-light uppercase tracking-widest">Email Address *</label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all {{ $errors->has('email') ? 'border-danger bg-white' : '' }}"
                           placeholder="ahmed@example.com" required>
                    @error('email')<span class="text-[0.65rem] font-bold text-danger uppercase tracking-tight">{{ $message }}</span>@enderror
                </div>

                <div class="flex flex-col gap-2 mt-6">
                    <label for="address" class="text-xs font-black text-light uppercase tracking-widest">Street Address *</label>
                    <textarea id="address" name="address" rows="3"
                              class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all resize-none {{ $errors->has('address') ? 'border-danger bg-white' : '' }}"
                              placeholder="Street, Building, Apartment..." required>{{ old('address') }}</textarea>
                    @error('address')<span class="text-[0.65rem] font-bold text-danger uppercase tracking-tight">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div class="flex flex-col gap-2">
                        <label for="city" class="text-xs font-black text-light uppercase tracking-widest">City *</label>
                        <input type="text" id="city" name="city" 
                               value="{{ old('city') }}"
                               class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all {{ $errors->has('city') ? 'border-danger bg-white' : '' }}"
                               placeholder="Akka" required>
                        @error('city')<span class="text-[0.65rem] font-bold text-danger uppercase tracking-tight">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label for="postal_code" class="text-xs font-black text-light uppercase tracking-widest">Postal Code</label>
                        <input type="text" id="postal_code" name="postal_code" 
                               value="{{ old('postal_code') }}"
                               class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all" placeholder="e.g. 85450">
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-6">
                    <label for="notes" class="text-xs font-black text-light uppercase tracking-widest">Order Notes (Optional)</label>
                    <textarea id="notes" name="notes" rows="2" class="w-full px-4 py-3 bg-surface border-2 border-transparent rounded-xl text-sm font-bold focus:outline-none focus:border-primary focus:bg-white transition-all resize-none"
                              placeholder="Special delivery instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="bg-white border border-border/50 rounded-3xl p-8 shadow-sm">
                <h3 class="text-xl font-black text-dark uppercase tracking-tighter mb-8 flex items-center gap-3">
                    <span class="w-10 h-10 flex items-center justify-center bg-surface rounded-xl text-xl">💳</span>
                    Payment Method
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-primary bg-primary/5 cursor-pointer group transition-all">
                        <input type="radio" name="payment_method" value="cod" checked class="w-5 h-5 text-primary border-primary focus:ring-primary">
                        <div class="flex items-center gap-4">
                            <span class="text-3xl grayscale group-hover:grayscale-0 transition-all">💵</span>
                            <div>
                                <strong class="block text-sm font-black text-dark uppercase tracking-tight">Cash on Delivery</strong>
                                <small class="text-[0.7rem] font-bold text-light uppercase tracking-widest">Pay when your order arrives</small>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <aside class="sticky top-28 flex flex-col gap-8">
            <div class="bg-dark text-white rounded-[2.5rem] p-8 lg:p-10 shadow-2xl overflow-hidden relative">
                <!-- Decorative background -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <h3 class="text-2xl font-black uppercase tracking-tighter mb-8 border-b border-white/10 pb-4 relative z-10">📋 Order Review</h3>
                
                <div class="flex flex-col gap-4 mb-8 relative z-10 max-h-[300px] overflow-y-auto no-scrollbar">
                    @foreach($cart as $id => $item)
                    <div class="flex items-center justify-between gap-4 p-3 bg-white/5 rounded-2xl border border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center text-xs text-dark font-black shrink-0">{{ $item['quantity'] }}×</div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[0.7rem] font-bold text-white leading-tight truncate">{{ $item['name'] }}</span>
                                <span class="text-[0.6rem] font-black text-white/40 uppercase tracking-widest">{{ $item['brand'] }}</span>
                            </div>
                        </div>
                        <div class="text-[0.8rem] font-black text-white whitespace-nowrap">{{ number_format($item['price'] * $item['quantity'], 2) }} <span class="text-[0.6em] text-white/40">MAD</span></div>
                    </div>
                    @endforeach
                </div>

                <div class="space-y-4 mb-8 relative z-10">
                    <div class="flex items-center justify-between text-white/50 text-sm font-bold">
                        <span>Subtotal</span>
                        <span class="text-white">{{ number_format($subtotal, 2) }} MAD</span>
                    </div>
                    <div class="flex items-center justify-between text-white/50 text-sm font-bold">
                        <span>Shipping</span>
                        <span class="text-white">{{ $shippingCost == 0 ? '🎉 FREE' : number_format($shippingCost, 2) . ' MAD' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-white/20 mb-10 relative z-10">
                    <span class="text-lg font-bold uppercase tracking-widest text-white/40">Total</span>
                    <span class="text-3xl font-black text-white tracking-tight">{{ number_format($total, 2) }} <span class="text-[0.5em] text-white/40">MAD</span></span>
                </div>

                <button type="submit" class="w-full py-5 bg-primary text-white rounded-2xl font-black text-xl hover:bg-primary-dark transition-all shadow-xl hover:shadow-primary/30 active:scale-95 flex items-center justify-center gap-3 group relative z-10">
                    Place Order <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M20 6 9 17l-5-5"/></svg>
                </button>
                
                <div class="mt-8 flex items-center justify-center gap-2 text-white/20 text-[0.65rem] font-black uppercase tracking-widest relative z-10">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    ENCRYPTED DATA
                </div>
            </div>
            
            <div class="p-6 bg-surface rounded-3xl border border-border/50">
                <p class="text-[0.65rem] text-center font-bold text-light uppercase leading-relaxed">By placing an order, you agree to our <a href="#" class="text-primary hover:underline">Terms & Conditions</a> and <a href="#" class="text-primary hover:underline">Refund Policy</a>.</p>
            </div>
        </aside>
    </form>
</div>
@endsection
