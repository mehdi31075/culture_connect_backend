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
        'views_count', // Stored in DB, auto-incremented on fetch
        // likes_count and comments_count are calculated via accessors
        'is_trending',
        'trending_position',
        'trending_score',
        'preparation_time',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'views_count' => 'integer', // Stored in DB
        // likes_count and comments_count are calculated via accessors, not stored
        'is_trending' => 'boolean',
        'trending_position' => 'integer',
        'trending_score' => 'decimal:2',
        'preparation_time' => 'integer',
        'is_available' => 'boolean',
    ];

    protected $attributes = [
        'views_count' => 0,
        // likes_count and comments_count are calculated via accessors
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

    public function likes()
    {
        return $this->hasMany(FoodLike::class);
    }

    public function userLikes()
    {
        return $this->hasMany(FoodLike::class)->where('user_id', auth()->id());
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

    /**
     * Get likes count from food_likes table
     * This accessor overrides the database column value
     */
    public function getLikesCountAttribute($value)
    {
        // Always calculate from the relationship, ignore DB value
        return $this->likes()->count();
    }

    /**
     * Get comments count from reviews (reviews with comments)
     * This accessor overrides the database column value
     */
    public function getCommentsCountAttribute($value)
    {
        // Always calculate from the relationship, ignore DB value
        return $this->reviews()->whereNotNull('comment')->where('comment', '!=', '')->count();
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Check if authenticated user has liked this food
     */
    public function getIsLikedAttribute()
    {
        if (!auth()->check()) {
            return false;
        }
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}

