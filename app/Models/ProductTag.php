<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ProductTag extends Model
{
    use HasFactory;

    /**
     * Get the table name dynamically based on what exists in the database.
     * This handles the migration from food_tags -> product_tags.
     */
    public function getTable()
    {
        // Check which table exists and use that
        if (Schema::hasTable('product_tags')) {
            return 'product_tags';
        } elseif (Schema::hasTable('tags')) {
            return 'tags';
        } elseif (Schema::hasTable('food_tags')) {
            return 'food_tags';
        }
        // Default fallback (should not happen if migrations are run)
        return 'product_tags';
    }
    
    // Default table name (will be overridden by getTable() if needed)
    protected $table = 'product_tags';

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag_maps', 'tag_id', 'product_id');
    }
}
