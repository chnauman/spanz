<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:buyer,supplier',
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'is_approved' => $request->role === 'buyer', // Buyers are auto-approved, suppliers need approval
        ]);

        auth()->login($user);

        if ($user->role === 'supplier') {
            return redirect('/dashboard')->with('success', 'Account created successfully. Your supplier account is pending admin approval.');
        }

        return redirect('/dashboard')->with('success', 'Account created successfully.');
    }
}
