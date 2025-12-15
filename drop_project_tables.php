<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "Đang xóa các bảng đồ án...\n";

DB::statement('SET FOREIGN_KEY_CHECKS=0');

$projectTables = [
    'order_items',
    'orders',
    'cart_items',
    'carts',
    'product_variants',
    'product_images',
    'products',
    'categories',
    'banners',
];

foreach ($projectTables as $table) {
    if (Schema::hasTable($table)) {
        Schema::drop($table);
        echo "✓ Đã xóa bảng: {$table}\n";
    } else {
        echo "- Bảng không tồn tại: {$table}\n";
    }
}

// Xóa migration records của các bảng đồ án
DB::table('migrations')->whereIn('migration', [
    '2025_11_25_000000_create_base_tables',
    '2025_11_25_192408_update_products_for_hanzo',
    '2025_11_25_193007_update_categories_for_hanzo',
    '2025_11_25_193034_update_banners_for_hanzo',
    '2025_11_25_193100_update_product_images_for_hanzo',
    '2025_11_25_193120_update_product_variants_for_hanzo',
    '2025_11_29_183612_add_collection_to_products_table',
    '2025_12_13_014600_create_carts_table',
    '2025_12_13_014602_create_cart_items_table',
    '2025_12_13_014830_create_orders_table',
    '2025_12_13_014831_create_order_items_table',
])->delete();

DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n✅ Hoàn tất! Các bảng Laravel mặc định vẫn còn.\n";
echo "Bảng còn lại: users, cache, cache_locks, jobs, job_batches, failed_jobs, sessions, password_reset_tokens, migrations\n";
