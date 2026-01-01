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

        // Tìm kiếm theo tên ?q=
        if ($request->filled('q')) {
            $keyword = $request->q;

            $query->where('name', 'like', "%{$keyword}%");
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
        $products = $query->paginate(30);

        // Lấy categories để làm filter
        $categories = Category::orderBy('name')->get();

        return view('shop.index', compact('products', 'categories'));

        
    }

    public function show($slug)
    {
        $product = Product::with(['images', 'category', 'mainImage', 'variants'])
            ->where('slug', $slug)
            ->firstOrFail();
        return view('product.show', compact('product'));
    }

    /**
     * API endpoint để lấy product data cho Quick Add modal
     */
    public function apiShow($id)
    {
        $product = Product::with(['category', 'variants', 'mainImage', 'images'])
            ->findOrFail($id);

        // Lấy ảnh main (is_main = 1), fallback về first image, rồi placeholder
        $imageUrl = asset('images/placeholder.jpg');
        
        if ($product->mainImage) {
            $imageUrl = $product->mainImage->full_url ?? asset('images/placeholder.jpg');
        } elseif ($product->images && $product->images->count() > 0) {
            $imageUrl = $product->images->first()->full_url ?? asset('images/placeholder.jpg');
        }

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'old_price' => $product->old_price,
            'category' => $product->category?->name,
            'image' => $imageUrl,
            'variants' => $product->variants->map(function($v) {
                return [
                    'id' => $v->id,
                    'color' => $v->color,
                    'size' => $v->size,
                    'price' => $v->price ?? $v->product->price,
                    'stock' => $v->stock ?? 0,
                ];
            })->toArray(),
        ]);
    }
}
