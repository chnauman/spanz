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

        // Clean and normalize OTP input (remove spaces, ensure it's exactly 6 digits)
        $otp = trim($request->otp);
        $otp = preg_replace('/\D/', '', $otp); // Remove any non-digit characters
        
        // Check if OTP is exactly 6 digits
        if (strlen($otp) !== 6) {
            \Log::warning('OTP length invalid', [
                'user_id' => $user->id,
                'otp_received' => $otp,
                'otp_length' => strlen($otp)
            ]);
            return back()->withErrors(['otp' => 'OTP must be exactly 6 digits.'])->withInput();
        }
        
        // Get all OTPs for this user for debugging
        $allOtps = EmailVerificationOtp::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['otp', 'is_used', 'expires_at', 'created_at']);
        
        // Log for debugging
        \Log::info('OTP Verification Attempt', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'otp_received' => $otp,
            'otp_length' => strlen($otp),
            'recent_otps' => $allOtps->toArray()
        ]);

        // Verify OTP
        $isValid = EmailVerificationOtp::verifyOtp($user->id, $otp);

        if ($isValid) {
            // Mark email as verified
            $user->update([
                'email_verified_at' => now(),
            ]);

            // Refresh the user model from database to get the updated email_verified_at
            $user->refresh();
            
            // Re-authenticate the user to update the session with fresh data
            // This will automatically update the session with the refreshed user data
            Auth::login($user);

            \Log::info('Email verified successfully', [
                'user_id' => $user->id,
                'email_verified_at' => $user->email_verified_at,
                'auth_user_verified' => Auth::user()->email_verified_at
            ]);

            return redirect()->route('dashboard')->with('success', 'Email verified successfully! Welcome to Spanz.');
        }

        // Log failed verification attempt
        \Log::warning('OTP verification failed', [
            'user_id' => $user->id,
            'otp_attempted' => $otp
        ]);

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

