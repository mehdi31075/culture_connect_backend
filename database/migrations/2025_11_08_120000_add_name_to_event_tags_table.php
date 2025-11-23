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
        Schema::table('event_tags', function (Blueprint $table) {
            if (!Schema::hasColumn('event_tags', 'name')) {
                $table->string('name', 60)->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tags', function (Blueprint $table) {
            if (Schema::hasColumn('event_tags', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};

