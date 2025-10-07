<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    const TYPE_SHOP = 'shop';
    const TYPE_FOOD_TRUCK = 'food_truck';
    const TYPE_RESTAURANT = 'restaurant';

    protected $fillable = [
        'pavilion_id',
        'name',
        'description',
        'type',
    ];

    protected $attributes = [
        'type' => self::TYPE_SHOP,
    ];

    public function pavilion()
    {
        return $this->belongsTo(Pavilion::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function pois()
    {
        return $this->hasMany(POI::class);
    }

    public static function getTypes()
    {
        return [
            self::TYPE_SHOP => 'Shop',
            self::TYPE_FOOD_TRUCK => 'Food Truck',
            self::TYPE_RESTAURANT => 'Restaurant',
        ];
    }
}
