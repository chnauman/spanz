<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SupplierInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'email',
        'name',
        'supplier_id',
        'message',
        'is_used',
        'expires_at'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime'
    ];

    /**
     * Generate a unique token for the invitation
     */
    public static function generateToken()
    {
        do {
            $token = Str::random(60);
        } while (self::where('token', $token)->exists());

        return $token;
    }

    /**
     * Create a new invitation
     */
    public static function createInvitation($supplierId, $email, $name, $message = null)
    {
        return self::create([
            'token' => self::generateToken(),
            'email' => $email,
            'name' => $name,
            'supplier_id' => $supplierId,
            'message' => $message,
            'expires_at' => Carbon::now()->addDays(7) // Expires in 7 days
        ]);
    }

    /**
     * Check if invitation is valid
     */
    public function isValid()
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }

    /**
     * Mark invitation as used
     */
    public function markAsUsed()
    {
        $this->update(['is_used' => true]);
    }

    /**
     * Get the supplier who sent the invitation
     */
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
