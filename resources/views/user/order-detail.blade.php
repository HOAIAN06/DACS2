@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - HANZO')

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white min-h-screen py-8">
    <div class="hanzo-container px-3">
        {{-- Header --}}
        <div class="mb-8 flex justify-between items-start">
            <div>
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Chi tiết đơn hàng</h1>
                <p class="text-slate-600">
                    Mã: <span class="font-mono font-semibold text-blue-600">{{ $order->code }}</span>
                    | Ngày: {{ $order->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <a href="{{ route('user.orders') }}" class="px-4 py-2 bg-slate-100 text-slate-900 rounded-lg hover:bg-slate-200 transition font-medium">
                ← Quay lại
            </a>
        </div>

        {{-- Order Status Timeline --}}
        <div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-slate-900 mb-6">Trạng thái giao hàng</h2>
            <div class="order-timeline">
                <div class="timeline-item {{ in_array($order->status, ['processing', 'shipping', 'completed']) ? 'timeline-item--active' : '' }} {{ $order->status == 'completed' ? 'timeline-item--completed' : '' }}">
                    <div class="timeline-item__icon">1</div>
                    <p class="timeline-item__label">Đã đặt hàng</p>
                    <p class="timeline-item__date">{{ $order->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="timeline-item {{ in_array($order->status, ['processing', 'shipping', 'completed']) ? 'timeline-item--active' : '' }} {{ in_array($order->status, ['shipping', 'completed']) ? 'timeline-item--completed' : '' }}">
                    <div class="timeline-item__icon">2</div>
                    <p class="timeline-item__label">Đang xử lý</p>
                </div>
                <div class="timeline-item {{ in_array($order->status, ['shipping', 'completed']) ? 'timeline-item--active' : '' }} {{ $order->status == 'completed' ? 'timeline-item--completed' : '' }}">
                    <div class="timeline-item__icon">3</div>
                    <p class="timeline-item__label">Đang giao</p>
                </div>
                <div class="timeline-item {{ $order->status == 'completed' ? 'timeline-item--completed' : '' }}">
                    <div class="timeline-item__icon"><img src="{{ asset('icons/user.png') }}" alt="✓" class="w-4 h-4"></div>
                    <p class="timeline-item__label">Hoàn thành</p>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Products & Shipping --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order Items --}}
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-900"><img src="{{ asset('icons/donhang.png') }}" alt="Sản phẩm" class="w-5 h-5 inline mr-2"> Sản phẩm đã mua</h2>
                        <p class="text-sm text-slate-600 mt-1">{{ $items->count() }} sản phẩm</p>
                    </div>
                    <div class="divide-y divide-slate-200">
                        @foreach($items as $item)
                            <div class="p-6 hover:bg-slate-50 transition">
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-slate-900 mb-2">{{ $item->product_name }}</h3>
                                        <div class="flex gap-3 text-sm text-slate-600 mb-3">
                                            @if($item->size)
                                                <span class="bg-slate-100 px-2 py-1 rounded">Size: <strong>{{ $item->size }}</strong></span>
                                            @endif
                                            @if($item->color)
                                                <span class="bg-slate-100 px-2 py-1 rounded">Màu: <strong>{{ $item->color }}</strong></span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-600">Số lượng: <strong class="text-slate-900">{{ $item->qty }}</strong> cái</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-slate-500 mb-1">Đơn giá</p>
                                        <p class="font-bold text-slate-900 mb-2">{{ number_format($item->unit_price, 0, ',', '.') }}₫</p>
                                        <p class="text-xs text-slate-500 mb-1">Thành tiền</p>
                                        <p class="text-lg font-bold text-slate-900">{{ number_format($item->line_total, 0, ',', '.') }}₫</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="bg-white rounded-lg border border-slate-200 p-6">
                    <h2 class="text-xl font-bold text-slate-900 mb-4"><img src="{{ asset('icons/giaohang.png') }}" alt="Giao hàng" class="w-5 h-5 inline mr-2"> Thông tin giao hàng</h2>
                    <div class="space-y-3 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-slate-600 font-medium">Tên người nhận</p>
                                <p class="text-slate-900 font-semibold">{{ $order->shipping_name }}</p>
                            </div>
                            <div>
                                <p class="text-slate-600 font-medium">Số điện thoại</p>
                                <p class="text-slate-900 font-semibold">{{ $order->shipping_phone }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-slate-600 font-medium">Địa chỉ</p>
                            <p class="text-slate-900 font-semibold">{{ $order->shipping_address }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-slate-600 font-medium">Tỉnh/Thành phố</p>
                                <p class="text-slate-900 font-semibold">{{ $order->province_name }}</p>
                            </div>
                            <div>
                                <p class="text-slate-600 font-medium">Quận/Huyện</p>
                                <p class="text-slate-900 font-semibold">{{ $order->ward_name }}</p>
                            </div>
                        </div>
                        @if($order->note)
                            <div class="pt-2 border-t border-slate-200">
                                <p class="text-slate-600 font-medium">Ghi chú</p>
                                <p class="text-slate-900">{{ $order->note }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Summary & Actions --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Summary --}}
                <div class="bg-white rounded-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Tóm tắt đơn hàng</h2>
                    <div class="space-y-3 text-sm mb-4 pb-4 border-b border-slate-200">
                        <div class="flex justify-between">
                            <span class="text-slate-600">Tạm tính:</span>
                            <span class="font-semibold text-slate-900">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-red-600">
                                <span>Giảm giá:</span>
                                <span class="font-semibold">-{{ number_format($order->discount, 0, ',', '.') }}₫</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-600">Phí vận chuyển:</span>
                            <span class="font-semibold text-slate-900">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="font-bold text-slate-900">Tổng cộng:</span>
                        <span class="text-3xl font-bold text-slate-900">{{ number_format($order->total, 0, ',', '.') }}<span class="text-lg">₫</span></span>
                    </div>
                </div>

                {{-- Status Cards --}}
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-4">
                    <div>
                        <p class="text-xs text-slate-600 font-semibold mb-2">TRẠNG THÁI ĐƠN HÀNG</p>
                        @if($order->status == 'pending')
                            <span class="status-badge status-badge--pending"><img src="{{ asset('icons/choxacnhan.png') }}" alt="Chờ" class="w-4 h-4 inline mr-1"> Chờ xác nhận</span>
                        @elseif($order->status == 'processing')
                            <span class="status-badge status-badge--processing"><img src="{{ asset('icons/dashboard.png') }}" alt="Xử lý" class="w-4 h-4 inline mr-1"> Đang xử lý</span>
                        @elseif($order->status == 'shipping')
                            <span class="status-badge status-badge--shipping"><img src="{{ asset('icons/giaohang.png') }}" alt="Giao" class="w-4 h-4 inline mr-1"> Đang giao</span>
                        @elseif($order->status == 'completed')
                            <span class="status-badge status-badge--completed"><img src="{{ asset('icons/user.png') }}" alt="Hoàn" class="w-4 h-4 inline mr-1"> Hoàn thành</span>
                        @elseif($order->status == 'canceled')
                            <span class="status-badge status-badge--canceled">✕ Đã hủy</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-600 font-semibold mb-2">TRẠNG THÁI THANH TOÁN</p>
                        @if($order->payment_status == 'unpaid')
                            <span class="status-badge status-badge--unpaid"><img src="{{ asset('icons/doimk.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Chưa thanh toán</span>
                        @elseif($order->payment_status == 'paid')
                            <span class="status-badge status-badge--paid"><img src="{{ asset('icons/user.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Đã thanh toán</span>
                        @elseif($order->payment_status == 'refunded')
                            <span class="status-badge" style="background-color: #dbeafe; color: #1e40af;">Đã hoàn tiền</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                @if(in_array($order->status, ['pending', 'processing']))
                    <form action="{{ route('user.order-cancel', $order->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?');">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                            Hủy đơn hàng
                        </button>
                    </form>
                @else
                    <div class="bg-slate-50 rounded-lg p-4 text-center">
                        <p class="text-sm text-slate-600">
                            @if($order->status == 'completed')
                                ✓ Đơn hàng đã hoàn thành
                            @else
                                Không thể hủy đơn hàng này
                            @endif
                        </p>
                    </div>
                @endif

                {{-- Contact Support --}}
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-blue-900 mb-2">Cần trợ giúp?</p>
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold text-sm">📞 Liên hệ hỗ trợ</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
