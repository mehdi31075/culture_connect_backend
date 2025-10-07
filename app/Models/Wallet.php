<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    const TIER_BRONZE = 'bronze';
    const TIER_SILVER = 'silver';
    const TIER_GOLD = 'gold';

    protected $fillable = [
        'user_id',
        'points',
        'tier',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    protected $attributes = [
        'points' => 0,
        'tier' => self::TIER_BRONZE,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getTiers()
    {
        return [
            self::TIER_BRONZE => 'Bronze',
            self::TIER_SILVER => 'Silver',
            self::TIER_GOLD => 'Gold',
        ];
    }
}
