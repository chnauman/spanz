<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderView extends Model
{
    protected $fillable = [
        'user_id',
        'tender_id',
        'viewed_at',
        'credits_deducted',
        'credit_cost_per_view',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
            'credits_deducted' => 'integer',
            'credit_cost_per_view' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    /**
     * Check if user has already viewed this tender
     */
    public static function hasUserViewedTender($userId, $tenderId)
    {
        return static::where('user_id', $userId)
            ->where('tender_id', $tenderId)
            ->exists();
    }

    /**
     * Record a tender view
     */
    public static function recordView($userId, $tenderId, $creditsDeducted, $creditCostPerView)
    {
        return static::create([
            'user_id' => $userId,
            'tender_id' => $tenderId,
            'viewed_at' => now(),
            'credits_deducted' => $creditsDeducted,
            'credit_cost_per_view' => $creditCostPerView,
        ]);
    }
}
