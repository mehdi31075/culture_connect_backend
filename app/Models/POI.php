<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POI extends Model
{
    use HasFactory;

    protected $table = 'pois';

    const TYPE_PAVILION = 'pavilion';
    const TYPE_STAGE = 'stage';
    const TYPE_PHOTO_SPOT = 'photo_spot';
    const TYPE_RESTROOM = 'restroom';
    const TYPE_OTHER = 'other';

    protected $fillable = [
        'type',
        'name',
        'lat',
        'lng',
        'shop_id',
        'pavilion_id',
    ];

    protected $casts = [
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function pavilion()
    {
        return $this->belongsTo(Pavilion::class);
    }

    public function routeStops()
    {
        return $this->hasMany(RouteStop::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    public static function getTypes()
    {
        return [
            self::TYPE_PAVILION => 'Pavilion',
            self::TYPE_STAGE => 'Stage',
            self::TYPE_PHOTO_SPOT => 'Photo Spot',
            self::TYPE_RESTROOM => 'Restroom',
            self::TYPE_OTHER => 'Other',
        ];
    }
}
