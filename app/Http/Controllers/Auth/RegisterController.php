<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierInvitation;
use App\Models\EmailVerificationOtp;

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
            'email_verified_at' => null, // Email not verified yet
        ]);

        // Mark invitation as used if it was a sub-supplier registration
        if ($invitation) {
            $invitation->markAsUsed();
        }

        // Generate and send OTP
        $otpRecord = EmailVerificationOtp::createOtp($user->id);

        // Send email verification notification (same pattern as password reset)
        try {
            $user->notify(new \App\Notifications\EmailVerificationNotification($otpRecord->otp));
        } catch (\Exception $e) {
            // Log error but continue with registration
            \Log::error('Failed to send email verification OTP: ' . $e->getMessage());
        }

        // Login the user
        auth()->login($user);

        // Redirect to email verification page
        return redirect()->route('email.verify.show')->with('success', 'Registration successful! Please verify your email address with the OTP sent to your inbox.');
    }
}
