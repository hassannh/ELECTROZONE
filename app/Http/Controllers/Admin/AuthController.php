<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Unique key per email+IP combination to prevent both credential stuffing and distributed attacks
        $key = 'admin-login:' . Str::lower($request->email) . '|' . $request->ip();

        // Block if already locked out (decays after 5 minutes)
        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()
                ->withErrors(['email' => "Too many login attempts. Please try again in {$seconds} second(s)."])
                ->withInput($request->only('email'));
        }

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($key); // Successful login clears the attempt counter
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Record failed attempt; lock after 5 failures for 5 minutes
        RateLimiter::hit($key, 300);

        $remaining = RateLimiter::remaining($key, 5);
        return back()
            ->withErrors(['email' => "Invalid credentials. {$remaining} attempt(s) remaining before lockout."])
            ->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
