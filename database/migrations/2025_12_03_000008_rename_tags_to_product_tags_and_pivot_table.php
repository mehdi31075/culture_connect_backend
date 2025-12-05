<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Rename food_tags or tags table directly to product_tags
        // Handle both cases: food_tags -> product_tags OR tags -> product_tags
        if (Schema::hasTable('food_tags') && !Schema::hasTable('product_tags')) {
            // Direct rename from food_tags to product_tags
            Schema::rename('food_tags', 'product_tags');
        } elseif (Schema::hasTable('tags') && !Schema::hasTable('product_tags')) {
            // Rename from intermediate tags to product_tags
            Schema::rename('tags', 'product_tags');
        }

        // Step 2: Update foreign key in product_tag_maps to point to product_tags (if it exists)
        if (Schema::hasTable('product_tag_maps')) {
            // Drop existing foreign key constraint (may reference 'tags' or 'food_tags')
            try {
                DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
            } catch (\Exception $e) {
                // Try alternative constraint names
                try {
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_fkey');
                } catch (\Exception $e2) {
                    // Ignore if constraint doesn't exist
                }
            }
            
            // Re-add foreign key constraint pointing to product_tags
            if (Schema::hasTable('product_tags')) {
                Schema::table('product_tag_maps', function (Blueprint $table) {
                    $table->foreign('tag_id')->references('id')->on('product_tags')->onDelete('cascade');
                });
            }
        }

        // Step 3: Handle case where pivot table is still named 'product_tags' (old naming)
        if (Schema::hasTable('product_tags')) {
            $columns = Schema::getColumnListing('product_tags');
            // If it has both product_id and tag_id, it's the pivot table (old naming)
            if (in_array('product_id', $columns) && in_array('tag_id', $columns) && !Schema::hasTable('product_tag_maps')) {
                Schema::rename('product_tags', 'product_tag_maps');
                
                // Update foreign key constraint to point to product_tags (the tags table)
                // First check if product_tags (tags table) exists
                if (Schema::hasTable('product_tags')) {
                    try {
                        DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
                    } catch (\Exception $e) {
                        // Ignore if constraint doesn't exist
                    }
                    
                    Schema::table('product_tag_maps', function (Blueprint $table) {
                        $table->foreign('tag_id')->references('id')->on('product_tags')->onDelete('cascade');
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Step 1: Rename product_tag_maps back to product_tags
        if (Schema::hasTable('product_tag_maps') && !Schema::hasTable('product_tags')) {
            try {
                DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
            } catch (\Exception $e) {
                // Ignore
            }
            
            Schema::rename('product_tag_maps', 'product_tags');
        }

        // Step 2: Rename product_tags (tags table) back to tags
        if (Schema::hasTable('product_tags')) {
            $columns = Schema::getColumnListing('product_tags');
            // If it doesn't have product_id, it's the tags table
            if (!in_array('product_id', $columns) && !Schema::hasTable('tags')) {
                Schema::rename('product_tags', 'tags');
            }
        }
    }
};

