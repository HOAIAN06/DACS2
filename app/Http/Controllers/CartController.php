<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
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

    private function updateCartCount(): void
    {
        $cart = $this->currentCart();
        $totalItems = $cart->items()->sum('qty');
        session(['cart_count' => $totalItems]);
    }

    public function index()
    {
        $cart = $this->currentCart()->load([
            'items.product.mainImage',
            'items.product.images',
            'items.variant',
        ]);

        $subtotal = $cart->items->sum(fn($i) => (float)$i->price * (int)$i->qty);

        $this->updateCartCount();

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer'],
            'variant_id' => ['nullable','integer'],
            'qty'        => ['nullable','integer','min:1'],
            'action'     => ['nullable','string','in:add_to_cart,buy_now'],
        ]);

        $qty = (int)($data['qty'] ?? 1);
        $action = $data['action'] ?? 'add_to_cart';

        $product = Product::findOrFail($data['product_id']);

        $variant = null;
        if (!empty($data['variant_id'])) {
            $variant = ProductVariant::where('id', $data['variant_id'])
                ->where('product_id', $product->id)
                ->firstOrFail();
        }

        // Xác định tồn kho tối đa của mặt hàng (biến thể hoặc sản phẩm)
        $maxStock = $variant?->stock ?? $product->stock ?? 0;
        if ($maxStock === 0) {
            return back()->withErrors(['stock' => 'Sản phẩm này hiện đã hết hàng!']);
        }

        // Lấy giỏ hiện tại và item hiện có (nếu có) để kiểm tra tổng số lượng
        $cart = $this->currentCart();

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant?->id)
            ->first();

        $existingQty = $existingItem?->qty ?? 0;
        if (($existingQty + $qty) > $maxStock) {
            return back()->withErrors(['stock' => 'Số lượng yêu cầu vượt quá tồn kho. Vui lòng giảm số lượng!']);
        }

        $unitPrice = $variant?->price ?? $product->price;

        if ($existingItem) {
            $existingItem->qty = $existingQty + $qty;
            $existingItem->price = $unitPrice;
            $existingItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'qty' => $qty,
                'price' => $unitPrice,
            ]);
        }

        $this->updateCartCount();

        if ($action === 'buy_now') {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required','integer'],
            'qty'     => ['required','integer','min:1'],
        ]);

        $cart = $this->currentCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('id', $data['item_id'])
            ->firstOrFail();

        $newQty = (int)$data['qty'];

        // Kiểm tra tồn kho với biến thể hoặc sản phẩm thường
        if ($item->variant_id) {
            $variant = ProductVariant::find($item->variant_id);
            $maxStock = $variant?->stock ?? 0;
        } else {
            $product = Product::find($item->product_id);
            $maxStock = $product?->stock ?? 0;
        }

        if ($maxStock === 0) {
            return back()->withErrors(['stock' => 'Sản phẩm này hiện đã hết hàng!']);
        }

        if ($newQty > $maxStock) {
            return back()->withErrors(['stock' => 'Số lượng yêu cầu vượt quá tồn kho. Vui lòng giảm số lượng!']);
        }

        $item->qty = $newQty;
        $item->save();

        $this->updateCartCount();

        return back()->with('success', 'Đã cập nhật giỏ hàng!');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'item_id' => ['required','integer'],
        ]);

        $cart = $this->currentCart();

        CartItem::where('cart_id', $cart->id)
            ->where('id', $data['item_id'])
            ->delete();

        $this->updateCartCount();

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ!');
    }
}
