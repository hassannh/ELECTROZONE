@extends('layouts.admin')

@section('title', 'Manage Admins')
@section('page-title', 'Admins Management')

@section('content')
<div class="admin-card">
    <div class="card-header">
        <h3>System Administrators</h3>
        <a href="{{ route('admin.admins.create') }}" class="btn-create">➕ Add New Admin</a>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td><strong>{{ $admin->name }}</strong></td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                    <td class="admin-actions">
                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn-edit" title="Edit Admin Credentials">✏️ Edit</a>
                        
                        @if(auth('admin')->id() !== $admin->id && \App\Models\Admin::count() > 1)
                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Delete Admin">🗑️ Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
