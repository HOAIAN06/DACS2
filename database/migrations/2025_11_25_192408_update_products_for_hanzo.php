<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nếu bảng products chưa tồn tại (DB mới tinh) thì bỏ qua, tránh lỗi
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            // slug – em đã có trong DB rồi nên chỉ thêm nếu thiếu, KHÔNG gắn unique() nữa
            if (! Schema::hasColumn('products', 'slug')) {
                $table->string('slug', 255)->nullable()->after('name');
            }

            // Ảnh đại diện chính
            if (! Schema::hasColumn('products', 'thumbnail_url')) {
                $table->string('thumbnail_url', 255)->nullable()->after('slug');
            }

            // Giá bán & giá gốc
            // Lưu ý: trong DB hiện tại em đang dùng DECIMAL(12,2), 
            // nên nếu đã có thì KHÔNG đụng vào nữa.
            if (! Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)->default(0)->after('thumbnail_url');
            }

            if (! Schema::hasColumn('products', 'old_price')) {
                $table->decimal('old_price', 12, 2)->nullable()->after('price');
            }

            // Tồn kho tổng (tuỳ em có dùng hay không)
            if (! Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('old_price');
            }

            // Các flag phục vụ home page
            if (! Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false)->after('stock');
            }

            if (! Schema::hasColumn('products', 'is_best_seller')) {
                $table->boolean('is_best_seller')->default(false)->after('is_new');
            }

            if (! Schema::hasColumn('products', 'is_outlet')) {
                $table->boolean('is_outlet')->default(false)->after('is_best_seller');
            }

            // Tag (ProCOOL, SMART JEANS…) – tuỳ em có dùng thì để, không thì bỏ
            if (! Schema::hasColumn('products', 'tag')) {
                $table->string('tag', 255)->nullable()->after('is_outlet');
            }

            // Trạng thái hiển thị – em đang có is_active rồi,
            // status chỉ là flag phụ, nếu không cần có thể bỏ luôn đoạn này.
            if (! Schema::hasColumn('products', 'status')) {
                $table->boolean('status')->default(true)->after('tag');
            }

            // Liên kết category – trong DB của em đã có sẵn category_id,
            // nên chỉ thêm nếu thiếu
            if (! Schema::hasColumn('products', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('id');
            }
        });
    }

    public function down(): void
    {
        // Khi rollback cũng phải check tồn tại để tránh lỗi
        if (! Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
            $columns = [
                'slug',
                'thumbnail_url',
                'price',
                'old_price',
                'stock',
                'is_new',
                'is_best_seller',
                'is_outlet',
                'tag',
                'status',
                'category_id',
            ];

            // Chỉ drop những cột thực sự tồn tại
            foreach ($columns as $column) {
                if (Schema::hasColumn('products', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
