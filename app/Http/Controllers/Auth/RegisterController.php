<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierInvitation;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $invitation = null;

        // Check if user is coming from an invitation
        if ($request->has('token')) {
            $invitation = SupplierInvitation::where('token', $request->token)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if (!$invitation) {
                return redirect()->route('register')->with('error', 'Invalid or expired invitation link.');
            }
        }

        return view('auth.register', compact('invitation'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'nullable|string',
        ]);

        $invitation = null;
        $role = 'buyer'; // Default role
        $isApproved = true;
        $parentSupplierId = null;

        // Check if user is registering via invitation
        if ($request->has('token') && $request->token) {
            $invitation = SupplierInvitation::where('token', $request->token)
                ->where('is_used', false)
                ->where('expires_at', '>', now())
                ->first();

            if ($invitation) {
                $role = 'sub_supplier';
                $isApproved = true; // Auto-approve since supplier already invited them
                $parentSupplierId = $invitation->supplier_id;
            }
        }

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $role,
            'is_approved' => $isApproved,
            'parent_supplier_id' => $parentSupplierId,
        ]);

        // Mark invitation as used if it was a sub-supplier registration
        if ($invitation) {
            $invitation->markAsUsed();
        }

        auth()->login($user);

        $message = $role === 'sub_supplier'
            ? 'Account created successfully. You are now a sub-supplier.'
            : 'Account created successfully.';

        return redirect('/dashboard')->with('success', $message);
    }
}
