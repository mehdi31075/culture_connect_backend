<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTag extends Model
{
    use HasFactory;

    protected $table = 'food_tags';

    protected $fillable = [
        'name',
        'tag_type',
    ];

    const TYPE_PRODUCT = 'product';
    const TYPE_FOOD = 'food';
    const TYPE_BOTH = 'both';

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }

    public function foods()
    {
        return $this->belongsToMany(Food::class, 'food_tag_maps', 'tag_id', 'food_id');
    }
}
