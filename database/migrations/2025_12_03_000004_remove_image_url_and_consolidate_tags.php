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
        // Drop food_tag_maps table if it exists
        // Note: Since foods have been merged into products, food_tag_maps is no longer needed
        // All products (both food and non-food) now use product_tags table
        if (Schema::hasTable('food_tag_maps')) {
            // Drop foreign key constraints first
            try {
                DB::statement('ALTER TABLE food_tag_maps DROP CONSTRAINT IF EXISTS food_tag_maps_food_id_foreign');
                DB::statement('ALTER TABLE food_tag_maps DROP CONSTRAINT IF EXISTS food_tag_maps_tag_id_foreign');
            } catch (\Exception $e) {
                // Ignore if constraints don't exist or already dropped
            }
            Schema::dropIfExists('food_tag_maps');
        }

        // Remove image_url column from products table
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'image_url')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('image_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add image_url column
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'image_url')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('image_url')->nullable()->after('is_food');
            });
        }

        // Re-create food_tag_maps table (but we can't restore the data)
        if (!Schema::hasTable('food_tag_maps')) {
            Schema::create('food_tag_maps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('food_id')->constrained('products')->onDelete('cascade');
                $table->foreignId('tag_id')->constrained('food_tags')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['food_id', 'tag_id']);
            });
        }
    }
};

