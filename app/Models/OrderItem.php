<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'food_id',
        'qty',
        'unit_price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'unit_price' => 'decimal:2',
    ];

    protected $attributes = [
        'qty' => 1,
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Ensure unit_price is returned as a float (double) with 2 decimal places in JSON responses
     */
    public function getUnitPriceAttribute($value)
    {
        return $value !== null ? (float) number_format((float) $value, 2, '.', '') : null;
    }
}
