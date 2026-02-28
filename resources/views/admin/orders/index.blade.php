@extends('layouts.admin')

@section('title', 'Orders')
@section('page-title', 'Order Management')

@section('content')
<div class="page-actions">
    <form method="GET" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." class="input input-sm">
        <select name="status" class="input input-sm">
            <option value="">All Statuses</option>
            @foreach(['new','processing','shipped','delivered','cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm">Reset</a>
    </form>
</div>

<div class="admin-card">
    <div class="table-responsive desktop-only">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Order Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><code>#{{ strtoupper(substr($order->id, 0, 8)) }}</code></td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong>
                        <small>{{ $order->customer_phone }}</small>
                    </td>
                    <td>{{ count($order->items) }} item(s)</td>
                    <td>{{ number_format($order->total_amount, 2) }} MAD</td>
                    <td><span class="status-pill status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span></td>
                    <td><span class="status-pill status-pay-{{ $order->payment_status }}">{{ ucfirst($order->payment_status) }}</span></td>
                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-xs">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
    <div class="mobile-only">
        @foreach($orders as $order)
        <div class="mobile-dashboard-card">
            <div class="mobile-card-row">
                <span class="mobile-card-label">Order:</span>
                <span class="mobile-card-value">#{{ strtoupper(substr($order->id, 0, 8)) }}</span>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Customer:</span>
                <span class="mobile-card-value">{{ $order->customer_name }}</span>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Total:</span>
                <span class="mobile-card-value">{{ number_format($order->total_amount, 0) }} MAD</span>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Status:</span>
                <span class="status-pill status-{{ $order->order_status }}">{{ ucfirst($order->order_status) }}</span>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-xs">View Details</a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination-wrap">{{ $orders->links() }}</div>
</div>

@push('styles')
<style>
    .desktop-only { display: block; }
    .mobile-only { display: none; }
    @media(max-width: 768px) {
        .desktop-only { display: none; }
        .mobile-only { display: block; }
    }
</style>
@endpush
@endsection
