@extends('layouts.app')

@section('title', 'Thanh Toán – HANZO')

@section('content')
<div class="hz-checkout">

    {{-- Header --}}
    <div class="hz-checkout__head">
        <div>
            <h1 class="hz-checkout__title">Thanh Toán</h1>
            <p class="hz-checkout__sub">Hoàn tất đơn hàng của bạn</p>
        </div>

        <a href="{{ route('cart.index') }}" class="hz-chip-link">← Quay lại giỏ hàng</a>
    </div>

    <div class="hz-checkout__grid">

        {{-- LEFT: Form --}}
        <div class="hz-checkout__left">
            
            {{-- Error Messages --}}
            @if($errors->any())
                <div class="hz-alert hz-alert--error" style="margin-bottom: 24px;">
                    <div style="display: flex; gap: 12px; align-items: flex-start;">
                        <div style="font-size: 20px; flex-shrink: 0;">⚠️</div>
                        <div>
                            <h3 style="color: #dc2626; font-size: 15px; font-weight: 700; margin: 0 0 8px;">Vui lòng sửa lỗi sau:</h3>
                            <ul style="margin: 0; padding-left: 20px; color: #991b1b; font-size: 14px; line-height: 1.6;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('checkout.store') }}" class="hz-form">
                @csrf

                {{-- Shipping --}}
                <div class="hz-card">
                    <div class="hz-card__head">
                        <h2 style="display: flex; align-items: center; gap: 10px; margin: 0;">
                            <img src="{{ asset('icons/diachigiaohang.png') }}" alt="" style="width: 24px; height: 24px;">
                            Thông Tin Giao Hàng
                        </h2>
                        <span class="hz-badge">Bắt buộc *</span>
                    </div>

                    {{-- Hàng 1: Họ tên + SĐT --}}
                    <div class="hz-field-grid">
                        <div class="hz-field">
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                                <img src="{{ asset('icons/user.png') }}" alt="" style="width: 18px; height: 18px;">
                                Họ Tên <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="text"
                                   name="shipping_name"
                                   value="{{ $user?->name ?? old('shipping_name') }}"
                                   class="hz-input @error('shipping_name') is-invalid @enderror"
                                   placeholder="Nguyễn Văn A"
                                   required>
                            @error('shipping_name') <p class="hz-err">{{ $message }}</p> @enderror
                        </div>

                        <div class="hz-field">
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                                <img src="{{ asset('icons/dienthoai.png') }}" alt="" style="width: 18px; height: 18px;">
                                Số Điện Thoại <span style="color: #ef4444;">*</span>
                            </label>
                            <input type="tel"
                                   name="shipping_phone"
                                   value="{{ old('shipping_phone') }}"
                                   class="hz-input @error('shipping_phone') is-invalid @enderror"
                                   placeholder="0987654321"
                                   required>
                            @error('shipping_phone') <p class="hz-err">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Hàng 2: Email --}}
                    <div class="hz-field">
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                            <img src="{{ asset('icons/email.png') }}" alt="" style="width: 18px; height: 18px;">
                            Email <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="email"
                               name="shipping_email"
                               value="{{ $user?->email ?? old('shipping_email') }}"
                               class="hz-input @error('shipping_email') is-invalid @enderror"
                               placeholder="email@example.com"
                               required>
                        @error('shipping_email') <p class="hz-err">{{ $message }}</p> @enderror
                    </div>

                    {{-- Hàng 3: Tỉnh/Thành phố + Phường/Xã --}}
                    <div class="hz-field-grid">
                        <div class="hz-field">
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                                <img src="{{ asset('icons/phuongxa.png') }}" alt="" style="width: 18px; height: 18px;">
                                Tỉnh/Thành Phố <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="province_name"
                                    id="province-select"
                                    class="hz-select @error('province_name') is-invalid @enderror"
                                    required>
                                <option value="">-- Chọn Tỉnh/Thành Phố --</option>
                            </select>
                            @error('province_name') <p class="hz-err">{{ $message }}</p> @enderror
                        </div>

                        <div class="hz-field">
                            <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                                <img src="{{ asset('icons/phuongxa.png') }}" alt="" style="width: 18px; height: 18px;">
                                Phường/Xã <span style="color: #ef4444;">*</span>
                            </label>
                            <select name="ward_name"
                                    id="ward-select"
                                    class="hz-select @error('ward_name') is-invalid @enderror"
                                    required
                                    disabled>
                                <option value="">-- Chọn Tỉnh/Thành Phố trước --</option>
                            </select>
                            @error('ward_name') <p class="hz-err">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Hàng 4: Địa chỉ giao hàng --}}
                    <div class="hz-field">
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                            <img src="{{ asset('icons/diachigiaohang.png') }}" alt="" style="width: 18px; height: 18px;">
                            Địa Chỉ Giao Hàng <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text"
                               name="shipping_address"
                               value="{{ old('shipping_address') }}"
                               placeholder="123 Đường Lê Lợi, Quận 1, TP.HCM"
                               class="hz-input @error('shipping_address') is-invalid @enderror"
                               required>
                        @error('shipping_address') <p class="hz-err">{{ $message }}</p> @enderror
                    </div>

                    {{-- Hàng 5: Ghi chú --}}
                    <div class="hz-field">
                        <label style="display: flex; align-items: center; gap: 6px; font-weight: 600; margin-bottom: 8px;">
                            <img src="{{ asset('icons/note.png') }}" alt="" style="width: 18px; height: 18px;">
                            Ghi Chú (tùy chọn)
                        </label>
                        <textarea name="note"
                                  rows="3"
                                  placeholder="Ví dụ: Giao buổi tối, gọi trước khi giao..."
                                  class="hz-textarea"
                                  style="font-size: 14px; font-family: inherit;">{{ old('note') }}</textarea>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="hz-card">
                    <div class="hz-card__head">
                        <h2 style="display: flex; align-items: center; gap: 10px; margin: 0;">
                            <img src="{{ asset('icons/thanhtoan.png') }}" alt="" style="width: 24px; height: 24px;">
                            Phương Thức Thanh Toán
                        </h2>
                        <span class="hz-note">An toàn • Dễ thanh toán</span>
                    </div>

                    <div class="hz-pay">
                        <label class="hz-pay__item">
                            <input type="radio" name="payment_method" value="cod" checked>
                            <div class="hz-pay__box">
                                <div class="hz-pay__title">
                                    <span class="hz-pay__dot"></span>
                                    Thanh toán khi nhận hàng (COD)
                                </div>
                                <div class="hz-pay__desc">Nhận hàng kiểm tra rồi thanh toán.</div>
                            </div>
                        </label>

                        <label class="hz-pay__item is-disabled">
                            <input type="radio" name="payment_method" value="bank_transfer" disabled>
                            <div class="hz-pay__box">
                                <div class="hz-pay__title">
                                    <span class="hz-pay__dot"></span>
                                    Chuyển khoản ngân hàng
                                    <span class="hz-pill">Sắp có</span>
                                </div>
                                <div class="hz-pay__desc">Tự động xác nhận sau khi chuyển khoản.</div>
                            </div>
                        </label>

                        <label class="hz-pay__item is-disabled">
                            <input type="radio" name="payment_method" value="credit_card" disabled>
                            <div class="hz-pay__box">
                                <div class="hz-pay__title">
                                    <span class="hz-pay__dot"></span>
                                    Thanh toán bằng thẻ
                                    <span class="hz-pill">Sắp có</span>
                                </div>
                                <div class="hz-pay__desc">Visa/Mastercard & ví điện tử.</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="hz-submit">
                    <button type="submit" class="hz-btn hz-btn--primary">
                        Xác Nhận Đơn Hàng
                    </button>

                    <div class="hz-trust">
                        <span>✓ Miễn phí vận chuyển</span>
                        <span class="dot">•</span>
                        <span>✓ Đổi trả 15 ngày</span>
                    </div>
                </div>
            </form>
        </div>

        {{-- RIGHT: Summary --}}
        <div class="hz-checkout__right">
            <div class="hz-summary">
                <div class="hz-summary__head">
                    <h3>Tóm Tắt Đơn Hàng</h3>
                    <span class="hz-summary__tag">Free Shipping</span>
                </div>

                <div class="hz-mini-list">
                    @foreach($cart->items as $item)
                        @php $itemTotal = (float)$item->price * (int)$item->qty; @endphp

                        <div class="hz-mini">
                            <div class="hz-mini__img">
                                @if($item->product->thumbnail)
                                    <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}">
                                @else
                                    <div class="hz-mini__ph"></div>
                                @endif
                            </div>

                            <div class="hz-mini__info">
                                <div class="hz-mini__name">{{ $item->product->name }}</div>
                                <div class="hz-mini__meta">
                                    x{{ $item->qty }}
                                    @if($item->variant && ($item->variant->size || $item->variant->color))
                                        <span class="sep">•</span>
                                        <span>
                                            @if($item->variant->size) Size {{ $item->variant->size }} @endif
                                            @if($item->variant->color) {{ $item->variant->size ? '•' : '' }} {{ $item->variant->color }} @endif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="hz-mini__price">
                                {{ number_format($itemTotal, 0, ',', '.') }} đ
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hz-sum-rows">
                    <div class="row">
                        <span>Tổng tiền hàng</span>
                        <b>{{ number_format($subtotal, 0, ',', '.') }} đ</b>
                    </div>
                    <div class="row">
                        <span>Phí vận chuyển</span>
                        <b>0 đ</b>
                    </div>
                </div>

                <div class="hz-sum-total">
                    <span>Tổng cộng</span>
                    <b>{{ number_format($total, 0, ',', '.') }} đ</b>
                </div>

                <div class="hz-sum-hint">
                    Bạn sẽ nhận được xác nhận đơn hàng sau khi đặt thành công.
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Location Selector Script --}}
<script src="{{ asset('js/location.js') }}"></script>
@endsection
