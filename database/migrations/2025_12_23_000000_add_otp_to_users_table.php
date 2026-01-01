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
        Schema::table('users', function (Blueprint $table) {
            // OTP và reset password
            $table->string('otp')->nullable()->comment('Mã OTP để đặt lại mật khẩu');
            $table->timestamp('otp_expires_at')->nullable()->comment('Thời gian hết hạn OTP');
            $table->integer('otp_attempts')->default(0)->comment('Số lần nhập sai OTP');
            $table->boolean('otp_verified')->default(false)->comment('OTP đã được xác minh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp', 'otp_expires_at', 'otp_attempts', 'otp_verified']);
        });
    }
};
