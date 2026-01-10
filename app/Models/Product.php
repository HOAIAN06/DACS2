<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\OrderItem;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'price_original',
        'description',
        'stock',
        'total_sold',
        'thumbnail_url',
        'is_active',
        'is_new',
        'is_best_seller',
        'is_outlet',
        'tag',
        'status',
        'collection',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_new' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_outlet' => 'boolean',
        'total_sold' => 'integer',
    ];

    /**
     * Lấy tổng stock từ variants
     * Nếu stock trên product = 0, tính từ tổng stock các variants
     */
    public function getTotalStockAttribute(): int
    {
        // Nếu product có stock > 0, dùng luôn
        if ($this->attributes['stock'] > 0) {
            return $this->attributes['stock'];
        }

        // Nếu stock = 0, tính từ variants
        return $this->variants()->sum('stock') ?? 0;
    }

    /**
     * Update stock của product từ tổng stock các variants
     */
    public function updateStockFromVariants(): void
    {
        $totalStock = $this->variants()->sum('stock') ?? 0;
        $this->update(['stock' => $totalStock]);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)
            ->where('is_main', 1)
            ->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('status', 'approved');
    }

    private function resolveImageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//'])) {
            return $path;
        }

        // Ảnh cũ nằm trong public/images/, dùng asset()
        if (Str::startsWith($path, ['/images/', 'images/'])) {
            return asset($path);
        }

        // Ảnh mới lưu trong storage/app/public/products/, dùng Storage::url()
        return \Illuminate\Support\Facades\Storage::url($path);
    }

    public function getMainImageUrlAttribute(): ?string
    {
        $image = null;

        if ($this->relationLoaded('mainImage')) {
            $image = $this->mainImage;
        }

        if (!$image) {
            $image = $this->mainImage()->first();
        }

        if (!$image && $this->relationLoaded('images')) {
            $image = $this->images->sortBy('sort_order')->first();
        }

        if (!$image) {
            $image = $this->images()->orderBy('sort_order')->first();
        }

        // Nếu không tìm thấy trong product_images, fallback về thumbnail_url (cho sản phẩm cũ)
        if (!$image && $this->thumbnail_url) {
            return $this->resolveImageUrl($this->thumbnail_url);
        }

        return $this->resolveImageUrl($image?->image_url);
    }

    // $product->thumbnail => trả ra image_url (string) từ product_images
    public function getThumbnailAttribute()
    {
        return $this->getMainImageUrlAttribute();
    }
}
