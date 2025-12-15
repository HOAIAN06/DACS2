@extends('layouts.app')

@section('title', 'Shop – HANZO')

@section('content')
    <div class="container">

        {{-- TIÊU ĐỀ + THANH LỌC --}}
        <section class="hz-section">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3 mb-3">
                <div>
                    <h1 class="hz-section-title mb-1">SHOP</h1>
                    <p class="hz-section-sub mb-0">
                        Tất cả sản phẩm thời trang nam & phụ kiện tại HANZO.
                    </p>
                </div>

                <form method="GET" action="{{ route('shop') }}" class="d-flex flex-wrap gap-2">
                    {{-- Ô tìm kiếm --}}
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           class="form-control form-control-sm"
                           placeholder="Tìm kiếm sản phẩm...">

                    {{-- Chọn danh mục (nếu controller có truyền $categories) --}}
                    @isset($categories)
                        <select name="category"
                                class="form-select form-select-sm">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                        @selected(request('category') == $cat->id)>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    @endisset

                    {{-- Sắp xếp --}}
                    <select name="sort"
                            class="form-select form-select-sm">
                        <option value="">Mới nhất</option>
                        <option value="price_asc"  @selected(request('sort') === 'price_asc')>Giá tăng dần</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>Giá giảm dần</option>
                    </select>

                    <button type="submit" class="hz-btn-primary border-0">
                        Lọc
                    </button>
                </form>
            </div>
        </section>

        {{-- DANH SÁCH SẢN PHẨM --}}
        <section class="hz-section">
            @if(isset($products) && $products->count())
                <div class="row g-3">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="{{ route('product.show', $product->slug) }}"
                               class="text-decoration-none text-reset">
                                <div class="hz-card h-100 d-flex flex-column">
                                    {{-- Ảnh sản phẩm --}}
                                    <div class="hz-card-thumb mb-2"
                                         @if($product->thumbnail)
                                             style="background-image:url('{{ asset('storage/'.$product->thumbnail) }}');
                                                    background-size:cover;
                                                    background-position:center;"
                                         @endif
                                    ></div>

                                    {{-- Tên --}}
                                    <div style="font-size:13px; font-weight:600;">
                                        {{ $product->name }}
                                    </div>

                                    {{-- Giá --}}
                                    <div class="hz-price mt-1">
                                        {{ number_format($product->price, 0, ',', '.') }} đ
                                        @if(!empty($product->old_price) && $product->old_price > $product->price)
                                            <span class="hz-price-old">
                                                {{ number_format($product->old_price, 0, ',', '.') }} đ
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Nhãn nhỏ --}}
                                    <div class="mt-1" style="font-size:11px; color:#9CA3AF;">
                                        @if(!empty($product->category))
                                            {{ $product->category->name }}
                                            @if(!empty($product->is_featured))
                                                • <span style="color:#10b981;">Featured</span>
                                            @endif
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- PHÂN TRANG --}}
                <div class="mt-4">
                    {{ $products->withQueryString()->links() }}
                </div>
            @else
                <div class="hz-card text-center">
                    <p class="mb-1" style="font-size:14px; font-weight:500;">
                        Hiện chưa có sản phẩm nào phù hợp điều kiện lọc.
                    </p>
                    <p class="hz-section-sub mb-0">
                        Hãy thử xoá bộ lọc hoặc quay lại sau nhé.
                    </p>
                </div>
            @endif
        </section>

    </div>
@endsection
