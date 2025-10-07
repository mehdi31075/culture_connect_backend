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
        'lat',
        'lng',
        'open_hours',
    ];

    protected $casts = [
        'lat' => 'decimal:6',
        'lng' => 'decimal:6',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function pois()
    {
        return $this->hasMany(POI::class);
    }
}
