@extends('layouts.admin')

@section('title', 'Admin Dashboard - HANZO')

@section('content')
<div class="hanzo-container py-8 px-3">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Admin Dashboard</h1>
        <p class="text-slate-600">Chào mừng {{ Auth::user()->name }}!</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Tổng sản phẩm</p>
            <p class="text-3xl font-bold text-slate-900">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Tổng danh mục</p>
            <p class="text-3xl font-bold text-slate-900">{{ $totalCategories }}</p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Tổng khách hàng</p>
            <p class="text-3xl font-bold text-slate-900">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Tổng đơn hàng</p>
            <p class="text-3xl font-bold text-slate-900">{{ $totalOrders }}</p>
        </div>
    </div>

    {{-- Revenue Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Doanh thu tổng cộng</p>
            <p class="text-3xl font-bold text-slate-900">{{ number_format($totalRevenue, 0, ',', '.') }}₫</p>
        </div>
        <div class="bg-white rounded-lg border border-slate-200 p-6">
            <p class="text-slate-600 text-sm font-medium mb-2">Doanh thu tháng này</p>
            <p class="text-3xl font-bold text-green-600">{{ number_format($monthlyRevenue, 0, ',', '.') }}₫</p>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-lg border border-slate-200 p-6 mb-8">
        <h2 class="text-lg font-bold text-slate-900 mb-6">Biểu đồ doanh thu 12 tháng gần nhất</h2>
        <div class="relative" style="height: 350px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Content Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="space-y-6 xl:col-span-2">
            {{-- New Orders --}}
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Đơn hàng mới</h2>
                        <p class="text-sm text-slate-500">5 đơn gần nhất đang chờ xử lý</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Xem tất cả →</a>
                </div>

                @if($newOrders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 bg-slate-50">
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Mã đơn</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Khách</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Tổng tiền</th>
                                    <th class="text-left px-6 py-3 font-semibold text-slate-900">Trạng thái</th>
                                    <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($newOrders as $order)
                                    @php
                                        $status = strtolower($order->status);
                                        $statusMap = [
                                            'pending' => ['label' => 'Chờ xác nhận', 'class' => 'bg-amber-100 text-amber-800 border border-amber-200'],
                                            'processing' => ['label' => 'Đang xử lý', 'class' => 'bg-blue-100 text-blue-800 border border-blue-200'],
                                            'shipping' => ['label' => 'Đang giao', 'class' => 'bg-sky-100 text-sky-800 border border-sky-200'],
                                            'completed' => ['label' => 'Hoàn thành', 'class' => 'bg-emerald-100 text-emerald-800 border border-emerald-200'],
                                            'canceled' => ['label' => 'Đã hủy', 'class' => 'bg-red-100 text-red-700 border border-red-200'],
                                        ];
                                        $statusBadge = $statusMap[$status] ?? ['label' => ucfirst($order->status), 'class' => 'bg-slate-100 text-slate-700 border border-slate-200'];
                                    @endphp
                                    <tr class="border-b border-slate-200 last:border-0 hover:bg-slate-50">
                                        <td class="px-6 py-3 font-medium text-slate-900">{{ $order->code }}</td>
                                        <td class="px-6 py-3 text-slate-600">{{ $order->user->name ?? 'Khách lạ' }}</td>
                                        <td class="px-6 py-3 font-medium text-slate-900">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                                        <td class="px-6 py-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                                        </td>
                                        <td class="px-6 py-3 text-center">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600 hover:text-blue-700 font-medium">Chi tiết</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center text-slate-600">Không có đơn hàng mới</div>
                @endif
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Hành động nhanh</h3>
                    <span class="text-xs text-slate-500">Tạo / duyệt / quản lý</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <a href="{{ route('admin.products.create') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800 transition">+ Thêm sản phẩm</a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-slate-50 text-slate-900 rounded-lg font-semibold border border-slate-200 hover:bg-slate-100 transition">📋 Duyệt đơn</a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-slate-50 text-slate-900 rounded-lg font-semibold border border-slate-200 hover:bg-slate-100 transition">📂 Danh mục</a>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Top Products --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Top 5 Sản phẩm</h3>
                <div class="space-y-3">
                    @forelse($topProducts as $index => $product)
                        <div class="flex items-start justify-between border-b border-slate-200 pb-3 last:border-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">#{{ $index + 1 }} {{ $product->name }}</p>
                                <p class="text-xs text-slate-500">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                            </div>
                            <span class="text-[11px] px-2 py-1 rounded-full bg-slate-100 text-slate-700 border border-slate-200">SKU: {{ $product->id }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-600">Chưa có sản phẩm</p>
                    @endforelse
                </div>
            </div>

            {{-- New Customers --}}
            <div class="bg-white rounded-lg border border-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Khách mới</h3>
                <div class="space-y-3">
                    @forelse($newUsers as $user)
                        <div class="flex items-start justify-between border-b border-slate-200 pb-3 last:border-0 last:pb-0">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                            <span class="text-[11px] px-2 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">Mới</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-600">Chưa có khách mới</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Doanh thu (₫)',
                data: @json($revenueData),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 13,
                            weight: '600'
                        },
                        color: '#334155',
                        padding: 15
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(148, 163, 184, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#64748b',
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value) + '₫';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#64748b'
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
