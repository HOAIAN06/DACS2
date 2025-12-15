<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class RetroSportsProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'          => 'Ao Ni Sweatshirt Nam Intramural',
                'slug'          => 'ao-ni-sweatshirt-nam-intramural',
                'category_slug' => 'ao-ni-len',
                'price'         => 379000,
                'old_price'     => 379000,
                'thumbnail_url' => '/images/Retro_sports/ao-ni-sweatshirt-nam-intramural.jpg',
                'description'   => 'Ao ni sweatshirt nam từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/ao-ni-sweatshirt-nam-intramural.jpg',
                ],
            ],
            [
                'name'          => 'Quan Short Nam Field Day',
                'slug'          => 'quan-short-nam-field-day',
                'category_slug' => 'quan-short',
                'price'         => 349000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/Retro_sports/quan-short-nam-retro.jpg',
                'description'   => 'Quần short nam Field Day từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/quan-short-nam-retro.jpg',
                ],
            ],
            [
                'name'          => 'Ao Thun Nam New Rules',
                'slug'          => 'ao-thun-nam-new-rules',
                'category_slug' => 'ao-thun',
                'price'         => 329000,
                'old_price'     => 329000,
                'thumbnail_url' => '/images/Retro_sports/ao-thun-nam-new-rules.jpg',
                'description'   => 'Áo thun nam New Rules từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/ao-thun-nam-new-rules.jpg',
                ],
            ],
            [
                'name'          => 'Ao Thun Nam The Playbook',
                'slug'          => 'ao-thun-nam-the-playbook',
                'category_slug' => 'ao-thun',
                'price'         => 299000,
                'old_price'     => 299000,
                'thumbnail_url' => '/images/Retro_sports/ao-thun-nam-the-playbook.jpg',
                'description'   => 'Áo thun nam The Playbook từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/ao-thun-nam-the-playbook.jpg',
                ],
            ],
            [
                'name'          => 'Non Luoi Trai Nam Retro Sports',
                'slug'          => 'non-luoi-trai-nam-retro-sports',
                'category_slug' => 'non',
                'price'         => 249000,
                'old_price'     => 249000,
                'thumbnail_url' => '/images/Retro_sports/non-luoi-trai-nam-retro-sports.jpg',
                'description'   => 'Nón lưỡi trai nam Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/non-luoi-trai-nam-retro-sports.jpg',
                ],
            ],
            [
                'name'          => 'Non Luoi Trai Nam Twentyfive',
                'slug'          => 'non-luoi-trai-nam-twentyfive',
                'category_slug' => 'non',
                'price'         => 279000,
                'old_price'     => 279000,
                'thumbnail_url' => '/images/Retro_sports/non-luoi-trai-nam-twentyfive.jpg',
                'description'   => 'Nón lưỡi trai nam Twentyfive từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/non-luoi-trai-nam-twentyfive.jpg',
                ],
            ],
            [
                'name'          => 'Quan Jogger Nam Dashfield',
                'slug'          => 'quan-jogger-nam-dashfield',
                'category_slug' => 'quan-jogger-quan-dai',
                'price'         => 429000,
                'old_price'     => 429000,
                'thumbnail_url' => '/images/Retro_sports/quan-jogger-nam-dashfield.jpg',
                'description'   => 'Quần jogger nam Dashfield từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/quan-jogger-nam-dashfield.jpg',
                ],
            ],
            [
                'name'          => 'Quan Jogger Nam Milesway',
                'slug'          => 'quan-jogger-nam-milesway',
                'category_slug' => 'quan-jogger-quan-dai',
                'price'         => 399000,
                'old_price'     => 399000,
                'thumbnail_url' => '/images/Retro_sports/quan-jogger-nam-milesway.jpg',
                'description'   => 'Quần jogger nam Milesway từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/quan-jogger-nam-milesway.jpg',
                ],
            ],
            [
                'name'          => 'Ao Ni Sweatshirt Nam Heritage',
                'slug'          => 'ao-ni-sweatshirt-nam-heritage',
                'category_slug' => 'ao-ni-len',
                'price'         => 419000,
                'old_price'     => 419000,
                'thumbnail_url' => '/images/Retro_sports/ao-ni-sweatshirt-nam-heritage.jpg',
                'description'   => 'Áo nỉ sweatshirt nam Heritage từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/ao-ni-sweatshirt-nam-heritage.jpg',
                ],
            ],
            [
                'name'          => 'Ao Khoac Ni Nam Rally',
                'slug'          => 'ao-khoac-ni-nam-rally',
                'category_slug' => 'ao-khoac',
                'price'         => 499000,
                'old_price'     => 499000,
                'thumbnail_url' => '/images/Retro_sports/ao-khoac-ni-nam-rally.jpg',
                'description'   => 'Áo khoác nỉ nam Rally từ bộ sưu tập Retro Sports',
                'collection'    => 'Retro Sports',
                'images'        => [
                    '/images/Retro_sports/ao-khoac-ni-nam-rally.jpg',
                ],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();

            if (! $category) {
                // Nếu thiếu category thì bỏ qua, tránh lỗi seeder
                continue;
            }

            $images = $data['images'] ?? [];
            unset($data['images'], $data['category_slug']);

            $product = Product::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, [
                    'category_id' => $category->id,
                    'is_active'   => 1,
                ])
            );

            // Seed ảnh
            foreach ($images as $index => $path) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'image_url'  => $path,
                    ],
                    [
                        'is_main'    => $index === 0 ? 1 : 0,
                        'sort_order' => $index,
                    ]
                );
            }
        }
    }
}
