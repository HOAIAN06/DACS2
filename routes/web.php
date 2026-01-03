<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ChatController;
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

// Trang đặc biệt: Hàng mới & Thu Đông
Route::get('/new-arrivals', [ProductController::class, 'newArrivals'])->name('products.new-arrivals');
Route::get('/best-sellers', [ProductController::class, 'bestSellers'])->name('products.best-sellers');
Route::get('/winter-collection', [ProductController::class, 'winterCollection'])->name('products.winter-collection');

// Tất cả sản phẩm / lọc / sắp xếp
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Chi tiết sản phẩm - constraint để slug phải chứa chữ cái hoặc gạch ngang, không phải số thuần
Route::get('/product/{slug}', [ProductController::class, 'show'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->name('product.show');

Route::middleware('auth')->group(function () {
    Route::post('/product/{product}/reviews', [ProductReviewController::class, 'store'])
        ->name('product.reviews.store');
    Route::get('/product/{product}/reviews/{review}/edit', [ProductReviewController::class, 'edit'])
        ->name('product.reviews.edit');
    Route::put('/product/{product}/reviews/{review}', [ProductReviewController::class, 'update'])
        ->name('product.reviews.update');
    Route::delete('/product/{product}/reviews/{review}', [ProductReviewController::class, 'destroy'])
        ->name('product.reviews.destroy');
    Route::post('/product/{product}/reviews/{review}/respond', [ProductReviewController::class, 'respond'])
        ->name('product.reviews.respond');

    // Chat Routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/start', [ChatController::class, 'startConversation'])->name('start');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
        Route::post('/{conversation}/message', [ChatController::class, 'store'])->name('store');
        Route::get('/{conversation}/messages', [ChatController::class, 'getNewMessages'])->name('get-messages');
        Route::post('/{conversation}/read', [ChatController::class, 'markAsRead'])->name('mark-read');
        Route::post('/{conversation}/close', [ChatController::class, 'closeConversation'])->name('close');
    });
});

// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Thanh toán (không bắt buộc đăng nhập)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

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
        // Specific routes BEFORE generic {id} routes
        Route::delete('/images/{id}', [AdminProductController::class, 'deleteImage'])->name('delete-image');
        Route::delete('/variants/{id}', [AdminProductController::class, 'deleteVariant'])->name('delete-variant');
        Route::post('/{id}/apply-discount', [AdminProductController::class, 'applyDiscount'])->name('apply-discount');
        Route::post('/{id}/toggle-flags', [AdminProductController::class, 'toggleFlags'])->name('toggle-flags');
        Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProductController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle', [AdminProductController::class, 'toggle'])->name('toggle');
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

    // Quản lý chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'adminList'])->name('index');
        Route::get('/{conversation}', [ChatController::class, 'adminShow'])->name('show');
        Route::post('/{conversation}/message', [ChatController::class, 'adminStore'])->name('store');
        Route::post('/{conversation}/mark-resolved', [ChatController::class, 'markResolved'])->name('mark-resolved');
        Route::post('/{conversation}/close', [ChatController::class, 'closeConversation'])->name('close');
        Route::post('/{conversation}/pin', [ChatController::class, 'togglePin'])->name('toggle-pin');
        Route::delete('/{conversation}/delete', [ChatController::class, 'deleteConversation'])->name('delete');
        Route::get('/{conversation}/messages', [ChatController::class, 'adminGetMessages'])->name('get-messages');
        Route::get('/unread-count', [ChatController::class, 'adminUnreadCount'])->name('unread-count');
    });
});

// API
Route::get('/api/products/{id}', [ProductController::class, 'apiShow']);
Route::get('/api/provinces', [LocationController::class, 'getProvinces']);
Route::get('/api/wards/{provinceCode}', [LocationController::class, 'getWards']);
