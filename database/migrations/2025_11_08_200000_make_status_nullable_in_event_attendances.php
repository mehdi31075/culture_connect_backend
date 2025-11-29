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
        if (Schema::hasTable('event_attendances') && Schema::hasColumn('event_attendances', 'status')) {
            // For PostgreSQL, we need to drop the default first, then alter the column
            DB::statement('ALTER TABLE event_attendances ALTER COLUMN status DROP DEFAULT');
            DB::statement('ALTER TABLE event_attendances ALTER COLUMN status DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('event_attendances') && Schema::hasColumn('event_attendances', 'status')) {
            // Set default back and make NOT NULL
            DB::statement("ALTER TABLE event_attendances ALTER COLUMN status SET DEFAULT 'interested'");
            DB::statement('ALTER TABLE event_attendances ALTER COLUMN status SET NOT NULL');
        }
    }
};

