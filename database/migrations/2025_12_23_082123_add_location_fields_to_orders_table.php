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
        Schema::table('orders', function (Blueprint $table) {
            // Thêm các cột location sau shipping_address - KHÔNG dùng foreign key
            $table->string('province_code', 20)->nullable()->after('shipping_address');
            $table->string('ward_code', 20)->nullable()->after('province_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['province_code', 'ward_code']);
        });
    }
};
