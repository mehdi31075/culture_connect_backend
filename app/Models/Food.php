<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'name',
        'description',
        'price',
        'images',
        'views_count',
        'likes_count',
        'comments_count',
        'is_trending',
        'trending_position',
        'trending_score',
        'preparation_time',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'is_trending' => 'boolean',
        'trending_position' => 'integer',
        'trending_score' => 'decimal:2',
        'preparation_time' => 'integer',
        'is_available' => 'boolean',
    ];

    protected $attributes = [
        'views_count' => 0,
        'likes_count' => 0,
        'comments_count' => 0,
        'is_trending' => false,
        'is_available' => true,
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'food_tag_maps', 'food_id', 'tag_id')
            ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Ensure price is returned as a float (double) with 2 decimal places in JSON responses
     */
    public function getPriceAttribute($value)
    {
        return $value !== null ? (float) number_format((float) $value, 2, '.', '') : null;
    }

    /**
     * Ensure images array contains complete URLs
     */
    public function getImagesAttribute($value)
    {
        if (!$value || !is_array($value)) {
            return [];
        }

        return array_map(function ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                return $image;
            }

            if (str_starts_with($image, '/storage/')) {
                return url($image);
            }

            if (str_starts_with($image, 'storage/')) {
                return url($image);
            }

            if (!str_starts_with($image, 'http')) {
                return url('storage/' . ltrim($image, '/'));
            }

            return $image;
        }, $value);
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
}

