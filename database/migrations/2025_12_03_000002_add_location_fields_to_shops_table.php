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
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (!Schema::hasColumn('shops', 'lat')) {
                    $table->decimal('lat', 10, 6)->nullable()->after('type');
                }
                if (!Schema::hasColumn('shops', 'lng')) {
                    $table->decimal('lng', 10, 6)->nullable()->after('lat');
                }
                if (!Schema::hasColumn('shops', 'location_name')) {
                    $table->string('location_name', 160)->nullable()->after('lng');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shops')) {
            Schema::table('shops', function (Blueprint $table) {
                if (Schema::hasColumn('shops', 'location_name')) {
                    $table->dropColumn('location_name');
                }
                if (Schema::hasColumn('shops', 'lng')) {
                    $table->dropColumn('lng');
                }
                if (Schema::hasColumn('shops', 'lat')) {
                    $table->dropColumn('lat');
                }
            });
        }
    }
};

