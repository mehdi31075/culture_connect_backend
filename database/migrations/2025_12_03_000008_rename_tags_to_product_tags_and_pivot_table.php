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
        // Step 1: Rename tags table to product_tags (if tags exists and product_tags doesn't)
        if (Schema::hasTable('tags') && !Schema::hasTable('product_tags')) {
            Schema::rename('tags', 'product_tags');
        }

        // Step 2: Rename product_tags pivot table to product_tag_maps (if product_tags exists as pivot)
        // We need to check if product_tags is a pivot table (has product_id and tag_id columns)
        if (Schema::hasTable('product_tags')) {
            $columns = Schema::getColumnListing('product_tags');
            // If it has both product_id and tag_id, it's the pivot table
            if (in_array('product_id', $columns) && in_array('tag_id', $columns) && !Schema::hasTable('product_tag_maps')) {
                Schema::rename('product_tags', 'product_tag_maps');
                
                // Update foreign key constraint to point to product_tags (the tags table)
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

