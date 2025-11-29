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
        'capacity',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'decimal:2',
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
        return $this->belongsToMany(EventTag::class, 'event_tag_maps', 'event_id', 'tag_id');
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }

    /**
     * Get confirmed attendees count (status = 'going' or 'checked_in')
     */
    public function getConfirmedAttendeesCountAttribute()
    {
        return $this->attendees()
            ->whereIn('status', [EventAttendance::STATUS_GOING, EventAttendance::STATUS_CHECKED_IN])
            ->count();
    }

    /**
     * Get price attribute - ensure it's always returned as float
     */
    public function getPriceAttribute($value)
    {
        return $value !== null ? (float) $value : -1.00;
    }
}
