<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function show($slug)
    {
        // Map slug to actual collection names in database
        $slugToCollectionMap = [
            'retro-sports'   => 'Retro Sports',
            'snoopy'         => 'Snoopy',
            'mickey-friends' => 'Mickey&Friends',
        ];

        // Get the actual collection name from database
        $collectionName = $slugToCollectionMap[$slug] ?? null;
        
        if (!$collectionName) {
            abort(404, 'Collection not found');
        }

        // Lấy các sản phẩm thuộc bộ sưu tập này
        $products = Product::where('collection', $collectionName)
            ->where('is_active', 1)
            ->orderByDesc('created_at')
            ->paginate(16)
            ->withQueryString();

        return view('shop.collection', [
            'slug'           => $slug,
            'collectionName' => $collectionName,
            'products'       => $products,
        ]);
    }
}
