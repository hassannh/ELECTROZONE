@extends('layouts.admin')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Administrator')

@section('content')
<div class="admin-card">
    <div class="card-header">
        <h3>Update Credentials</h3>
    </div>

    <form action="{{ route('admin.admins.update', $admin) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')
        
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
                @error('name') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
                @error('email') <span class="text-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <hr class="form-divider">
        <h4 class="form-subtitle">Change Password (Leave blank to keep current)</h4>

        <div class="form-grid">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-input">
                @error('password') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">💾 Save Changes</button>
            <a href="{{ route('admin.admins.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection
