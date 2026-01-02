<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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

        $canReview = false;

        if (Auth::check()) {
            $userId = Auth::id();
            $canReview = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) use ($userId) {
                    $q->where('user_id', $userId)
                      ->where('status', '!=', 'canceled');
                })
                ->exists();
        }

        $ratingSummary = $product->approvedReviews()
            ->selectRaw('COUNT(*) as total, AVG(rating) as avg_rating')
            ->first();

        $ratingDistribution = $product->approvedReviews()
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $reviews = $product->approvedReviews()
            ->with('user')
            ->latest()
            ->take(20)
            ->get();

        $avgRating = $ratingSummary?->avg_rating ? round($ratingSummary->avg_rating, 1) : null;
        $totalReviews = $ratingSummary?->total ?? 0;

        return view('product.show', compact('product', 'reviews', 'avgRating', 'totalReviews', 'ratingDistribution', 'canReview'));
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

    /**
     * Trang hiển thị sản phẩm mới (Hàng mới)
     */
    public function newArrivals(Request $request)
    {
        $query = Product::with(['category','images'])
            ->where('is_active', 1)
            ->where(function ($q) {
                $q->where('is_new', 1)
                  ->orWhere('created_at', '>=', now()->subDays(30));
            });

        // Sắp xếp
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderByDesc('created_at'); // mới nhất
        }

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.new-arrivals', [
            'products' => $products,
            'categories' => $categories,
            'pageTitle' => 'Hàng Mới Về',
            'breadcrumb' => 'Hàng Mới'
        ]);
    }

    /**
     * Trang hiển thị sản phẩm bán chạy
     */
    public function bestSellers(Request $request)
    {
        $hasTotalSold = Schema::hasColumn('products', 'total_sold');

        $query = Product::with(['category','images'])
            ->where('is_active', 1)
            ->where(function ($q) use ($hasTotalSold) {
                if ($hasTotalSold) {
                    $q->where('total_sold', '>', 10);
                }
                $q->orWhere('is_best_seller', 1);
            });

        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                if ($hasTotalSold) {
                    $query->orderByDesc('total_sold');
                }
                $query->orderByDesc('created_at');
        }

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.best-sellers', [
            'products' => $products,
            'categories' => $categories,
            'pageTitle' => 'Sản Phẩm Bán Chạy',
            'breadcrumb' => 'Bán chạy'
        ]);
    }

    /**
     * Trang hiển thị sản phẩm Thu Đông
     */
    public function winterCollection(Request $request)
    {
        // Lọc theo collection "thu-dong" trong database
        $query = Product::with(['category','images'])
            ->where('is_active', 1)
            ->where('collection', 'thu-dong');

        // Sắp xếp
        switch ($request->get('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderByDesc('created_at'); // mới nhất
        }

        $products = $query->paginate(16)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('shop.winter-collection', [
            'products' => $products,
            'categories' => $categories,
            'pageTitle' => 'Bộ Sưu Tập Thu Đông',
            'breadcrumb' => 'Thu Đông'
        ]);
    }
}
