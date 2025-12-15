<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        // Sizes giống ICONDENIM
        $sizes = ['S', 'M', 'L', 'XL', '2XL'];
        
        // Colors phổ biến
        $colors = [
            'Đen' => '#000000',
            'Trắng' => '#FFFFFF',
            'Xám' => '#808080',
            'Navy' => '#000080',
            'Be' => '#F5F5DC',
        ];

        foreach ($products as $product) {
            $basePrice = $product->price;
            
            foreach ($sizes as $index => $size) {
                foreach ($colors as $colorName => $colorCode) {
                    // Random stock cho realistic
                    $stock = rand(5, 50);
                    
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => strtoupper($product->slug) . '-' . $size . '-' . strtoupper(substr($colorName, 0, 2)),
                        'size' => $size,
                        'color' => $colorName,
                        'price' => $basePrice, // Giá giống product
                        'stock' => $stock,
                    ]);
                }
            }
        }
    }
}
