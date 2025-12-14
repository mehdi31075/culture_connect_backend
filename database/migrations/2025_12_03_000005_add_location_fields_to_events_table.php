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
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'lat')) {
                    $table->decimal('lat', 10, 6)->nullable()->after('pavilion_id');
                }
                if (!Schema::hasColumn('events', 'lng')) {
                    $table->decimal('lng', 10, 6)->nullable()->after('lat');
                }
                if (!Schema::hasColumn('events', 'location')) {
                    $table->string('location', 160)->nullable()->after('lng');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'location')) {
                    $table->dropColumn('location');
                }
                if (Schema::hasColumn('events', 'lng')) {
                    $table->dropColumn('lng');
                }
                if (Schema::hasColumn('events', 'lat')) {
                    $table->dropColumn('lat');
                }
            });
        }
    }
};

