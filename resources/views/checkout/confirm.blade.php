@extends('layouts.app')

@section('title', 'Order Confirmed – ELECTROZONE AKKA')

@section('content')
<div class="container">
    <div class="confirm-page">
        <!-- Success Animation -->
        <div class="confirm-hero">
            <div class="confirm-icon">✅</div>
            <h1>Order Placed Successfully!</h1>
            <p>Thank you, <strong>{{ $order->customer_name }}</strong>! Your order has been received and is being processed.</p>
        </div>

        <div class="confirm-order-id">
            Order ID: <span>#{{ strtoupper(substr($order->id, 0, 8)) }}</span>
        </div>

        <div class="confirm-layout">
            <!-- Order Items -->
            <div class="confirm-items">
                <h3>📦 Items Ordered</h3>
                @foreach($order->items as $item)
                <div class="confirm-item">
                    <div class="confirm-item-qty">{{ $item['quantity'] }}×</div>
                    <div class="confirm-item-info">
                        <strong>{{ $item['name'] }}</strong>
                    </div>
                    <div class="confirm-item-price">{{ number_format($item['subtotal'], 2) }} MAD</div>
                </div>
                @endforeach

                <div class="confirm-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>{{ number_format($order->subtotal, 2) }} MAD</span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>{{ $order->shipping_cost == 0 ? 'FREE' : number_format($order->shipping_cost, 2) . ' MAD' }}</span>
                    </div>
                    <div class="summary-total">
                        <span>Total</span>
                        <span>{{ number_format($order->total_amount, 2) }} MAD</span>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="confirm-details">
                <h3>🚚 Delivery Information</h3>
                <div class="detail-row"><label>Name:</label><span>{{ $order->customer_name }}</span></div>
                <div class="detail-row"><label>Email:</label><span>{{ $order->customer_email }}</span></div>
                <div class="detail-row"><label>Phone:</label><span>{{ $order->customer_phone }}</span></div>
                <div class="detail-row">
                    <label>Address:</label>
                    <span>
                        {{ $order->shipping_address['address'] }},
                        {{ $order->shipping_address['city'] }}
                        @if($order->shipping_address['postal_code'])
                            {{ $order->shipping_address['postal_code'] }}
                        @endif
                    </span>
                </div>

                <div class="confirm-status">
                    <div class="status-badge status-new">🆕 New Order</div>
                    <p>We'll contact you at <strong>{{ $order->customer_phone }}</strong> to confirm delivery details.</p>
                </div>
            </div>
        </div>

        <div class="confirm-actions">
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">← Back to Store</a>
            <a href="{{ route('products.index') }}" class="btn btn-outline btn-lg">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
