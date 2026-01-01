@extends('layouts.app')

@section('title', 'Dashboard - HANZO')

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white min-h-screen py-8">
    <div class="hanzo-container px-3">
        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-4xl font-bold text-slate-900 mb-2">Tài khoản của tôi</h1>
                <p class="text-slate-600">Chào mừng, <span class="font-semibold text-slate-900">{{ Auth::user()->name }}</span>! 👋</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium text-sm">
                    Về trang chủ
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar Navigation --}}
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

                    {{-- Navigation Menu --}}
                    <nav class="divide-y divide-slate-200">
                        <a href="{{ route('user.dashboard') }}" class="account-sidebar__nav-item account-sidebar__nav-item--active flex items-center gap-3 px-6 py-3">
                            <img src="{{ asset('icons/dashboard.png') }}" alt="Dashboard" class="w-5 h-5">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('user.profile') }}" class="account-sidebar__nav-item flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/hoso.png') }}" alt="Hồ sơ" class="w-5 h-5">
                            <span>Hồ sơ cá nhân</span>
                        </a>
                        <a href="{{ route('user.orders') }}" class="account-sidebar__nav-item flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-5 h-5">
                            <span>Đơn hàng</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Total Orders --}}
                    <div class="stats-card bg-white p-6 rounded-lg border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-6 h-6">
                            </div>
                            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">Tổng</span>
                        </div>
                        <p class="stats-card__label text-slate-600 text-sm mb-1">Tổng đơn hàng</p>
                        <p class="stats-card__value text-3xl font-bold text-slate-900">{{ $totalOrders }}</p>
                        <p class="text-xs text-slate-600 mt-2">Tính từ lần đầu</p>
                    </div>

                    {{-- Total Spent --}}
                    <div class="stats-card bg-white p-6 rounded-lg border border-slate-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <img src="{{ asset('icons/thanhtoan.png') }}" alt="Chi tiêu" class="w-6 h-6">
                            </div>
                            <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded">Chi tiêu</span>
                        </div>
                        <p class="stats-card__label text-slate-600 text-sm mb-1">Tổng chi tiêu</p>
                        <p class="stats-card__value text-2xl font-bold text-slate-900">{{ number_format($totalSpent, 0, ',', '.') }}<span class="text-lg">₫</span></p>
                        <p class="text-xs text-slate-600 mt-2">{{ $totalOrders > 0 ? 'TB: ' . number_format($totalSpent / $totalOrders, 0, ',', '.') . '₫' : 'N/A' }}</p>
                    </div>

                </div>

                {{-- Recent Orders Section --}}
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                    <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Đơn hàng gần đây</h2>
                            <p class="text-sm text-slate-600 mt-1">{{ $recentOrders->count() }}/{{ $totalOrders }} đơn hàng mới nhất</p>
                        </div>
                        <a href="{{ route('user.orders') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium text-sm">
                            Xem tất cả
                            <span>→</span>
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="order-table">
                                <thead>
                                    <tr class="border-b border-slate-200 bg-slate-50">
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Mã đơn hàng</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Ngày tạo</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Tổng tiền</th>
                                        <th class="px-6 py-3 text-left font-semibold text-slate-900">Trạng thái</th>
                                        <th class="px-6 py-3 text-center font-semibold text-slate-900">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-semibold text-slate-900">
                                                <span class="font-mono text-blue-600">{{ $order->code }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600">
                                                {{ $order->created_at->format('d/m/Y') }}
                                                <span class="block text-xs text-slate-500">{{ $order->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td class="px-6 py-4 font-bold text-slate-900">
                                                {{ number_format($order->total, 0, ',', '.') }}₫
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $status = strtolower($order->status);
                                                    $statusMap = [
                                                        'pending' => ['label' => 'Chờ xác nhận', 'class' => 'status-badge status-badge--pending', 'icon' => 'icons/choxacnhan.png'],
                                                        'processing' => ['label' => 'Đang xử lý', 'class' => 'status-badge status-badge--processing', 'icon' => 'icons/dashboard.png'],
                                                        'shipping' => ['label' => 'Đang giao', 'class' => 'status-badge status-badge--shipping', 'icon' => 'icons/giaohang.png'],
                                                        'completed' => ['label' => 'Hoàn thành', 'class' => 'status-badge status-badge--completed', 'icon' => 'icons/user.png'],
                                                        'canceled' => ['label' => 'Đã hủy', 'class' => 'status-badge status-badge--canceled', 'icon' => null],
                                                    ];
                                                    $statusBadge = $statusMap[$status] ?? ['label' => ucfirst($order->status), 'class' => 'status-badge', 'icon' => null];
                                                @endphp
                                                <span class="{{ $statusBadge['class'] }}">
                                                    @if($statusBadge['icon'])
                                                        <img src="{{ asset($statusBadge['icon']) }}" alt="{{ $statusBadge['label'] }}" class="w-4 h-4 inline mr-1">
                                                    @endif
                                                    {{ $statusBadge['label'] }}
                                                </span>
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
                    @else
                        <div class="empty-state">
                            <div class="empty-state__icon">📭</div>
                            <h3 class="empty-state__title">Chưa có đơn hàng</h3>
                            <p class="empty-state__description">Bạn chưa mua sắm trên HANZO. Hãy bắt đầu mua sắm ngay!</p>
                            <a href="{{ route('products.index') }}" class="inline-block px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                                Bắt đầu mua sắm
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="{{ route('user.profile') }}" class="bg-white border border-slate-200 rounded-lg p-4 hover:border-slate-300 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">Chỉnh sửa hồ sơ</p>
                                <p class="text-sm text-slate-600">Cập nhật thông tin cá nhân</p>
                            </div>
                            <span class="text-2xl">→</span>
                        </div>
                    </a>
                    <a href="{{ route('user.profile') }}" class="bg-white border border-slate-200 rounded-lg p-4 hover:border-slate-300 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">Đổi mật khẩu</p>
                                <p class="text-sm text-slate-600">Bảo mật tài khoản</p>
                            </div>
                            <span class="text-2xl">→</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
