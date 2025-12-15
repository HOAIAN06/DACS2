<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class JeansProductsSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name'          => 'Quần Jean Nam Blue Sand Ong Suong Vintage Wash',
                'slug'          => 'quan-jean-nam-blue-sand-ong-suong-vintage-wash',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-blue-sand-ong-suong-vintage-wash-1.jpg',
                'description'   => 'Quần jeans nam với thiết kế vintage wash, phù hợp cho phong cách casual',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-blue-sand-ong-suong-vintage-wash-1.jpg',
                    '/images/quanjeans/quan-jean-nam-blue-sand-ong-suong-vintage-wash-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Cotton Offwhite',
                'slug'          => 'quan-jean-nam-cotton-offwhite',
                'category_slug' => 'quan-jean',
                'price'         => 279000,
                'old_price'     => 329000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-cotton-offwhite-1.jpg',
                'description'   => 'Quần jeans nam chất liệu cotton cao cấp, màu trắng offwhite',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-cotton-offwhite-1.jpg',
                    '/images/quanjeans/quan-jean-nam-cotton-offwhite-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Edge Link Light Blue',
                'slug'          => 'quan-jean-nam-edge-link-light-blue',
                'category_slug' => 'quan-jean',
                'price'         => 289000,
                'old_price'     => 339000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-edge-link-light-blue-1.jpg',
                'description'   => 'Quần jeans nam màu xanh nhạt, chi tiết edge link độc đáo',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-edge-link-light-blue-1.jpg',
                    '/images/quanjeans/quan-jean-nam-edge-link-light-blue-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Grey Form',
                'slug'          => 'quan-jean-nam-grey-form',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-grey-1.jpg',
                'description'   => 'Quần jeans nam màu xám hiện đại, form vừa vặn',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-grey-1.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Ink Navy Form',
                'slug'          => 'quan-jean-nam-ink-navy-form',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-ink-navy-1.jpg',
                'description'   => 'Quần jeans nam màu xanh đậm, form ôm vừa phải',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-ink-navy-1.jpg',
                    '/images/quanjeans/quan-jean-nam-ink-navy-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Moss Black Ong Suong Wash',
                'slug'          => 'quan-jean-nam-moss-black-ong-suong-wash',
                'category_slug' => 'quan-jean',
                'price'         => 289000,
                'old_price'     => 339000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-moss-black-ong-suong-wash-1.jpg',
                'description'   => 'Quần jeans nam đen ống suông với wash tinh tế',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-moss-black-ong-suong-wash-1.jpg',
                    '/images/quanjeans/quan-jean-nam-moss-black-ong-suong-wash-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Sieu Mat Ong Om Procool Black',
                'slug'          => 'quan-jean-nam-sieu-mat-ong-om-procool-black',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-sieu-mat-ong-om-procool-black-1.jpg',
                'description'   => 'Quần jeans nam siêu mỏng công nghệ Procool, ống ôm',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-sieu-mat-ong-om-procool-black-1.jpg',
                    '/images/quanjeans/quan-jean-nam-sieu-mat-ong-om-procool-black-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Smart Jeans Sieu Co Dan',
                'slug'          => 'quan-jean-nam-smart-jeans-sieu-co-dan',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-sieu-co-dan-xanh-nhat-1.jpg',
                'description'   => 'Quần jeans nam siêu co dãn, thoải mái suốt ngày',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-sieu-co-dan-xanh-nhat-1.jpg',
                    '/images/quanjeans/quan-jean-nam-sieu-co-dan-xanh-nhat-2.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Xanh Nhat Ong Suong',
                'slug'          => 'quan-jean-nam-xanh-nhat-ong-suong',
                'category_slug' => 'quan-jean',
                'price'         => 289000,
                'old_price'     => 339000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-xanh-nhat-ong-suong-en-icon-1051.jpg',
                'description'   => 'Quần jeans nam xanh nhạt, thiết kế ống suông thoải mái',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-xanh-nhat-ong-suong-en-icon-1051.jpg',
                    '/images/quanjeans/quan-jean-nam-xanh-nhat-ong-suong-en-icon-1052.jpg',
                ],
            ],
            [
                'name'          => 'Quần Jean Nam Xam Trang Ong Suong Dirt Wash',
                'slug'          => 'quan-jean-nam-xam-trang-ong-suong-dirt-wash',
                'category_slug' => 'quan-jean',
                'price'         => 299000,
                'old_price'     => 349000,
                'thumbnail_url' => '/images/quanjeans/quan-jean-nam-xam-trang-ong-suong-dirt-wash-1.jpg',
                'description'   => 'Quần jeans nam xám trắng ống suông, dirt wash vintage',
                'collection'    => 'Quần Jeans',
                'images'        => [
                    '/images/quanjeans/quan-jean-nam-xam-trang-ong-suong-dirt-wash-1.jpg',
                    '/images/quanjeans/quan-jean-nam-xam-trang-ong-suong-dirt-wash-2.jpg',
                ],
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();

            if (! $category) {
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
