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
        Schema::table('offers', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('offers', 'shop_id')) {
                $table->foreignId('shop_id')->nullable()->after('id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('offers', 'product_id')) {
                $table->foreignId('product_id')->nullable()->after('shop_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('offers', 'title')) {
                $table->string('title', 160)->after('product_id');
            }
            if (!Schema::hasColumn('offers', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
            if (!Schema::hasColumn('offers', 'discount_type')) {
                $table->string('discount_type', 16)->after('description');
            }
            if (!Schema::hasColumn('offers', 'value')) {
                $table->decimal('value', 10, 2)->after('discount_type');
            }
            if (!Schema::hasColumn('offers', 'is_bundle')) {
                $table->boolean('is_bundle')->default(false)->after('value');
            }
            if (!Schema::hasColumn('offers', 'start_at')) {
                $table->timestamp('start_at')->after('is_bundle');
            }
            if (!Schema::hasColumn('offers', 'end_at')) {
                $table->timestamp('end_at')->after('start_at');
            }

            // Add index if it doesn't exist
            if (Schema::hasColumn('offers', 'start_at') && Schema::hasColumn('offers', 'end_at')) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $indexesFound = $sm->listTableIndexes('offers');
                $indexName = 'offers_start_at_end_at_index';
                if (!isset($indexesFound[$indexName])) {
                    $table->index(['start_at', 'end_at']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            if (Schema::hasColumn('offers', 'end_at')) {
                $table->dropIndex(['start_at', 'end_at']);
                $table->dropColumn('end_at');
            }
            if (Schema::hasColumn('offers', 'start_at')) {
                $table->dropColumn('start_at');
            }
            if (Schema::hasColumn('offers', 'is_bundle')) {
                $table->dropColumn('is_bundle');
            }
            if (Schema::hasColumn('offers', 'value')) {
                $table->dropColumn('value');
            }
            if (Schema::hasColumn('offers', 'discount_type')) {
                $table->dropColumn('discount_type');
            }
            if (Schema::hasColumn('offers', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('offers', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('offers', 'product_id')) {
                $table->dropForeign(['product_id']);
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('offers', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
        });
    }
};

