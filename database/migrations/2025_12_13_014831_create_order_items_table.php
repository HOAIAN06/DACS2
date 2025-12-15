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
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();

                $table->unsignedInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products')->restrictOnDelete();
                
                $table->unsignedBigInteger('product_variant_id')->nullable();
                $table->foreign('product_variant_id')->references('id')->on('product_variants')->nullOnDelete();

                $table->string('name_snapshot', 150);            // snapshot tên lúc mua
                $table->string('variant_snapshot', 100)->nullable(); // size/màu snapshot
                $table->decimal('unit_price', 12, 2);
                $table->unsignedInteger('qty');
                $table->decimal('line_total', 12, 2);

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
