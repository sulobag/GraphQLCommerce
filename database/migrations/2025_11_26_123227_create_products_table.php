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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('sku')->unique();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('brand')->nullable();
            $table->string('category')->default('default');
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency', 3)->default('TRY');
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->json('search_tags')->nullable();
            $table->string('primary_image_url')->nullable();
            $table->timestamps();

            $table->index(['brand', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
