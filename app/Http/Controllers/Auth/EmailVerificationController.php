<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification form
     */
    public function show(Request $request)
    {
        // If user is not authenticated, redirect to login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to verify your email.');
        }

        $user = Auth::user();

        // If email is already verified, redirect to dashboard
        if ($user->email_verified_at) {
            return redirect()->route('dashboard')->with('info', 'Your email is already verified.');
        }

        return view('auth.verify-email', compact('user'));
    }

    /**
     * Verify the OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        // Check if email is already verified
        if ($user->email_verified_at) {
            return redirect()->route('dashboard')->with('info', 'Your email is already verified.');
        }

        // Verify OTP
        $isValid = EmailVerificationOtp::verifyOtp($user->id, $request->otp);

        if ($isValid) {
            // Mark email as verified
            $user->update([
                'email_verified_at' => now(),
            ]);

            // Refresh the authenticated user instance to reflect the changes
            $user->refresh();
            Auth::setUser($user);

            return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome to Spanz.');
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again or request a new one.'])->withInput();
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        // Check if email is already verified
        if ($user->email_verified_at) {
            return redirect()->route('dashboard')->with('info', 'Your email is already verified.');
        }

        // Create new OTP
        $otpRecord = EmailVerificationOtp::createOtp($user->id);

        // Send email verification notification (same pattern as password reset)
        try {
            $user->notify(new \App\Notifications\EmailVerificationNotification($otpRecord->otp));
            
            return back()->with('success', 'A new OTP has been sent to your email address.');
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to send OTP email: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'exception' => $e
            ]);
            
            return back()->withErrors(['error' => 'Failed to send OTP. Please try again later.']);
        }
    }
}

