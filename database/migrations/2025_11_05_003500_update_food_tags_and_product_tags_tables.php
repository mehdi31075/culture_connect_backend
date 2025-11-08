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
            if (!Schema::hasColumn('food_tags', 'name')) {
                $table->string('name', 160)->unique()->after('id');
            }
        });

        Schema::table('product_tags', function (Blueprint $table) {
            if (!Schema::hasColumn('product_tags', 'product_id')) {
                $table->foreignId('product_id')->after('id')->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('product_tags', 'tag_id')) {
                $table->foreignId('tag_id')->after('product_id')->constrained('food_tags')->onDelete('cascade');
            }

            if (!Schema::hasColumn('product_tags', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_tags', function (Blueprint $table) {
            if (Schema::hasColumn('product_tags', 'tag_id')) {
                $table->dropForeign(['tag_id']);
                $table->dropColumn('tag_id');
            }

            if (Schema::hasColumn('product_tags', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }

            if (Schema::hasColumn('product_tags', 'created_at')) {
                $table->dropColumn(['created_at', 'updated_at']);
            }
        });

        Schema::table('food_tags', function (Blueprint $table) {
            if (Schema::hasColumn('food_tags', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};

