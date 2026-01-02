<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $userId = Auth::id();
        
        // Admin không được phép đánh giá sản phẩm
        if (Auth::user() && Auth::user()->is_admin) {
            return back()
                ->withErrors(['content' => 'Admin không thể đánh giá sản phẩm.'])
                ->withInput();
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:191'],
            'content' => ['required', 'string', 'max:2000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
        ]);

        // Chỉ cho phép đánh giá nếu đã mua sản phẩm (đơn không bị hủy)
        $purchaseItem = OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn($q) => $q
                ->where('user_id', $userId)
                ->where('status', '!=', 'canceled'))
            ->latest('id')
            ->first();

        if (!$purchaseItem) {
            return back()
                ->withErrors(['content' => 'Bạn chỉ có thể đánh giá sau khi mua sản phẩm này.'])
                ->withInput();
        }

        // Kiểm tra xem user đã đánh giá sản phẩm này chưa
        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('user_id', $userId)
            ->first();

        if ($existingReview) {
            return back()
                ->withErrors(['content' => 'Bạn đã đánh giá sản phẩm này rồi. Vui lòng sửa đánh giá cũ nếu muốn thay đổi.'])
                ->withInput();
        }

        $orderId = $purchaseItem->order_id;

        $review = new ProductReview();
        $review->product_id = $product->id;
        $review->user_id = $userId;
        $review->order_id = $orderId;
        $review->rating = $data['rating'];
        $review->title = $data['title'] ?? null;
        $review->content = $data['content'];
        $review->is_verified = true;
        $review->status = 'approved';

        // Xử lý tải ảnh
        $imageUrls = [];
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reviews', 'public');
                    $imageUrls[] = \Illuminate\Support\Facades\Storage::url($path);
                }
            }
        }
        $review->images = !empty($imageUrls) ? $imageUrls : null;

        $review->save();

        return redirect()
            ->route('product.show', $product->slug)
            ->with('success', 'Đã gửi đánh giá của bạn. Cảm ơn!');
    }

    public function edit(Product $product, ProductReview $review)
    {
        // Kiểm tra ownership
        if ($review->user_id !== Auth::id() || $review->product_id !== $product->id) {
            return back()->withErrors(['error' => 'Bạn không có quyền chỉnh sửa đánh giá này.']);
        }

        return view('product.review-edit', compact('product', 'review'));
    }

    public function update(Request $request, Product $product, ProductReview $review)
    {
        // Kiểm tra ownership
        if ($review->user_id !== Auth::id() || $review->product_id !== $product->id) {
            return back()->withErrors(['error' => 'Bạn không có quyền chỉnh sửa đánh giá này.']);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:191'],
            'content' => ['required', 'string', 'max:2000'],
            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['string'],
        ]);

        $review->rating = $data['rating'];
        $review->title = $data['title'] ?? null;
        $review->content = $data['content'];

        // Lấy danh sách ảnh hiện tại
        $imageUrls = $review->images ?? [];

        // Xóa ảnh cũ nếu có
        if (!empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $deleteUrl) {
                // Xóa khỏi mảng
                $imageUrls = array_filter($imageUrls, fn($url) => $url !== $deleteUrl);
                
                // Xóa file vật lý
                $path = str_replace('/storage/', '', parse_url($deleteUrl, PHP_URL_PATH));
                \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            }
            // Reset array keys
            $imageUrls = array_values($imageUrls);
        }

        // Thêm ảnh mới
        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                if ($image->isValid()) {
                    $path = $image->store('reviews', 'public');
                    $imageUrls[] = \Illuminate\Support\Facades\Storage::url($path);
                }
            }
        }
        
        $review->images = !empty($imageUrls) ? $imageUrls : null;

        $review->save();

        return redirect()
            ->route('product.show', $product->slug)
            ->with('success', 'Cập nhật đánh giá thành công!');
    }

    public function destroy(Product $product, ProductReview $review)
    {
        // Kiểm tra ownership
        if ($review->user_id !== Auth::id() || $review->product_id !== $product->id) {
            return back()->withErrors(['error' => 'Bạn không có quyền xóa đánh giá này.']);
        }

        $review->delete();

        return redirect()
            ->route('product.show', $product->slug)
            ->with('success', 'Xóa đánh giá thành công!');
    }

    public function respond(Request $request, Product $product, ProductReview $review)
    {
        // Chỉ admin mới có thể phản hồi
        if (!Auth::check() || !Auth::user()->is_admin) {
            return back()->withErrors(['error' => 'Bạn không có quyền phản hồi đánh giá.']);
        }

        // Kiểm tra review thuộc product này
        if ($review->product_id !== $product->id) {
            return back()->withErrors(['error' => 'Đánh giá không tồn tại.']);
        }

        $data = $request->validate([
            'admin_response' => ['required', 'string', 'max:2000'],
        ]);

        $review->admin_response = $data['admin_response'];
        $review->admin_response_at = now();
        $review->save();

        return redirect()
            ->route('product.show', $product->slug)
            ->with('success', 'Phản hồi đã được gửi thành công!');
    }
}
