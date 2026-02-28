<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login – ELECTROZONE AKKA</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/electrozone.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-login-body">
    <div class="login-container">
        <div class="login-card">
            <div class="login-brand">
                <div class="login-icon">⚡</div>
                <h1>ELECTROZONE AKKA</h1>
                <p>Admin Panel</p>
            </div>

            @if(session('error'))
            <div class="admin-alert admin-alert-error">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="login-form">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                           class="input {{ $errors->has('email') ? 'input-error' : '' }}"
                           placeholder="admin@electrozone.ma" required autofocus>
                    @error('email')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password"
                           class="input" placeholder="••••••••" required>
                </div>
                <label class="remember-label">
                    <input type="checkbox" name="remember"> Stay signed in
                </label>
                <button type="submit" class="btn btn-primary btn-lg btn-block">
                    Sign In →
                </button>
            </form>
        </div>
    </div>
</body>
</html>
