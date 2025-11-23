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
        Schema::table('event_tag_maps', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('event_tag_maps', 'event_id')) {
                $table->foreignId('event_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('event_tag_maps', 'tag_id')) {
                $table->foreignId('tag_id')->after('event_id')->constrained('event_tags')->onDelete('cascade');
            }
        });

        // Add unique constraint if columns exist
        if (Schema::hasColumn('event_tag_maps', 'event_id') && Schema::hasColumn('event_tag_maps', 'tag_id')) {
            $connection = Schema::getConnection();

            // Check if unique constraint already exists
            $indexExists = $connection->selectOne("
                SELECT 1
                FROM pg_indexes
                WHERE tablename = 'event_tag_maps'
                AND indexdef LIKE '%UNIQUE%'
                AND (indexdef LIKE '%event_id%' AND indexdef LIKE '%tag_id%')
                LIMIT 1
            ");

            if (!$indexExists) {
                Schema::table('event_tag_maps', function (Blueprint $table) {
                    $table->unique(['event_id', 'tag_id']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = Schema::getConnection();

        // Drop unique constraint if it exists
        $index = $connection->selectOne("
            SELECT indexname
            FROM pg_indexes
            WHERE tablename = 'event_tag_maps'
            AND indexdef LIKE '%UNIQUE%'
            AND (indexdef LIKE '%event_id%' AND indexdef LIKE '%tag_id%')
            LIMIT 1
        ");

        if ($index) {
            $connection->statement("DROP INDEX IF EXISTS {$index->indexname}");
        }

        Schema::table('event_tag_maps', function (Blueprint $table) {
            if (Schema::hasColumn('event_tag_maps', 'tag_id')) {
                $table->dropForeign(['tag_id']);
                $table->dropColumn('tag_id');
            }
            if (Schema::hasColumn('event_tag_maps', 'event_id')) {
                $table->dropForeign(['event_id']);
                $table->dropColumn('event_id');
            }
        });
    }
};

