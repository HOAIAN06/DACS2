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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
