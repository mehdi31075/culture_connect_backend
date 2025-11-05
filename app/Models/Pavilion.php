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

    /**
     * Get the icon URL as a complete URL
     */
    public function getIconAttribute($value)
    {
        if (!$value) {
            return null;
        }

        // If it's already a complete URL, return it as is
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // If it starts with /storage/, convert to full URL
        if (str_starts_with($value, '/storage/')) {
            return url($value);
        }

        // If it's a relative path, prepend storage URL
        if (str_starts_with($value, 'storage/')) {
            return url($value);
        }

        // If it's just a path without prefix, add storage prefix
        if (!str_starts_with($value, 'http')) {
            return url('storage/' . $value);
        }

        return $value;
    }
}
