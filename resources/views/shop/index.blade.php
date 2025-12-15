@extends('layouts.app')

@section('title', 'Shop - HANZO')

@section('content')
<div class="hanzo-container py-8 px-3">

    {{-- Thanh tiêu đề + filter --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-8">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Shop</h1>
            <p class="text-slate-600 text-sm md:text-base">
                Tất cả sản phẩm thời trang nam & phụ kiện từ HANZO.
            </p>
        </div>

        <form class="flex flex-wrap gap-2" method="GET" action="{{ route('products.index') }}">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900"
                placeholder="Tìm kiếm sản phẩm..."
                style="max-width: 220px;"
            >

            <select name="category" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900" style="max-width: 180px;">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <select name="sort" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900" style="max-width: 160px;">
                <option value="">Mới nhất</option>
                <option value="price_asc"  {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
            </select>

            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition-colors">
                Lọc
            </button>
        </form>
    </div>

    {{-- Lưới sản phẩm --}}
    @if($products->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        {{-- Phân trang --}}
        <div class="mt-8 flex justify-center">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-16">
            <p class="text-slate-600 text-lg">Hiện chưa có sản phẩm nào phù hợp điều kiện lọc.</p>
        </div>
    @endif
</div>
@endsection
