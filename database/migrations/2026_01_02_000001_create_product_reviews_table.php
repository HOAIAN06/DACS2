<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Skip creation if the table already exists (e.g., pre-seeded DB).
        if (Schema::hasTable('product_reviews')) {
            return;
        }

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id'); // Match products.id type
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            
            // Add foreign key for product_id
            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned()->comment('1-5');
            $table->string('title', 191)->nullable();
            $table->text('content');
            $table->json('images')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reply')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'product_id', 'order_id']);
            $table->index(['product_id', 'status']);
            $table->index(['product_id', 'is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
