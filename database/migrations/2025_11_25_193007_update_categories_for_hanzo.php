<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->unique()->nullable()->after('name');
            }

            // Danh mục cha (nếu muốn menu nhiều cấp)
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable()->after('slug');
            }

            // Thứ tự hiển thị
            if (!Schema::hasColumn('categories', 'position')) {
                $table->integer('position')->default(0)->after('parent_id');
            }

            // Ẩn/hiện
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('position');
            }

            // Hiện trên menu / homepage
            if (!Schema::hasColumn('categories', 'show_in_menu')) {
                $table->boolean('show_in_menu')->default(true)->after('is_active');
            }
            if (!Schema::hasColumn('categories', 'show_on_home')) {
                $table->boolean('show_on_home')->default(false)->after('show_in_menu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'parent_id',
                'position',
                'is_active',
                'show_in_menu',
                'show_on_home',
            ]);
        });
    }
};
