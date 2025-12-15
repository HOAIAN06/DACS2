<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (!Schema::hasColumn('product_variants', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('product_variants', 'sku')) {
                $table->string('sku')->nullable()->unique()->after('product_id');
            }
            if (!Schema::hasColumn('product_variants', 'color')) {
                $table->string('color')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('product_variants', 'size')) {
                $table->string('size')->nullable()->after('color');
            }
            if (!Schema::hasColumn('product_variants', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('size');
            }
            if (!Schema::hasColumn('product_variants', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn([
                'product_id',
                'sku',
                'color',
                'size',
                'price',
                'stock',
            ]);
        });
    }
};
