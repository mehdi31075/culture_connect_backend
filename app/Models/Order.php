<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'shop_id',
        'total_amount',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Ensure total_amount is returned as a float (double) with 2 decimal places in JSON responses
     */
    public function getTotalAmountAttribute($value)
    {
        return $value !== null ? (float) number_format((float) $value, 2, '.', '') : null;
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PAID => 'Paid',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }
}
