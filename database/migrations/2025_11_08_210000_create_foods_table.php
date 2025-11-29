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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->json('images')->nullable(); // Array of image URLs
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->boolean('is_trending')->default(false);
            $table->integer('trending_position')->nullable(); // 1, 2, 3, etc.
            $table->decimal('trending_score', 5, 2)->nullable(); // Percentage like 98%, 94%, etc.
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};

