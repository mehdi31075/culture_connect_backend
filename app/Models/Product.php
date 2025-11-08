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
        'is_food',
        'image_url',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_food' => 'boolean',
    ];

    protected $attributes = [
        'is_food' => false,
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function productTags()
    {
        return $this->hasMany(ProductTag::class);
    }

    public function tags()
    {
        return $this->belongsToMany(FoodTag::class, 'product_tags');
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    /**
     * Ensure image_url is returned as a complete URL when available
     */
    public function getImageUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        if (str_starts_with($value, '/storage/')) {
            return url($value);
        }

        if (str_starts_with($value, 'storage/')) {
            return url($value);
        }

        if (!str_starts_with($value, 'http')) {
            return url('storage/' . ltrim($value, '/'));
        }

        return $value;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
