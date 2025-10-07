<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function productTags()
    {
        return $this->hasMany(ProductTag::class, 'tag_id');
    }

    public function tagProducts()
    {
        return $this->belongsToMany(Product::class, 'product_tags', 'tag_id', 'product_id');
    }
}
