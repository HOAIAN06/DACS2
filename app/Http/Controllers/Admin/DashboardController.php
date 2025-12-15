<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalProducts'  => Product::count(),
            'totalCategories'=> Category::count(),
            'totalUsers'     => User::count(),
        ]);
    }
}
