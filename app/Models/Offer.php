<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    const DISCOUNT_TYPE_PERCENT = 'percent';
    const DISCOUNT_TYPE_FIXED = 'fixed';

    protected $fillable = [
        'shop_id',
        'product_id',
        'food_id',
        'title',
        'description',
        'discount_type',
        'value',
        'is_bundle',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'is_bundle' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    protected $attributes = [
        'is_bundle' => false,
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function redemptions()
    {
        return $this->hasMany(OfferRedemption::class);
    }

    /**
     * Ensure value is returned as a float (double) with 2 decimal places in JSON responses
     */
    public function getValueAttribute($value)
    {
        return $value !== null ? (float) number_format((float) $value, 2, '.', '') : null;
    }

    public static function getDiscountTypes()
    {
        return [
            self::DISCOUNT_TYPE_PERCENT => 'Percent',
            self::DISCOUNT_TYPE_FIXED => 'Fixed',
        ];
    }
}
