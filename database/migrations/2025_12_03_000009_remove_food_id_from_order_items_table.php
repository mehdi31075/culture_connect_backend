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
        if (Schema::hasTable('order_items') && Schema::hasColumn('order_items', 'food_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                // Drop foreign key constraint first
                try {
                    DB::statement('ALTER TABLE order_items DROP CONSTRAINT IF EXISTS order_items_food_id_foreign');
                } catch (\Exception $e) {
                    // Try alternative constraint name formats
                    try {
                        DB::statement('ALTER TABLE order_items DROP CONSTRAINT IF EXISTS order_items_food_id_fkey');
                    } catch (\Exception $e2) {
                        // Ignore if constraint doesn't exist
                    }
                }
                
                // Drop the column
                $table->dropColumn('food_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items') && !Schema::hasColumn('order_items', 'food_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('food_id')->nullable()->after('product_id')->constrained('foods')->onDelete('cascade');
            });
        }
    }
};

