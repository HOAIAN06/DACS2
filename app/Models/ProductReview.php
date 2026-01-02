<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'content',
        'images',
        'is_verified',
        'status',
        'reply',
        'admin_response',
        'admin_response_at',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified' => 'boolean',
        'admin_response_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
