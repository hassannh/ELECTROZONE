@extends('layouts.admin')

@section('title', 'Order #' . strtoupper(substr($order->id, 0, 8)))
@section('page-title', 'Order Details')

@section('content')
<div class="order-detail-layout">
    <!-- Left: Order Info -->
    <div>
        <div class="admin-card">
            <div class="card-header">
                <h3>Order #{{ strtoupper(substr($order->id, 0, 8)) }}</h3>
                <span class="status-pill status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
            </div>

            <div class="order-items-table">
                @foreach($order->items as $item)
                <div class="order-item-row">
                    <div class="order-item-qty">{{ $item['quantity'] }}×</div>
                    <div class="order-item-name">{{ $item['name'] }}</div>
                    <div class="order-item-price">{{ number_format($item['subtotal'], 2) }} MAD</div>
                </div>
                @endforeach
            </div>
            <div class="order-totals">
                <div class="summary-row"><span>Subtotal</span><span>{{ number_format($order->subtotal, 2) }} MAD</span></div>
                <div class="summary-row"><span>Shipping</span><span>{{ number_format($order->shipping_cost, 2) }} MAD</span></div>
                <div class="summary-total"><span>Total</span><span>{{ number_format($order->total_amount, 2) }} MAD</span></div>
            </div>
        </div>

        <div class="admin-card">
            <h3>Customer Information</h3>
            <div class="detail-row"><label>Name:</label><span>{{ $order->customer_name }}</span></div>
            <div class="detail-row"><label>Email:</label><span><a href="mailto:{{ $order->customer_email }}">{{ $order->customer_email }}</a></span></div>
            <div class="detail-row"><label>Phone:</label><span>{{ $order->customer_phone }}</span></div>
            <div class="detail-row"><label>City:</label><span>{{ $order->shipping_address['city'] }}</span></div>
            <div class="detail-row"><label>Address:</label><span>{{ $order->shipping_address['address'] }}</span></div>
            @if($order->notes)
            <div class="detail-row"><label>Notes:</label><span>{{ $order->notes }}</span></div>
            @endif
        </div>
    </div>

    <!-- Right: Update Status -->
    <div class="admin-card" style="align-self: start">
        <h3>Update Order</h3>
        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="update-form">
            @csrf @method('PUT')

            <div class="form-group">
                <label>Order Status</label>
                <select name="order_status" class="input">
                    @foreach(['new','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ $order->order_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Payment Status</label>
                <select name="payment_status" class="input">
                    @foreach(['pending','paid','failed'] as $s)
                    <option value="{{ $s }}" {{ $order->payment_status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Tracking Number</label>
                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" class="input" placeholder="e.g. MA123456789">
            </div>

            <button type="submit" class="btn btn-primary btn-block">Update Order</button>
        </form>

        <div class="order-meta">
            <small>Created: {{ $order->created_at->format('d/m/Y H:i') }}</small>
            <small>Updated: {{ $order->updated_at->format('d/m/Y H:i') }}</small>
        </div>
    </div>
</div>
@endsection
