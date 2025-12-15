<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            if (!Schema::hasColumn('product_images', 'image_url')) {
                $table->string('image_url')->nullable()->after('id');
            }
            if (!Schema::hasColumn('product_images', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('product_images', 'is_main')) {
                $table->boolean('is_main')->default(false)->after('product_id');
            }
            if (!Schema::hasColumn('product_images', 'sort_order')) {
                $table->integer('sort_order')->default(0)->after('is_main');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn([
                'image_url',
                'product_id',
                'is_main',
                'sort_order',
            ]);
        });
    }
};
