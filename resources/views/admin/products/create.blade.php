@extends('layouts.admin')

@section('title', 'Add Product')
@section('page-title', 'Add New Product')

@section('content')
<div class="admin-card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
        @csrf

        <div class="form-grid-2">
            <div class="form-group">
                <label>Product Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="input {{ $errors->has('name') ? 'input-error' : '' }}" required>
                @error('name')<span class="error-msg">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label>Brand *</label>
                <input type="text" name="brand" value="{{ old('brand') }}" class="input" required>
            </div>
        </div>

        <div class="form-group">
            <label>Short Description * (max 500 chars)</label>
            <input type="text" name="short_description" value="{{ old('short_description') }}" class="input" required maxlength="500">
        </div>

        <div class="form-group">
            <label>Full Description *</label>
            <textarea name="description" rows="5" class="input textarea" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Price (MAD) *</label>
                <input type="number" name="price" value="{{ old('price') }}" class="input" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label>Old Price (MAD) <small>optional, for sale</small></label>
                <input type="number" name="old_price" value="{{ old('old_price') }}" class="input" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label>Stock Quantity *</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" class="input" min="0" required>
            </div>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="input">
                <option value="">– No Category –</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @foreach($cat->children as $child)
                        <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;└ {{ $child->name }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Specifications <small>One per line: Key: Value</small></label>
            <textarea name="specifications" rows="5" class="input textarea font-mono" placeholder="RAM: 8GB&#10;Storage: 256GB&#10;Battery: 5000mAh">{{ old('specifications') }}</textarea>
        </div>

        <div class="form-group">
            <label>Key Features <small>One per line</small></label>
            <textarea name="features" rows="4" class="input textarea" placeholder="Feature 1&#10;Feature 2&#10;Feature 3">{{ old('features') }}</textarea>
        </div>

        <div class="form-group">
            <label>Product Images <small>(Max 5, JPG/PNG/WebP, max 5MB each)</small></label>
            <input type="file" name="images[]" multiple accept="image/*" class="input-file">
        </div>

        <div class="checkbox-grid">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                <span>Active (visible on store)</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                <span>⭐ Featured</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new') ? 'checked' : '' }}>
                <span>🆕 New Arrival</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale') ? 'checked' : '' }}>
                <span>🔥 On Sale</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">Save Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
