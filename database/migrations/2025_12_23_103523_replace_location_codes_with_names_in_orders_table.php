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
            // Drop cột cũ
            $table->dropColumn(['province_code', 'ward_code']);
            
            // Thêm cột mới
            $table->string('province_name', 100)->nullable()->after('shipping_address');
            $table->string('ward_name', 100)->nullable()->after('province_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Rollback: xóa cột mới
            $table->dropColumn(['province_name', 'ward_name']);
            
            // Thêm lại cột cũ
            $table->string('province_code', 20)->nullable();
            $table->string('ward_code', 20)->nullable();
        });
    }
};
