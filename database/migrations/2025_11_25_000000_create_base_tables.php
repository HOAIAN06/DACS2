<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id'); // UNSIGNED INT để match với cũ
                $table->string('name');
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('banners')) {
            Schema::create('banners', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('image_url');
                $table->string('link_url')->nullable();
                $table->integer('position')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_images')) {
            Schema::create('product_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('product_id');
                $table->string('image_url');
                $table->boolean('is_main')->default(false);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('product_id'); // Match với products.id là UNSIGNED INT
                $table->string('sku')->nullable();
                $table->string('color')->nullable();
                $table->string('size')->nullable();
                $table->decimal('price', 12, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};
