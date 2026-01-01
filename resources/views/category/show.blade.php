@extends('layouts.app')

@section('title', $category->name . ' - HANZO')

@section('content')
<div class="hanzo-container py-8 md:py-12 px-3 md:px-6 lg:px-8">
    {{-- Banner khu vực danh mục --}}
    <div class="mb-10">
        @if($bannerUrl)
            <div class="category-banner">
                <img src="{{ $bannerUrl }}" alt="{{ $category->name }}">
            </div>
        @else
            <div class="category-banner-fallback">
                <p class="uppercase text-xs tracking-[0.3em] opacity-80 mb-2">Danh mục</p>
                <h1>{{ $category->name }}</h1>
                <p>Khám phá tất cả sản phẩm trong danh mục {{ strtolower($category->name) }}.</p>
            </div>
        @endif
    </div>

    {{-- Lưới sản phẩm --}}
    @if($products->count())
        <div class="product-grid">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        <div class="pagination">
            {{ $products->links('vendor.pagination.hanzo') }}
        </div>
    @else
        <div class="empty-state">
            <p>Hiện chưa có sản phẩm nào trong danh mục này.</p>
        </div>
    @endif
</div>
@endsection
