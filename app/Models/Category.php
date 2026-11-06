<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'is_storefront_category',
    ];

    protected $casts = [
        'is_storefront_category' => 'boolean',
    ];

    /**
     * Get products belonging to this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Get courses belonging to this category.
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
