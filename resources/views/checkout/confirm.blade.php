@extends('layouts.app')

@section('title', 'Order Confirmed – ELECTROZONE AKKA')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12 lg:py-20">
    <div class="text-center mb-12 lg:mb-16">
        <div class="w-24 h-24 lg:w-32 lg:h-32 bg-accent/10 text-accent text-5xl lg:text-6xl flex items-center justify-center rounded-full mx-auto mb-8 shadow-inner animate-[bounce_2s_infinite]">✓</div>
        <h1 class="text-3xl lg:text-6xl font-black text-dark tracking-tighter uppercase mb-4">Order Placed Successfully!</h1>
        <p class="text-lg lg:text-xl text-mid font-medium max-w-2xl mx-auto leading-relaxed">
            Thank you, <strong class="text-dark">{{ $order->customer_name }}</strong>! Your order has been received and is being processed by our team.
        </p>
    </div>

    <div class="bg-surface rounded-3xl p-6 lg:p-8 flex flex-col md:flex-row items-center justify-between gap-6 mb-12 border border-border/50">
        <div class="flex flex-col">
            <span class="text-[0.65rem] font-black text-light uppercase tracking-widest mb-1">Order Identifier</span>
            <span class="text-2xl font-black text-dark tracking-tight">#{{ strtoupper(substr($order->id, 0, 8)) }}</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="px-5 py-2.5 bg-accent text-white rounded-xl font-black text-sm shadow-lg shadow-accent/20 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span> 🆕 NEW ORDER
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_350px] gap-8 items-start">
        <!-- Order Items -->
        <div class="bg-white border border-border/50 rounded-[2rem] p-8 shadow-sm">
            <h3 class="text-xl font-black text-dark uppercase tracking-tighter mb-8 flex items-center gap-3">
                <span class="w-10 h-10 flex items-center justify-center bg-surface rounded-xl text-xl">📦</span>
                Items Ordered
            </h3>
            
            <div class="flex flex-col gap-4 mb-8">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between gap-4 p-4 bg-surface rounded-2xl border border-border/30">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white border border-border/50 rounded-lg flex items-center justify-center font-black text-sm text-dark">{{ $item['quantity'] }}×</div>
                        <span class="text-sm font-bold text-dark leading-tight">{{ $item['name'] }}</span>
                    </div>
                    <span class="text-sm font-black text-dark whitespace-nowrap">{{ number_format($item['subtotal'], 2) }} MAD</span>
                </div>
                @endforeach
            </div>

            <div class="space-y-3 pt-6 border-t border-border/50">
                <div class="flex items-center justify-between text-xs font-black text-light uppercase tracking-widest">
                    <span>Subtotal</span>
                    <span class="text-dark">{{ number_format($order->subtotal, 2) }} MAD</span>
                </div>
                <div class="flex items-center justify-between text-xs font-black text-light uppercase tracking-widest">
                    <span>Shipping</span>
                    <span class="text-dark">{{ $order->shipping_cost == 0 ? 'FREE' : number_format($order->shipping_cost, 2) . ' MAD' }}</span>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-border/30">
                    <span class="text-lg font-black text-dark uppercase tracking-tighter">Total Amount</span>
                    <span class="text-2xl font-black text-primary tracking-tight">{{ number_format($order->total_amount, 2) }} MAD</span>
                </div>
            </div>
        </div>

        <!-- Delivery Details -->
        <div class="flex flex-col gap-6">
            <div class="bg-dark text-white rounded-[2rem] p-8 shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                
                <h3 class="text-lg font-black uppercase tracking-tighter mb-8 border-b border-white/10 pb-4 relative z-10">🚚 Delivery Details</h3>
                
                <div class="space-y-6 relative z-10">
                    <div class="flex flex-col gap-1">
                        <span class="text-[0.6rem] font-black text-white/30 uppercase tracking-[0.2em]">Customer</span>
                        <span class="text-sm font-bold">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[0.6rem] font-black text-white/30 uppercase tracking-[0.2em]">Contact</span>
                        <span class="text-sm font-bold">{{ $order->customer_email }}</span>
                        <span class="text-sm font-bold text-accent">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-[0.6rem] font-black text-white/30 uppercase tracking-[0.2em]">Shipping Address</span>
                        <span class="text-sm font-bold leading-relaxed">
                            {{ $order->shipping_address['address'] }},<br>
                            {{ $order->shipping_address['city'] }}
                            @if($order->shipping_address['postal_code'])
                                {{ $order->shipping_address['postal_code'] }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface rounded-2xl p-6 border border-border/50">
                <p class="text-[0.7rem] font-bold text-mid text-center leading-relaxed italic">
                    Note: We will contact you at <strong>{{ $order->customer_phone }}</strong> within 24 hours to confirm the delivery window.
                </p>
            </div>
        </div>
    </div>

    <div class="mt-16 flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ route('home') }}" class="w-full sm:w-auto px-10 py-4 bg-dark text-white rounded-xl font-black text-lg hover:bg-neutral-800 transition-all shadow-xl active:scale-95 flex items-center justify-center gap-2">
            ← Back to Home
        </a>
        <a href="{{ route('products.index') }}" class="w-full sm:w-auto px-10 py-4 bg-white border-2 border-border/50 text-dark rounded-xl font-black text-lg hover:border-primary hover:text-primary transition-all active:scale-95">
            Continue Shopping
        </a>
    </div>
</div>
@endsection
