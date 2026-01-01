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
                $table->string('status', 30)->default('pending'); // pending/processing/shipping/completed/canceled

                // Shipping info
                $table->string('shipping_name', 150);
                $table->string('shipping_email', 191);
                $table->string('shipping_phone', 30);
                $table->string('shipping_address', 255);
                
                // Location fields (will be added via separate migration)
                // province_code, ward_code, postal_code
                
                $table->text('note')->nullable();

                // Payment
                $table->string('payment_method', 30); // cod/bank_transfer/credit_card
                $table->string('payment_status', 30)->default('unpaid'); // unpaid/paid/refunded

                // Pricing
                $table->decimal('subtotal', 12, 2)->default(0);
                $table->decimal('discount', 12, 2)->default(0);
                $table->decimal('shipping_fee', 12, 2)->default(0);
                $table->decimal('total', 12, 2)->default(0);

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
