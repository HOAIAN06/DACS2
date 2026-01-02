<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Trang danh mục: Banner + danh sách sản phẩm
     */
    public function show(string $slug)
    {
        // Redirect old/incorrect slugs to new ones for backward compatibility
        $slugMapping = [
            'quan-jeans' => 'quan-jean',
            'ao-so-mi' => 'ao-somi',
            'giay-dep' => 'giay-phu-kien',
        ];
        
        if (isset($slugMapping[$slug])) {
            return redirect()->route('category.show', $slugMapping[$slug]);
        }
        
        // 1. Lấy category theo slug
        $category = Category::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // 2. Lấy sản phẩm thuộc category
        $products = Product::with(['mainImage'])
            ->where('category_id', $category->id)
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->paginate(16)
            ->withQueryString();

        // 3. Banner category
        // banner_url trong DB chỉ lưu tên file: banner_aothun.jpg
        $bannerUrl = $category->banner_url
            ? asset('images/banner/' . $category->banner_url)
            : asset('images/banner/default.jpg');

        return view('category.show', compact(
            'category',
            'products',
            'bannerUrl'
        ));
    }
}
