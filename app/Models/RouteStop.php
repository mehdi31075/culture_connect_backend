<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'poi_id',
        'sequence',
    ];

    protected $casts = [
        'sequence' => 'integer',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function poi()
    {
        return $this->belongsTo(POI::class);
    }
}
