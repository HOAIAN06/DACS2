<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Quan hệ: 1 category có nhiều product
    public function products()
    {
            return $this->hasMany(Product::class, 'category_id');
        }
    // Tự generate slug nếu chưa có
    protected static function booted()
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
