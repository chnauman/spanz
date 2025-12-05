<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerificationOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate a 6-digit OTP
     */
    public static function generateOtp()
    {
        return str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP for user
     */
    public static function createOtp($userId)
    {
        // Invalidate all previous unused OTPs for this user
        self::where('user_id', $userId)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Create new OTP
        return self::create([
            'user_id' => $userId,
            'otp' => self::generateOtp(),
            'expires_at' => Carbon::now()->addMinutes(15), // OTP expires in 15 minutes
        ]);
    }

    /**
     * Verify OTP
     */
    public static function verifyOtp($userId, $otp)
    {
        // Normalize OTP (ensure it's a string and trim whitespace)
        $otp = trim((string) $otp);
        
        // Find matching OTP record
        $otpRecord = self::where('user_id', $userId)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            // Mark OTP as used
            $otpRecord->update(['is_used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Check if user has a valid unused OTP
     */
    public static function hasValidOtp($userId)
    {
        return self::where('user_id', $userId)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->exists();
    }

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


