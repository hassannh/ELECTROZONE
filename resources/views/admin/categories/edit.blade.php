@extends('layouts.admin')

@section('title', 'Edit: ' . $category->name)
@section('page-title', 'Edit Category')

@section('content')
<div class="admin-card" style="max-width:500px">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="input" required>
        </div>
        <div class="form-group">
            <label>Icon</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="input" placeholder="📱">
        </div>
        <div class="form-group">
            <label>Parent Category</label>
            <select name="parent_id" class="input">
                <option value="">– Top Level –</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="checkbox-label">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
            <span>Active</span>
        </label>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
