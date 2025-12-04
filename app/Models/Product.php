<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'discounted_price',
        'images',
        'views_count',
        'is_trending',
        'trending_position',
        'trending_score',
        'preparation_time',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discounted_price' => 'decimal:2',
        'images' => 'array',
        'views_count' => 'integer',
        'is_trending' => 'boolean',
        'trending_position' => 'integer',
        'trending_score' => 'decimal:2',
        'preparation_time' => 'string',
        'is_available' => 'boolean',
    ];

    protected $attributes = [
        'views_count' => 0,
        'is_trending' => false,
        'is_available' => true,
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_maps', 'product_id', 'tag_id');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Ensure price is returned as a float (double) with 2 decimal places in JSON responses
     */
    public function getPriceAttribute($value)
    {
        return $value !== null ? (float) number_format((float) $value, 2, '.', '') : null;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Ensure images array contains complete URLs
     */
    public function getImagesAttribute($value)
    {
        // Access raw attribute from database (JSON string)
        $rawValue = $this->attributes['images'] ?? null;

        // If null or empty, return empty array
        if ($rawValue === null || $rawValue === '') {
            return [];
        }

        // Decode JSON string to array
        $images = is_string($rawValue)
            ? json_decode($rawValue, true)
            : $rawValue;

        // If decoding failed or not an array, return empty array
        if (!is_array($images) || empty($images)) {
            return [];
        }

        // Process each image URL to ensure full URLs
        return array_values(array_filter(array_map(function ($image) {
            if (empty($image) || !is_string($image)) {
                return null;
            }

            // Already a full URL
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            // Handle relative paths
            if (str_starts_with($image, '/storage/')) {
                return url($image);
            }

            if (str_starts_with($image, 'storage/')) {
                return url('/' . $image);
            }

            // Assume relative path, prepend storage
            if (!str_starts_with($image, 'http')) {
                return url('storage/' . ltrim($image, '/'));
            }

            return $image;
        }, $images)));
    }

    /**
     * Get average rating from reviews
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Get total reviews count
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
