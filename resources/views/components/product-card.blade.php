@props([
    'product',
    'size' => 'default',    // default | compact | mini
    'fit'  => 'cover',      // cover | contain | bottom
])

@php
    $detailUrl = route('product.show', $product->slug);

    $discountPercent = $product->old_price && $product->old_price > $product->price
        ? round(100 - ($product->price / $product->old_price * 100))
        : null;

    // Fallback ảnh
    $img = $product->thumbnail_url
        ?? optional($product->images->first())->image_url;

    if ($img) {
        $src = preg_match('/^https?:\/\//', $img)
            ? $img
            : asset(ltrim($img, '/'));
    } else {
        $src = null;
    }

    // Kích thước card theo size
    $sizeClass = match ($size) {
        'compact' => 'w-[160px] md:w-[170px] lg:w-[180px]',
        'mini'    => 'w-[175px] md:w-[185px] lg:w-[190px]',
        default   => 'w-[240px] md:w-[250px] lg:w-[255px]',
    };

    // Cách fit ảnh
    $fitClass = match ($fit) {
        'contain' => 'object-contain',
        'bottom'  => 'object-cover object-bottom',
        default   => 'object-cover',
    };

    $isMini = $size === 'mini';
@endphp

<div
    class="product-card group relative flex flex-col overflow-hidden
           rounded-[18px] border-[2.5px] border-red-600 bg-white
           transition-all duration-300
           hover:-translate-y-1 hover:shadow-[0_8px_20px_rgba(0,0,0,0.15)]
           {{ $isMini ? '' : 'h-full' }} {{ $sizeClass }}">

    {{-- KHUNG ẢNH --}}
    <div class="relative">
        <a href="{{ $detailUrl }}" class="block">
            <div class="product-image-wrapper w-full aspect-[3/4] bg-[#f3f3f3] flex items-center justify-center">
                @if ($src)
                    <img src="{{ $src }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full {{ $fitClass }}">
                @else
                    <span class="text-[11px] text-slate-500">
                        CHỖ NÀY CHÈN ẢNH
                    </span>
                @endif
            </div>
        </a>

        {{-- BADGE GIẢM GIÁ --}}
        @if ($discountPercent)
            <div class="absolute bottom-2 left-2 bg-red-600 text-white px-2 py-[2px]
                        text-[11px] font-bold rounded shadow">
                -{{ $discountPercent }}%
            </div>
        @endif

        {{-- ICON HOVER --}}
        <div class="product-actions absolute left-1/2 -translate-x-1/2 bottom-3
                    flex items-center
                    opacity-0 group-hover:opacity-100
                    transition duration-200">

            {{-- Thêm giỏ --}}
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="action-btn">
                    <img src="{{ asset('icons/shopping-cart.png') }}" class="w-4 h-4" alt="cart">
                </button>
            </form>

            {{-- Xem nhanh --}}
            <a href="{{ $detailUrl }}" class="action-btn">
                <img src="{{ asset('icons/eye.png') }}" class="w-4 h-4" alt="view">
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

            @if ($product->old_price && $product->old_price > $product->price)
                <span class="text-[12px] text-slate-400 line-through">
                    {{ number_format($product->old_price, 0, ',', '.') }}đ
                </span>
            @endif
        </div>
    </div>
</div>
