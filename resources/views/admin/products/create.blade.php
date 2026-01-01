@extends('layouts.admin')

@section('title', 'Thêm sản phẩm - HANZO')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Thêm sản phẩm mới</h1>
        <p class="text-slate-600">Tạo sản phẩm cho cửa hàng</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Tên sản phẩm</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="Nhập tên sản phẩm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Danh mục</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900">
                    <option value="">Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Giá</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="0">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Kho hàng <span class="text-xs text-slate-500">(tự động tính từ biến thể)</span></label>
                <input type="number" name="stock" readonly class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-600" placeholder="0" value="0">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Mô tả</label>
            <textarea name="description" rows="5" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" placeholder="Mô tả sản phẩm"></textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Ảnh chính (bắt buộc) <span class="text-red-600">*</span></label>
            <input type="file" name="main_image" accept="image/*" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" onchange="previewMainImage(event)">
            <div id="mainImagePreview" class="mt-3 hidden">
                <img id="mainPreview" src="" alt="Main Preview" class="h-48 rounded-lg border border-slate-200 object-cover">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Ảnh phụ (tùy chọn)</label>
            <input type="file" name="additional_images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" onchange="previewAdditionalImages(event)">
            <div id="additionalImagesPreview" class="mt-3 grid grid-cols-4 gap-3"></div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Biến thể sản phẩm (Màu sắc & Kích cỡ)</h3>
            <div id="variantsContainer">
                <div class="variant-item border border-slate-200 rounded-lg p-4 mb-3">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Màu sắc</label>
                            <input type="text" name="variants[0][color]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="VD: Đen, Trắng">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kích cỡ</label>
                            <input type="text" name="variants[0][size]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="VD: S, M, L, XL">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá (để trống = giá gốc)</label>
                            <input type="number" name="variants[0][price]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng</label>
                            <input type="number" name="variants[0][stock]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0" value="0">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addVariant()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">+ Thêm biến thể</button>
        </div>

        <script>
        function previewMainImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('mainPreview').src = e.target.result;
                    document.getElementById('mainImagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }

        function previewAdditionalImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('additionalImagesPreview');
            previewContainer.innerHTML = '';
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-32 rounded-lg border border-slate-200 object-cover';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }

        let variantIndex = 1;
        
        function calculateTotalStock() {
            const stockInputs = document.querySelectorAll('input[name*="[stock]"]');
            let total = 0;
            stockInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.querySelector('input[name="stock"]').value = total;
        }
        
        function addVariant() {
            const container = document.getElementById('variantsContainer');
            const variantHtml = `
                <div class="variant-item border border-slate-200 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-sm font-medium text-slate-600">Biến thể #${variantIndex + 1}</span>
                        <button type="button" onclick="removeVariant(this)" class="text-red-600 hover:text-red-700 text-sm">Xóa</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Màu sắc</label>
                            <input type="text" name="variants[${variantIndex}][color]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="VD: Đen, Trắng">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Kích cỡ</label>
                            <input type="text" name="variants[${variantIndex}][size]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="VD: S, M, L, XL">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Giá</label>
                            <input type="number" name="variants[${variantIndex}][price]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng</label>
                            <input type="number" name="variants[${variantIndex}][stock]" class="variant-stock w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0" value="0" onchange="calculateTotalStock()" oninput="calculateTotalStock()">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', variantHtml);
            variantIndex++;
        }

        function removeVariant(button) {
            button.closest('.variant-item').remove();
            calculateTotalStock();
        }
        
        // Tính tổng stock ban đầu
        document.addEventListener('DOMContentLoaded', calculateTotalStock);
        </script>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">Thêm sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-slate-300 text-slate-900 rounded-lg font-medium hover:bg-slate-50 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
