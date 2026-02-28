@extends('layouts.admin')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div class="page-actions">
    <form method="GET" class="search-form">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="input input-sm">
        <select name="category_id" class="input input-sm">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm">Reset</a>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-accent">+ Add Product</a>
</div>

<div class="admin-card">
    <div class="table-responsive desktop-only">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>
                        <div class="product-row">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->path) }}" class="table-thumb">
                            @else
                                <div class="table-thumb-placeholder">📦</div>
                            @endif
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <small>{{ $product->brand }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>{{ number_format($product->price, 2) }} MAD</td>
                    <td>
                        <span class="{{ $product->stock_quantity == 0 ? 'text-danger' : ($product->stock_quantity <= 5 ? 'text-warning' : 'text-success') }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td>
                        <div class="status-flags">
                            @if($product->is_active)<span class="flag flag-green">Active</span>@else<span class="flag flag-red">Hidden</span>@endif
                            @if($product->is_featured)<span class="flag flag-blue">⭐</span>@endif
                            @if($product->is_on_sale)<span class="flag flag-orange">Sale</span>@endif
                        </div>
                    </td>
                    <td class="table-actions">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-ghost btn-xs">Edit</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger-ghost btn-xs" title="Delete Product">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
    <div class="mobile-only">
        @foreach($products as $product)
        <div class="mobile-dashboard-card">
            <div class="mobile-card-row">
                <div class="product-row">
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->path) }}" class="table-thumb">
                    @else
                        <div class="table-thumb-placeholder">📦</div>
                    @endif
                    <div>
                        <strong>{{ $product->name }}</strong>
                        <small>{{ $product->brand }}</small>
                    </div>
                </div>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Price:</span>
                <span class="mobile-card-value">{{ number_format($product->price, 0) }} MAD</span>
            </div>
            <div class="mobile-card-row">
                <span class="mobile-card-label">Stock:</span>
                <span class="mobile-card-value {{ $product->stock_quantity == 0 ? 'text-danger' : ($product->stock_quantity <= 5 ? 'text-warning' : 'text-success') }}">
                    {{ $product->stock_quantity }}
                </span>
            </div>
            <div class="mobile-card-actions">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-ghost btn-xs">Edit</a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger-ghost btn-xs">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div class="pagination-wrap">{{ $products->links() }}</div>
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
