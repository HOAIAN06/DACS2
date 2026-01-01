<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show all users
     */
    public function index()
    {
        $users = User::where('is_admin', false)
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user detail
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $orders = Order::where('user_id', $id)
            ->latest()
            ->paginate(10);

        $totalOrders = Order::where('user_id', $id)->count();
        $totalSpent = Order::where('user_id', $id)
            ->where('status', '!=', 'canceled')
            ->sum('total');

        return view('admin.users.show', compact('user', 'orders', 'totalOrders', 'totalSpent'));
    }

    /**
     * Search users
     */
    public function search(Request $request)
    {
        $q = $request->get('q');
        $users = User::where('is_admin', false)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            })
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'q'));
    }
}
