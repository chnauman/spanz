<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'currency',
        'category_id',
        'status',
        'images',
        'specs',
        'user_id',
        'featured',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'images' => 'array',
            'specs' => 'array',
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function hasPendingRequest($userId = null)
    {
        $query = $this->purchaseRequests()->where('status', 'pending');
        
        if ($userId) {
            $query->where('user_id', $userId);
        }
        
        return $query->exists();
    }
}


