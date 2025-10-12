<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRequest extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'status',
        'admin_notes',
        'requested_at',
        'processed_at',
        'processed_by',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'processed_at' => 'datetime',
        ];
    }

    // Accessor to ensure requested_at is always a Carbon instance
    public function getRequestedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    // Accessor to ensure processed_at is always a Carbon instance
    public function getProcessedAtAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Status check methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isDeclined()
    {
        return $this->status === 'declined';
    }

    // Approve the request
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'admin_notes' => $notes,
        ]);

        // Create user subscription
        \App\Models\UserSubscription::create([
            'user_id' => $this->user_id,
            'subscription_id' => $this->subscription_id,
            'starts_at' => now(),
            'expires_at' => now()->addMonth(), // Default 1 month subscription
            'is_active' => true,
        ]);
    }

    // Decline the request
    public function decline($adminId, $notes = null)
    {
        $this->update([
            'status' => 'declined',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'admin_notes' => $notes,
        ]);
    }
}
