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
        Schema::table('food_tags', function (Blueprint $table) {
            if (!Schema::hasColumn('food_tags', 'tag_type')) {
                $table->string('tag_type', 20)->default('both')->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food_tags', function (Blueprint $table) {
            if (Schema::hasColumn('food_tags', 'tag_type')) {
                $table->dropColumn('tag_type');
            }
        });
    }
};

