@extends('layouts.admin')

@section('title', 'Edit: ' . $product->name)
@section('page-title', 'Edit Product')

@section('content')
<div class="admin-card">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="product-form">
        @csrf @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label>Product Name <span class="text-primary">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="input" required>
            </div>
            <div class="form-group">
                <label>Brand</label>
                <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" class="input">
            </div>
        </div>

        <div class="form-group">
            <label>Short Description</label>
            <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="input">
        </div>

        <div class="form-group">
            <label>Full Description</label>
            <textarea name="description" rows="5" class="input textarea">{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label>Price (MAD)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="input" step="0.01" min="0">
            </div>
            <div class="form-group">
                <label>Old Price (MAD)</label>
                <input type="number" name="old_price" value="{{ old('old_price', $product->old_price) }}" class="input" step="0.01" min="0">
            </div>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="input">
                <option value="">– No Category –</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @foreach($cat->children as $child)
                        <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }}>&nbsp;&nbsp;&nbsp;└ {{ $child->name }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Specifications <small>Key: Value per line</small></label>
            <textarea name="specifications" rows="5" class="input textarea font-mono">{{ old('specifications', $product->specifications ? collect($product->specifications)->map(fn($v,$k) => "$k: $v")->implode("\n") : '') }}</textarea>
        </div>

        <div class="form-group">
            <label>Key Features <small>One per line</small></label>
            <textarea name="features" rows="4" class="input textarea">{{ old('features', $product->features ? implode("\n", $product->features) : '') }}</textarea>
        </div>

        <!-- Current Images -->
        @if($product->images->count())
        <div class="form-group">
            <label>Current Images</label>
            <div class="current-images">
                @foreach($product->images as $img)
                <div class="current-img-item">
                    <img src="{{ asset('storage/' . $img->path) }}" alt="">
                    <form action="{{ route('admin.products.deleteImage', $img) }}" method="POST" onsubmit="return confirm('Remove this image?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="img-delete-btn">✕</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="form-group">
            <label>Add More Images <small>(Select multiple files. Max 20, JPG/PNG/WebP, max 5MB each)</small></label>
            <input type="file" name="images[]" multiple accept="image/*" class="input-file">
        </div>

        <div class="checkbox-grid">
            <label class="checkbox-label">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <span>Active</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                <span>⭐ Featured</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                <span>🆕 New</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="is_on_sale" value="1" {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}>
                <span>🔥 On Sale</span>
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
