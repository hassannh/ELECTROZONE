@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Category Management')

@section('content')
<div class="page-actions">
    <a href="{{ route('admin.categories.create') }}" class="btn btn-accent">+ Add Category</a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr><th>Icon</th><th>Name</th><th>Slug</th><th>Parent</th><th>Active</th><th>Products</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->icon ?? '📦' }}</td>
                <td><strong>{{ $category->name }}</strong></td>
                <td><code>{{ $category->slug }}</code></td>
                <td>—</td>
                <td>{{ $category->is_active ? '✅' : '❌' }}</td>
                <td>{{ $category->products()->count() }}</td>
                <td class="table-actions">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-ghost btn-xs">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete?')" style="display:inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger-ghost btn-xs">Delete</button>
                    </form>
                </td>
            </tr>
            @if($category->children->count())
                @foreach($category->children as $child)
                <tr class="sub-row">
                    <td>{{ $child->icon ?? '📦' }}</td>
                    <td>&nbsp;&nbsp;&nbsp;└ {{ $child->name }}</td>
                    <td><code>{{ $child->slug }}</code></td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $child->is_active ? '✅' : '❌' }}</td>
                    <td>{{ $child->products()->count() }}</td>
                    <td class="table-actions">
                        <a href="{{ route('admin.categories.edit', $child) }}" class="btn btn-ghost btn-xs">Edit</a>
                        <form action="{{ route('admin.categories.destroy', $child) }}" method="POST" onsubmit="return confirm('Delete?')" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger-ghost btn-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection
