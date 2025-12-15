<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Áo Thun', 'slug' => 'ao-thun', 'parent_id' => null],
            ['name' => 'Áo Sơ Mi', 'slug' => 'ao-so-mi', 'parent_id' => null],
            ['name' => 'Áo Polo', 'slug' => 'ao-polo', 'parent_id' => null],
            ['name' => 'Quần Short', 'slug' => 'quan-short', 'parent_id' => null],
            ['name' => 'Quần Jean', 'slug' => 'quan-jean', 'parent_id' => null],
            ['name' => 'Quần Tây', 'slug' => 'quan-tay', 'parent_id' => null],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
