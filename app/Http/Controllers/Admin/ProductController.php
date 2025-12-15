<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderByDesc('id')
            ->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $data['slug']        = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'old_price'   => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'thumbnail'   => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
        ]);

        $data['slug']        = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Xóa sản phẩm thành công.');
    }
}
