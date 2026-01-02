<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Show user dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('status', '!=', 'canceled')
            ->sum('total');

        return view('user.dashboard', compact('user', 'recentOrders', 'totalOrders', 'totalSpent'));
    }

    /**
     * Show profile page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        // Only update allowed fields
        Auth::user()->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('user.profile')->with('success', 'Cập nhật hồ sơ thành công!');
    }

    /**
     * Show change password page
     */
    public function showChangePassword()
    {
        return view('user.change-password');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.profile')->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Show all orders
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    /**
     * Show order detail
     */
    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        $items = $order->items;

        return view('user.order-detail', compact('order', 'items'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Không thể hủy đơn hàng này!');
        }

        $order->update(['status' => 'canceled']);

        return back()->with('success', 'Hủy đơn hàng thành công!');
    }
}
