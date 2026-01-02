@extends('layouts.app')

@section('title', 'Sản Phẩm Bán Chạy - HANZO')

@section('content')
<div class="hanzo-container py-8 md:py-12 px-3 md:px-6 lg:px-8">
    {{-- Breadcrumb --}}
    <nav class="mb-8 text-sm">
        <a href="{{ route('home') }}" class="text-slate-600 hover:text-slate-900 transition">Trang chủ</a>
        <span class="text-slate-400 mx-2">/</span>
        <span class="text-slate-900 font-medium">Sản phẩm bán chạy</span>
    </nav>

    {{-- Tiêu đề --}}
    <div class="mb-12 space-y-4">
        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-slate-900 text-white text-xs font-semibold tracking-[0.2em] uppercase shadow-lg shadow-slate-900/15">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-300 animate-pulse"></span>
            Signature Picks
        </div>
        <h1 class="text-4xl md:text-5xl font-black leading-tight text-slate-900 tracking-tight">
            Sản phẩm <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-rose-400 to-slate-900">bán chạy</span>
        </h1>
        <p class="text-lg md:text-xl text-slate-600 max-w-3xl leading-relaxed">
            Tuyển chọn những thiết kế được ưa chuộng nhất, xếp hạng theo doanh số thực và nhãn bán chạy thủ công.
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
            <p class="text-lg text-slate-600">Chưa có dữ liệu bán chạy.</p>
            <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2 bg-slate-900 text-white rounded hover:bg-slate-800 transition">
                Quay lại trang chủ
            </a>
        </div>
    @endif
</div>
@endsection
