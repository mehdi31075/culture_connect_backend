<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferRedemption extends Model
{
    use HasFactory;

    const STATUS_ISSUED = 'issued';
    const STATUS_REDEEMED = 'redeemed';
    const STATUS_EXPIRED = 'expired';

    protected $fillable = [
        'offer_id',
        'user_id',
        'qr_code',
        'status',
        'expires_at',
        'redeemed_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'redeemed_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => self::STATUS_ISSUED,
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_ISSUED => 'Issued',
            self::STATUS_REDEEMED => 'Redeemed',
            self::STATUS_EXPIRED => 'Expired',
        ];
    }
}
