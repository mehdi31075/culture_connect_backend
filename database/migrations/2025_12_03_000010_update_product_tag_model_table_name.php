<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Note: This migration doesn't change the database schema,
     * but ensures the ProductTag model uses the correct table name.
     * After this migration runs, update app/Models/ProductTag.php
     * to use 'product_tags' instead of 'food_tags'.
     */
    public function up(): void
    {
        // This migration is a placeholder to ensure proper migration order
        // The actual table rename happens in 2025_12_03_000008
        // After that migration runs, update ProductTag model to use 'product_tags'
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse
    }
};

