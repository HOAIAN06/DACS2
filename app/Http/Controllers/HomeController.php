<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Lấy 20 sản phẩm mới nhất (dự phòng)
        $products = Product::orderBy('created_at', 'desc')->take(20)->get();

        // Lấy 10 sản phẩm Retro Sports (so khớp cả 'retro-sports' và 'Retro Sports')
        $collectionKey = 'retro-sports';
        $collectionLabel = str_replace('-', ' ', $collectionKey); // "retro sports"

        $retroSportsProducts = Product::with('images')
                          ->whereRaw('LOWER(collection) = ?', [strtolower($collectionLabel)])
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        // Lấy 10 sản phẩm Quần Jeans
        $jeansKey = 'jeans';
        $jeansLabel = 'Quần Jeans'; // hoặc 'Jeans'

        $jeansProducts = Product::with('images')
                          ->whereRaw('LOWER(collection) = ?', [strtolower($jeansLabel)])
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        // Lấy 5 sản phẩm cho các danh mục Áo Thun / Áo Sơmi / Áo Polo
        $teeProducts = Product::with('images')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-thun'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        $somiProducts = Product::with('images')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-somi'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

        $poloProducts = Product::with('images')
                ->whereHas('category', function ($q) { $q->where('slug', 'ao-polo'); })
                ->where('is_active', 1)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
        $tayProducts = Product::with('images')
        ->whereHas('category', function ($q) { $q->where('slug', 'quan-tay'); })
        ->where('is_active', 1)
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();


                // Hàng mới (is_new = 1)
                $newProducts = Product::with('images')
                        ->where('is_new', 1)
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->take(12)
                        ->get();

                // Bộ sưu tập Thu Đông
                $winterProducts = Product::with('images')
                        ->whereRaw('LOWER(collection) = ?', ['thu-dong'])
                        ->where('is_active', 1)
                        ->orderBy('created_at', 'desc')
                        ->take(12)
                        ->get();

                // Các section khác tạm dùng chung
                $signatureProducts  = $products;
                $bestSellerProducts = $products;
                $outletProducts     = $products;

        // SHORT: ví dụ lấy 10 sản phẩm có collection = "Short" hoặc tên bạn đang lưu
        $shortProducts = Product::with('images')
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
