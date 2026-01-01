<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    private function currentCart(): Cart
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $query = Cart::query()->active();

        if ($userId) {
            $cart = $query->where('user_id', $userId)->first();
            if ($cart) return $cart;

            $sessionCart = $query->whereNull('user_id')->where('session_id', $sessionId)->first();
            if ($sessionCart) {
                $sessionCart->update(['user_id' => $userId]);
                return $sessionCart;
            }

            return Cart::create(['user_id' => $userId, 'status' => 'active']);
        }

        $cart = $query->whereNull('user_id')->where('session_id', $sessionId)->first();
        return $cart ?: Cart::create(['session_id' => $sessionId, 'status' => 'active']);
    }

    public function index()
    {
        $cart = $this->currentCart()->load([
            'items.product.mainImage',
            'items.product.images',
            'items.variant',
        ]);

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        $subtotal = $cart->items->sum(fn($i) => (float)$i->price * (int)$i->qty);
        $shippingFee = 0; // Miễn phí vận chuyển
        $total = $subtotal + $shippingFee;

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'subtotal', 'shippingFee', 'total', 'user'));
    }

    public function store(Request $request)
    {
        // Log request data để debug
        Log::info('Checkout Request:', $request->all());
        
        $data = $request->validate([
            'shipping_name' => ['required', 'string', 'max:100'],
            'shipping_email' => ['required', 'email'],
            'shipping_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'province_name' => ['required', 'string', 'max:100'],
            'ward_name' => ['required', 'string', 'max:100'],
            'note' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'string', 'in:cod,bank_transfer,credit_card'],
        ]);
        
        Log::info('Validation passed');

        $cart = $this->currentCart()->load('items');

        if ($cart->items->count() === 0) {
            return back()->withErrors(['cart' => 'Giỏ hàng trống!']);
        }

        // Tính toán tổng tiền
        $subtotal = $cart->items->sum(fn($i) => (float)$i->price * (int)$i->qty);
        $shippingFee = 0;
        $discount = 0;
        $total = $subtotal + $shippingFee - $discount;

        // Bắt đầu transaction
        DB::beginTransaction();

        try {
            // Khóa và trừ tồn kho theo từng item
            $productsNeedRecalc = [];

            foreach ($cart->items as $item) {
                // Nếu có biến thể
                if ($item->variant_id) {
                    $variant = ProductVariant::where('id', $item->variant_id)->lockForUpdate()->first();
                    if (!$variant) {
                        throw new \Exception('Biến thể không tồn tại.');
                    }

                    if (($variant->stock ?? 0) < $item->qty) {
                        throw new \Exception("Biến thể {$variant->color} - {$variant->size} không đủ hàng.");
                    }

                    $variant->decrement('stock', $item->qty);
                    $productsNeedRecalc[$variant->product_id] = true;
                } else {
                    // Sản phẩm không có biến thể
                    $product = Product::where('id', $item->product_id)->lockForUpdate()->first();
                    if (!$product) {
                        throw new \Exception('Sản phẩm không tồn tại.');
                    }

                    if (($product->stock ?? 0) < $item->qty) {
                        throw new \Exception("Sản phẩm {$product->name} không đủ hàng.");
                    }

                    $product->decrement('stock', $item->qty);
                }
            }

            // Sau khi trừ tồn từng biến thể, cập nhật lại tổng tồn kho sản phẩm
            foreach (array_keys($productsNeedRecalc) as $productId) {
                $totalStock = ProductVariant::where('product_id', $productId)->sum('stock');
                Product::where('id', $productId)->update(['stock' => $totalStock]);
            }

            // Tạo order
            $order = Order::create([
                'user_id' => Auth::id(),
                'code' => 'ORD-' . time(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount' => $discount,
                'total' => $total,
                'payment_status' => 'unpaid',
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_address' => $data['shipping_address'],
                'province_name' => $data['province_name'],
                'ward_name' => $data['ward_name'],
                'note' => $data['note'] ?? null,
            ]);

            // Tạo order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'product_slug' => $item->product->slug ?? null,
                    'variant_sku' => $item->variant?->sku,
                    'size' => $item->variant?->size,
                    'color' => $item->variant?->color,
                    'qty' => $item->qty,
                    'unit_price' => $item->price,
                    'line_total' => (float)$item->price * (int)$item->qty,
                ]);
            }

            // Xóa giỏ hàng
            $cart->update(['status' => 'completed']);
            $cart->items()->delete();

            // Reset cart count
            session(['cart_count' => 0]);

            DB::commit();

            return redirect()->route('home')
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->code);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
    }
}
