<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBudgetRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'min_budget',
        'max_budget',
        'currency',
        'budget_type',
    ];

    protected function casts(): array
    {
        return [
            'min_budget' => 'decimal:2',
            'max_budget' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCurrencyAttribute($value): string
    {
        return 'AUD';
    }

    public function setCurrencyAttribute($value): void
    {
        $this->attributes['currency'] = 'AUD';
    }

    /**
     * Get formatted budget range string
     */
    public function getFormattedBudgetRangeAttribute()
    {
        switch ($this->budget_type) {
            case 'less':
                return 'Less than ' . number_format($this->max_budget, 2) . ' ' . $this->currency;
            case 'greater':
                return 'Greater than ' . number_format($this->min_budget, 2) . ' ' . $this->currency;
            case 'range':
                $min = $this->min_budget ? number_format($this->min_budget, 2) : '0';
                $max = $this->max_budget ? number_format($this->max_budget, 2) : '∞';
                return $min . ' - ' . $max . ' ' . $this->currency;
            default:
                return 'No budget set';
        }
    }

    /**
     * Get budget type color for UI
     */
    public function getBudgetTypeColorAttribute()
    {
        switch ($this->budget_type) {
            case 'less':
                return 'bg-red-100 text-red-800 border-red-200';
            case 'greater':
                return 'bg-green-100 text-green-800 border-green-200';
            case 'range':
                return 'bg-blue-100 text-blue-800 border-blue-200';
            default:
                return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    }

    /**
     * Get formatted range for display (alias for formatted_budget_range)
     */
    public function getFormattedRangeAttribute()
    {
        return $this->formatted_budget_range;
    }
}
