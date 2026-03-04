@extends('layouts.admin')

@section('title', 'Add New Admin')
@section('page-title', 'Create Administrator')

@section('content')
<div class="admin-card">
    <div class="card-header">
        <h3>Admin Credentials</h3>
    </div>

    <form action="{{ route('admin.admins.store') }}" method="POST" class="admin-form">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                @error('name') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required>
                @error('email') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
                @error('password') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">💾 Create Admin</button>
            <a href="{{ route('admin.admins.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection
