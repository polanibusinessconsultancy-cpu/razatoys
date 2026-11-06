<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Canonical E-Commerce Product Model for One Stop Mart.
 * Maps directly to 'products' table.
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'long_description',
        'price',
        'price_regular',
        'stock_qty',
        'instructor',
        'intro_video_url',
        'weekly_price',
        'monthly_price',
        'image_path',
        'category_id',
        'is_free',
        'purchase_model',
        'catalog_source',
        'source_key',
        'product_kind',
        'is_storefront_visible',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_storefront_visible' => 'boolean',
        'price' => 'float',
        'price_regular' => 'float',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }
        });
    }

    /**
     * Get resolved image URL with WebP fallback support.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return asset('one-stop-mart/images/category-placeholder.webp');
        }

        $path = trim((string) $this->image_path);
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim(str_replace('\\', '/', $path), '/');

        // Check if WebP version exists for PNG asset
        if (\Illuminate\Support\Str::endsWith($path, '.png')) {
            $webpPath = \Illuminate\Support\Str::beforeLast($path, '.png') . '.webp';
            if (is_file(public_path($webpPath))) {
                return asset($webpPath);
            }
        }

        if (is_file(public_path($path))) {
            return asset($path);
        }

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
        }

        return asset('one-stop-mart/images/category-placeholder.webp');
    }

    /**
     * Relationship: Product belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Product has many shopping cart entries.
     */
    public function shoppingCarts()
    {
        return $this->hasMany(Shoppingcart::class, 'product_id');
    }

    /**
     * Get all ratings for this product
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Get the average rating for this product
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->avg('rating') ?: 0;
    }

    /**
     * Get the total number of ratings for this product
     */
    public function getRatingCountAttribute()
    {
        return $this->ratings()
            ->where('is_approved', true)
            ->count();
    }

    /**
     * Get formatted price for display.
     */
    public function getPriceFormattedAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }
}
