<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DowngradeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'current_subscription_id',
        'status',
        'reason',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function currentSubscription()
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id');
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Approve the downgrade request
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => $adminId,
            'admin_notes' => $notes,
        ]);

        // Log the approval - the actual downgrade will be processed by the cron job
        $user = $this->user;
        Log::info("Downgrade request approved for user {$user->email}", [
            'user_id' => $user->id,
            'current_subscription' => $this->currentSubscription->name,
            'approved_by' => $adminId
        ]);
    }

    // Decline the downgrade request
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
