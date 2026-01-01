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
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $products = $query->orderByDesc('id')->paginate(15);
        
        // Force paginator to use correct path
        $products->setPath(route('admin.products.index'));

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
            'stock'       => 'nullable|integer|min:0',
            'main_image'  => 'required|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
            'is_active'   => 'nullable|boolean',
        ]);

        // Generate slug if not provided
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active']   = $request->boolean('is_active');

        $product = Product::create($data);

        // Save main image
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('products', 'public');
            $product->images()->create([
                'image_url' => $mainImagePath,
                'is_main' => true,
                'sort_order' => 0,
            ]);
        }

        // Save additional images
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_url' => $imagePath,
                    'is_main' => false,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Save variants (color & size)
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (empty($variantData['color']) && empty($variantData['size'])) {
                    continue;
                }

                // Tách màu và size theo dấu phẩy
                $colors = !empty($variantData['color']) 
                    ? array_map('trim', explode(',', $variantData['color'])) 
                    : [null];
                    
                $sizes = !empty($variantData['size']) 
                    ? array_map('trim', explode(',', $variantData['size'])) 
                    : [null];

                // Tạo tất cả các tổ hợp màu x size
                foreach ($colors as $color) {
                    foreach ($sizes as $size) {
                        if ($color || $size) {
                            $product->variants()->create([
                                'color' => $color,
                                'size' => $size,
                                'price' => $variantData['price'] ?? $product->price,
                                'stock' => $variantData['stock'] ?? 0,
                            ]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Tạo sản phẩm thành công.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', 1)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'category_id'  => 'required|exists:categories,id',
            'price'        => 'required|numeric|min:0',
            'old_price'    => 'nullable|numeric|min:0',
            'description'  => 'nullable|string',
            'stock'        => 'nullable|integer|min:0',
            'main_image'   => 'nullable|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
            'is_active'    => 'nullable|boolean',
            'tag'          => 'nullable|string|max:255',
            'status'       => 'nullable|string|max:50',
            'collection'   => 'nullable|string|max:255',
        ]);

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $data['is_active'] = $request->boolean('is_active', true);

        // Update main image if provided
        if ($request->hasFile('main_image')) {
            $mainImage = $product->mainImage;
            if ($mainImage) {
                Storage::disk('public')->delete($mainImage->image_url);
                $mainImagePath = $request->file('main_image')->store('products', 'public');
                $mainImage->update(['image_url' => $mainImagePath]);
            } else {
                $mainImagePath = $request->file('main_image')->store('products', 'public');
                $product->images()->create([
                    'image_url' => $mainImagePath,
                    'is_main' => true,
                    'sort_order' => 0,
                ]);
            }
        }

        // Add new additional images
        if ($request->hasFile('additional_images')) {
            $currentMaxOrder = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('additional_images') as $index => $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_url' => $imagePath,
                    'is_main' => false,
                    'sort_order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        // Update existing variants
        if ($request->has('existing_variants')) {
            foreach ($request->existing_variants as $variantId => $variantData) {
                $variant = $product->variants()->find($variantId);
                if ($variant) {
                    $variant->update([
                        'color' => $variantData['color'] ?? null,
                        'size' => $variantData['size'] ?? null,
                        'price' => $variantData['price'] ?? $product->price,
                        'stock' => $variantData['stock'] ?? 0,
                    ]);
                }
            }
        }

        // Add new variants
        if ($request->has('variants')) {
            foreach ($request->variants as $variantData) {
                if (!empty($variantData['color']) || !empty($variantData['size'])) {
                    $product->variants()->create([
                        'color' => $variantData['color'] ?? null,
                        'size' => $variantData['size'] ?? null,
                        'price' => $variantData['price'] ?? $product->price,
                        'stock' => $variantData['stock'] ?? 0,
                    ]);
                }
            }
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function deleteVariant($id)
    {
        $variant = \App\Models\ProductVariant::findOrFail($id);
        $variant->delete();

        return response()->json(['success' => true]);
    }

    public function deleteImage($id)
    {
        $image = \App\Models\ProductImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_url);
        $image->delete();

        return response()->json(['success' => true]);
    }

    public function toggle($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => !$product->is_active]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật trạng thái hiển thị thành công.');
    }

    public function applyDiscount(Request $request, $id)
    {
        $validated = $request->validate([
            'discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $product = Product::findOrFail($id);
        $percent = $validated['discount_percent'];

        // Nếu chưa có giá gốc, lưu giá hiện tại vào price_original
        if (!$product->price_original) {
            $product->price_original = $product->price;
        }

        // Tính giá mới
        $newPrice = round($product->price_original * (1 - $percent / 100));
        $product->price = $newPrice;
        $product->save();

        return response()->json([
            'success' => true,
            'new_price' => $newPrice,
            'price_original' => $product->price_original,
            'discount_percent' => $percent,
        ]);
    }

    public function destroy(Request $request, Product $product)
    {
        // Delete all product images
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_url);
            $image->delete();
        }

        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        // Preserve query parameters (page, filters, etc.)
        $queryParams = $request->except(['_token', '_method']);
        
        return redirect()->route('admin.products.index', $queryParams)
            ->with('success', 'Xóa sản phẩm thành công.');
    }
}
