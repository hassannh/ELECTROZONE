@extends('layouts.admin')

@section('title', 'Add Category')
@section('page-title', 'Add Category')

@section('content')
<div class="admin-card" style="max-width:500px">
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Name *</label>
            <input type="text" name="name" value="{{ old('name') }}" class="input" required>
        </div>
        <div class="form-group">
            <label>Icon (emoji or symbol)</label>
            <input type="text" name="icon" value="{{ old('icon') }}" class="input" placeholder="📱">
        </div>
        <div class="form-group">
            <label>Parent Category <small>(leave empty for top-level)</small></label>
            <select name="parent_id" class="input">
                <option value="">– Top Level –</option>
                @foreach($parents as $parent)
                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <label class="checkbox-label">
            <input type="checkbox" name="is_active" value="1" checked>
            <span>Active</span>
        </label>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Category</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
