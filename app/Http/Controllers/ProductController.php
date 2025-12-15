<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Chỉ lấy sản phẩm đang hoạt động + kèm theo quan hệ category và images
        $query = Product::with(['category','images'])
            ->where('is_active', 1);

        // Tìm kiếm theo tên / sku ?q=
        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%");
            });
        }

        // Lọc theo category ?category=slug-category
        if ($request->filled('category')) {
            $slug = $request->category;

            $query->whereHas('category', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        // Lọc theo collection ?collection=retro-sports  (chấp nhận 'retro-sports' hoặc 'Retro Sports')
        if ($request->filled('collection')) {
            $collectionKey = $request->collection;
            // chuyển 'retro-sports' -> 'retro sports' để so khớp với DB
            $collectionLabel = str_replace('-', ' ', $collectionKey);

            $query->whereRaw('LOWER(collection) = ?', [strtolower($collectionLabel)]);
        }

        // Sắp xếp đơn giản ?sort=price_asc / price_desc / newest
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderByDesc('id'); // mới nhất
        }

        // Lấy sản phẩm + phân trang
        $products = $query->paginate(12)->withQueryString();

        // Lấy categories để làm filter
        $categories = Category::orderBy('name')->get();

        return view('shop.index', compact('products', 'categories'));

        
    }

    public function show($slug)
    {
        $product = Product::with(['images','category'])->where('slug', $slug)->firstOrFail();
        return view('product.show', compact('product'));
    }
}
