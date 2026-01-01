@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - HANZO')

@section('content')
<div class="hanzo-container py-8 px-3">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Quản lý đơn hàng</h1>
            <p class="text-slate-600">Tất cả đơn hàng của cửa hàng</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" placeholder="Tìm mã đơn hàng, tên khách..." value="{{ request('search') }}"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900">
            </div>
            <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Đang giao</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                <option value="canceled" {{ request('status') === 'canceled' ? 'selected' : '' }}>Đã hủy</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
                Lọc
            </button>
        </form>
    </div>

    {{-- Orders Table --}}
    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
        @if($orders->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Mã đơn hàng</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Khách hàng</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Ngày tạo</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Tổng tiền</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Trạng thái</th>
                            <th class="text-left px-6 py-3 font-semibold text-slate-900">Thanh toán</th>
                            <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b border-slate-200 hover:bg-slate-50">
                                <td class="px-6 py-3 font-medium text-slate-900">{{ $order->code }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $order->user->name ?? 'Khách lạ' }}</td>
                                <td class="px-6 py-3 text-slate-600">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-3 font-medium text-slate-900">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                <td class="px-6 py-3">
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
                                </td>
                                <td class="px-6 py-3">
                                    @if($order->payment_status == 'unpaid')
                                        <span class="status-badge status-badge--unpaid"><img src="{{ asset('icons/doimk.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Chưa thanh toán</span>
                                    @elseif($order->payment_status == 'paid')
                                        <span class="status-badge status-badge--paid"><img src="{{ asset('icons/user.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Đã thanh toán</span>
                                    @elseif($order->payment_status == 'refunded')
                                        <span class="status-badge" style="background-color: #dbeafe; color: #1e40af;">Đã hoàn tiền</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">Chi tiết</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-slate-200">
                {{ $orders->links() }}
            </div>
        @else
            <div class="p-12 text-center text-slate-600">
                Không tìm thấy đơn hàng nào.
            </div>
        @endif
    </div>
</div>
@endsection
