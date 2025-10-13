<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'budget',
        'currency',
        'deadline',
        'status',
        'requirements',
        'location',
        'contact_email',
        'contact_phone',
        'request_type',
        'categories',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'budget' => 'decimal:2',
            'categories' => 'array',
            'attachments' => 'array',
        ];
    }

    // Override the getAttribute method to handle JSON decoding and datetime casting
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, ['categories', 'attachments']) && is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : [];
        }
        
        // Ensure deadline is always a Carbon instance
        if ($key === 'deadline' && is_string($value)) {
            return \Carbon\Carbon::parse($value);
        }
        
        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invitations()
    {
        return $this->hasMany(TenderInvitation::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->deadline > now();
    }

    public function isExpired()
    {
        return $this->deadline < now();
    }

    public function getFormattedDeadline($format = 'd F Y')
    {
        if (!$this->deadline) {
            return 'Not specified';
        }
        
        return \Carbon\Carbon::parse($this->deadline)->format($format);
    }
}
