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
        Schema::table('pavilions', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('description');
            $table->string('country')->nullable()->after('icon');
        });

        // Make existing fields nullable
        Schema::table('pavilions', function (Blueprint $table) {
            $table->decimal('lat', 10, 6)->nullable()->change();
            $table->decimal('lng', 10, 6)->nullable()->change();
            $table->string('open_hours')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pavilions', function (Blueprint $table) {
            $table->dropColumn(['icon', 'country']);
        });
    }
};
