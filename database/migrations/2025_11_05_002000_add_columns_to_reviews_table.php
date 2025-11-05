<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('reviews', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('id');
            }
            if (!Schema::hasColumn('reviews', 'shop_id')) {
                $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null')->after('user_id');
            }
            if (!Schema::hasColumn('reviews', 'product_id')) {
                $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null')->after('shop_id');
            }
            if (!Schema::hasColumn('reviews', 'rating')) {
                $table->tinyInteger('rating')->after('product_id');
            }
            if (!Schema::hasColumn('reviews', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'comment')) {
                $table->dropColumn('comment');
            }
            if (Schema::hasColumn('reviews', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('reviews', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('reviews', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
            if (Schema::hasColumn('reviews', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};

