<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'HANZO - Thời trang nam')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- 1. Tailwind CDN (KHÔNG xoá) --}}
    <script src="https://cdn.tailwindcss.com"></script>
     {{-- Load CSS riêng của bạn --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quick-add-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <script src="{{ asset('js/hanzo.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/quick-add-modal.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/user.js') }}"></script>
    <!-- SWIPER CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">


    @stack('styles')
</head>
{{-- QUICK VIEW MODAL --}}
<div id="hz-quickview"
     class="fixed inset-0 bg-black/40 hidden items-center justify-center z-40">
    <div class="bg-white max-w-xl w-full rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex justify-between items-center px-4 py-3 border-b">
            <h3 class="text-sm font-semibold">Xem nhanh sản phẩm</h3>
            <button type="button" data-hz-qv-close class="text-slate-500 hover:text-black text-lg">
                ×
            </button>
        </div>
        <div class="p-4 max-h-[70vh] overflow-y-auto" data-hz-qv-body>
            {{-- Nội dung sẽ được JS load vào --}}
        </div>
    </div>
</div>

<body class="bg-white text-slate-900">

    {{-- Thanh ưu đãi trên cùng (Redesigned with animations) --}}
    <div id="hz-topbar" class="hz-announcement-bar relative overflow-hidden">
        <div class="hanzo-container px-4 py-2.5">
            <div class="flex items-center justify-between gap-4">
                
                {{-- Left: Sliding Announcements --}}
                <div class="flex-1 overflow-hidden">
                    <div class="hz-announcement-slider">
                        <div class="hz-announcement-track">
                            {{-- Item 1 --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="font-bold text-red-400">Đồng giá 99K - 149K - 199K</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                            
                            {{-- Item 2 --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                                <span class="text-white">Giảm 10% toàn bộ hàng mới</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                            
                            {{-- Item 3 --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                <span class="text-white">Voucher 50K cho đơn từ 599K</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                            
                            {{-- Item 4 --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="text-white">Membership đến 15%</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                            
                            {{-- Item 5 --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                <span class="text-white">Freeship từ 99K</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                            
                            {{-- Duplicate for seamless loop --}}
                            <div class="hz-announcement-item">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="font-bold text-red-400">Đồng giá 99K - 149K - 199K</span>
                                <span class="text-slate-300 mx-2">|</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Hotline + Close Button --}}
                <div class="flex items-center gap-4 flex-shrink-0">
                    <a href="tel:0900000000" class="hidden md:flex items-center gap-2 group">
                        <svg class="w-4 h-4 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span class="text-white font-semibold text-sm group-hover:text-green-400 transition-colors">0900.000.000</span>
                    </a>
                    
                    <button id="hz-topbar-close" class="text-slate-400 hover:text-white transition-colors" title="Đóng">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

<header id="hz-header" class="bg-white shadow-md sticky top-0 z-30 transition-all duration-300">
    <div class="hanzo-container px-4">

        {{-- Hàng duy nhất: Logo (trái) + Menu (giữa) + Icons (phải) --}}
        <div class="relative flex items-center py-4 gap-6">

            {{-- MOBILE HAMBURGER --}}
            <button id="hz-mobile-open" class="md:hidden w-11 h-11 rounded-full border border-slate-300 flex items-center justify-center hover:border-slate-900 hover:bg-slate-50 transition">
                <span class="sr-only">Mở menu</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- LOGO + SLOGAN BÊN TRÁI --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                <img src="{{ asset('icons/logohanzo.png') }}" alt="HANZO" class="h-10 w-auto group-hover:opacity-80 transition">

                <div class="flex flex-col leading-tight">
                    <span class="text-[20px] font-bold tracking-[0.12em] text-slate-900 uppercase group-hover:text-slate-700 transition">
                        HANZO
                    </span>
                    <span class="text-[11px] tracking-[0.15em] text-slate-400 uppercase font-medium">
                        ELEGANCE IN EVERY LINE
                    </span>
                </div>
            </a>

            {{-- MENU CHÍNH Ở GIỮA (ABSOLUTE, GIỐNG ICONDENIM) --}}
            <nav
                 class="hz-main-nav flex-1 hidden md:flex items-center justify-center
             gap-12 text-[16px] font-semibold text-slate-900 md:ml-40">



                {{-- Sản phẩm + MEGA MENU --}}
                <div class="group relative">
                    <button
                        class="pb-3 border-b-2 border-transparent group-hover:border-slate-900
                               flex items-center gap-2 text-slate-700 group-hover:text-slate-900 transition-colors">
                        Sản phẩm
                        <span class="text-xs">▼</span>
                    </button>

                    {{-- MEGA MENU SẢN PHẨM --}}
                    <div
                        class="hz-megamenu invisible opacity-0 group-hover:visible group-hover:opacity-100
                               transition duration-200 pointer-events-none group-hover:pointer-events-auto
                               absolute absolute left-1/2 -translate-x-1/2 top-full mt-0
                               bg-white rounded-2xl shadow-2xl border border-slate-100
                               w-[95vw] max-w-[1000px] px-12 py-10 grid grid-cols-5 gap-12 z-40">

                        {{-- Cột 1: Tất cả sản phẩm --}}
                        <div>
                            <div class="hz-megamenu-title">Tất cả sản phẩm</div>
                            <ul class="space-y-3 hz-megamenu-list">
                                <li>
                                    <a href="{{ route('products.index', ['is_new' => 1]) }}"
                                       class="hz-megamenu-link">Sản phẩm mới</a>
                                </li>
                                <li>
                                    <a href="{{ route('products.index', ['is_best_seller' => 1]) }}"
                                       class="hz-megamenu-link">Bán chạy nhất</a>
                                </li>
                                <li>
                                    <a href="{{ route('products.index', ['is_outlet' => 1]) }}"
                                       class="hz-megamenu-link">OUTLET - Sale đến 50%</a>
                                </li>
                            </ul>
                        </div>

                        {{-- Cột 2: Áo nam --}}
                        <div>
                            <div class="hz-megamenu-title">Áo nam</div>
                            <ul class="space-y-3 hz-megamenu-list">
                                <li><a href="{{ route('category.show', 'ao-thun') }}" class="hz-megamenu-link">Áo thun</a></li>
                                <li><a href="{{ route('category.show', 'ao-polo') }}" class="hz-megamenu-link">Áo polo</a></li>
                                <li><a href="{{ route('category.show', 'ao-so-mi') }}" class="hz-megamenu-link">Áo sơ mi</a></li>
                                <li><a href="{{ route('category.show', 'ao-khoac') }}" class="hz-megamenu-link">Áo khoác</a></li>
                                <li><a href="{{ route('category.show', 'hoodie') }}" class="hz-megamenu-link">Hoodie</a></li>
                            </ul>
                        </div>

                        {{-- Cột 3: Quần nam --}}
                        <div>
                            <div class="hz-megamenu-title">Quần nam</div>
                            <ul class="space-y-3 hz-megamenu-list">
                                <li><a href="{{ route('category.show', 'quan-jeans') }}" class="hz-megamenu-link">Quần jeans</a></li>
                                <li><a href="{{ route('category.show', 'quan-kaki-chino') }}" class="hz-megamenu-link">Quần kaki / chino</a></li>
                                <li><a href="{{ route('category.show', 'quan-short') }}" class="hz-megamenu-link">Quần short</a></li>
                                <li><a href="{{ route('category.show', 'quan-tay') }}" class="hz-megamenu-link">Quần tây</a></li>
                            </ul>
                        </div>

                        {{-- Cột 4: Giày & phụ kiện --}}
                        <div>
                            <div class="hz-megamenu-title">Giày & phụ kiện</div>
                            <ul class="space-y-3 hz-megamenu-list">
                                <li><a href="{{ route('category.show', 'giay-dep') }}" class="hz-megamenu-link">Giày / Dép</a></li>
                                <li><a href="{{ route('category.show', 'balo-tui-vi') }}" class="hz-megamenu-link">Balo, túi, ví</a></li>
                                <li><a href="{{ route('category.show', 'non') }}" class="hz-megamenu-link">Nón</a></li>
                                <li><a href="{{ route('category.show', 'that-lung') }}" class="hz-megamenu-link">Thắt lưng</a></li>
                            </ul>
                        </div>

                        {{-- Cột 5: Ảnh bộ sưu tập --}}
                        <div class="flex flex-col gap-5">
                                {{-- Ảnh đồ thu đông --}}
                                   <a href="{{ route('products.index', ['collection' => 'do-thu-dong']) }}"
                                       class="relative block rounded-2xl overflow-hidden shadow-md">
                                      <img src="{{ asset('images/sanpham/do-thu-dong.jpg') }}"
                                          class="w-full h-[150px] object-cover">
                                <span class="absolute bottom-3 left-3
                                             bg-black/70 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                                    Đồ Thu Đông
                                </span>
                            </a>

                            {{-- Ảnh Retro Sports --}}
                                   <a href="{{ route('products.index', ['collection' => 'retro-sports']) }}"
                                       class="relative block rounded-2xl overflow-hidden shadow-md">
                                      <img src="{{ asset('images/sanpham/Retro_sports.jpg') }}"
                                          class="w-full h-[150px] object-cover">
                                <span class="absolute bottom-3 left-3
                                             bg-black/70 text-white px-3 py-1 rounded-lg text-sm font-semibold">
                                    Retro Sports
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Hàng mới --}}
                <a href="{{ route('products.index', ['is_new' => 1]) }}"
                   class="pb-3 border-b-2 border-transparent hover:border-black hover:text-black">
                    Hàng mới
                </a>

                {{-- Hàng bán chạy --}}
                <a href="{{ route('products.index') }}"
                   class="pb-3 border-b-2 border-transparent hover:border-black hover:text-black">
                    Tất cả sản phẩm
                </a>

               
                {{-- COLLECTION --}}
                <div class="group relative">
                    <button
                        class="pb-3 border-b-2 border-transparent group-hover:border-black
                            flex items-center gap-1">
                        Collection
                        <span class="text-xs">▼</span>
                    </button>

                    {{-- MEGA MENU COLLECTION --}}
                    <div
                         class="hz-collection-menu invisible opacity-0 group-hover:visible group-hover:opacity-100
                            transition duration-200
                            absolute left-[-40px] -translate-x-1/2 top-full
                            bg-white rounded-2xl shadow-2xl border border-slate-100
                            w-[900px] px-8 py-8 grid grid-cols-3 gap-8 z-40">

                        {{-- Retro Sports --}}
                        <a href="{{ route('collections.show', 'retro-sports') }}"
                        class="relative block rounded-2xl overflow-hidden shadow-md">
                            <img src="{{ asset('images/collections/retro_sport.jpg') }}"
                                alt="Retro Sports"
                                class="w-full h-[220px] object-cover">
                            <div class="mt-3 text-center text-[18px] font-semibold">
                             Retro Sports
                            </div>
                        </a>

                        {{-- Snoopy --}}
                        <a href="{{ route('collections.show', 'snoopy') }}"
                        class="relative block rounded-2xl overflow-hidden shadow-md">
                            <img src="{{ asset('images/collections/snoopy.jpg') }}"
                                alt="Snoopy"
                                class="w-full h-[220px] object-cover">
                                <div class="mt-3 text-center text-[18px] font-semibold">
                                    Snoopy
                                </div>
    
                        </a>

                        {{-- Mickey & Friends --}}
                        <a href="{{ route('collections.show', 'mickey-friends') }}"
                        class="relative block rounded-2xl overflow-hidden shadow-md">
                            <img src="{{ asset('images/collections/mickey.jpg') }}"
                                alt="Mickey & Friends"
                                class="w-full h-[220px] object-cover">
                             <div class="mt-3 text-center text-[18px] font-semibold">
                                    Mickey & Friends
                                </div>
                        </a>
                    </div>
                </div>


            {{-- ICON SEARCH / USER / CART BÊN PHẢI --}}
            <div class="flex items-center gap-4 md:gap-5 ml-auto">
                {{-- Search Icon Button (link to products page) --}}
                    <div class="relative hz-icon-wrapper">
                        <a href="{{ route('products.index') }}"
                        class="w-11 h-11 rounded-full border border-slate-300 flex items-center justify-center hover:border-slate-900 hover:bg-slate-50 transition-all">
                            <svg class="w-5 h-5 text-slate-600 hover:text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </a>
                        <span class="hz-tooltip">Tìm kiếm</span>
                    </div>

                
                {{-- User Account / Login with tooltip --}}
                <div class="relative hz-icon-wrapper group">
                    @if(Auth::check())
                        <button id="hz-user-dropdown"
                                class="w-11 h-11 rounded-full border border-slate-300 flex items-center justify-center hover:border-slate-900 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('icons/login.png') }}" alt="Tài khoản" class="w-5 h-5">
                        </button>
                        <span class="hz-tooltip">{{ Auth::user()->name }}</span>
                        
                            {{-- Dropdown Menu --}}
                            <div id="user-dropdown-menu" 
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 hidden z-50">
                            @if(Auth::user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b transition">
                                    <img src="{{ asset('icons/dashboard.png') }}" alt="Admin" class="w-4 h-4"> Admin Dashboard
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b transition">
                                    <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-4 h-4"> Quản lý đơn hàng
                                </a>
                            @else
                                <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b transition">
                                    <img src="{{ asset('icons/hoso.png') }}" alt="Hồ sơ" class="w-4 h-4"> Hồ sơ
                                </a>
                                <a href="{{ route('user.orders') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b transition">
                                    <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-4 h-4"> Đơn hàng
                                </a>
                                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 border-b transition">
                                    <img src="{{ asset('icons/dashboard.png') }}" alt="Tài khoản" class="w-4 h-4"> Tài khoản
                                </a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                    <img src="{{ asset('icons/login.png') }}" alt="Đăng xuất" class="w-4 h-4"> Đăng xuất
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-11 h-11 rounded-full border border-slate-300 flex items-center justify-center hover:border-slate-900 hover:bg-slate-50 transition-all">
                            <img src="{{ asset('icons/login.png') }}" alt="Đăng nhập" class="w-5 h-5">
                        </a>
                        <span class="hz-tooltip">Đăng nhập</span>
                    @endif
                </div>

                {{-- Cart with tooltip and pulse badge --}}
                <div class="relative hz-icon-wrapper">
                    <a href="{{ route('cart.index') }}"
                       class="relative w-11 h-11 rounded-full border border-slate-300 flex items-center justify-center hover:border-slate-900 hover:bg-slate-50 transition-all">
                        <img src="{{ asset('icons/shopping-cart.png') }}" alt="Giỏ hàng" class="w-5 h-5">
                        @if(session('cart_count', 0) > 0)
                        <span class="hz-cart-badge">
                            {{ session('cart_count', 0) }}
                        </span>
                        @endif
                    </a>
                    <span class="hz-tooltip">Giỏ hàng ({{ session('cart_count', 0) }})</span>
                </div>
            </div>

        </div>
    </div>
</header>


    {{-- DIM OVERLAY --}}
<div id="hz-search-overlay"
     class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-200 z-40"
     style="display: none;"></div>

{{-- SEARCH MODAL --}}
<div id="hz-search-modal"
     class="fixed left-0 right-0 top-0 bg-white opacity-0 pointer-events-none z-50 shadow-lg border-b border-slate-200"
     style="display: none;">
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 relative">



        {{-- Search Row --}}
        <div class="flex items-center gap-6 mb-5">
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <div class="flex flex-col leading-tight">
                    <span class="text-2xl font-bold text-slate-900 tracking-[0.12em]">HANZO</span>
                    <span class="text-[11px] font-light text-slate-500 tracking-[0.15em]">ELEGANCE IN EVERY LINE</span>
                </div>
            </a>

            <form id="hz-search-form" class="flex-1 flex items-center gap-3">
                <input type="text"
                       id="hz-search-input"
                       placeholder="Tìm kiếm sản phẩm..."
                       class="flex-1 px-6 py-4 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 text-base transition-all">
                <button type="submit"
                        class="px-8 py-4 bg-slate-900 text-white rounded-lg hover:bg-slate-800 font-semibold transition-all flex items-center gap-2 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="text-base">Tìm kiếm</span>
                </button>
            </form>
        </div>

        {{-- Keywords --}}
        <p class="text-sm text-slate-600 font-semibold mb-3 uppercase tracking-wider">Từ khóa nổi bật hôm nay</p>
        <div class="flex flex-wrap gap-3">
            <button type="button" class="hz-search-category " data-keyword="">smartjean</button>
            <button type="button" class="hz-search-category" data-keyword="Áo thun">Áo thun</button>
            <button type="button" class="hz-search-category" data-keyword="Áo polo">Áo polo</button>
            <button type="button" class="hz-search-category" data-keyword="Quần short">Quần short</button>
            <button type="button" class="hz-search-category" data-keyword="Áo khoác">Áo khoác</button>
            <button type="button" class="hz-search-category" data-keyword="Quần tây">Quần tây</button>
        </div>

    </div>
</div>


    {{-- Mobile Drawer Menu --}}
    <div id="hz-mobile-overlay" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300 z-40" style="display: none;"></div>
    <aside id="hz-mobile-drawer" class="fixed inset-y-0 left-0 w-[85%] max-w-sm bg-white shadow-2xl -translate-x-full transition-transform duration-300 z-50 flex flex-col">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200">
            <div class="flex items-center gap-2">
                <img src="{{ asset('icons/logohanzo.png') }}" alt="HANZO" class="h-8 w-auto">
                <span class="text-lg font-semibold tracking-[0.2em]">HANZO</span>
            </div>
            <button id="hz-mobile-close" class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center hover:border-black hover:bg-slate-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="px-4 py-3 border-b border-slate-200">
            <div class="relative">
                <input type="text" placeholder="Tìm sản phẩm..." class="w-full bg-slate-100 rounded-full px-4 py-2.5 text-sm pr-10 focus:outline-none focus:ring-2 focus:ring-slate-300">
                <svg class="w-4 h-4 text-slate-500 absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto pb-6">
            <nav class="px-2 space-y-1 text-slate-900 text-[15px] font-semibold">
                <a href="{{ route('products.index', ['is_new' => 1]) }}" class="block px-3 py-3 rounded-lg hover:bg-slate-100">Hàng mới</a>
                <a href="{{ route('products.index', ['is_best_seller' => 1]) }}" class="block px-3 py-3 rounded-lg hover:bg-slate-100">Hàng bán chạy</a>
                <a href="{{ route('products.index', ['category' => 'denim']) }}" class="block px-3 py-3 rounded-lg hover:bg-slate-100">DENIM</a>

                {{-- Accordion: Sản phẩm --}}
                <div class="border-t border-slate-200 my-2"></div>
                <button class="w-full flex items-center justify-between px-3 py-3 rounded-lg hover:bg-slate-100" data-mobile-accordion="products">
                    <span>Sản phẩm</span>
                    <svg class="w-4 h-4 transition-transform" data-accordion-icon fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="pl-4 space-y-2 hidden" data-accordion-panel="products">
                    <a href="{{ route('category.show', 'ao-thun') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Áo thun</a>
                    <a href="{{ route('category.show', 'ao-polo') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Áo polo</a>
                    <a href="{{ route('category.show', 'ao-so-mi') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Áo sơ mi</a>
                    <a href="{{ route('category.show', 'ao-khoac') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Áo khoác</a>
                    <a href="{{ route('category.show', 'hoodie') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Hoodie</a>
                    <a href="{{ route('category.show', 'quan-jeans') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Quần jeans</a>
                    <a href="{{ route('category.show', 'quan-kaki-chino') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Quần kaki / chino</a>
                    <a href="{{ route('category.show', 'quan-short') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Quần short</a>
                    <a href="{{ route('category.show', 'quan-tay') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Quần tây</a>
                </div>

                {{-- Accordion: Collection --}}
                <button class="w-full flex items-center justify-between px-3 py-3 rounded-lg hover:bg-slate-100" data-mobile-accordion="collections">
                    <span>Collection</span>
                    <svg class="w-4 h-4 transition-transform" data-accordion-icon fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div class="pl-4 space-y-2 hidden" data-accordion-panel="collections">
                    <a href="{{ route('collections.show', 'retro-sports') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Retro Sports</a>
                    <a href="{{ route('collections.show', 'snoopy') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Snoopy</a>
                    <a href="{{ route('collections.show', 'mickey-friends') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-100 text-[14px] font-medium">Mickey & Friends</a>
                </div>
            </nav>
        </div>

        <div class="px-4 py-4 border-t border-slate-200 space-y-3">
            <a href="#" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-slate-900 text-slate-900 font-semibold hover:bg-slate-900 hover:text-white transition">
                <img src="{{ asset('icons/login.png') }}" class="w-4 h-4" alt="Đăng nhập">
                Đăng nhập / Đăng ký
            </a>
            <a href="{{ route('cart.index') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                <img src="{{ asset('icons/shopping-cart.png') }}" class="w-4 h-4" alt="Giỏ hàng">
                Giỏ hàng ({{ session('cart_count', 0) }})
            </a>
        </div>
    </aside>




    {{-- Nội dung --}}
    <main class="min-h-screen bg-white">
        <!-- DEBUG: Main loaded -->
        @yield('content')
    </main>

    {{-- Footer: Thiết kế mới style ICONDENIM --}}
    <footer class="mt-16 bg-slate-900 text-white">
        <div class="hanzo-container py-12 px-4">
            
            {{-- Top Section: Logo + Social --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center pb-8 border-b border-slate-700">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <img src="{{ asset('icons/logohanzo.png') }}" alt="HANZO" class="h-10 w-auto brightness-0 invert">
                        <div class="text-2xl font-bold tracking-[0.3em] uppercase">HANZO</div>
                    </div>
                    <p class="text-sm text-slate-400 max-w-md leading-relaxed">
                        Thương hiệu thời trang nam hiện đại, tối giản và lịch lãm.<br>
                        Kiến tạo phong cách riêng cho phái mạnh.
                    </p>
                </div>

                {{-- Social Icons --}}
                <div class="mt-6 md:mt-0">
                    <h4 class="text-xs font-semibold uppercase tracking-wider mb-3 text-slate-400">Kết nối với chúng tôi</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-white hover:text-black transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-white hover:text-black transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-white hover:text-black transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-5.2 1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V9.4a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 5 20.1a6.34 6.34 0 0 0 10.86-4.43v-7a8.16 8.16 0 0 0 4.77 1.52v-3.4a4.85 4.85 0 0 1-1-.1z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-slate-600 flex items-center justify-center hover:bg-white hover:text-black transition duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 py-10">
                
                {{-- Cột 1: Về HANZO --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider mb-4">Về HANZO</h4>
                    <ul class="space-y-2.5 text-sm text-slate-300">
                        <li><a href="#" class="hover:text-white transition">Giới thiệu</a></li>
                        <li><a href="#" class="hover:text-white transition">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog thời trang</a></li>
                        <li><a href="#" class="hover:text-white transition">Liên hệ</a></li>
                    </ul>
                </div>

                {{-- Cột 2: Hỗ trợ khách hàng --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider mb-4">Hỗ trợ khách hàng</h4>
                    <ul class="space-y-2.5 text-sm text-slate-300">
                        <li><a href="#" class="hover:text-white transition">Chính sách đổi hàng</a></li>
                        <li><a href="#" class="hover:text-white transition">Chính sách giao hàng</a></li>
                        <li><a href="#" class="hover:text-white transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-white transition">Hướng dẫn chọn size</a></li>
                        <li><a href="#" class="hover:text-white transition">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>

            
        

                {{-- Cột 4: Đăng ký nhận tin --}}
                <div>
                    <h4 class="text-sm font-bold uppercase tracking-wider mb-4">Đăng ký nhận tin</h4>
                    <p class="text-sm text-slate-300 mb-4">
                        Nhận ngay ưu đãi & bộ sưu tập mới nhất từ HANZO
                    </p>
                    <form action="#" method="POST" class="space-y-3">
                        <input type="email" 
                               class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-slate-500"
                               placeholder="Email của bạn">
                        <button type="submit" 
                                class="w-full bg-white text-slate-900 font-semibold py-2.5 rounded-lg text-sm uppercase tracking-wider hover:bg-red-500 hover:text-white transition duration-300">
                            Đăng ký
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-slate-700">
                        <p class="text-xs text-slate-400 mb-1">Hotline hỗ trợ</p>
                        <a href="tel:0900000000" class="text-xl font-bold hover:text-red-400 transition">0900.000.000</a>
                        <p class="text-xs text-slate-400 mt-1">8h30 - 22h00 (cả T7, CN)</p>
                    </div>
                </div>

            </div>

            {{-- Bottom Bar --}}
            <div class="border-t border-slate-700 pt-6 flex flex-col md:flex-row justify-between items-center text-xs text-slate-400">
                <p>© {{ date('Y') }} HANZO. All rights reserved.</p>
                <div class="flex gap-4 mt-3 md:mt-0">
                    <a href="#" class="hover:text-white">Điều khoản</a>
                    <a href="#" class="hover:text-white">Bảo mật</a>
                    <a href="#" class="hover:text-white">Sitemap</a>
                </div>
            </div>

        </div>
    </footer>

    {{-- Quick Add to Cart Modal --}}
    @include('components.quick-add-modal')

            <!-- SWIPER JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
