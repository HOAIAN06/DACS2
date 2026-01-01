<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id',
        'image_url',
        'is_main',
        'sort_order',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getFullUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        if (Str::startsWith($this->image_url, ['http://', 'https://', '//'])) {
            return $this->image_url;
        }

        // Ảnh cũ nằm trong public/images/, dùng asset()
        if (Str::startsWith($this->image_url, ['/images/', 'images/'])) {
            return asset($this->image_url);
        }

        // Ảnh mới lưu trong storage/app/public/products/, dùng Storage::url()
        return Storage::url($this->image_url);
    }
}
