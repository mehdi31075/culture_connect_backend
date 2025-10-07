<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    const TYPE_EVENT = 'event';
    const TYPE_OFFER = 'offer';
    const TYPE_SYSTEM = 'system';
    const TYPE_ORDER = 'order';

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getTypes()
    {
        return [
            self::TYPE_EVENT => 'Event',
            self::TYPE_OFFER => 'Offer',
            self::TYPE_SYSTEM => 'System',
            self::TYPE_ORDER => 'Order',
        ];
    }
}
