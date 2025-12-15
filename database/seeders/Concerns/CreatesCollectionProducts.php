<?php

namespace Database\Seeders\Concerns;

use App\Models\Product;
use App\Models\ProductImage;

trait CreatesCollectionProducts
{
    protected function createProductWithImages(array $data): Product
    {
        // tách images ra khỏi $data
        $images = $data['images'] ?? [];
        unset($data['images']);

        // tạo / update product theo slug
        $product = Product::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );

        // Nếu không truyền images mà có thumbnail, auto lấy thumbnail làm ảnh chính
        if (empty($images) && !empty($data['thumbnail_url'])) {
            $images = [$data['thumbnail_url']];
        }

        // Gắn ảnh vào product_images
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

        return $product;
    }
}
