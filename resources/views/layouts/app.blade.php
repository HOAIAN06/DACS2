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
    <script src="{{ asset('js/hanzo.js') }}"></script>
    <!-- SWIPER CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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

    {{-- Thanh ưu đãi trên cùng (đen / trắng / đỏ) --}}
    <div class="bg-black text-white text-xs md:text-sm py-2">
        <div class="hanzo-container flex flex-wrap gap-3 justify-center md:justify-between items-center px-3">
            <div class="flex flex-wrap gap-x-4 gap-y-1 justify-center">
                <span>⚡ <span class="font-semibold text-red-500">Đồng giá 99K – 149K – 199K</span></span>
                <span class="hidden md:inline text-slate-300">| Giảm 10% toàn bộ hàng mới</span>
                <span class="hidden lg:inline text-slate-300">| Voucher 50K cho đơn từ 599K</span>
                <span class="hidden lg:inline text-slate-300">| Membership đến 15%</span>
                <span class="hidden lg:inline text-slate-300">| Freeship từ 99K</span>
            </div>
            <div class="hidden md:flex items-center gap-1 text-slate-200">
                <span class="uppercase tracking-wide text-[11px]">Hotline:</span>
                <a href="tel:0900000000" class="font-semibold text-white hover:text-red-400">0900.000.000</a>
            </div>
        </div>
    </div>

<header class="bg-white shadow-sm sticky top-0 z-30">
    <div class="hanzo-container px-4">

        {{-- Hàng duy nhất: Logo (trái) + Menu (giữa) + Icons (phải) --}}
        <div class="relative flex items-center py-3 gap-6">

            {{-- LOGO + SLOGAN BÊN TRÁI --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('icons/logohanzo.png') }}" alt="HANZO" class="h-8 w-auto">

                <div class="flex flex-col leading-tight">
                    <span class="text-[18px] font-semibold tracking-[0.28em] text-slate-900 uppercase">
                        HANZO
                    </span>
                    <span class="text-[10px] tracking-[0.30em] text-slate-500 uppercase">
                        ELEGANCE IN EVERY LINE
                    </span>
                </div>
            </a>

            {{-- MENU CHÍNH Ở GIỮA (ABSOLUTE, GIỐNG ICONDENIM) --}}
            <nav
                 class="hz-main-nav flex-1 flex items-center justify-center
           gap-10 text-[15px] font-semibold text-slate-900 ml-40">



                {{-- Sản phẩm + MEGA MENU --}}
                <div class="group ">
                    <button
                        class="pb-3 border-b-2 border-transparent group-hover:border-black
                               flex items-center gap-1">
                        Sản phẩm
                        <span class="text-xs">▼</span>
                    </button>

                    {{-- MEGA MENU SẢN PHẨM --}}
                    <div
                        class="hz-megamenu invisible opacity-0 group-hover:visible group-hover:opacity-100
                               transition duration-200
                               absolute left-1/2 -translate-x-1/2 top-full
                               bg-white rounded-2xl shadow-2xl border border-slate-100
                               w-[1200px] px-12 py-10 grid grid-cols-5 gap-12 z-40">

                        {{-- Cột 1: Tất cả sản phẩm --}}
                        <div>
                            <div class="hz-megamenu-title">Tất cả sản phẩm</div>
                            <ul class="space-y-3">
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
                            <ul class="space-y-3">
                                <li><a href="#" class="hz-megamenu-link">Áo thun</a></li>
                                <li><a href="#" class="hz-megamenu-link">Áo polo</a></li>
                                <li><a href="#" class="hz-megamenu-link">Áo sơ mi</a></li>
                                <li><a href="#" class="hz-megamenu-link">Áo khoác</a></li>
                                <li><a href="#" class="hz-megamenu-link">Hoodie</a></li>
                            </ul>
                        </div>

                        {{-- Cột 3: Quần nam --}}
                        <div>
                            <div class="hz-megamenu-title">Quần nam</div>
                            <ul class="space-y-3">
                                <li><a href="#" class="hz-megamenu-link">Quần jeans</a></li>
                                <li><a href="#" class="hz-megamenu-link">Quần kaki / chino</a></li>
                                <li><a href="#" class="hz-megamenu-link">Quần short</a></li>
                                <li><a href="#" class="hz-megamenu-link">Quần tây</a></li>
                            </ul>
                        </div>

                        {{-- Cột 4: Giày & phụ kiện --}}
                        <div>
                            <div class="hz-megamenu-title">Giày & phụ kiện</div>
                            <ul class="space-y-3">
                                <li><a href="#" class="hz-megamenu-link">Giày / Dép</a></li>
                                <li><a href="#" class="hz-megamenu-link">Balo, túi, ví</a></li>
                                <li><a href="#" class="hz-megamenu-link">Nón</a></li>
                                <li><a href="#" class="hz-megamenu-link">Thắt lưng</a></li>
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
                <a href="{{ route('products.index', ['is_best_seller' => 1]) }}"
                   class="pb-3 border-b-2 border-transparent hover:border-black hover:text-black">
                    Hàng bán chạy
                </a>

                {{-- DENIM --}}
                <a href="{{ route('products.index', ['category' => 'denim']) }}"
                   class="pb-3 border-b-2 border-transparent hover:border-black hover:text-black">
                    DENIM
                </a>

                {{-- BLACK FRIDAY --}}
                <a href="{{ route('products.index', ['tag' => 'black-friday']) }}"
                   class="pb-3 border-b-2 border-red-600 text-red-600">
                    BLACK FRIDAY
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
            <div class="flex items-center gap-3 md:gap-4 ml-auto">
                <button
                    class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center hover:border-black">
                    <img src="{{ asset('icons/search.png') }}" alt="Tìm kiếm" class="w-4 h-4">
                </button>

                <a href="#"
                   class="w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center hover:border-black">
                    <img src="{{ asset('icons/login.png') }}" alt="Đăng nhập" class="w-4 h-4">
                </a>

                <a href="{{ route('cart.index') }}"
                   class="relative w-9 h-9 rounded-full border border-slate-200 flex items-center justify-center hover:border-black">
                    <img src="{{ asset('icons/shopping-cart.png') }}" alt="Giỏ hàng" class="w-4 h-4">
                    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] rounded-full px-1.5">
                        {{ session('cart_count', 0) }}
                    </span>
                </a>
            </div>

        </div>
    </div>
</header>




    {{-- Nội dung --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer: đen / trắng / đỏ nhẹ --}}
    <footer class="mt-12 border-t border-slate-200 bg-white">
        <div class="hanzo-container py-10 grid grid-cols-1 md:grid-cols-4 gap-8 text-sm px-3">
            <div>
                <div class="text-lg font-semibold tracking-[0.35em] uppercase mb-3">HANZO</div>
                <p class="text-slate-600 text-sm">
                    Thương hiệu thời trang nam tối giản, lịch lãm, với 3 màu chủ đạo: Đen – Trắng – Đỏ.
                </p>
                <p class="mt-3 text-xs text-slate-500">
                    Tổng đài CSKH: <a href="tel:0900000000" class="underline text-red-600">0900.000.000</a><br>
                    Email: cskh@hanzo.vn
                </p>
            </div>

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wide mb-3 text-slate-700">Hỗ trợ khách hàng</h4>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li><a href="#" class="hover:text-red-600">Chính sách đổi hàng & bảo hành</a></li>
                    <li><a href="#" class="hover:text-red-600">Chính sách giao hàng</a></li>
                    <li><a href="#" class="hover:text-red-600">Chính sách Membership</a></li>
                    <li><a href="#" class="hover:text-red-600">Chính sách ưu đãi sinh nhật</a></li>
                    <li><a href="#" class="hover:text-red-600">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wide mb-3 text-slate-700">Hệ thống cửa hàng</h4>
                <ul class="space-y-2 text-sm text-slate-600">
                    <li>HANZO Q1 – 123 Lê Lợi, Quận 1</li>
                    <li>HANZO Q7 – Crescent Mall, Quận 7</li>
                    <li>HANZO Hà Nội – Vincom Bà Triệu</li>
                    <li><a href="#" class="underline text-red-600 text-xs">Xem tất cả cửa hàng</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-semibold uppercase tracking-wide mb-3 text-slate-700">Đăng ký nhận tin</h4>
                <p class="text-sm text-slate-600 mb-3">
                    Nhận thông tin về bộ sưu tập & ưu đãi lớn từ HANZO.
                </p>
                <form action="#" method="POST" class="flex gap-2">
                    <input type="email" class="flex-1 border border-slate-300 rounded-full px-3 py-2 text-xs"
                           placeholder="Nhập email của bạn">
                    <button class="px-4 py-2 text-xs rounded-full bg-black text-white uppercase tracking-wide hover:bg-red-600">
                        Đăng ký
                    </button>
                </form>

                <h4 class="text-xs font-semibold uppercase tracking-wide mt-5 mb-2 text-slate-700">Kết nối</h4>
                <div class="flex gap-3 text-lg">
                    <a href="#" class="hover:text-red-600">Zalo</a>
                    <a href="#" class="hover:text-red-600">FB</a>
                    <a href="#" class="hover:text-red-600">IG</a>
                    <a href="#" class="hover:text-red-600">TikTok</a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 py-4 text-center text-xs text-slate-500 bg-[#fafafa]">
            © {{ date('Y') }} HANZO. All rights reserved.
        </div>
    </footer>
            <!-- SWIPER JS -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    @stack('scripts')
</body>
</html>
