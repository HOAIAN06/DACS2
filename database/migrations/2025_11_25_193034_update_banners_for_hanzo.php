<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            if (!Schema::hasColumn('banners', 'title')) {
                $table->string('title')->nullable()->after('id');
            }
            if (!Schema::hasColumn('banners', 'subtitle')) {
                $table->string('subtitle')->nullable()->after('title');
            }
            if (!Schema::hasColumn('banners', 'image_url')) {
                $table->string('image_url')->nullable()->after('subtitle');
            }
            if (!Schema::hasColumn('banners', 'link_url')) {
                $table->string('link_url')->nullable()->after('image_url');
            }
            if (!Schema::hasColumn('banners', 'position')) {
                $table->integer('position')->default(0)->after('link_url');
            }
            if (!Schema::hasColumn('banners', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('position');
            }
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'subtitle',
                'image_url',
                'link_url',
                'position',
                'is_active',
            ]);
        });
    }
};
