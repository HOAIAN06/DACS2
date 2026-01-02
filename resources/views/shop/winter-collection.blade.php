@extends('layouts.app')

@section('title', 'Bộ Sưu Tập Thu Đông - HANZO')

@section('content')
<div class="hanzo-container py-8 md:py-12 px-3 md:px-6 lg:px-8">
    {{-- Breadcrumb --}}
    <nav class="mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 transition">Trang chủ</a>
        <span class="text-slate-400 mx-2">/</span>
        <a href="#" class="text-slate-600 hover:text-slate-900 transition">Bộ sưu tập</a>
        <span class="text-slate-400 mx-2">/</span>
        <span class="text-slate-900 font-medium">Thu Đông</span>
    </nav>

    {{-- Tiêu đề --}}
    <div class="mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-slate-900 mb-4">
            Bộ Sưu Tập Thu Đông
        </h1>
        <p class="text-lg text-slate-600">
            Khám phá bộ sưu tập Thu Đông với những thiết kế ấm áp, phong cách và đầy cá tính cho mùa lạnh.
        </p>
    </div>

    {{-- Grid sản phẩm --}}
    @if($products->count())
        <div class="product-grid mb-12">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="pagination">
            {{ $products->links('vendor.pagination.hanzo') }}
        </div>
    @else
        <div class="empty-state text-center py-16">
            <p class="text-lg text-slate-600">Chưa có sản phẩm nào trong bộ sưu tập Thu Đông.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition">
                Quay lại trang chủ
            </a>
        </div>
    @endif
</div>
@endsection
