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
        // Check if old table name exists and rename it
        if (Schema::hasTable('event_attendance') && !Schema::hasTable('event_attendances')) {
            Schema::rename('event_attendance', 'event_attendances');
        }

        // If table doesn't exist at all, create it
        if (!Schema::hasTable('event_attendances')) {
            Schema::create('event_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->string('status', 20)->default('interested');
                $table->timestamp('reminder_at')->nullable();
                $table->timestamp('checked_in_at')->nullable();
                $table->timestamps();
                $table->unique(['user_id', 'event_id']);
            });
            return;
        }

        // Add missing columns to existing table
        Schema::table('event_attendances', function (Blueprint $table) {
            if (!Schema::hasColumn('event_attendances', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('event_attendances', 'event_id')) {
                $table->foreignId('event_id')->after('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('event_attendances', 'status')) {
                $table->string('status', 20)->default('interested')->after('event_id');
            }
            if (!Schema::hasColumn('event_attendances', 'reminder_at')) {
                $table->timestamp('reminder_at')->nullable()->after('status');
            }
            if (!Schema::hasColumn('event_attendances', 'checked_in_at')) {
                $table->timestamp('checked_in_at')->nullable()->after('reminder_at');
            }
        });

        // Add unique constraint if it doesn't exist
        if (Schema::hasColumn('event_attendances', 'user_id') && Schema::hasColumn('event_attendances', 'event_id')) {
            $connection = Schema::getConnection();
            $indexExists = $connection->selectOne("
                SELECT 1
                FROM pg_indexes
                WHERE tablename = 'event_attendances'
                AND indexdef LIKE '%UNIQUE%'
                AND (indexdef LIKE '%user_id%' AND indexdef LIKE '%event_id%')
                LIMIT 1
            ");

            if (!$indexExists) {
                Schema::table('event_attendances', function (Blueprint $table) {
                    $table->unique(['user_id', 'event_id']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is additive, so we don't drop columns in down()
        // If needed, you can manually drop columns
    }
};

