<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the expires_at attribute as a Carbon instance
     */
    public function getExpiresAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    /**
     * Get the starts_at attribute as a Carbon instance
     */
    public function getStartsAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
