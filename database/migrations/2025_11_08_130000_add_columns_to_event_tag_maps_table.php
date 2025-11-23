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

            // Add unique constraint if it doesn't exist
            $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('event_tag_maps');
            $uniqueExists = false;
            foreach ($indexes as $index) {
                if ($index->isUnique() && count($index->getColumns()) === 2 &&
                    in_array('event_id', $index->getColumns()) &&
                    in_array('tag_id', $index->getColumns())) {
                    $uniqueExists = true;
                    break;
                }
            }
            if (!$uniqueExists && Schema::hasColumn('event_tag_maps', 'event_id') && Schema::hasColumn('event_tag_maps', 'tag_id')) {
                $table->unique(['event_id', 'tag_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tag_maps', function (Blueprint $table) {
            // Drop unique constraint
            $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes('event_tag_maps');
            foreach ($indexes as $indexName => $index) {
                if ($index->isUnique() && count($index->getColumns()) === 2 &&
                    in_array('event_id', $index->getColumns()) &&
                    in_array('tag_id', $index->getColumns())) {
                    $table->dropUnique([$indexName]);
                    break;
                }
            }

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

