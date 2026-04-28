<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'parent_category_id',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_category_id');
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class, 'category_id');
    }

    public function isParent()
    {
        return $this->subcategories()->exists();
    }

    public function isChild()
    {
        return !is_null($this->parent_category_id);
    }
}
