<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (auth()->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user is approved (for suppliers and sub-suppliers)
            if (in_array(auth()->user()->role, ['supplier', 'sub_supplier']) && !auth()->user()->is_approved) {
                auth()->logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval. Please contact administrator.',
                ]);
            }

            // Check for redirect parameter
            $redirectTo = $request->get('redirect', '/dashboard');
            return redirect($redirectTo);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
