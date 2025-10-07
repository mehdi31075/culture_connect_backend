<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function eventMaps()
    {
        return $this->hasMany(EventTagMap::class, 'tag_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_tag_maps', 'tag_id', 'event_id');
    }
}
