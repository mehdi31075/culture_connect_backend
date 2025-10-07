<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFactory;

    const STATUS_GOING = 'going';
    const STATUS_INTERESTED = 'interested';
    const STATUS_CHECKED_IN = 'checked_in';

    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'reminder_at',
        'checked_in_at',
    ];

    protected $casts = [
        'reminder_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_GOING => 'Going',
            self::STATUS_INTERESTED => 'Interested',
            self::STATUS_CHECKED_IN => 'Checked In',
        ];
    }
}
