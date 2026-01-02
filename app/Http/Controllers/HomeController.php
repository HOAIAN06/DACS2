<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
                $hasTotalSold = Schema::hasColumn('products', 'total_sold');

        // Lấy 20 sản phẩm mới nhất (dự phòng)
        $products = Product::orderBy('created_at', 'desc')->take(20)->get();

        // Lấy 10 sản phẩm Retro Sports (so khớp cả 'retro-sports' và 'Retro Sports')
        $collectionKey = 'retro-sports';
        $collectionLabel = str_replace('-', ' ', $collectionKey); // "retro sports"

        $retroSportsProducts = Product::with('mainImage')
                          ->whereRaw('LOWER(collection) = ?', [strtolower($collectionLabel)])
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        // Lấy 10 sản phẩm Quần Jeans
        $jeansKey = 'jeans';
        $jeansLabel = 'Quần Jeans'; // hoặc 'Jeans'

        $jeansProducts = Product::with('mainImage')
                          ->whereRaw('LOWER(collection) = ?', [strtolower($jeansLabel)])
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        // Lấy 5 sản phẩm cho các danh mục Áo Thun / Áo Sơmi / Áo Polo
        $teeProducts = Product::with('mainImage')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-thun'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        $somiProducts = Product::with('mainImage')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-somi'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        $poloProducts = Product::with('mainImage')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-polo'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        $tayProducts = Product::with('mainImage')
        ->whereHas('category', function ($q) { $q->where('slug', 'quan-tay'); })
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();


                // Hàng mới: flag is_new hoặc auto 30 ngày
                $newProducts = Product::with('mainImage')
                        ->where('is_active', 1)
                        ->where(function ($q) {
                            $q->where('is_new', 1)
                              ->orWhere('created_at', '>=', now()->subDays(30));
                        })
                        ->orderBy('created_at', 'desc')
                        ->take(12)
                        ->get();

                // Bộ sưu tập Thu Đông
                $winterProducts = Product::with('mainImage')
                        ->whereRaw('LOWER(collection) = ?', ['thu-dong'])
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->take(12)
                        ->get();

                // Bán chạy: ưu tiên total_sold, fallback is_best_seller
                                $bestSellerProducts = Product::with('mainImage')
                                                ->where('is_active', 1)
                                                ->where(function ($q) use ($hasTotalSold) {
                                                        if ($hasTotalSold) {
                                                                $q->where('total_sold', '>', 10);
                                                        }
                                                        $q->orWhere('is_best_seller', 1);
                                                })
                                                ->when($hasTotalSold, fn($q) => $q->orderByDesc('total_sold'))
                                                ->orderByDesc('created_at')
                                                ->take(12)
                                                ->get();

                // Các section khác tạm dùng chung
                $signatureProducts  = $products;
                $outletProducts     = $products;

        // SHORT: ví dụ lấy 10 sản phẩm có collection = "Short" hoặc tên bạn đang lưu
        $shortProducts = Product::with('mainImage')
        ->where(function ($q) {
        $q->whereRaw('LOWER(collection) = ?', ['short'])
          ->orWhereRaw('LOWER(collection) = ?', ['quan short'])
          ->orWhereRaw('LOWER(name) LIKE ?', ['%short%']);
    })
    ->where('is_active', 1)
    ->orderBy('created_at', 'desc')
    ->take(10)
    ->get();


    return view('home', compact(
        'signatureProducts',
        'newProducts',
        'bestSellerProducts',
        'outletProducts',
        'winterProducts',
        'retroSportsProducts',
        'jeansProducts',
        'teeProducts',
        'somiProducts',
        'poloProducts',
        'shortProducts',
        'tayProducts'
    ));

    }
}
