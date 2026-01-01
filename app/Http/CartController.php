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

            // nếu trước đó user add khi chưa login -> merge theo session
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
            'items.product.images',
            'items.variant',
        ]);

        $subtotal = $cart->items->sum(fn($i) => (float)$i->price * (int)$i->qty);

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
            
            // Validate stock
            if ($variant->stock === 0) {
                return back()->withErrors(['stock' => 'Sản phẩm này hiện đã hết hàng!']);
            }
            
            if ($qty > $variant->stock) {
                return back()->withErrors(['stock' => "Chỉ còn {$variant->stock} sản phẩm trong kho!"]);
            }
        }

        // Giá ưu tiên theo variant nếu có, không thì lấy price của product
        $unitPrice = $variant?->price ?? $product->price;

        $cart = $this->currentCart();

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('variant_id', $variant?->id)
            ->first();

        if ($item) {
            $item->qty += $qty;
            $item->price = $unitPrice;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'variant_id' => $variant?->id,
                'qty' => $qty,
                'price' => $unitPrice,
            ]);
        }

        // Nếu là buy_now thì chuyển thẳng đến checkout
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

        $item->qty = (int)$data['qty'];
        $item->save();

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

        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ!');
    }
}
