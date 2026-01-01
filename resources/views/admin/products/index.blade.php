@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm - HANZO')

@section('content')

{{-- Discount Modal --}}
<div id="discountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-none p-6 max-w-sm w-full mx-4 shadow-2xl border border-slate-200">
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Áp dụng giảm giá</h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Nhập % giảm giá (0 - 100)</label>
                <div class="flex gap-2">
                    <input type="number" id="discountInput" 
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-none focus:outline-none focus:border-slate-900" 
                        min="0" max="100" value="10" placeholder="Nhập số %">
                    <span class="flex items-center text-slate-600 font-semibold">%</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">Nhập số từ 0 đến 100</p>
            </div>

            <div class="pt-2">
                <label class="block text-sm font-medium text-slate-700 mb-2">Giá dự kiến</label>
                <div class="bg-slate-50 p-3 rounded-none border border-slate-200">
                    <p class="text-sm text-slate-600">Giá gốc: <span id="originalPrice" class="font-semibold text-slate-900">0₫</span></p>
                    <p class="text-sm text-slate-600 mt-1">Giá sau giảm: <span id="newPrice" class="font-semibold text-green-600">0₫</span></p>
                    <p class="text-sm text-slate-600 mt-1">Tiết kiệm: <span id="savedAmount" class="font-semibold text-red-600">0₫</span></p>
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" id="discountConfirm" class="flex-1 px-4 py-2 bg-slate-900 text-white rounded-none font-medium hover:bg-slate-800 transition">
                Áp dụng
            </button>
            <button type="button" id="discountCancel" class="flex-1 px-4 py-2 border border-slate-300 text-slate-900 rounded-none font-medium hover:bg-slate-50 transition">
                Hủy
            </button>
        </div>
    </div>
</div>

{{-- Success Toast --}}
<div id="discountSuccessToast" class="hidden fixed top-6 right-6 z-50">
    <div class="bg-white border border-green-200 text-green-700 shadow-lg rounded-none px-4 py-3 flex items-start gap-3 w-72">
        <div class="mt-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold">Áp dụng giảm giá thành công</p>
            <p class="text-xs text-slate-600" id="discountSuccessText">Đã cập nhật giá mới.</p>
        </div>
    </div>
</div>


<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Quản lý sản phẩm</h1>
        <p class="text-slate-600">Tất cả sản phẩm của cửa hàng</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">+ Thêm sản phẩm</a>
</div>

{{-- Filter Form --}}
<div class="bg-white rounded-lg border border-slate-200 p-6 mb-6">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text" name="search" placeholder="Tìm tên sản phẩm..." value="{{ request('search') }}"
                class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:border-slate-900">
        </div>
        <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg text-sm font-medium hover:bg-slate-800 transition">
            Lọc
        </button>
    </form>
</div>

{{-- Products Table --}}
<div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50">
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Sản phẩm</th>
                        <th class="text-right px-6 py-3 font-semibold text-slate-900">Giá</th>
                        <th class="text-right px-6 py-3 font-semibold text-slate-900">Giá mới</th>
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Danh mục</th>
                        <th class="text-left px-6 py-3 font-semibold text-slate-900">Kho</th>
                        <th class="text-center px-6 py-3 font-semibold text-slate-900">Hiển thị</th>
                        <th class="text-center px-6 py-3 font-semibold text-slate-900">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr class="border-b border-slate-200 hover:bg-slate-50">
                            <td class="px-6 py-3 font-medium text-slate-900">{{ $product->name }}</td>
                            <td class="px-6 py-3 text-right text-slate-900 font-semibold cursor-pointer hover:text-blue-700"
                                data-price-cell
                                data-product-id="{{ $product->id }}"
                                data-price="{{ $product->price }}">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-3 text-right text-slate-900 font-semibold" data-new-price="{{ $product->id }}">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </td>
                            <td class="px-6 py-3 text-slate-600">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="px-6 py-3 text-slate-600">{{ $product->stock }}</td>
                            <td class="px-6 py-3 text-center">
                                <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PATCH')
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" onchange="this.form.submit()" {{ $product->is_active ? 'checked' : '' }}>
                                        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    </label>
                                </form>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-700 font-medium text-xs">Sửa</a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        {{-- Preserve query parameters --}}
                                        @foreach(request()->query() as $key => $value)
                                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                        @endforeach
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium text-xs" data-confirm-delete="Bạn có chắc muốn xóa sản phẩm '{{ $product->name }}'?">Xóa</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200">
            {{ $products->links('vendor.pagination.hanzo') }}
        </div>
    @else
        <div class="p-6 text-center text-slate-600">
            Không có sản phẩm nào
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('discountModal');
    const discountInput = document.getElementById('discountInput');
    const confirmBtn = document.getElementById('discountConfirm');
    const cancelBtn = document.getElementById('discountCancel');
    const toast = document.getElementById('discountSuccessToast');
    const toastText = document.getElementById('discountSuccessText');
    
    let currentProductId = null;
    let currentBasePrice = null;
    let currentPriceCell = null;

    // Hàm tính giá sau giảm
    function calculateDiscountedPrice() {
        const percent = Math.max(0, Math.min(100, parseFloat(discountInput.value) || 0));
        const discounted = currentBasePrice * (100 - percent) / 100;
        const saved = currentBasePrice - discounted;

        document.getElementById('originalPrice').textContent = currentBasePrice.toLocaleString('vi-VN') + '₫';
        document.getElementById('newPrice').textContent = Math.round(discounted).toLocaleString('vi-VN') + '₫';
        document.getElementById('savedAmount').textContent = Math.round(saved).toLocaleString('vi-VN') + '₫';
    }

    // Update giá dự kiến khi người dùng nhập
    discountInput.addEventListener('input', calculateDiscountedPrice);
    discountInput.addEventListener('change', calculateDiscountedPrice);

    // Mở modal khi click vào ô giá
    const priceCells = document.querySelectorAll('[data-price-cell]');
    priceCells.forEach(cell => {
        cell.addEventListener('click', function() {
            currentProductId = this.getAttribute('data-product-id');
            currentBasePrice = parseFloat(this.getAttribute('data-price')) || 0;
            currentPriceCell = document.querySelector(`[data-new-price="${currentProductId}"]`);
            
            if (!currentPriceCell || currentBasePrice <= 0) return;

            discountInput.value = '10';
            calculateDiscountedPrice();
            modal.classList.remove('hidden');
            discountInput.focus();
        });
    });

    // Đóng modal
    cancelBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    // Xác nhận áp dụng giảm giá
    confirmBtn.addEventListener('click', function() {
        const percent = Math.max(0, Math.min(100, parseFloat(discountInput.value) || 0));

        fetch(`/admin/products/${currentProductId}/apply-discount`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ discount_percent: percent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const discounted = data.new_price;
                
                // Cập nhật hiển thị
                currentPriceCell.textContent = discounted.toLocaleString('vi-VN') + '₫';

                // Thêm badge phần trăm
                const priceRow = currentPriceCell.closest('tr');
                const priceOriginalCell = priceRow.querySelector('[data-price-cell]');
                const existingBadge = priceOriginalCell.querySelector('.hz-discount-badge');
                
                if (percent > 0) {
                    if (!existingBadge) {
                        const badge = document.createElement('span');
                        badge.className = 'hz-discount-badge ml-2 inline-flex items-center rounded-full bg-red-100 text-red-700 text-xs font-semibold px-2 py-0.5';
                        badge.textContent = `-${percent}%`;
                        priceOriginalCell.appendChild(badge);
                    } else {
                        existingBadge.textContent = `-${percent}%`;
                    }
                } else if (existingBadge) {
                    existingBadge.remove();
                }

                modal.classList.add('hidden');
                toastText.textContent = `Giá mới: ${discounted.toLocaleString('vi-VN')}₫ (-${percent}%)`;
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 2500);
            } else {
                alert('Lỗi khi áp dụng giảm giá!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    });

    // Đóng modal khi click ngoài
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endpush
