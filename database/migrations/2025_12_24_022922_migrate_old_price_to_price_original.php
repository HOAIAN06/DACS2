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
        // Copy old_price to price_original where price_original is null
        \DB::statement('UPDATE products SET price_original = old_price WHERE old_price IS NOT NULL AND price_original IS NULL');
        
        // Drop old_price column
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('old_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('old_price', 10, 2)->nullable()->after('price');
        });
        
        // Restore data from price_original to old_price
        \DB::statement('UPDATE products SET old_price = price_original WHERE price_original IS NOT NULL');
    }
};
