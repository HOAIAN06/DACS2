<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Quan hệ: 1 sản phẩm thuộc 1 category
    public function category()
    {
        // 'category_id' là foreign key trong bảng products
        return $this->belongsTo(Category::class, 'category_id');
    }

    // 1. Tất cả ảnh của sản phẩm
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // 2. Ảnh chính (ưu tiên is_main = 1)
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_main', 1)
            ->orderBy('sort_order');   // nếu nhiều ảnh main thì lấy cái sort nhỏ nhất
    }

    // 3. Accessor trả về URL thumbnail (dùng luôn trong Blade)
    public function getThumbnailAttribute()
    {
        // Ưu tiên ảnh main
        $img = $this->images
            ->sortBy('sort_order')
            ->firstWhere('is_main', 1);

        // Nếu không có ảnh main -> lấy ảnh đầu tiên
        if (! $img) {
            $img = $this->images->sortBy('sort_order')->first();
        }

        return $img?->image_url;
    }
}
