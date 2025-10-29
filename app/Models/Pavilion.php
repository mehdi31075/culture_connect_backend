<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pavilion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'country',
        'lat',
        'lng',
        'open_hours',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    // Ensure numeric serialization regardless of DB type/driver
    public function getLatAttribute($value)
    {
        return $value === null ? null : (float) $value;
    }

    public function getLngAttribute($value)
    {
        return $value === null ? null : (float) $value;
    }

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }

    // public function pois()
    // {
    //     return $this->hasMany(POI::class);
    // }

    /**
     * Get the shops count for this pavilion
     */
    public function getShopsCountAttribute()
    {
        return $this->shops()->count();
    }
}
