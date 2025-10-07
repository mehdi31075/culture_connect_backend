<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'pavilion_id',
        'title',
        'description',
        'stage',
        'price',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    protected $attributes = [
        'price' => 'Free',
    ];

    public function pavilion()
    {
        return $this->belongsTo(Pavilion::class);
    }

    public function tagMaps()
    {
        return $this->hasMany(EventTagMap::class);
    }

    public function tags()
    {
        return $this->belongsToMany(EventTag::class, 'event_tag_maps');
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
}
