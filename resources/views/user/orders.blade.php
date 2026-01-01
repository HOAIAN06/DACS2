@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - HANZO')

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white min-h-screen py-8">
    <div class="hanzo-container px-3">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Lịch sử đơn hàng</h1>
            <p class="text-slate-600">Theo dõi tất cả đơn hàng của bạn</p>
        </div>

        {{-- Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden sticky top-8">
                    {{-- Profile Section --}}
                    <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <p class="font-semibold text-lg mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-white/70 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Navigation --}}
                    <nav class="divide-y divide-slate-200">
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/dashboard.png') }}" alt="Dashboard" class="w-5 h-5">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/hoso.png') }}" alt="Hồ sơ" class="w-5 h-5">
                            <span>Hồ sơ cá nhân</span>
                        </a>
                        <a href="{{ route('user.orders') }}" class="flex items-center gap-3 px-6 py-3 text-slate-900 font-medium bg-slate-50 border-l-4 border-slate-900">
                            <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-5 h-5">
                            <span>Đơn hàng</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                @if (session('success'))
                    <div class="alert alert--success mb-6">
                        <p class="font-medium">✓ {{ session('success') }}</p>
                    </div>
                @endif

                @if($orders->count() > 0)
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                        {{-- Stats Header --}}
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200 px-6 py-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm text-slate-600 font-medium">Tổng đơn hàng</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $orders->total() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-600 font-medium">Trang hiện tại</p>
                                <p class="text-2xl font-bold text-slate-900">{{ $orders->currentPage() }} / {{ $orders->lastPage() }}</p>
                            </div>
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto">
                            <table class="order-table">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Mã đơn hàng</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Ngày tạo</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Tổng tiền</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Trạng thái</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Thanh toán</th>
                                        <th class="px-6 py-3 text-center font-semibold text-slate-900">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-semibold text-slate-900">
                                                <span class="font-mono text-blue-600 bg-blue-50 px-3 py-1 rounded">{{ $order->code }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600">
                                                {{ $order->created_at->format('d/m/Y') }}
                                                <span class="block text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                {{ number_format($order->total, 0, ',', '.') }}₫
                                            </td>
                                            <td class="px-6 py-4">
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
                                            <td class="px-6 py-4">
                                                @if($order->payment_status == 'unpaid')
                                                    <span class="status-badge status-badge--unpaid"><img src="{{ asset('icons/doimk.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Chưa thanh toán</span>
                                                @elseif($order->payment_status == 'paid')
                                                    <span class="status-badge status-badge--paid"><img src="{{ asset('icons/user.png') }}" alt="Thanh toán" class="w-4 h-4 inline mr-1"> Đã thanh toán</span>
                                                @elseif($order->payment_status == 'refunded')
                                                    <span class="status-badge" style="background-color: #dbeafe; color: #1e40af;">Đã hoàn tiền</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <a href="{{ route('user.order-detail', $order->id) }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-sm hover:underline">
                                                    Chi tiết
                                                    <span>→</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="p-6 border-t border-slate-200">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-sm text-slate-600">
                                    Hiển thị <span class="font-semibold">{{ $orders->count() }}</span> đơn hàng
                                </p>
                            </div>
                            <div class="flex justify-center">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                        <div class="empty-state">
                            <div class="empty-state__icon">📭</div>
                            <h3 class="empty-state__title">Chưa có đơn hàng</h3>
                            <p class="empty-state__description">Bạn chưa tạo bất kỳ đơn hàng nào. Hãy bắt đầu mua sắm ngay!</p>
                            <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-semibold">
                                Khám phá sản phẩm
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
