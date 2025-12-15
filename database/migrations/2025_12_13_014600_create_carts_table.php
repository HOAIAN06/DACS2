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
        if (!Schema::hasTable('carts')) {
            Schema::create('carts', function (Blueprint $table) {
                $table->id(); // BIGINT UNSIGNED (Laravel chuẩn)
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('session_id', 191)->nullable()->index(); // khách chưa login
                $table->timestamps();

                $table->unique(['user_id']);
                $table->unique(['session_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
