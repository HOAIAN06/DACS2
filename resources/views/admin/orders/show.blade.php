@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng - HANZO Admin')

@section('content')
<div class="hanzo-container py-8 px-3">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Chi tiết đơn hàng</h1>
            <p class="text-slate-600">Mã: <span class="font-bold">{{ $order->code }}</span></p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Order Items & Shipping --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Items --}}
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">Sản phẩm đã mua</h2>
                </div>
                <div class="divide-y divide-slate-200">
                    @foreach($order->items as $item)
                        <div class="p-6 flex gap-4">
                            <div class="flex-1">
                                <p class="font-medium text-slate-900 mb-1">{{ $item->product_name }}</p>
                                <p class="text-sm text-slate-600 mb-2">
                                    @if($item->size)
                                        Size: {{ $item->size }}
                                    @endif
                                    @if($item->color)
                                        | Màu: {{ $item->color }}
                                    @endif
                                </p>
                                <p class="text-sm text-slate-600">Số lượng: {{ $item->qty }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-600 mb-1">Đơn giá</p>
                                <p class="font-medium text-slate-900">{{ number_format($item->unit_price, 0, ',', '.') }}₫</p>
                                <p class="text-sm text-slate-600 mt-2">Tổng</p>
                                <p class="font-bold text-slate-900">{{ number_format($item->line_total, 0, ',', '.') }}₫</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Shipping Info --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Thông tin giao hàng</h2>
                <div class="space-y-2 text-sm">
                    <p><span class="text-slate-600">Tên người nhận:</span> <span class="font-medium text-slate-900">{{ $order->shipping_name }}</span></p>
                    <p><span class="text-slate-600">Email:</span> <span class="font-medium text-slate-900">{{ $order->shipping_email }}</span></p>
                    <p><span class="text-slate-600">Số điện thoại:</span> <span class="font-medium text-slate-900">{{ $order->shipping_phone }}</span></p>
                    <p><span class="text-slate-600">Địa chỉ:</span> <span class="font-medium text-slate-900">{{ $order->shipping_address }}</span></p>
                    <p><span class="text-slate-600">Tỉnh/Thành phố:</span> <span class="font-medium text-slate-900">{{ $order->province_name }}</span></p>
                    <p><span class="text-slate-600">Quận/Huyện:</span> <span class="font-medium text-slate-900">{{ $order->ward_name }}</span></p>
                    @if($order->note)
                        <p><span class="text-slate-600">Ghi chú:</span> <span class="font-medium text-slate-900">{{ $order->note }}</span></p>
                    @endif
                </div>
            </div>

            {{-- Customer Info --}}
            @if($order->user)
                <div class="bg-white rounded-lg border border-slate-200 p-6">
                    <h2 class="text-lg font-bold text-slate-900 mb-4">Thông tin khách hàng</h2>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-slate-600">Tên:</span> <span class="font-medium text-slate-900">{{ $order->user->name }}</span></p>
                        <p><span class="text-slate-600">Email:</span> <span class="font-medium text-slate-900">{{ $order->user->email }}</span></p>
                        <p><span class="text-slate-600">Số điện thoại:</span> <span class="font-medium text-slate-900">{{ $order->user->phone ?? 'N/A' }}</span></p>
                        <p><span class="text-slate-600">Ngày tạo tài khoản:</span> <span class="font-medium text-slate-900">{{ $order->user->created_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Summary & Actions --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Summary --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Tóm tắt</h2>
                <div class="space-y-3 text-sm mb-4 pb-4 border-b border-slate-200">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Tạm tính:</span>
                        <span class="font-medium text-slate-900">{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-red-600">
                            <span>Giảm giá:</span>
                            <span class="font-medium">-{{ number_format($order->discount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-slate-600">Phí vận chuyển:</span>
                        <span class="font-medium text-slate-900">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold text-slate-900">Tổng cộng:</span>
                    <span class="font-bold text-lg text-slate-900">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                </div>
            </div>

            {{-- Status Update --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Cập nhật trạng thái</h2>

                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900 mb-2">
                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipping" {{ $order->status === 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="canceled" {{ $order->status === 'canceled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">
                        Cập nhật
                    </button>
                </form>

                <div class="text-sm">
                    <p class="text-slate-600 mb-2">Trạng thái hiện tại:</p>
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
            </div>

            {{-- Payment Status --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Trạng thái thanh toán</h2>

                <form action="{{ route('admin.orders.update-payment-status', $order->id) }}" method="POST" class="mb-4">
                    @csrf
                    @method('PATCH')
                    <select name="payment_status" class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900 mb-2">
                            <option value="unpaid" {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">
                        Cập nhật
                    </button>
                </form>

                <div class="text-sm">
                    <p class="text-slate-600 mb-2">Hiện tại:</p>
                    @if($order->payment_status == 'unpaid')
                        <span class="status-badge status-badge--unpaid"><img src="{{ asset('icons/doimk.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Chưa thanh toán</span>
                    @elseif($order->payment_status == 'paid')
                        <span class="status-badge status-badge--paid"><img src="{{ asset('icons/user.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Đã thanh toán</span>
                    @elseif($order->payment_status == 'refunded')
                        <span class="status-badge" style="background-color: #dbeafe; color: #1e40af;">Đã hoàn tiền</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
