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
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'is_food')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_food');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products') && !Schema::hasColumn('products', 'is_food')) {
            Schema::table('products', function (Blueprint $table) {
                $table->boolean('is_food')->default(false)->after('discounted_price');
            });
        }
    }
};

