<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\LocationController;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot Password Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/send-otp', [AuthController::class, 'sendOtp'])->name('send.otp');
Route::get('/verify-otp', [AuthController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');

// Danh mục / bộ sưu tập
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

// Tất cả sản phẩm / lọc / sắp xếp
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Chi tiết sản phẩm - constraint để slug phải chứa chữ cái hoặc gạch ngang, không phải số thuần
Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('product.show');

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Thanh toán (yêu cầu đăng nhập)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::get('/collections/{slug}', [CollectionController::class, 'show'])
     ->name('collections.show');

// User Routes (yêu cầu đăng nhập)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::get('/change-password', [UserController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [UserController::class, 'changePassword']);
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [UserController::class, 'orderDetail'])->name('order-detail');
    Route::put('/orders/{id}/cancel', [UserController::class, 'cancelOrder'])->name('order-cancel');
});

// Admin Routes (yêu cầu đăng nhập và là admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Quản lý đơn hàng
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{id}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
    });
    
    // Quản lý khách hàng
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/search', [AdminUserController::class, 'search'])->name('search');
        Route::get('/{id}', [AdminUserController::class, 'show'])->name('show');
    });
    
    // Quản lý sản phẩm
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProductController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle', [AdminProductController::class, 'toggle'])->name('toggle');
        Route::post('/{id}/apply-discount', [AdminProductController::class, 'applyDiscount'])->name('apply-discount');
        Route::delete('/images/{id}', [AdminProductController::class, 'deleteImage'])->name('delete-image');
        Route::delete('/variants/{id}', [AdminProductController::class, 'deleteVariant'])->name('delete-variant');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
    });
    
    // Quản lý danh mục
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [AdminCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->name('destroy');
    });
});

// API
Route::get('/api/products/{id}', [ProductController::class, 'apiShow']);
Route::get('/api/provinces', [LocationController::class, 'getProvinces']);
Route::get('/api/wards/{provinceCode}', [LocationController::class, 'getWards']);
