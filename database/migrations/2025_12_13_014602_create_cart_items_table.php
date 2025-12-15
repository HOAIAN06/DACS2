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
        if (!Schema::hasTable('cart_items')) {
            Schema::create('cart_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();

                $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
                $table->foreignId('product_variant_id')->nullable()
                    ->constrained('product_variants')->nullOnDelete();

                $table->unsignedInteger('qty')->default(1);
                $table->decimal('unit_price', 12, 2)->nullable(); // chốt giá lúc add
                $table->timestamps();

                $table->unique(['cart_id','product_id','product_variant_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
