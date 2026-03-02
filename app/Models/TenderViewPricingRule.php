<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenderViewPricingRule extends Model
{
    protected $fillable = [
        'budget_min',
        'budget_max',
        'credits_cost',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'credits_cost' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function matchForBudget(?float $budget): ?self
    {
        if ($budget === null || $budget <= 0) {
            return null;
        }

        return static::query()
            ->active()
            ->where('budget_min', '<=', $budget)
            ->where(function ($q) use ($budget) {
                $q->whereNull('budget_max')->orWhere('budget_max', '>=', $budget);
            })
            ->orderBy('budget_min', 'desc')
            ->first();
    }
}

