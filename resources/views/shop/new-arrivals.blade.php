@extends('layouts.app')

@section('title', 'Hàng Mới Về - HANZO')

@section('content')
<div class="hanzo-container py-8 md:py-12 px-3 md:px-6 lg:px-8">
    {{-- Breadcrumb --}}
    <nav class="mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 transition">Trang chủ</a>
        <span class="text-slate-400 mx-2">/</span>
        <span class="text-slate-900 font-medium">Hàng Mới Về</span>
    </nav>

    {{-- Tiêu đề --}}
    <div class="mb-12 space-y-4">
        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold tracking-[0.2em] uppercase shadow-lg shadow-slate-900/15">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
            New Season
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight text-slate-900 tracking-tight">
            Hàng <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-sky-400 to-slate-900">mới về</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 max-w-3xl leading-relaxed">
            Ra mắt các thiết kế mới nhất, chọn lọc tinh gọn để bạn chạm ngay xu hướng hiện đại.
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
            <p class="text-lg text-slate-600">Chưa có sản phẩm mới nào.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition">
                Quay lại trang chủ
            </a>
        </div>
    @endif
</div>
@endsection
