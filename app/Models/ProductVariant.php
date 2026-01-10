<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'color',
        'size',
        'sku',
        'price',
        'stock',
    ];

    protected $casts = [
        'price' => 'decimal:0',
    ];

    protected static function boot()
    {
        parent::boot();

        // Khi variant được lưu/cập nhật, update product stock
        static::saved(function ($variant) {
            if ($variant->product) {
                $variant->product->updateStockFromVariants();
            }
        });

        // Khi variant được xóa, update product stock
        static::deleted(function ($variant) {
            if ($variant->product) {
                $variant->product->updateStockFromVariants();
            }
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
