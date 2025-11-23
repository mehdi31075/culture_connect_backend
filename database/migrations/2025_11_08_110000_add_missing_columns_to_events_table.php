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
        Schema::table('events', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('events', 'pavilion_id')) {
                $table->foreignId('pavilion_id')->nullable()->after('id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('events', 'title')) {
                $table->string('title', 160)->after('pavilion_id');
            }
            if (!Schema::hasColumn('events', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('events', 'stage')) {
                $table->string('stage', 160)->nullable()->after('description');
            }
            if (!Schema::hasColumn('events', 'price')) {
                $table->string('price', 60)->default('Free')->after('stage');
            }
            if (!Schema::hasColumn('events', 'start_time')) {
                $table->timestamp('start_time')->after('price');
            }
            if (!Schema::hasColumn('events', 'end_time')) {
                $table->timestamp('end_time')->after('start_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'end_time')) {
                $table->dropColumn('end_time');
            }
            if (Schema::hasColumn('events', 'start_time')) {
                $table->dropColumn('start_time');
            }
            if (Schema::hasColumn('events', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('events', 'stage')) {
                $table->dropColumn('stage');
            }
            if (Schema::hasColumn('events', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('events', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('events', 'pavilion_id')) {
                $table->dropForeign(['pavilion_id']);
                $table->dropColumn('pavilion_id');
            }
        });
    }
};

