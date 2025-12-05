<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration ensures product_tags table exists.
     * It will:
     * 1. Rename food_tags to product_tags if food_tags exists
     * 2. Rename tags to product_tags if tags exists (intermediate step)
     * 3. Create product_tags if neither exists
     */
    public function up(): void
    {
        // Case 1: food_tags exists, rename it to product_tags
        if (Schema::hasTable('food_tags') && !Schema::hasTable('product_tags')) {
            Schema::rename('food_tags', 'product_tags');
            
            // Update foreign key in product_tag_maps if it exists
            if (Schema::hasTable('product_tag_maps')) {
                try {
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_fkey');
                } catch (\Exception $e) {
                    // Ignore if constraints don't exist
                }
                
                Schema::table('product_tag_maps', function (Blueprint $table) {
                    $table->foreign('tag_id')->references('id')->on('product_tags')->onDelete('cascade');
                });
            }
        }
        // Case 2: tags exists (intermediate step), rename it to product_tags
        elseif (Schema::hasTable('tags') && !Schema::hasTable('product_tags')) {
            Schema::rename('tags', 'product_tags');
            
            // Update foreign key in product_tag_maps if it exists
            if (Schema::hasTable('product_tag_maps')) {
                try {
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_foreign');
                    DB::statement('ALTER TABLE product_tag_maps DROP CONSTRAINT IF EXISTS product_tag_maps_tag_id_fkey');
                } catch (\Exception $e) {
                    // Ignore if constraints don't exist
                }
                
                Schema::table('product_tag_maps', function (Blueprint $table) {
                    $table->foreign('tag_id')->references('id')->on('product_tags')->onDelete('cascade');
                });
            }
        }
        // Case 3: Neither exists, create product_tags table
        elseif (!Schema::hasTable('product_tags')) {
            Schema::create('product_tags', function (Blueprint $table) {
                $table->id();
                $table->string('name', 160)->unique();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if we created it (not if we renamed it)
        // This is a safety check - we don't want to drop if it was renamed from food_tags
        if (Schema::hasTable('product_tags')) {
            // Check if this was created by us or renamed
            // We'll be conservative and not drop it
            // If you need to reverse, manually rename back to food_tags
        }
    }
};

