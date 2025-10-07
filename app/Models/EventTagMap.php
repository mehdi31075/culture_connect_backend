<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTagMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'tag_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tag()
    {
        return $this->belongsTo(EventTag::class, 'tag_id');
    }
}
