<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CollectionController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Danh mục / bộ sưu tập
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Tất cả sản phẩm / lọc / sắp xếp
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Chi tiết sản phẩm
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

// Giỏ hàng (đơn giản)
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::get('/collections/{slug}', [CollectionController::class, 'show'])
     ->name('collections.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
