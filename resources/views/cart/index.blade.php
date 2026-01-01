@extends('layouts.app')

@section('title', 'Giỏ Hàng – HANZO')

@section('content')
<div class="hz-cart-wrap">
    {{-- Header --}}
    <div class="hz-cart-head">
        <div>
            <h1 class="hz-cart-title">
                <img src="{{ asset('icons/shopping-cart.png') }}" alt="" style="width: 32px; height: 32px; margin-right: 12px;">
                Giỏ Hàng
            </h1>
            <p class="hz-cart-sub">
                📦 {{ $cart->items->count() }} sản phẩm trong giỏ hàng của bạn
            </p>
        </div>

        <a href="{{ route('products.index') }}" class="hz-chip-link">
            ← Tiếp tục mua hàng
        </a>
    </div>

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

    @if($cart->items->count() > 0)
        <div class="hz-cart-grid">
            {{-- LEFT: Items --}}
            <div class="hz-cart-left">
                <div class="hz-cart-list">
                    @foreach($cart->items as $item)
                        @php
                            $total = (float)$item->price * (int)$item->qty;
                        @endphp

                        <div class="hz-item">
                            {{-- Image --}}
                            <a class="hz-item__img" href="{{ route('product.show', $item->product->slug) }}">
                                @if($item->product->thumbnail)
                                    <img src="{{ $item->product->thumbnail }}"
                                         alt="{{ $item->product->name }}">
                                @else
                                    <div class="hz-item__img--placeholder"></div>
                                @endif
                            </a>

                            {{-- Content --}}
                            <div class="hz-item__body">
                                <div class="hz-item__top">
                                    <div class="hz-item__info">
                                        <a class="hz-item__name" href="{{ route('product.show', $item->product->slug) }}">
                                            {{ $item->product->name }}
                                        </a>

                                        @if($item->variant)
                                            <div class="hz-item__meta">
                                                @if($item->variant->size)
                                                    <span class="hz-badge-sm">Size {{ $item->variant->size }}</span>
                                                @endif
                                                @if($item->variant->color)
                                                    <span class="hz-badge-sm hz-badge-sm--color">{{ $item->variant->color }}</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Remove --}}
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <button type="submit"
                                                class="hz-item__remove"
                                                onclick="return confirm('Xóa sản phẩm này?')"
                                                aria-label="Remove"
                                                title="Xóa">
                                            ✕
                                        </button>
                                    </form>
                                </div>

                                <div class="hz-item__bottom">
                                    {{-- Price --}}
                                    <div class="hz-item__col">
                                        <div class="hz-label">Đơn giá</div>
                                        <div class="hz-value">
                                            {{ number_format((float)$item->price, 0, ',', '.') }} đ
                                        </div>
                                    </div>

                                    {{-- Qty --}}
                                    <div class="hz-item__col">
                                        <div class="hz-label">Số lượng</div>

                                        <form method="POST" action="{{ route('cart.update') }}" class="hz-qty-form">
                                            @csrf
                                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                                            <div class="hz-qty" data-item="{{ $item->id }}">
                                                <button type="button" class="hz-qty__btn hz-qty__minus" data-item="{{ $item->id }}" title="Giảm">−</button>

                                                <input type="number"
                                                       name="qty"
                                                       class="hz-qty__input"
                                                       value="{{ $item->qty }}"
                                                       min="1"
                                                       max="999"
                                                       data-item="{{ $item->id }}"
                                                       onchange="this.form.submit()">

                                                <button type="button" class="hz-qty__btn hz-qty__plus" data-item="{{ $item->id }}" title="Tăng">+</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Line total --}}
                                    <div class="hz-item__col hz-item__col--right">
                                        <div class="hz-label">Thành tiền</div>
                                        <div class="hz-total">
                                            {{ number_format($total, 0, ',', '.') }} đ
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- RIGHT: Summary --}}
            <div class="hz-cart-right">
                <div class="hz-summary">
                    <div class="hz-summary__head">
                        <h3>📋 Tóm tắt đơn hàng</h3>
                        <span class="hz-free-ship">🚀 Free Shipping</span>
                    </div>

                    <div class="hz-summary__rows">
                        <div class="row">
                            <span>Tổng tiền hàng</span>
                            <b class="hz-price">{{ number_format($subtotal, 0, ',', '.') }} đ</b>
                        </div>
                        <div class="row">
                            <span>Phí vận chuyển</span>
                            <b class="hz-price hz-price--free">0 đ</b>
                        </div>
                    </div>

                    <div class="hz-summary__total">
                        <span>Tổng cộng</span>
                        <b class="hz-price-total">{{ number_format($subtotal, 0, ',', '.') }} đ</b>
                    </div>

                    <a href="{{ route('checkout.index') }}" class="hz-btn hz-btn--primary hz-btn--lg">
                        🛍️ Tiến Hành Thanh Toán
                    </a>

                    <a href="{{ route('products.index') }}" class="hz-btn hz-btn--ghost">
                        ← Tiếp Tục Mua Hàng
                    </a>

                    <div class="hz-summary__hint">
                        ℹ️ Bạn có thể áp dụng mã giảm giá tại trang thanh toán.
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Empty --}}
        <div class="hz-empty">
            <div class="hz-empty__icon">🛒</div>
            <h2 class="hz-empty__title">Giỏ hàng trống</h2>
            <p class="hz-empty__text">Hãy khám phá các sản phẩm thời trang nam & phụ kiện từ HANZO.</p>
            <a href="{{ route('products.index') }}" class="hz-btn hz-btn--primary hz-empty__btn">
                🛍️ Khám Phá Sản Phẩm
            </a>
        </div>
    @endif
</div>
@endsection
