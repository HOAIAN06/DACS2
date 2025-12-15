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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

                $table->string('code', 50)->unique();
                $table->string('status', 30)->default('pending'); // pending/paid/shipping/completed/canceled

                $table->string('customer_name', 150);
                $table->string('customer_phone', 30);
                $table->string('customer_email', 191)->nullable();

                $table->string('shipping_address', 255);
                $table->string('shipping_note', 255)->nullable();

                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('discount_total', 12, 2)->default(0);
                $table->decimal('shipping_fee', 12, 2)->default(0);
                $table->decimal('grand_total', 12, 2)->default(0);

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
