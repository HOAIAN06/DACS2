@extends('layouts.app')

@section('title', 'HANZO - Cửa hàng thời trang nam')

@section('content')


    @php
    // Slug đúng theo menu của bạn
    $shirtCats = [
        'thun' => 'ao-thun',
        'polo' => 'ao-polo',
        'somi' => 'ao-somi',
    ];

    $pantCats = [
        'short' => 'quan-short',
        'jean'  => 'quan-jean',
        'tay'   => 'quan-tay',
    ];
    @endphp

    {{-- Inject asset paths for JS category showcase --}}
    <script>
        window.HANZO = window.HANZO || {};
        window.HANZO.categoryBanners = {
            // Áo
            thun: "{{ asset('images/banner/banner_aothun.jpg') }}",
            somi: "{{ asset('images/banner/banner_aosomi.jpg') }}",
            polo: "{{ asset('images/banner/banner_aopolo.jpg') }}",
            // Quần
            short: "{{ asset('images/banner/banner_quanshort.jpg') }}",
            jean: "{{ asset('images/banner/banner_quanjean.jpg') }}",
            tay: "{{ asset('images/banner/banner_quantay.jpg') }}"
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
            {{-- HERO BANNER ---}}
            <div class="relative w-full h-[350px] md:h-[500px] lg:h-[600px] rounded-xl overflow-hidden mb-10 group">
                <img src="{{ asset('images/banner/retro-sport-banner.jpg') }}" 
                     alt="Retro Sports Collection" 
                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                
                

            </div>

            {{-- PRODUCT CAROUSEL --}}
            <div class="relative">
                {{-- Heading --}}
                <div class="mb-12 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight mb-2">Sản phẩm nổi bật</h3>
                        <div class="h-1.5 w-16 bg-slate-900 rounded-full"></div>
                    </div>
                    <a href="{{ route('collections.show', 'retro-sports') }}" 
                       class="px-6 py-3 bg-slate-900 text-white rounded-full text-xs font-bold 
                              hover:bg-slate-700 hover:shadow-lg transition duration-300 
                              uppercase tracking-widest flex-shrink-0 whitespace-nowrap">
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

                
            </div> 

            {{-- PRODUCT CAROUSEL --}}
            <div class="relative">
                {{-- Heading --}}
                <div class="mb-12 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">Sản phẩm nổi bật</h3>
                        <div class="h-1.5 w-16 bg-white rounded-full"></div>
                    </div>
                    <a href="{{ route('category.show', 'quan-jean') }}" 
                       class="px-6 py-3 bg-white text-blue-600 rounded-full text-xs font-bold 
                              hover:bg-slate-100 hover:shadow-lg transition duration-300 
                              uppercase tracking-widest flex-shrink-0 whitespace-nowrap">
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










{{-- HIGHLIGHT SECTION: Hàng mới / Bán chạy / Thu Đông (style ICONDENIM) --}}
@if(($newProducts ?? collect())->count() || ($winterProducts ?? collect())->count() || ($bestSellerProducts ?? collect())->count())
<section class="my-16 hanzo-container px-3" data-highlight-group>

    {{-- Tabs --}}
    <div class="flex items-center justify-end gap-6 mb-6 border-b border-slate-200">
        <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-black border-b-2 border-black transition-all"
                data-highlight-tab="new">
            Hàng mới
        </button>
        <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-slate-400 border-b-2 border-transparent hover:text-slate-600 transition-all"
                data-highlight-tab="best">
            Hàng bán chạy
        </button>
        <button class="highlight-tab px-0 py-3 text-[15px] font-semibold text-slate-400 border-b-2 border-transparent hover:text-slate-600 transition-all"
                data-highlight-tab="winter">
            Đồ Thu Đông
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 xl:gap-6">

        {{-- LEFT: banner + 1 sản phẩm featured (thay đổi theo tab) --}}
        <div class="hidden lg:flex lg:flex-col lg:gap-5">
            {{-- Banner: Hàng mới --}}
            <a href="{{ route('products.new-arrivals') }}"
               class="highlight-banner group block rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all bg-white"
               data-highlight-banner="new">
                <div class="relative w-full aspect-[3/5] bg-[#f3f3f3] overflow-hidden">
                    <img src="{{ asset('images/banner/highlight-new.jpg') }}"
                         alt="Hàng mới"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-transparent"></div>
                    <div class="absolute left-5 bottom-6 text-white space-y-2">
                        <h3 class="text-3xl font-bold leading-tight">Hàng mới</h3>
                        <span class="inline-flex items-center gap-2 bg-white text-black px-5 py-2.5 rounded-full font-semibold text-sm group-hover:bg-black group-hover:text-white transition">
                            Xem ngay <span class="text-lg">›</span>
                        </span>
                    </div>
                </div>
            </a>

            {{-- Featured product: Hàng mới --}}
            @if(($newProducts ?? collect())->isNotEmpty())
                <div class="highlight-banner rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white"
                     data-highlight-banner="new">
                    <x-product-card :product="$newProducts->first()" fit="cover" />
                </div>
            @endif

            {{-- Banner: Bán chạy --}}
            <a href="{{ route('products.best-sellers') }}"
               class="highlight-banner hidden group block rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all bg-white"
               data-highlight-banner="best">
                <div class="relative w-full aspect-[3/5] bg-[#f3f3f3] overflow-hidden">
                    <img src="{{ asset('images/banner/highlight-new.jpg') }}"
                         alt="Bán chạy"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-transparent"></div>
                    <div class="absolute left-5 bottom-6 text-white space-y-2">
                        <h3 class="text-3xl font-bold leading-tight">Bán chạy</h3>
                        <span class="inline-flex items-center gap-2 bg-white text-black px-5 py-2.5 rounded-full font-semibold text-sm group-hover:bg-black group-hover:text-white transition">
                            Xem ngay <span class="text-lg">›</span>
                        </span>
                    </div>
                </div>
            </a>

            {{-- Featured product: Bán chạy --}}
            @if(($bestSellerProducts ?? collect())->isNotEmpty())
                <div class="highlight-banner hidden rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white"
                     data-highlight-banner="best">
                    <x-product-card :product="$bestSellerProducts->first()" fit="cover" />
                </div>
            @endif

            {{-- Banner: Thu Đông --}}
            <a href="{{ route('products.winter-collection') }}"
               class="highlight-banner hidden group block rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all bg-white"
               data-highlight-banner="winter">
                <div class="relative w-full aspect-[3/5] bg-[#f3f3f3] overflow-hidden">
                    <img src="{{ asset('images/banner/highlight-winter.jpg') }}"
                         alt="Đồ Thu Đông"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/20 to-transparent"></div>
                    <div class="absolute left-5 bottom-6 text-white space-y-2">
                        <h3 class="text-3xl font-bold leading-tight">Thu Đông</h3>
                        <span class="inline-flex items-center gap-2 bg-white text-black px-5 py-2.5 rounded-full font-semibold text-sm group-hover:bg-black group-hover:text-white transition">
                            Xem ngay <span class="text-lg">›</span>
                        </span>
                    </div>
                </div>
            </a>

            {{-- Featured product: Thu Đông --}}
            @if(($winterProducts ?? collect())->isNotEmpty())
                <div class="highlight-banner hidden rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white"
                     data-highlight-banner="winter">
                    <x-product-card :product="$winterProducts->first()" fit="cover" />
                </div>
            @endif
        </div>

        {{-- RIGHT: grid sản phẩm --}}
        <div class="lg:col-span-4">
            {{-- Panel: Hàng mới --}}
            <div class="highlight-panel grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4 xl:gap-5 items-stretch"
                 data-highlight-panel="new">
                {{-- Giới hạn 8 sản phẩm để hiển thị 2 hàng (4 cột) trên desktop --}}
                @foreach(($newProducts ?? collect())->take(8) as $product)
                    <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white">
                        <x-product-card :product="$product" fit="cover" />
                    </div>
                @endforeach
            </div>

            {{-- Panel: Bán chạy --}}
            <div class="highlight-panel hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4 xl:gap-5 items-stretch"
                 data-highlight-panel="best">
                @foreach(($bestSellerProducts ?? collect())->take(8) as $product)
                    <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white">
                        <x-product-card :product="$product" fit="cover" />
                    </div>
                @endforeach

                @if(($bestSellerProducts ?? collect())->isEmpty())
                    <div class="col-span-2 md:col-span-3 lg:col-span-4 p-12 border border-dashed border-slate-300 rounded-lg bg-slate-50 text-slate-500 text-sm text-center">
                        Chưa có dữ liệu bán chạy.
                    </div>
                @endif
            </div>

            {{-- Panel: Thu Đông --}}
            <div class="highlight-panel hidden grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4 xl:gap-5 items-stretch"
                 data-highlight-panel="winter">
                {{-- Giới hạn 8 sản phẩm để hiển thị 2 hàng (4 cột) trên desktop --}}
                @foreach(($winterProducts ?? collect())->take(8) as $product)
                    <div class="rounded-lg overflow-hidden border border-slate-200 hover:border-slate-300 shadow-sm transition-all group bg-white">
                        <x-product-card :product="$product" fit="cover" />
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{-- CTA: Hàng mới --}}
                <a href="{{ route('products.new-arrivals') }}"
                   data-highlight-cta="new"
                   class="inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                    Xem tất cả
                </a>

                {{-- CTA: Bán chạy --}}
                <a href="{{ route('products.best-sellers') }}"
                   data-highlight-cta="best"
                   class="hidden inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                    Xem tất cả
                </a>

                {{-- CTA: Thu Đông --}}
                <a href="{{ route('products.winter-collection') }}"
                   data-highlight-cta="winter"
                   class="hidden inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                    Xem tất cả
                </a>
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

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl md:text-3xl font-bold text-slate-900">Áo Nam</h2>
            <div class="flex gap-8" id="category-tabs-shirts">
                <button class="cat-tab text-[15px] font-semibold text-slate-900 pb-2 border-b-2 border-slate-900 hover:text-slate-900 transition-colors"
                        data-tab="thun" data-group="shirts">Áo Thun</button>
                <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors"
                        data-tab="somi" data-group="shirts">Áo Sơ Mi</button>
                <button class="cat-tab text-[15px] font-semibold text-slate-500 pb-2 border-b-2 border-transparent hover:text-slate-900 transition-colors"
                        data-tab="polo" data-group="shirts">Áo Polo</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 items-start">
            {{-- Left hero banner --}}
            <div class="hidden md:block md:col-span-1">
                <div class="left-hero-shirts rounded-2xl overflow-hidden relative shadow-lg group hover:shadow-2xl transition-shadow duration-300">
                    <img id="hero-image-shirts"
                         src="{{ asset('images/banner/banner_aothun.jpg') }}"
                         alt="Áo Thun"
                         class="w-full h-[520px] object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                    <div class="absolute left-6 bottom-8 text-white z-10">
                        <h4 id="hero-title-shirts" class="text-3xl font-bold mb-3 tracking-tight">ÁO THUN</h4>

                        {{-- XEM NGAY (đổi theo tab) --}}
                        <a id="hero-link-shirts"
                           href="{{ route('category.show', $shirtCats['thun']) }}"
                           data-url-thun="{{ route('category.show', $shirtCats['thun']) }}"
                           data-url-somi="{{ route('category.show', $shirtCats['somi']) }}"
                           data-url-polo="{{ route('category.show', $shirtCats['polo']) }}"
                           class="inline-block bg-white text-black px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-black hover:text-white transition-colors duration-300">
                            XEM NGAY
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right carousel panels --}}
            <div class="col-span-1 md:col-span-4">

                <div class="tab-panel" data-panel="thun">
                    <div class="relative">
                        <div class="swiper thunSwiper">
                            <div class="swiper-wrapper">
                                @foreach($teeProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="thun-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="thun-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                <div class="tab-panel hidden" data-panel="somi">
                    <div class="relative">
                        <div class="swiper somiSwiper">
                            <div class="swiper-wrapper">
                                @foreach($somiProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="somi-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="somi-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                <div class="tab-panel hidden" data-panel="polo">
                    <div class="relative">
                        <div class="swiper poloSwiper">
                            <div class="swiper-wrapper">
                                @foreach($poloProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="polo-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="polo-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                {{-- Xem tất cả (đổi theo tab) --}}
                <div class="text-center mt-6">
                    <a id="view-all-shirts"
                       href="{{ route('category.show', $shirtCats['thun']) }}"
                       data-url-thun="{{ route('category.show', $shirtCats['thun']) }}"
                       data-url-somi="{{ route('category.show', $shirtCats['somi']) }}"
                       data-url-polo="{{ route('category.show', $shirtCats['polo']) }}"
                       class="inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                        Xem tất cả
                    </a>
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
            {{-- Left hero banner --}}
            <div class="hidden md:block md:col-span-1">
                <div class="left-hero-pants rounded-2xl overflow-hidden relative shadow-lg group hover:shadow-2xl transition-shadow duration-300">
                    <img id="hero-image-pants"
                         src="{{ asset('images/banner/banner_quanshort.jpg') }}"
                         alt="Quần Short"
                         class="w-full h-[520px] object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                    <div class="absolute left-6 bottom-8 text-white z-10">
                        <h4 id="hero-title-pants" class="text-3xl font-bold mb-3 tracking-tight">QUẦN SHORT</h4>

                        {{-- XEM NGAY (đổi theo tab) --}}
                        <a id="hero-link-pants"
                           href="{{ route('category.show', $pantCats['short']) }}"
                           data-url-short="{{ route('category.show', $pantCats['short']) }}"
                           data-url-jean="{{ route('category.show', $pantCats['jean']) }}"
                           data-url-tay="{{ route('category.show', $pantCats['tay']) }}"
                           class="inline-block bg-white text-black px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-black hover:text-white transition-colors duration-300">
                            XEM NGAY
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right carousel panels --}}
            <div class="col-span-1 md:col-span-4">

                <div class="tab-panel" data-panel="short">
                    <div class="relative">
                        <div class="swiper shortSwiper">
                            <div class="swiper-wrapper">
                                @foreach($shortProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="short-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="short-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                <div class="tab-panel hidden" data-panel="jean">
                    <div class="relative">
                        <div class="swiper jeanSwiper">
                            <div class="swiper-wrapper">
                                @foreach($jeansProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="jean-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="jean-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                <div class="tab-panel hidden" data-panel="tay">
                    <div class="relative">
                        <div class="swiper taySwiper">
                            <div class="swiper-wrapper">
                                @foreach($tayProducts as $product)
                                    <div class="swiper-slide"><x-product-card :product="$product" size="mini" /></div>
                                @endforeach
                            </div>
                        </div>
                        <button class="tay-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">‹</span></button>
                        <button class="tay-next absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex"><span class="text-2xl">›</span></button>
                    </div>
                </div>

                {{-- Xem tất cả (đổi theo tab) --}}
                <div class="text-center mt-6">
                    <a id="view-all-pants"
                       href="{{ route('category.show', $pantCats['short']) }}"
                       data-url-short="{{ route('category.show', $pantCats['short']) }}"
                       data-url-jean="{{ route('category.show', $pantCats['jean']) }}"
                       data-url-tay="{{ route('category.show', $pantCats['tay']) }}"
                       class="inline-block px-6 py-2 border-2 border-slate-900 rounded-md font-medium transition-all duration-300 hover:bg-slate-900 hover:text-white hover:scale-105">
                        Xem tất cả
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>


{{-- Bộ sưu tập mới nhất (3 banner) --}}
<section class="my-16">
    <div class="hanzo-container px-3">
        <h2 class="text-center text-2xl md:text-3xl font-bold text-slate-900 mb-8">Bộ Sưu Tập Mới Nhất</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Card 1 --}}
            <a href="{{ route('collections.show', 'retro-sports') }}" class="group block rounded-2xl overflow-hidden relative shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="{{ asset('images/banner/bst_retro.jpg') }}" alt="Retro Sports" class="w-full h-[520px] md:h-[620px] object-cover object-center group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/30 to-transparent"></div>
                <div class="absolute left-5 right-5 bottom-6 text-white">
            
                    <h3 class="text-2xl md:text-3xl font-extrabold leading-tight">Retro Sports</h3>
                </div>
            </a>

            {{-- Card 2 --}}
            <a href="{{ route('collections.show', 'snoopy') }}" class="group block rounded-2xl overflow-hidden relative shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="{{ asset('images/banner/bst_snoopy.jpg') }}" alt="Snoopy" class="w-full h-[520px] md:h-[620px] object-cover object-center group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/30 to-transparent"></div>
                <div class="absolute left-5 right-5 bottom-6 text-white">
                    <h3 class="text-2xl md:text-3xl font-extrabold leading-tight">Snoopy Collection</h3>
                </div>
            </a>

            {{-- Card 3 --}}
            <a href="{{ route('collections.show', 'mickey-friends') }}" class="group block rounded-2xl overflow-hidden relative shadow-lg hover:shadow-2xl transition-all duration-500">
                <img src="{{ asset('images/banner/bst_m&f.jpg') }}" alt="Mickey & Friends" class="w-full h-[520px] md:h-[620px] object-cover object-center group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-black/30 to-transparent"></div>
                <div class="absolute left-5 right-5 bottom-6 text-white">
            
                    <h3 class="text-2xl md:text-3xl font-extrabold leading-tight">Mickey & Friends</h3>
                </div>
            </a>
        </div>
    </div>
</section>

@endsection
