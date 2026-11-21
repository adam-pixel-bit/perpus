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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('isbn', 30)->unique();
            $table->string('author');
            $table->string('publisher');
            $table->unsignedSmallInteger('publication_year');
            $table->unsignedInteger('stock')->default(1);
            $table->unsignedInteger('available_stock')->default(1);
            $table->string('shelf_location', 50)->default('Rak A-1');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            $table->index(['title', 'author']);
            $table->index('is_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
