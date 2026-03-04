<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminProfileController extends Controller
{
    public function showChangePassword()
    {
        return view('admin.profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password:admin',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = auth('admin')->user();
        $admin->password = Hash::make($request->password);
        /** @var \App\Models\Admin $admin */
        $admin->save();

        return back()->with('success', 'Password changed successfully.');
    }
}
