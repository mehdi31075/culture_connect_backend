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

        // Update foreign key constraints in product_tags pivot table
        if (Schema::hasTable('product_tags')) {
            try {
                // Drop existing foreign key if it exists
                DB::statement('ALTER TABLE product_tags DROP CONSTRAINT IF EXISTS product_tags_tag_id_foreign');
            } catch (\Exception $e) {
                // Ignore if constraint doesn't exist
            }

            // Re-add foreign key pointing to the renamed table
            Schema::table('product_tags', function (Blueprint $table) {
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update foreign key constraints back
        if (Schema::hasTable('product_tags')) {
            try {
                DB::statement('ALTER TABLE product_tags DROP CONSTRAINT IF EXISTS product_tags_tag_id_foreign');
            } catch (\Exception $e) {
                // Ignore if constraint doesn't exist
            }

            Schema::table('product_tags', function (Blueprint $table) {
                $table->foreign('tag_id')->references('id')->on('food_tags')->onDelete('cascade');
            });
        }

        // Rename the table back
        if (Schema::hasTable('tags') && !Schema::hasTable('food_tags')) {
            Schema::rename('tags', 'food_tags');
        }
    }
};

