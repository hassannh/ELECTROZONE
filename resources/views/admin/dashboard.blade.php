@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-blue">
        <div class="stat-icon">📦</div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($totalOrders) }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="stat-card stat-green">
        <div class="stat-icon">💰</div>
        <div class="stat-body">
            <div class="stat-value">{{ number_format($totalRevenue, 0) }} MAD</div>
            <div class="stat-label">Revenue (Paid)</div>
        </div>
    </div>
    <div class="stat-card stat-orange">
        <div class="stat-icon">🆕</div>
        <div class="stat-body">
            <div class="stat-value">{{ $newOrders }}</div>
            <div class="stat-label">New Orders</div>
        </div>
    </div>
    <div class="stat-card stat-purple">
        <div class="stat-icon">🛍️</div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-label">Products</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <!-- Recent Orders -->
    <div class="admin-card">
        <div class="card-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm">View All</a>
        </div>

        <!-- Desktop Table View -->
        <div class="table-responsive desktop-only">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><code>#{{ strtoupper(substr($order->id, 0, 8)) }}</code></td>
                        <td>
                            <div class="customer-info">
                                <strong>{{ $order->customer_name }}</strong>
                                <small>{{ $order->customer_email }}</small>
                            </div>
                        </td>
                        <td>{{ number_format($order->total_amount, 2) }} MAD</td>
                        <td>
                            <span class="status-pill status-{{ $order->order_status }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-xs">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="mobile-only">
            @foreach($recentOrders as $order)
            <div class="mobile-dashboard-card">
                <div class="mobile-card-row">
                    <span class="mobile-card-label">ID:</span>
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
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-ghost btn-xs">View Order</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Low Stock Alert -->
    <div class="admin-card">
        <div class="card-header">
            <h3>⚠️ Low Stock Alert</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm">All Products</a>
        </div>
        @if($lowStock->count())
        <div class="low-stock-list">
            @foreach($lowStock as $product)
            <div class="low-stock-item">
                <div class="low-stock-info">
                    <strong>{{ $product->name }}</strong>
                    <small>{{ $product->brand }}</small>
                </div>
                <div class="stock-progress-wrap desktop-only">
                    @php 
                        $pct = min(100, ($product->stock_quantity / 10) * 100); 
                        $colorClass = $product->stock_quantity <= 2 ? 'fill-critical' : ($product->stock_quantity <= 5 ? 'fill-warning' : 'fill-good');
                    @endphp
                    <div class="stock-progress-bg">
                        <div class="stock-progress-fill {{ $colorClass }}" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
                <div class="low-stock-qty {{ $product->stock_quantity <= 2 ? 'critical' : 'warning' }}">
                    {{ $product->stock_quantity }}
                </div>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-ghost btn-xs">Edit</a>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state-sm">✅ All products are well-stocked!</div>
        @endif
        @if($outOfStock > 0)
        <div class="out-stock-warning">
            🚫 {{ $outOfStock }} products are out of stock.
            <a href="{{ route('admin.products.index') }}">Manage →</a>
        </div>
        @endif
    </div>
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
