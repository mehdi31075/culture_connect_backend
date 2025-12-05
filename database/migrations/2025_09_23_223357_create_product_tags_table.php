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
        Schema::create('product_tag_maps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // References 'product_tags' - if table doesn't exist yet, it will be created/renamed by migrations
            // The foreign key will be updated by migration 2025_12_03_000011 if needed
            $table->foreignId('tag_id')->nullable()->constrained('product_tags')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['product_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_tag_maps');
    }
};
