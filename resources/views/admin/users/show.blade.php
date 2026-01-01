@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng - HANZO Admin')

@section('content')
<div class="hanzo-container py-8 px-3">
    <div class="mb-8 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Chi tiết khách hàng</h1>
            <p class="text-slate-600">{{ $user->email }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Info & Orders --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- User Info --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-900 mb-4">Thông tin cá nhân</h2>
                <div class="space-y-3 text-sm">
                    <div class="grid grid-cols-2">
                        <span class="text-slate-600">Tên:</span>
                        <span class="font-medium text-slate-900">{{ $user->name }}</span>
                    </div>
                    <div class="grid grid-cols-2">
                        <span class="text-slate-600">Email:</span>
                        <span class="font-medium text-slate-900">{{ $user->email }}</span>
                    </div>
                    <div class="grid grid-cols-2">
                        <span class="text-slate-600">Số điện thoại:</span>
                        <span class="font-medium text-slate-900">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-2">
                        <span class="text-slate-600">Ngày tạo tài khoản:</span>
                        <span class="font-medium text-slate-900">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="grid grid-cols-2">
                        <span class="text-slate-600">Trạng thái:</span>
                        <span class="font-medium text-green-600">Hoạt động</span>
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900">Lịch sử đơn hàng</h2>
                </div>
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Mã đơn hàng</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Ngày</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Tổng tiền</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Trạng thái</th>
                                    <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50">
                                        <td class="px-6 py-3 font-medium text-slate-900">{{ $order->code }}</td>
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
                    <div class="p-6 text-center text-slate-600">
                        Khách hàng này chưa có đơn hàng nào.
                    </div>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Stats Cards --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <p class="text-slate-600 text-sm font-medium mb-2">Tổng đơn hàng</p>
                <p class="text-3xl font-bold text-slate-900">{{ $totalOrders }}</p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <p class="text-slate-600 text-sm font-medium mb-2">Tổng chi tiêu</p>
                <p class="text-3xl font-bold text-slate-900">{{ number_format($totalSpent, 0, ',', '.') }}₫</p>
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <p class="text-slate-600 text-sm font-medium mb-2">Trị giá trung bình</p>
                <p class="text-3xl font-bold text-slate-900">
                    {{ $totalOrders > 0 ? number_format($totalSpent / $totalOrders, 0, ',', '.') : 0 }}₫
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
