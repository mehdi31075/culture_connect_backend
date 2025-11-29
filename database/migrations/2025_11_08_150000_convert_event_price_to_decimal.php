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
        // Check if price column exists and is string type
        if (Schema::hasColumn('events', 'price')) {
            // For PostgreSQL, we need to convert string to numeric
            // First, update any 'Free' values to -1.00
            DB::statement("UPDATE events SET price = '-1.00' WHERE price = 'Free' OR price IS NULL OR price = ''");

            // For PostgreSQL, alter the column type
            DB::statement("ALTER TABLE events ALTER COLUMN price TYPE DECIMAL(10,2) USING CASE WHEN price ~ '^[0-9]+\.?[0-9]*$' THEN price::DECIMAL(10,2) ELSE -1.00 END");

            // Set default and nullable
            DB::statement("ALTER TABLE events ALTER COLUMN price SET DEFAULT -1.00");
            DB::statement("ALTER TABLE events ALTER COLUMN price SET NOT NULL");
        } else {
            // If column doesn't exist, create it
            Schema::table('events', function (Blueprint $table) {
                $table->decimal('price', 10, 2)->nullable()->default(-1.00)->after('stage');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('events', 'price')) {
            // Convert back to string
            DB::statement("ALTER TABLE events ALTER COLUMN price TYPE VARCHAR(60) USING CASE WHEN price = -1 THEN 'Free' ELSE price::TEXT END");
            DB::statement("ALTER TABLE events ALTER COLUMN price SET DEFAULT 'Free'");
        }
    }
};

