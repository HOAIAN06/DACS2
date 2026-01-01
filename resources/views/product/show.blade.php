@extends('layouts.app')

@section('title', $product->name . ' - HANZO')

@section('content')
<script id="hz-variants-json" type="application/json">
{!! $product->variants->map(function($v) {
    return [
        'id' => $v->id,
        'color' => $v->color,
        'size' => $v->size,
        'price' => $v->price,
        'sku' => $v->sku,
        'stock' => $v->stock ?? 0,
    ];
})->toJson() !!}
</script>

{{-- Breadcrumb --}}
<div class="bg-slate-50 border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-4">
        <nav class="flex items-center gap-2 text-sm">
            <a href="{{ route('home') }}" class="text-slate-500 hover:text-slate-900">Trang chủ</a>
            <span class="text-slate-400">/</span>
            @if($product->category)
                <a href="{{ route('category.show', $product->category->slug) }}" class="text-slate-500 hover:text-slate-900">{{ $product->category->name }}</a>
                <span class="text-slate-400">/</span>
            @endif
            <span class="text-slate-900 font-medium">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-8 md:py-12">
    @php
        $mainImageUrl = $product->main_image_url ?? asset('images/placeholder.jpg');
    @endphp
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">

       {{-- Ảnh sản phẩm --}}
<div class="flex gap-3">
    {{-- Thumbnails bên trái --}}
    @php
        $allImages = collect([]);
        if($product->images && $product->images->count() > 0) {
            // Hiển thị tất cả ảnh: ưu tiên ảnh main (is_main=1) lên đầu, sau đó các ảnh còn lại
            $mainImages = $product->images->filter(fn($img) => $img->is_main)->sortBy('sort_order');
            $otherImages = $product->images->filter(fn($img) => !$img->is_main)->sortBy('sort_order');
            $sortedImages = $mainImages->merge($otherImages);
            
            foreach($sortedImages as $img) {
                $allImages->push($img->full_url ?? asset('images/placeholder.jpg'));
            }
        }
        
        // Fallback: nếu không có ảnh trong product_images, dùng main_image_url
        if($allImages->isEmpty() && $mainImageUrl) {
            $allImages->push($mainImageUrl);
        }
        
        $displayImages = $allImages->filter()->values()->take(10);

        // Build size-guide mapping with base names; decide extension by actual file existence (.png or .jpg)
        $categorySlug = strtolower($product->category->slug ?? '');
        $productSlug  = strtolower($product->slug ?? '');
        $productName  = strtolower($product->name ?? '');
        $haystack = $categorySlug.' '.$productSlug.' '.$productName;

        // Determine if product is freesize-only
        $variantSizes = $product->variants?->pluck('size')->filter()->map(fn($s) => strtolower(trim($s)))->unique()->values() ?? collect();
        $freeKeywords = ['freesize','free size','one size','onesize','free'];
        $isFreeSizeOnly = ($variantSizes->count() > 0) && $variantSizes->every(fn($s) => in_array($s, $freeKeywords, true));

        $categoryBaseMap = [
            'ao-khoac'   => 'size_aokhoac',
            'ao-polo'    => 'size_aopolo',
            'ao-somi'    => 'size_aosomi',
            'ao-hoodie'  => 'size_hoodie',
            'ao-tanktop' => 'size_aotanktop',
            'ao-thun'    => 'size_aothun',
            'quan-jeans' => 'size_quanjeans',
            'quan-jogger'=> 'size_quanjogger',
            'quan-kaki'  => 'size_quankaki',
            'quan-tay'   => 'size_quantay',
            'quan-short' => 'size_quansort',
        ];

        $sizeGuideImage = null;

        // Helper to resolve file extension
        $resolveImage = function(string $base) {
            $png = public_path('images/bang_size/'.$base.'.png');
            $jpg = public_path('images/bang_size/'.$base.'.jpg');
            if (file_exists($png)) return $base.'.png';
            if (file_exists($jpg)) return $base.'.jpg';
            return null;
        };

        if (!$isFreeSizeOnly && isset($categoryBaseMap[$categorySlug])) {
            $sizeGuideImage = $resolveImage($categoryBaseMap[$categorySlug]);
        }

        // Fallback: keyword-based
        if (!$sizeGuideImage && !$isFreeSizeOnly) {
            $keywordMap = [
                'size_aokhoac'  => ['aokhoac','ao-khoac','khoac'],
                'size_aopolo'   => ['aopolo','ao-polo','polo'],
                'size_aosomi'   => ['aosomi','ao-so-mi','so mi','somi'],
                'size_aotanktop'=> ['aotanktop','tanktop','tank top'],
                'size_aothun'   => ['aothun','ao-thun','ao phong','tee','t-shirt','t shirt'],
                'size_quanjeans'=> ['quanjeans','quan-jeans','jeans'],
                'size_quanjogger'=>['quanjogger','quan-jogger','jogger'],
                'size_quankaki' => ['quankaki','quan-kaki','kaki','khaki'],
                'size_quantay'  => ['quantay','quan-tay','tay au','tay âu','tây âu'],
                'size_quansort' => ['quanshort','quan-short','short','sooc','sort'],
            ];
            foreach ($keywordMap as $base => $keys) {
                foreach ($keys as $k) {
                    if (str_contains($haystack, $k)) {
                        $sizeGuideImage = $resolveImage($base);
                        if ($sizeGuideImage) break 2;
                    }
                }
            }
        }
    @endphp

    <div class="flex flex-col gap-2 flex-shrink-0 max-h-[640px] overflow-auto pr-1">
        @foreach($displayImages as $index => $thumbUrl)
            <button type="button"
                    class="hz-gallery-thumb w-16 h-20 md:w-20 md:h-24 rounded-lg border-2 {{ $index === 0 ? 'border-slate-900' : 'border-slate-200 hover:border-slate-900' }} overflow-hidden transition-colors bg-white"
                    data-image="{{ $thumbUrl }}">
                <img src="{{ $thumbUrl }}"
                     alt="Thumb {{ $index + 1 }}"
                     class="w-full h-full object-cover object-center">
            </button>
        @endforeach
    </div>

    {{-- Main Image --}}
<div class="relative group overflow-hidden bg-white border border-slate-200 rounded-2xl shadow-sm
            w-full max-w-[520px] md:max-w-[560px]
            aspect-[5/7] max-h-[720px]">
           <img id="hz-main-image"
               src="{{ $mainImageUrl }}"
               alt="{{ $product->name }}"
               class="w-full h-full object-cover object-center">

        {{-- Gallery Navigation --}}
        <button type="button" id="hz-gallery-prev"
                class="absolute top-1/2 -translate-y-1/2 left-3 w-10 h-10 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button type="button" id="hz-gallery-next"
                class="absolute top-1/2 -translate-y-1/2 right-3 w-10 h-10 rounded-full bg-white/90 backdrop-blur shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>


        {{-- Thông tin sản phẩm --}}
        <div class="lg:pl-8">
            <div class="space-y-6">
                {{-- Tên sản phẩm --}}
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">
                        {{ $product->name }}
                    </h1>
                    <!-- <p class="pb-6 border-b border-slate-200">Mã sản phẩm: <span id="hz-product-sku">{{ $product->variants->first()->sku ?? 'N/A' }}</span></p> -->
                </div>

                {{-- Giá --}}
                <div class="pb-6 border-b border-slate-200">
                    @php
                        $basePrice = $product->price_original;
                        $discountPercent = ($basePrice && $basePrice > $product->price)
                            ? round(100 - ($product->price / $basePrice * 100))
                            : null;
                    @endphp
                    <div class="flex items-baseline gap-3 mb-2" id="hz-price-section">
                        <span class="text-3xl font-bold hz-product-price" id="hz-product-price">
                            {{ number_format($product->price, 0, ',', '.') }}₫
                        </span>
                        @if($discountPercent)
                            <span class="px-2.5 py-1 bg-red-500 text-white text-sm font-bold rounded">
                                -{{ $discountPercent }}%
                            </span>
                        @endif
                    </div>
                    @if($basePrice && $basePrice > $product->price)
                        <span class="text-lg text-slate-400 line-through">
                            {{ number_format($basePrice, 0, ',', '.') }}₫
                        </span>
                    @endif
                </div>

{{-- Form mua hàng (refined UI) --}}
<form action="{{ route('cart.add') }}" method="POST" id="hz-product-form" class="hz-buybox">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="hidden" name="variant_id" id="hz-selected-variant" value="">

    {{-- Hiển thị lỗi validation --}}
    @if($errors->any())
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center gap-2 text-red-700 text-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    @endif

    @php
        $colors = $product->variants->pluck('color')->unique()->filter()->values();
        $sizes  = $product->variants->pluck('size')->unique()->filter()->values();

        // Map màu sắc tiếng Việt sang mã màu CSS
        $colorMap = [
            'Đen' => '#000000',
            'Trắng' => '#FFFFFF',
            'Xám' => '#9CA3AF',
            'Nâu' => '#8B4513',
            'Be' => '#F5F5DC',
            'Xanh Navy' => '#001F3F',
            'Xanh Dương' => '#3B82F6',
            'Xanh Lá' => '#10B981',
            'Đỏ' => '#EF4444',
            'Cam' => '#F97316',
            'Vàng' => '#FCD34D',
            'Hồng' => '#EC4899',
            'Tím' => '#8B5CF6',
            'Kem' => '#FFFACD',
            'Xanh Rêu' => '#6B7280',
        ];
    @endphp

    {{-- Chọn màu sắc --}}
    @if($colors->count() > 0)
        <div class="hz-option">
            <div class="hz-option__head">
                <label class="hz-option__label">Màu sắc</label>
                <span class="hz-option__hint" id="hz-color-selected"></span>
            </div>

            <div class="hz-color-group">
                @foreach($colors as $color)
                    @php
                        $hexColor = $colorMap[$color] ?? '#6B7280';
                        $isLight = in_array($color, ['Trắng', 'Be', 'Kem', 'Vàng']);
                    @endphp
                    <button type="button"
                            class="hz-color-swatch hz-color-btn"
                            data-color="{{ $color }}"
                            title="{{ $color }}">
                        <span class="hz-color-circle {{ $isLight ? 'is-light' : '' }}" style="background-color: {{ $hexColor }}"></span>
                        <span class="hz-color-name">{{ $color }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Chọn size --}}
    @if($sizes->count() > 0)
        <div class="hz-option">
            <div class="hz-option__head">
                <label class="hz-option__label">Kích thước</label>
                <div class="hz-option__right">
                    <span class="hz-option__hint" id="hz-size-selected"></span>
                    @if($sizeGuideImage && !$isFreeSizeOnly)
                        <a href="#hz-size-guide-modal" id="hz-size-guide-btn" class="hz-link">
                            Hướng dẫn chọn size
                        </a>
                    @endif
                </div>
            </div>

            <div class="hz-size-grid" id="hz-size-container">
                @foreach($sizes as $size)
                    <button type="button"
                            class="hz-size-btn"
                            data-size="{{ $size }}">
                        {{ $size }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Số lượng + Thêm vào giỏ (cùng hàng) --}}
<div class="hz-buy-row">
    <div class="hz-qty-wrap">
        <button type="button" id="hz-qty-minus" class="hz-qty-btn" aria-label="Giảm">
            −
        </button>

        <input type="number" name="qty" id="hz-qty-input" value="1" min="1" class="hz-qty-input">

        <button type="button" id="hz-qty-plus" class="hz-qty-btn" aria-label="Tăng">
            +
        </button>
    </div>

    <button type="submit" name="action" value="add_to_cart" class="hz-btn hz-btn-outline">
        THÊM VÀO GIỎ
    </button>
</div>

{{-- Mua ngay (full width) --}}
<button type="submit" name="action" value="buy_now" class="hz-btn hz-btn-primary w-full">
    MUA NGAY
</button>

    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== GALLERY NAVIGATION =====
    const mainImage = document.getElementById('hz-main-image');
    const thumbBtns = document.querySelectorAll('.hz-gallery-thumb');
    const prevBtn = document.getElementById('hz-gallery-prev');
    const nextBtn = document.getElementById('hz-gallery-next');
    let currentIndex = 0;

    // Click thumbnail to change main image
    thumbBtns.forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            const newImageSrc = this.getAttribute('data-image');
            if (newImageSrc && mainImage) {
                mainImage.src = newImageSrc;
                currentIndex = index;
                updateActiveThumb();
            }
        });
    });

    // Prev/Next buttons
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateMainImage();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            if (currentIndex < thumbBtns.length - 1) {
                currentIndex++;
                updateMainImage();
            }
        });
    }

    function updateMainImage() {
        const newImageSrc = thumbBtns[currentIndex].getAttribute('data-image');
        if (newImageSrc && mainImage) {
            mainImage.src = newImageSrc;
            updateActiveThumb();
        }
    }

    function updateActiveThumb() {
        thumbBtns.forEach((thumb, idx) => {
            if (idx === currentIndex) {
                thumb.classList.remove('border-slate-200');
                thumb.classList.add('border-slate-900');
            } else {
                thumb.classList.remove('border-slate-900');
                thumb.classList.add('border-slate-200');
            }
        });
    }

    // ===== VARIANT SELECTION =====
    const form = document.getElementById('hz-product-form');
    const variantInput = document.getElementById('hz-selected-variant');
    const colorBtns = document.querySelectorAll('.hz-color-btn');
    const sizeBtns = document.querySelectorAll('.hz-size-btn');
    const variantsJson = document.getElementById('hz-variants-json');
    
    let variants = [];
    if (variantsJson) {
        try {
            variants = JSON.parse(variantsJson.textContent);
        } catch (e) {
            console.error('Lỗi parse variants:', e);
        }
    }
    
    let selectedColor = null;
    let selectedSize = null;

    // Bắt sự kiện chọn màu
    colorBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Bỏ chọn cái cũ
            colorBtns.forEach(b => b.classList.remove('active'));
            
            // Chọn cái mới
            this.classList.add('active');
            selectedColor = this.dataset.color;
            
            // Update hint text
            document.getElementById('hz-color-selected').textContent = selectedColor;
            
            // Reset size khi chọn màu khác
            selectedSize = null;
            sizeBtns.forEach(b => b.classList.remove('active'));
            document.getElementById('hz-size-selected').textContent = '';
            
            // Disable các size không phù hợp
            updateAvailableSizes();
        });
    });

    // Bắt sự kiện chọn size
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Bỏ chọn cái cũ
            sizeBtns.forEach(b => b.classList.remove('active'));
            
            // Chọn cái mới
            this.classList.add('active');
            selectedSize = this.dataset.size;
            
            // Update hint text
            document.getElementById('hz-size-selected').textContent = selectedSize;
            
            // Update variant_id dựa trên color + size
            updateVariantId();
        });
    });

    function updateAvailableSizes() {
        if (!selectedColor) {
            sizeBtns.forEach(btn => {
                btn.disabled = false;
                btn.classList.remove('disabled');
            });
            return;
        }

        const availableSizes = variants
            .filter(v => v.color === selectedColor && v.stock > 0)
            .map(v => v.size);

        sizeBtns.forEach(btn => {
            if (availableSizes.includes(btn.dataset.size)) {
                btn.disabled = false;
                btn.classList.remove('disabled');
            } else {
                btn.disabled = true;
                btn.classList.add('disabled');
            }
        });
    }

    function updateVariantId() {
        if (!selectedColor || !selectedSize) {
            variantInput.value = '';
            return;
        }

        const variant = variants.find(
            v => v.color === selectedColor && v.size === selectedSize
        );

        if (variant) {
            variantInput.value = variant.id;
            
            // Update giá nếu có
            if (variant.price) {
                document.getElementById('hz-product-price').textContent = 
                    new Intl.NumberFormat('vi-VN').format(variant.price) + '₫';
            }
        }
    }

    // Validate khi submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Kiểm tra xem có chọn màu không
        if (!selectedColor) {
            alert('⚠️ Vui lòng chọn màu sắc');
            return;
        }

        // Kiểm tra xem có chọn size không
        if (!selectedSize) {
            alert('⚠️ Vui lòng chọn kích thước');
            return;
        }

        // Kiểm tra xem có tìm thấy variant không
        if (!variantInput.value) {
            alert('⚠️ Sản phẩm này không có variant phù hợp');
            return;
        }

        // Nếu OK thì submit form
        this.submit();
    });

    // Quantity buttons
    const qtyInput = document.getElementById('hz-qty-input');
    const qtyMinus = document.getElementById('hz-qty-minus');
    const qtyPlus = document.getElementById('hz-qty-plus');

    if (qtyMinus) {
        qtyMinus.addEventListener('click', function(e) {
            e.preventDefault();
            const val = parseInt(qtyInput.value) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });
    }

    if (qtyPlus) {
        qtyPlus.addEventListener('click', function(e) {
            e.preventDefault();
            const val = parseInt(qtyInput.value) || 1;
            qtyInput.value = val + 1;
        });
    }
});
</script>

<style>
    /* Enhanced Color Selector */
    .hz-color-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .hz-color-swatch {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 85px;
    }

    .hz-color-swatch:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .hz-color-swatch.active {
        border-color: #0f172a;
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.15);
    }

    .hz-color-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12), inset 0 0 0 1px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }

    .hz-color-circle.is-light {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1), inset 0 0 0 1.5px rgba(0, 0, 0, 0.15);
    }

    .hz-color-swatch.active .hz-color-circle {
        border-color: #0f172a;
        transform: scale(1.1);
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2), inset 0 0 0 2px #fff;
    }

    .hz-color-name {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.3px;
        transition: color 0.2s ease;
    }

    .hz-color-swatch.active .hz-color-name {
        color: #0f172a;
        font-weight: 700;
    }

    /* Size buttons */
    .hz-size-btn {
        transition: all 0.2s ease;
    }

    .hz-size-btn.active {
        background-color: #0f172a;
        color: white;
        border-color: #0f172a;
    }

    .hz-size-btn:disabled,
    .hz-size-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background-color: #f1f5f9;
        color: #cbd5e1;
    }
</style>


                    {{-- Thông tin chính sách (layout ngang như web mẫu) --}}
<div class="hz-policy">
  <div class="hz-policy__list">

    <div class="hz-policy__item">
      <span class="hz-policy__icon">
        <img src="{{ asset('icons/vanchuyen.png') }}" alt="">
      </span>
      <span class="hz-policy__text">Freeship đơn từ 299K</span>
    </div>

    <div class="hz-policy__item">
      <span class="hz-policy__icon">
        <img src="{{ asset('icons/traodoi.png') }}" alt="">
      </span>
      <span class="hz-policy__text">Cộng dồn Membership đến 15%</span>
    </div>

    <div class="hz-policy__item">
      <span class="hz-policy__icon">
        <img src="{{ asset('icons/cod.png') }}" alt="">
      </span>
      <span class="hz-policy__text">Thanh toán COD</span>
    </div>

    <div class="hz-policy__item">
      <span class="hz-policy__icon">
        <img src="{{ asset('icons/doitra.png') }}" alt="">
      </span>
      <span class="hz-policy__text">Đổi trả trong 15 ngày</span>
    </div>

  </div>
</div>



                {{-- Mô tả ngắn --}}
                @if(!empty($product->description))
                    <div class="pt-6 border-t border-slate-200">
                        <h3 class="text-sm font-semibold text-slate-900 mb-2">Mô tả sản phẩm</h3>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                {{-- Thông tin giao hàng --}}
                <div class="pt-6 border-t border-slate-200 space-y-3">
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                        <span class="text-slate-600">Miễn phí vận chuyển đơn từ 299K</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span class="text-slate-600">Đổi hàng trong vòng 15 ngày</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span class="text-slate-600">Thanh toán COD - Yên tâm mua sắm</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

    @if(isset($sizeGuideImage) && $sizeGuideImage && !$isFreeSizeOnly)
    <div id="hz-size-guide-modal" class="hz-size-guide-backdrop">
        <div class="hz-size-guide-dialog">
            <a href="#" id="hz-size-guide-close" class="hz-size-guide-close" aria-label="Đóng hướng dẫn size">×</a>
            <img src="{{ asset('images/bang_size/' . $sizeGuideImage) }}" alt="Hướng dẫn chọn size" class="hz-size-guide-img" loading="lazy">
        </div>
    </div>
    @endif

@endsection



