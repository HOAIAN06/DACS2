<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function show($slug)
    {
        // Lấy các sản phẩm thuộc bộ sưu tập này
        $products = Product::where('collection', $slug)->paginate(12);

        // Nếu muốn, bạn có thể map tên hiển thị đẹp hơn ở đây
        $collectionNames = [
            'retro-sports'   => 'Retro Sports',
            'snoopy'         => 'Snoopy Collection',
            'mickey-friends' => 'Mickey & Friends',
        ];

        $collectionName = $collectionNames[$slug] ?? ucfirst(str_replace('-', ' ', $slug));

        return view('shop.collection', [
            'slug'           => $slug,
            'collectionName' => $collectionName,
            'products'       => $products,
        ]);
    }
}
