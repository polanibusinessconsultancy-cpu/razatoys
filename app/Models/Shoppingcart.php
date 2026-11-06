<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shoppingcart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'shoppingcarts';

    protected $fillable = [
        'quantity',
        'product_id',
        'price',
        'user_id',
        'price_type',
        'original_price',
        'discount_amount',
        'discount_reason',
    ];

    /**
     * Canonical Relationship: ShoppingCart belongs to a Product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship: ShoppingCart belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Effective product ID.
     */
    public function getEffectiveProductIdAttribute()
    {
        return $this->product_id;
    }

    public function getItemNameAttribute()
    {
        return $this->product?->name ?? 'Product';
    }
}
