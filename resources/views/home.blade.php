@extends('layouts.app')

@section('title', 'HANZO - Cửa hàng thời trang nam')

@section('content')

    {{-- Inject asset paths for JS category showcase --}}
    <script>
        window.HANZO = window.HANZO || {};
        window.HANZO.categoryBanners = {
            // Áo
            thun: '{{ asset('images/banner/banner_aothun.jpg') }}',
            somi: '{{ asset('images/banner/banner_aosomi.jpg') }}',
            polo: '{{ asset('images/banner/banner_aopolo.jpg') }}',
            // Quần
            short: '{{ asset('images/banner/banner_quanshort.jpg') }}',
            jean: '{{ asset('images/banner/banner_quanjean.jpg') }}',
            tay: '{{ asset('images/banner/banner_quantay.jpg') }}'
        };
    </script>

    {{-- ========================================== --}}
    {{-- (1) BANNER ĐỎ ĐEN GIỐNG ICONDENIM           --}}
    {{-- ========================================== --}}
<section class="w-full mb-10">
 <div class="swiper mySwiper w-full h-[600px]">
    <div class="hero-slider js-hero-slider">

    {{-- Slide 1 --}}
    <div class="hero-slide js-hero-slide is-active">
        <img src="{{ asset('images/banner/banner1.jpg') }}" class="hero-slide-img" alt="Banner 1">
    </div>

    {{-- Slide 2 --}}
    <div class="hero-slide js-hero-slide">
        <img src="{{ asset('images/banner/banner2.jpg') }}" class="hero-slide-img" alt="Banner 2">
    </div>

    {{-- Slide 3 --}}
    <div class="hero-slide js-hero-slide">
        <img src="{{ asset('images/banner/banner3.jpg') }}" class="hero-slide-img" alt="Banner 3">
    </div>

    {{-- Slide 4 --}}
    <div class="hero-slide js-hero-slide">
        <img src="{{ asset('images/banner/banner4.jpg') }}" class="hero-slide-img" alt="Banner 4">
    </div>

    {{-- Nút mũi tên --}}
    <button class="hero-arrow hero-arrow--prev" type="button" data-hero-prev>
        <span>&lt;</span>
    </button>
    <button class="hero-arrow hero-arrow--next" type="button" data-hero-next>
        <span>&gt;</span>
    </button>

    {{-- Dots dưới cùng --}}
    <div class="hero-dots" data-hero-dots></div>
</div>

</section>


    <section class="hanzo-bg-red text-slate-900">
    <div class="hanzo-container px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- ITEM --}}
            @php
                $items = [
                    ['img' => 'vanchuyen.png', 'title' => 'Miễn phí vận chuyển', 'sub' => 'đơn từ 99K'],
                    ['img' => 'traodoi.png', 'title' => 'Đổi hàng tận nhà', 'sub' => 'Trong vòng 15 ngày'],
                    ['img' => 'cod.png', 'title' => 'Thanh toán COD', 'sub' => 'Yên tâm mua sắm'],
                    ['img' => 'hotline.png', 'title' => 'Hotline: 0900.000.000', 'sub' => 'Hỗ trợ từ 8h30–24h00'],
                ];
            @endphp

            @foreach ($items as $i)
                <div class="flex items-center gap-3 bg-white rounded-xl shadow-[0_5px_18px_rgba(0,0,0,0.18)] px-5 py-4">

                    {{-- Icon --}}
                    <div class="w-11 h-11 rounded-full border border-slate-200 flex items-center justify-center">
                        <img src="{{ asset('icons/' . $i['img']) }}" class="w-6 h-6 opacity-90" alt="">
                    </div>

                    {{-- Text --}}
                    <div class="leading-tight">
                        <div class="text-[15px] font-bold text-slate-900 tracking-tight">
                            {{ $i['title'] }}
                        </div>
                        <div class="text-[13px] font-medium text-slate-500 mt-[2px]">
                            {{ $i['sub'] }}
                        </div>
                    </div>

                </div>
            @endforeach

        </div>
    </div>
</section>

         {{-- RETRO SPORTS COLLECTION SECTION --}}
         <div class="retro-sports-section my-10">
        @if($retroSportsProducts->count())
        <section class="mb-12 hanzo-container px-3">
            {{-- HERO BANNER - Giống Icon Denim style --}}
            <div class="relative w-full h-[350px] md:h-[500px] lg:h-[600px] rounded-xl overflow-hidden mb-10 group">
                <img src="{{ asset('images/banner/retro-sport-banner.jpg') }}" 
                     alt="Retro Sports Collection" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                
                {{-- Logo badge --}}
                <div class="absolute top-6 left-6 bg-white px-3 py-1 rounded text-xs font-bold tracking-wide">
                    RETRO SPORTS
                </div>

                {{-- Text overlay - dưới bên phải --}}
                <div class="absolute bottom-8 right-8 text-white text-right">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2">
                        Retro Sports
                    </h2>
                    <p class="text-sm md:text-base font-medium">
                        The Playbook - New Rules
                    </p>
                </div>
            </div> 

            {{-- PRODUCT CAROUSEL - Giống Icon Denim --}}
            <div class="relative">
                {{-- Heading --}}
                <div class="mb-10 flex items-center justify-between">
                    <h3 class="text-xl md:text-2xl font-semibold text-slate-900 tracking-normal">Sản phẩm nổi bật</h3>
                    <a href="{{ route('products.index', ['collection' => 'retro-sports']) }}" 
                       class="px-7 py-3 border-2 border-slate-900 rounded-full text-sm font-bold 
                              text-slate-900 hover:bg-slate-900 hover:text-white transition duration-300 
                              uppercase tracking-wider">
                        Xem tất cả
                    </a>
                </div>

                {{-- Swiper Carousel --}}
                <div class="relative group/swiper">
                    <div class="swiper retroSportsSwiper px-0">
                        <div class="swiper-wrapper">
                            @foreach($retroSportsProducts as $product)
                            <div class="swiper-slide">
                                <x-product-card :product="$product" />
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Navigation Arrows - Icon Denim style --}}
                    <button class="retro-sports-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 
                                   w-12 h-12 rounded-full border-2 border-slate-300 
                                   flex items-center justify-center bg-white
                                   hover:border-black hover:bg-black hover:text-white 
                                   transition duration-300 -translate-x-6 md:-translate-x-8
                                   hidden md:flex shadow-md">
                        <span class="text-xl font-light">‹</span>
                    </button>
                    <button class="retro-sports-next absolute right-0 top-1/2 -translate-y-1/2 z-20 
                                   w-12 h-12 rounded-full border-2 border-slate-300 
                                   flex items-center justify-center bg-white
                                   hover:border-black hover:bg-black hover:text-white 
                                   transition duration-300 translate-x-6 md:translate-x-8
                                   hidden md:flex shadow-md">
                        <span class="text-xl font-light">›</span>
                    </button>
                </div>
            </div>
        </section>
        @endif
        </div>

        {{-- JEANS COLLECTION SECTION --}}
        <div class="jeans-section my-10">
        @if($jeansProducts->count())
        <section class="mb-12 hanzo-container px-3">
            {{-- HERO BANNER - Giống Icon Denim style --}}
            <div class="relative w-full h-[350px] md:h-[500px] lg:h-[600px] rounded-xl overflow-hidden mb-10 group">
                <img src="{{ asset('images/banner/jeans-banner.jpg') }}" 
                     alt="Quần Jeans Collection" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                
                {{-- Logo badge --}}
                <div class="absolute top-6 left-6 bg-blue-600 text-white px-4 py-2 rounded font-bold tracking-wide text-sm">
                    QUẦN JEANS
                </div>

                {{-- Text overlay - dưới bên phải --}}
                <div class="absolute bottom-8 right-8 text-white text-right">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-2">
                        Quần Jeans
                    </h2>
                    <p class="text-sm md:text-base font-medium">
                        Tech Urban Jeans by IconDenim
                    </p>
                </div>
            </div> 

            {{-- PRODUCT CAROUSEL - Giống Icon Denim --}}
            <div class="relative">
                {{-- Heading --}}
                <div class="mb-10 flex items-center justify-between">
                    <h3 class="text-xl md:text-2xl font-semibold text-white tracking-normal">Sản phẩm nổi bật</h3>
                    <a href="{{ route('products.index', ['collection' => 'jeans']) }}" 
                       class="px-7 py-3 border-2 border-white rounded-full text-sm font-bold 
                              text-white hover:bg-white hover:text-blue-600 transition duration-300 
                              uppercase tracking-wider">
                        Xem tất cả
                    </a>
                </div>

                {{-- Swiper Carousel --}}
                <div class="relative group/swiper">
                    <div class="swiper jeansSwiper px-0">
                        <div class="swiper-wrapper">
                            @foreach($jeansProducts as $product)
                            <div class="swiper-slide">
                                <x-product-card :product="$product" fit="contain"/>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Navigation Arrows - Icon Denim style --}}
                    <button class="jeans-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 
                                   w-12 h-12 rounded-full border-2 border-slate-300 
                                   flex items-center justify-center bg-white
                                   hover:border-black hover:bg-black hover:text-white 
                                   transition duration-300 -translate-x-6 md:-translate-x-8
                                   hidden md:flex shadow-md">
                        <span class="text-xl font-light">‹</span>
                    </button>
                    <button class="jeans-next absolute right-0 top-1/2 -translate-y-1/2 z-20 
                                   w-12 h-12 rounded-full border-2 border-slate-300 
                                   flex items-center justify-center bg-white
                                   hover:border-black hover:bg-black hover:text-white 
                                   transition duration-300 translate-x-6 md:translate-x-8
                                   hidden md:flex shadow-md">
                        <span class="text-xl font-light">›</span>
                    </button>
                </div>
            </div>
        </section>
        @endif
        </div>










        {{-- HIGHLIGHT SECTION: Hàng mới / Thu Đông (style ICONDENIM) --}}
        @if(($newProducts ?? collect())->count() || ($winterProducts ?? collect())->count())
        <section class="my-16 hanzo-container px-3" data-highlight-group>
            {{-- Tabs - căn phải giống mẫu IconDenim --}}
            <div class="flex items-center justify-end gap-6 mb-6 border-b border-slate-200">
                <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-black border-b-2 border-black transition-all" data-highlight-tab="new">Hàng mới</button>
                <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-slate-400 border-b-2 border-transparent hover:text-slate-600 transition-all opacity-50 cursor-not-allowed" data-highlight-tab="best" disabled>Hàng bán chạy (sắp có)</button>
                <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-slate-400 border-b-2 border-transparent hover:text-slate-600 transition-all" data-highlight-tab="winter">Đồ Thu Đông</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Left banner + sản phẩm bên dưới banner --}}
                <div class="lg:col-span-3 hidden lg:block">
                    <div class="relative w-full h-[520px] rounded-xl overflow-hidden group shadow-sm">
                        <img src="{{ asset('images/banner/highlight-new.jpg') }}" alt="Hàng mới" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                        <div class="absolute left-5 bottom-7 text-white space-y-2">
                            <p class="text-xs uppercase tracking-[0.28em]">Hàng mới</p>
                            <h3 class="text-3xl font-bold leading-tight">IconDenim<br/>New Arrivals</h3>
                            <a href="{{ route('products.index', ['is_new' => 1]) }}" class="inline-flex items-center gap-2 bg-white text-black px-5 py-2.5 rounded-full font-semibold text-sm hover:bg-black hover:text-white transition">
                                Xem ngay <span class="text-lg">›</span>
                            </a>
                        </div>
                    </div>
                    {{-- Sản phẩm hiển thị dưới banner (1 sản phẩm) --}}
                    <div class="mt-4 grid grid-cols-1 gap-4">
                        @foreach(($newProducts ?? collect())->take(1) as $product)
                            <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all">
                                <x-product-card :product="$product" fit="cover" />
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Right product grid - 4 cột --}}
                <div class="lg:col-span-9">
                    {{-- Panel: Hàng mới --}}
                    <div class="highlight-panel grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 items-stretch" data-highlight-panel="new">
                        @foreach(($newProducts ?? collect())->take(8) as $product)
                            <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group h-full min-h-[520px]">
                                <x-product-card :product="$product" fit="cover" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Panel: Thu Đông --}}
                    <div class="highlight-panel hidden grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 items-stretch" data-highlight-panel="winter">
                        @foreach(($winterProducts ?? collect())->take(8) as $product)
                            <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group h-full min-h-[520px]">
                                <x-product-card :product="$product" fit="cover" />
                            </div>
                        @endforeach
                    </div>

                    {{-- Panel: Bán chạy (placeholder) --}}
                    <div class="highlight-panel hidden" data-highlight-panel="best">
                        <div class="p-12 border border-dashed border-slate-300 rounded-lg bg-slate-50 text-slate-500 text-sm text-center">
                            Bán chạy sẽ cập nhật tự động sau.
                        </div>
                    </div>

                    {{-- Chỉ giữ nút Xem tất cả ở giữa giống mẫu --}}
                    <div class="mt-8 flex justify-center">
                        <a href="{{ route('products.index', ['is_new' => 1]) }}" class="px-8 py-3 rounded border border-slate-300 text-sm font-semibold text-slate-700 hover:border-black hover:text-black transition">Xem tất cả</a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- =====================
            CATEGORY SHOWCASE (Áo Thun / Áo Sơmi / Áo Polo)
            Left: tall banner; Right: carousel of 5 products
        ===================== --}}
        <section class="category-showcase my-12" data-category-group="shirts">
            <div class="hanzo-container px-3">
                {{-- Header với tabs style ICONDENIM --}}
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Áo Nam</h2>
                    <div class="flex gap-8" id="category-tabs-shirts">
                        <button class="cat-tab text-[15px] font-semibold text-slate-900 pb-2 border-b-2 border-slate-900 hover:text-slate-900 transition-colors" 
                                data-tab="thun" data-group="shirts">Áo Thun</button>
                        <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors" 
                                data-tab="somi" data-group="shirts">Áo Sơmi</button>
                        <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors" 
                                data-tab="polo" data-group="shirts">Áo Polo</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-start">
                    {{-- Left hero banner (dynamic) --}}
                    <div class="hidden md:block md:col-span-1">
                        <div class="left-hero-shirts rounded-2xl overflow-hidden relative shadow-lg group hover:shadow-2xl transition-shadow duration-300">
                            <img src="{{ asset('images/banner/banner_aothun.jpg') }}" 
                                 alt="Áo Thun" 
                                 class="w-full h-[520px] object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                            <div class="absolute left-6 bottom-8 text-white z-10">
                                <h4 class="text-3xl font-bold mb-3 tracking-tight">ÁO THUN</h4>
                                <a href="{{ route('products.index', ['category' => 'ao-thun']) }}" 
                                   class="inline-block bg-white text-black px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-black hover:text-white transition-colors duration-300">
                                    XEM NGAY
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Right carousel panels --}}
                    <div class="col-span-1 md:col-span-4">

                        {{-- Thun panel --}}
                        <div class="tab-panel" data-panel="thun">
                            <div class="relative">
                                <div class="swiper thunSwiper">
                                    <div class="swiper-wrapper">
                                        @foreach($teeProducts as $product)
                                            <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                        @endforeach
                                    </div>
                                </div>

                                <button class="thun-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">‹</span>
                                </button>
                                <button class="thun-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">›</span>
                                </button>
                            </div>
                        </div>

                        {{-- Sơmi panel --}}
                        <div class="tab-panel hidden" data-panel="somi">
                            <div class="relative">
                                <div class="swiper somiSwiper">
                                    <div class="swiper-wrapper">
                                        @foreach($somiProducts as $product)
                                            <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="somi-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">‹</span>
                                </button>
                                <button class="somi-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">›</span>
                                </button>
                            </div>
                        </div>

                        {{-- Polo panel --}}
                        <div class="tab-panel hidden" data-panel="polo">
                            <div class="relative">
                                <div class="swiper poloSwiper">
                                    <div class="swiper-wrapper">
                                        @foreach($poloProducts as $product)
                                            <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                        @endforeach
                                    </div>
                                </div>
                                <button class="polo-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">‹</span>
                                </button>
                                <button class="polo-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                                    <span class="text-2xl">›</span>
                                </button>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">Xem tất cả</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- =====================
    CATEGORY QUẦN (Quần Short / Quần Jean / Quần Tây)
===================== --}}
<section class="category-showcase my-12" data-category-group="pants">
    <div class="hanzo-container px-3">
        {{-- Header với tabs style ICONDENIM --}}
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Quần Nam</h2>
            <div class="flex gap-8" id="category-tabs-pants">
                <button class="cat-tab text-[15px] font-semibold text-slate-900 pb-2 border-b-2 border-slate-900 hover:text-slate-900 transition-colors"
                        data-tab="short" data-group="pants">Quần Short</button>
                <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors"
                        data-tab="jean" data-group="pants">Quần Jean</button>
                <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors"
                        data-tab="tay" data-group="pants">Quần Tây</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-start">
            {{-- Left hero banner (dynamic) --}}
            <div class="hidden md:block md:col-span-1">
                <div class="left-hero-pants rounded-2xl overflow-hidden relative shadow-lg group hover:shadow-2xl transition-shadow duration-300">
                    <img src="{{ asset('images/banner/banner_quanshort.jpg') }}"
                         alt="Quần nam"
                         class="w-full h-[520px] object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                    <div class="absolute left-6 bottom-8 text-white z-10">
                        <h4 class="text-3xl font-bold mb-3 tracking-tight">QUẦN SHORT</h4>
                        <a href="{{ route('products.index', ['category' => 'quan-short']) }}"
                           class="inline-block bg-white text-black px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-black hover:text-white transition-colors duration-300">
                            XEM NGAY
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right carousel panels --}}
            <div class="col-span-1 md:col-span-4">

                {{-- SHORT panel --}}
                <div class="tab-panel" data-panel="short">
                    <div class="relative">
                        <div class="swiper shortSwiper">
                            <div class="swiper-wrapper">
                                @foreach($shortProducts as $product)
                                    <div class="swiper-slide">
                                        <x-product-card :product="$product" size="mini" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button class="short-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">‹</span>
                        </button>
                        <button class="short-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">›</span>
                        </button>
                    </div>
                </div>

                {{-- JEAN panel --}}
                <div class="tab-panel hidden" data-panel="jean">
                    <div class="relative">
                        <div class="swiper jeanSwiper">
                            <div class="swiper-wrapper">
                                @foreach($jeansProducts as $product)
                                    <div class="swiper-slide">
                                        <x-product-card :product="$product" size="mini" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button class="jean-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">‹</span>
                        </button>
                        <button class="jean-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">›</span>
                        </button>
                    </div>
                </div>

                {{-- TÂY panel --}}
                <div class="tab-panel hidden" data-panel="tay">
                    <div class="relative">
                        <div class="swiper taySwiper">
                            <div class="swiper-wrapper">
                                @foreach($tayProducts as $product)
                                    <div class="swiper-slide">
                                        <x-product-card :product="$product" size="mini" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <button class="tay-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">‹</span>
                        </button>
                        <button class="tay-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex">
                            <span class="text-2xl">›</span>
                        </button>
                    </div>
                </div>

                {{-- Xem tất cả --}}
                <div class="text-center mt-6">
                    <a href="{{ route('products.index', ['category_group' => 'pants']) }}"
                       class="inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium
                              transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                        Xem tất cả
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

    
@endsection
