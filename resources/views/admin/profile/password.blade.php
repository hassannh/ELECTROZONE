@extends('layouts.admin')

@section('title', 'Change Password')
@section('page-title', 'Profile Settings')

@section('content')
<div class="admin-card">
    <div class="card-header">
        <h3>Security Settings</h3>
    </div>

    <form action="{{ route('admin.profile.password.update') }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')
        
        <div class="form-grid" style="max-width: 500px;">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="form-input" required>
                @error('current_password') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
                @error('password') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-save">💾 Update Password</button>
        </div>
    </form>
</div>
@endsection
