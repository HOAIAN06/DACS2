@props([
    'product',
    'size' => 'default',    // default | compact | mini
    'fit'  => 'cover',      // cover | contain | bottom
])

@php
    $detailUrl = route('product.show', $product->slug);

    // Tính % giảm giá dựa trên price_original
    $basePrice = $product->price_original;
    $discountPercent = ($basePrice && $basePrice > $product->price)
        ? round(100 - ($product->price / $basePrice * 100))
        : null;

    // Ảnh lấy từ product_images (ưu tiên is_main=1) qua accessor trong Product model
    $img = $product->thumbnail;

    $src = $img ?: null;

    // UI size (KHÔNG set width để grid tự quyết định)
    $sizeUiClass = match ($size ?? 'default') {
        'compact' => 'text-[12px] [&_.product-title]:text-[12px] [&_.price]:text-[14px]',
        'mini'    => 'text-[13px] [&_.product-title]:text-[13px] [&_.price]:text-[15px]',
        default   => 'text-[14px] [&_.product-title]:text-[14px] [&_.price]:text-[16px]',
    };

    // Fit ảnh
    $fitClass = match ($fit ?? 'cover') {
        'contain' => 'object-contain',
        'bottom'  => 'object-cover object-bottom',
        default   => 'object-cover',
    };

    $totalStock = $product->stock ?? 0;
@endphp



<div
    class="product-card group relative flex flex-col overflow-hidden
           rounded-[14px] border border-slate-200 bg-white
           transition-all duration-300
           hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.12)]
           w-full h-full {{ $sizeUiClass }}">


    {{-- KHUNG ẢNH --}}
    <div class="relative">
        <a href="{{ $detailUrl }}" class="block relative z-10">
            <div class="product-image-wrapper hz-skeleton w-full aspect-[3/4] bg-[#f3f3f3] flex items-center justify-center">
                @if ($src)
                    <img src="{{ $src }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full {{ $fitClass }}"
                         onerror="this.style.display='none'">
                @else
                    <span class="text-[11px] text-slate-500">
                        Không có ảnh
                    </span>
                @endif
            </div>
        </a>

        {{-- BADGE GIẢM GIÁ --}}
        @if ($discountPercent)
            <div class="absolute bottom-2 left-2 bg-red-600 text-white px-2 py-[2px]
                        text-[11px] font-bold rounded shadow z-20">
                -{{ $discountPercent }}%
            </div>
        @endif

        {{-- BADGE TỒN KHO: chỉ hiển thị khi hết hàng --}}
        @if((($totalStock ?? 0) <= 0))
            <div class="absolute top-2 left-2 z-20">
                <span class="inline-flex items-center gap-1 bg-red-600 text-white text-[11px] font-semibold px-2 py-1 rounded">
                    Hết hàng
                </span>
            </div>
        @endif

        {{-- ICON HOVER - giống ICONDENIM style --}}
        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition duration-300 pointer-events-none"></div>
        
        <div class="product-actions absolute bottom-3 left-1/2 -translate-x-1/2
                    flex items-center gap-3
                    opacity-0 group-hover:opacity-100
                    transition duration-200 z-30">

            {{-- Thêm giỏ --}}
<button type="button"
        class="hz-quick-add-btn w-8 h-8 rounded-full border border-slate-400 bg-white hover:bg-slate-900 hover:border-slate-900
               flex items-center justify-center transition duration-300"
        title="Thêm vào giỏ"
        data-product-id="{{ $product->id }}"
        data-image="{{ $product->thumbnail ?? asset('images/placeholder.png') }}"
        data-name="{{ $product->name }}"
        data-price="{{ $product->price }}"
        data-old-price="{{ $product->old_price ?? '' }}"
        data-category="{{ $product->category->name ?? '' }}"
        onclick="openQuickAddModal(this)">
    <svg class="w-4 h-4 text-slate-900 hover:text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
</button>


            {{-- Xem nhanh --}}
            <a href="{{ $detailUrl }}" class="w-8 h-8 rounded-full border border-slate-400 bg-white hover:bg-slate-900 hover:border-slate-900
                                             flex items-center justify-center transition duration-300"
               title="Xem nhanh">
                <svg class="w-4 h-4 text-slate-900 hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
        </div>
    </div>

    {{-- TÊN + GIÁ --}}
    <div class="px-3 pt-4 pb-3 bg-white flex-1 flex flex-col justify-between">
        <a href="{{ $detailUrl }}"
           class="product-title text-[13px] font-semibold text-black">
            {{ $product->name }}
        </a>

        <div class="mt-2 flex items-baseline gap-2">
            <span class="text-[15px] font-extrabold text-black">
                {{ number_format($product->price, 0, ',', '.') }}đ
            </span>

            @if ($basePrice && $basePrice > $product->price)
                <span class="text-[12px] text-slate-400 line-through">
                    {{ number_format($basePrice, 0, ',', '.') }}đ
                </span>
            @endif
        </div>
    </div>
</div>
