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
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Change image_url to images (JSON array) - we'll keep both for migration
                if (!Schema::hasColumn('products', 'images')) {
                    $table->json('images')->nullable()->after('image_url');
                }
                if (!Schema::hasColumn('products', 'views_count')) {
                    $table->integer('views_count')->default(0)->after('images');
                }
                if (!Schema::hasColumn('products', 'is_trending')) {
                    $table->boolean('is_trending')->default(false)->after('views_count');
                }
                if (!Schema::hasColumn('products', 'trending_position')) {
                    $table->integer('trending_position')->nullable()->after('is_trending');
                }
                if (!Schema::hasColumn('products', 'trending_score')) {
                    $table->decimal('trending_score', 5, 2)->nullable()->after('trending_position');
                }
                if (!Schema::hasColumn('products', 'preparation_time')) {
                    $table->string('preparation_time', 50)->nullable()->after('trending_score');
                }
                if (!Schema::hasColumn('products', 'is_available')) {
                    $table->boolean('is_available')->default(true)->after('preparation_time');
                }
            });

            // Migrate existing image_url to images array for all products
            // Only for PostgreSQL
            if (DB::getDriverName() === 'pgsql') {
                DB::statement("
                    UPDATE products 
                    SET images = CASE 
                        WHEN image_url IS NOT NULL AND image_url != '' 
                        THEN json_build_array(image_url)::jsonb
                        ELSE NULL
                    END
                    WHERE (images IS NULL OR images::jsonb = '[]'::jsonb)
                    AND image_url IS NOT NULL AND image_url != ''
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (Schema::hasColumn('products', 'is_available')) {
                    $table->dropColumn('is_available');
                }
                if (Schema::hasColumn('products', 'preparation_time')) {
                    $table->dropColumn('preparation_time');
                }
                if (Schema::hasColumn('products', 'trending_score')) {
                    $table->dropColumn('trending_score');
                }
                if (Schema::hasColumn('products', 'trending_position')) {
                    $table->dropColumn('trending_position');
                }
                if (Schema::hasColumn('products', 'is_trending')) {
                    $table->dropColumn('is_trending');
                }
                if (Schema::hasColumn('products', 'views_count')) {
                    $table->dropColumn('views_count');
                }
                if (Schema::hasColumn('products', 'images')) {
                    $table->dropColumn('images');
                }
            });
        }
    }
};

