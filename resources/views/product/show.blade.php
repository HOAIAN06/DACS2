@extends('layouts.app')

@section('title', $product->name . ' - HANZO')

@section('content')

<div class="hanzo-container px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

        {{-- Ảnh sản phẩm --}}
        <div>
            <div class="bg-[#f3f3f3] rounded-xl overflow-hidden">
                @if(!empty($product->thumbnail_url))
                    <img src="{{ $product->thumbnail_url }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-auto object-cover">
                @else
                    <div class="w-full aspect-square flex items-center justify-center bg-slate-200">
                        <span class="text-slate-500">Chưa có ảnh</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Thông tin sản phẩm --}}
        <div class="space-y-6">
            {{-- Tên --}}
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900">
                {{ $product->name }}
            </h1>

            {{-- Giá --}}
            <div class="flex items-baseline gap-3">
                <span class="text-3xl font-bold text-red-600">
                    {{ number_format($product->price, 0, ',', '.') }}đ
                </span>
                @if($product->old_price && $product->old_price > $product->price)
                    <span class="text-lg text-slate-400 line-through">
                        {{ number_format($product->old_price, 0, ',', '.') }}đ
                    </span>
                @endif
            </div>

            {{-- Mô tả ngắn --}}
            @if(!empty($product->description))
                <div class="text-slate-600 leading-relaxed">
                    {{ $product->description }}
                </div>
            @endif

            {{-- Thêm giỏ hàng --}}
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="space-y-2">
                    <label class="block text-sm font-semibold">Số lượng:</label>
                    <input type="number" name="quantity" value="1" min="1" 
                           class="w-20 px-3 py-2 border border-slate-300 rounded-lg">
                </div>

                <button type="submit"
                        class="w-full px-6 py-3 bg-black text-white font-bold uppercase tracking-wide 
                               rounded-lg hover:bg-red-600 transition">
                    Thêm vào giỏ hàng
                </button>
            </form>

            {{-- Thông tin thêm --}}
            <div class="space-y-3 border-t border-slate-200 pt-6">
                <div class="flex justify-between">
                    <span class="text-slate-600">SKU:</span>
                    <span class="font-semibold">{{ $product->sku ?? 'N/A' }}</span>
                </div>
                @if(!empty($product->category))
                <div class="flex justify-between">
                    <span class="text-slate-600">Danh mục:</span>
                    <a href="{{ route('category.show', $product->category->slug) }}" 
                       class="font-semibold text-red-600 hover:underline">
                        {{ $product->category->name }}
                    </a>
                </div>
                @endif
            </div>

        </div>

    </div>
</div>

@endsection
