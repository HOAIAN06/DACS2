@extends('layouts.app')

@section('title', 'Shop - HANZO')

@section('content')
{{-- Breadcrumb --}}
<div class="bg-slate-50 border-b border-slate-200">
    <div class="hanzo-container px-4 py-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-900 transition-colors">Trang chủ</a>
            <span class="text-slate-400">/</span>
            @if(request('category'))
                @php
                    $category = $categories->firstWhere('slug', request('category'));
                @endphp
                @if($category)
                    <span class="text-slate-900 font-medium">{{ $category->name }}</span>
                @else
                    <span class="text-slate-900 font-medium">Shop</span>
                @endif
            @else
                <span class="text-slate-900 font-medium">Tất cả sản phẩm</span>
            @endif
        </nav>
    </div>
</div>

<div class="hanzo-container py-12 px-4">

    {{-- Header Section --}}
    <div class="mb-12">
        {{-- Logo & Title --}}
        <div class="flex flex-col items-center text-center mb-10">
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 blur-2xl opacity-20"></div>
                <h1 class="relative text-7xl md:text-8xl font-black tracking-tighter text-slate-900">
                    <span class="inline-block transform hover:scale-105 transition-transform duration-300">H</span><span class="inline-block transform hover:scale-105 transition-transform duration-300">A</span><span class="inline-block transform hover:scale-105 transition-transform duration-300">N</span><span class="inline-block transform hover:scale-105 transition-transform duration-300">Z</span><span class="inline-block transform hover:scale-105 transition-transform duration-300">O</span>
                </h1>
            </div>
            
            <div class="space-y-2 max-w-3xl">
                <h2 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                    Thời Trang Nam Cao Cấp
                </h2>
                <p class="text-lg md:text-xl text-slate-500 font-medium leading-relaxed">
                    Khám phá bộ sưu tập thời trang nam & phụ kiện độc đáo.<br>
                     Chất lượng đỉnh phong cách riêng biệt.
                </p>
            </div>
            
            <div class="mt-6 h-1 w-24 bg-gradient-to-r from-transparent via-slate-900 to-transparent rounded-full"></div>
        </div>

        {{-- Search & Filter Section --}}
        <form class="space-y-4" method="GET" action="{{ route('products.index') }}">
            {{-- Search Bar --}}
            <div class="relative">
                <img src="{{ asset('icons/search.png') }}" alt="Search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 opacity-50">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    class="w-full pl-12 pr-5 py-3.5 border-2 border-slate-200 rounded-2xl text-base focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100 transition-all placeholder-slate-400"
                    placeholder=" Tìm kiếm sản phẩm, thương hiệu, màu sắc..."
                >
            </div>

            {{-- Filters Row --}}
            <div class="flex flex-col md:flex-row gap-3 md:gap-4">
                {{-- Category Select --}}
                <div class="flex-1 relative">
                    <select name="category" class="w-full px-5 py-3 border-2 border-slate-200 rounded-xl text-base focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100 transition-all bg-white font-medium text-slate-900 appearance-none pr-10 pl-12">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <img src="{{ asset('icons/category.png') }}" alt="Category" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none opacity-60">
                    <img src="{{ asset('icons/category.png') }}" alt="Category" class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none opacity-60">
                </div>

                {{-- Sort Select --}}
                <div class="flex-1 relative">
                    <select name="sort" class="w-full px-5 py-3 border-2 border-slate-200 rounded-xl text-base focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-100 transition-all bg-white font-medium text-slate-900 appearance-none pr-10 pl-12">
                        <option value="">Mới nhất</option>
                        <option value="price_asc"  {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>
                    <img src="{{ asset('icons/calendar.png') }}" alt="" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 pointer-events-none opacity-60">
                    
                </div>

                <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl text-base font-bold hover:bg-slate-800 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <img src="{{ asset('icons/filter.png') }}" alt="Filter" class="w-5 h-5 brightness-200">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    {{-- Lưới sản phẩm --}}
    @if($products->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6 mb-12">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="flex justify-center">
            {{ $products->links('vendor.pagination.hanzo') }}
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-slate-600 text-lg">Hiện chưa có sản phẩm nào phù hợp điều kiện lọc.</p>
        </div>
    @endif
</div>
@endsection
