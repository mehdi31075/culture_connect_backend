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
        if (Schema::hasTable('food_tags') && Schema::hasColumn('food_tags', 'tag_type')) {
            Schema::table('food_tags', function (Blueprint $table) {
                $table->dropColumn('tag_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('food_tags') && !Schema::hasColumn('food_tags', 'tag_type')) {
            Schema::table('food_tags', function (Blueprint $table) {
                $table->string('tag_type', 20)->default('product')->after('name');
            });
        }
    }
};

