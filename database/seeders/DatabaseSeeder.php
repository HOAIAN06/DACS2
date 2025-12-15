<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin account
        $this->call([AdminUserSeeder::class]);

        // Tạo 1 user test nếu chưa có
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User']
        );

        // Chạy lần lượt các seeder
        $this->call([
            CategorySeeder::class,
            RetroSportsProductsSeeder::class,
            JeansProductsSeeder::class,
            BasicProductsSeeder::class,           // Products basic giống ICONDENIM
            ProductVariantSeeder::class,          // Tạo variants (size/color) cho tất cả products
        ]);
    }
}
