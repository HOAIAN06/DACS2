<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class BasicProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy categories
        $aoThun = Category::where('slug', 'ao-thun')->first();
        $aoSoMi = Category::where('slug', 'ao-so-mi')->first();
        $quanJean = Category::where('slug', 'quan-jean')->first();
        
        // ========================================
        // BASIC T-SHIRTS - giống ICONDENIM
        // ========================================
        $basicTshirts = [
            [
                'name' => 'Áo Thun Basic Cotton',
                'slug' => 'ao-thun-basic-cotton',
                'description' => 'Áo thun cotton 100% cao cấp, form regular fit thoải mái. Chất liệu mềm mại, thấm hút mồ hôi tốt.',
                'price' => 299000,
                'old_price' => 399000,
                'stock' => 100,
                'thumbnail' => '/images/products/basic-tshirt.jpg',
                'category_id' => $aoThun->id,
                'collection' => 'basics',
                'is_new' => true,
            ],
            [
                'name' => 'Áo Thun Premium Oversize',
                'slug' => 'ao-thun-premium-oversize',
                'description' => 'Áo thun oversize phong cách streetwear, chất cotton dày dặn. Form rộng thoải mái, phù hợp mix đồ.',
                'price' => 349000,
                'stock' => 80,
                'thumbnail' => '/images/products/oversize-tshirt.jpg',
                'category_id' => $aoThun->id,
                'collection' => 'basics',
                'is_best_seller' => true,
            ],
        ];

        foreach ($basicTshirts as $data) {
            $product = Product::create($data);
            
            // Tạo product images
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $data['thumbnail'],
                'is_main' => true,
                'sort_order' => 1,
            ]);
        }

        // ========================================
        // SHIRTS - Áo Sơ Mi
        // ========================================
        $shirts = [
            [
                'name' => 'Áo Sơ Mi Oxford Trắng',
                'slug' => 'ao-so-mi-oxford-trang',
                'description' => 'Áo sơ mi oxford classic, chất vải cao cấp. Form slim fit lịch sự, phù hợp đi làm và dự tiệc.',
                'price' => 549000,
                'old_price' => 699000,
                'stock' => 60,
                'thumbnail' => '/images/products/oxford-shirt.jpg',
                'category_id' => $aoSoMi->id,
                'collection' => 'formal',
                'is_new' => true,
            ],
        ];

        foreach ($shirts as $data) {
            $product = Product::create($data);
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $data['thumbnail'],
                'is_main' => true,
                'sort_order' => 1,
            ]);
        }

        // ========================================
        // JEANS - Quần Jean Basic
        // ========================================
        $jeans = [
            [
                'name' => 'Quần Jean Slim Fit Xanh Đậm',
                'slug' => 'quan-jean-slim-fit-xanh-dam',
                'description' => 'Quần jean slim fit classic, chất denim cao cấp co giãn nhẹ. Form ôm vừa phải, tôn dáng.',
                'price' => 599000,
                'old_price' => 799000,
                'stock' => 70,
                'thumbnail' => '/images/products/slim-jeans.jpg',
                'category_id' => $quanJean->id,
                'collection' => 'denim',
                'is_best_seller' => true,
            ],
            [
                'name' => 'Quần Jean Regular Fit Đen',
                'slug' => 'quan-jean-regular-fit-den',
                'description' => 'Quần jean regular fit màu đen, phong cách basic dễ phối đồ. Chất vải bền đẹp, không phai màu.',
                'price' => 649000,
                'stock' => 65,
                'thumbnail' => '/images/products/regular-jeans-black.jpg',
                'category_id' => $quanJean->id,
                'collection' => 'denim',
                'is_new' => true,
            ],
        ];

        foreach ($jeans as $data) {
            $product = Product::create($data);
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $data['thumbnail'],
                'is_main' => true,
                'sort_order' => 1,
            ]);
        }
    }
}
