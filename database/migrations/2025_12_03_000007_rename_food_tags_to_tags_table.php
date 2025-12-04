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
        // Rename the table if it exists
        if (Schema::hasTable('food_tags') && !Schema::hasTable('tags')) {
            Schema::rename('food_tags', 'tags');
        }

        // Update foreign key constraints in product_tag_maps pivot table (if it exists)
        if (Schema::hasTable('product_tag_maps')) {
            try {
                // Drop existing foreign key if it exists (may reference 'food_tags')
                DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
            } catch (\Exception $e) {
                // Try alternative constraint names
                try {
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_fkey');
                } catch (\Exception $e2) {
                    // Ignore if constraint doesn't exist
                }
            }

            // Re-add foreign key pointing to the renamed table (tags)
            if (Schema::hasTable('tags')) {
                Schema::table('product_tag_maps', function (Blueprint $table) {
                    $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update foreign key constraints back
        if (Schema::hasTable('product_tag_maps')) {
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

            if (Schema::hasTable('food_tags')) {
                Schema::table('product_tag_maps', function (Blueprint $table) {
                    $table->foreign('tag_id')->references('id')->on('food_tags')->onDelete('cascade');
                });
            }
        }

        // Rename the table back
        if (Schema::hasTable('tags') && !Schema::hasTable('food_tags')) {
            Schema::rename('tags', 'food_tags');
        }
    }
};

