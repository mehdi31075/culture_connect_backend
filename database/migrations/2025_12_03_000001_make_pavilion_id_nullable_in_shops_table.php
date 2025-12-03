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
        if (Schema::hasTable('shops') && Schema::hasColumn('shops', 'pavilion_id')) {
            // Drop the foreign key constraint first
            Schema::table('shops', function (Blueprint $table) {
                $table->dropForeign(['pavilion_id']);
            });
            
            // For PostgreSQL, we need to use raw SQL to make the column nullable
            DB::statement('ALTER TABLE shops ALTER COLUMN pavilion_id DROP NOT NULL');
            
            // Re-add the foreign key constraint with onDelete('set null')
            Schema::table('shops', function (Blueprint $table) {
                $table->foreign('pavilion_id')->references('id')->on('pavilions')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('shops') && Schema::hasColumn('shops', 'pavilion_id')) {
            // Drop the foreign key constraint
            Schema::table('shops', function (Blueprint $table) {
                $table->dropForeign(['pavilion_id']);
            });
            
            // Make the column NOT NULL again (this will fail if there are NULL values)
            DB::statement('ALTER TABLE shops ALTER COLUMN pavilion_id SET NOT NULL');
            
            // Re-add the foreign key constraint with onDelete('cascade')
            Schema::table('shops', function (Blueprint $table) {
                $table->foreign('pavilion_id')->references('id')->on('pavilions')->onDelete('cascade');
            });
        }
    }
};

