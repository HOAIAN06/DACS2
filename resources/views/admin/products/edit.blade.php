@extends('layouts.admin')

@section('title', 'Sửa sản phẩm - HANZO')

@section('content')
<div class="mb-8 flex justify-between items-start">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Sửa sản phẩm</h1>
        <p class="text-slate-600">{{ $product->name }}</p>
    </div>
    <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">← Quay lại</a>
</div>

<div class="bg-white rounded-lg border border-slate-200 p-6">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Tên sản phẩm</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" value="{{ $product->name }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Danh mục</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Giá</label>
                <input type="number" name="price" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" value="{{ $product->price }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Kho hàng <span class="text-xs text-slate-500">(tự động tính từ biến thể)</span></label>
                <input type="number" name="stock" readonly class="w-full px-4 py-2 border border-slate-300 rounded-lg bg-slate-50 text-slate-600" value="{{ $product->stock ?? 0 }}">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Mô tả</label>
            <textarea name="description" rows="5" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900">{{ $product->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Ảnh chính @if(!$product->images()->where('is_main', 1)->exists())<span class="text-red-600">* (bắt buộc)</span>@endif</label>
            <input type="file" name="main_image" accept="image/*" {{ !$product->images()->where('is_main', 1)->exists() ? 'required' : '' }} class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" onchange="previewMainImage(event)">
            @if($product->mainImage)
                <div class="mt-3">
                    <p class="text-sm text-slate-600 mb-2">Ảnh chính hiện tại:</p>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $product->mainImage->image_url) }}" alt="Main" class="h-48 rounded-lg border border-slate-200 object-cover">
                        <button type="button" onclick="deleteImage({{ $product->mainImage->id }}, this)" class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-2 hover:bg-red-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif
            <div id="mainImagePreview" class="mt-3 hidden">
                <p class="text-sm text-slate-600 mb-2">Ảnh chính mới:</p>
                <img id="mainPreview" src="" alt="Preview" class="h-48 rounded-lg border border-slate-200 object-cover">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-900 mb-2">Ảnh phụ</label>
            <input type="file" name="additional_images[]" accept="image/*" multiple class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900" onchange="previewAdditionalImages(event)">
            <p class="text-xs text-slate-500 mt-1">Có thể chọn nhiều ảnh cùng lúc</p>
            
            @if($product->images()->where('is_main', 0)->exists())
                <div class="mt-3">
                    <p class="text-sm text-slate-600 mb-2">Ảnh phụ hiện tại:</p>
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($product->images()->where('is_main', 0)->get() as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image->image_url) }}" alt="Additional" class="h-32 rounded-lg border border-slate-200 object-cover w-full">
                                <button type="button" onclick="deleteImage({{ $image->id }}, this)" class="absolute top-1 right-1 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div id="additionalImagesPreview" class="mt-3 grid grid-cols-4 gap-3"></div>
        </div>

        <div class="border-t border-slate-200 pt-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Biến thể sản phẩm (Màu sắc & Kích cỡ)</h3>
            <div id="variantsContainer">
                @forelse($product->variants as $index => $variant)
                    <div class="variant-item border border-slate-200 rounded-lg p-4 mb-3" data-variant-id="{{ $variant->id }}">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-sm font-medium text-slate-600">Biến thể #{{ $index + 1 }}</span>
                            <button type="button" onclick="deleteVariant({{ $variant->id }}, this)" class="text-red-600 hover:text-red-700 text-sm">Xóa</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Màu sắc</label>
                                <input type="text" name="existing_variants[{{ $variant->id }}][color]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" value="{{ $variant->color }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Kích cỡ</label>
                                <input type="text" name="existing_variants[{{ $variant->id }}][size]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" value="{{ $variant->size }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Giá</label>
                                <input type="number" name="existing_variants[{{ $variant->id }}][price]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" value="{{ $variant->price }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng</label>
                                <input type="number" name="existing_variants[{{ $variant->id }}][stock]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" value="{{ $variant->stock }}" onchange="calculateTotalStock()" oninput="calculateTotalStock()">
                            </div>
                        </div>
                    </div>
                @empty
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
                                <label class="block text-sm font-medium text-slate-700 mb-1">Giá</label>
                                <input type="number" name="variants[0][price]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng</label>
                                <input type="number" name="variants[0][stock]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0" value="0" onchange="calculateTotalStock()" oninput="calculateTotalStock()">
                            </div>
                        </div>
                    </div>
                @endforelse
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
                    img.className = 'h-32 rounded-lg border border-slate-200 object-cover w-full';
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        }

        function deleteImage(imageId, button) {
            if (confirm('Xóa ảnh này?')) {
                fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.closest('div').remove();
                    } else {
                        alert('Không thể xóa ảnh');
                    }
                })
                .catch(error => {
                    alert('Lỗi: ' + error);
                });
            }
        }
        let variantIndex = {{ $product->variants->count() }};
        
        function calculateTotalStock() {
            const stockInputs = document.querySelectorAll('input[name*="[stock]"]');
            let total = 0;
            stockInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.querySelector('input[name="stock"]').value = total;
        }

        document.addEventListener('DOMContentLoaded', calculateTotalStock);
        
        function addVariant() {
            const container = document.getElementById('variantsContainer');
            const variantHtml = `
                <div class="variant-item border border-slate-200 rounded-lg p-4 mb-3">
                    <div class="flex justify-between items-start mb-3">
                        <span class="text-sm font-medium text-slate-600">Biến thể mới</span>
                        <button type="button" onclick="removeNewVariant(this)" class="text-red-600 hover:text-red-700 text-sm">Xóa</button>
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
                            <input type="number" name="variants[${variantIndex}][stock]" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm" placeholder="0" value="0" onchange="calculateTotalStock()" oninput="calculateTotalStock()">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', variantHtml);
            variantIndex++;
        }

        function removeNewVariant(button) {
            button.closest('.variant-item').remove();
            calculateTotalStock();
        }

        function deleteVariant(variantId, button) {
            if (confirm('Xóa biến thể này?')) {
                fetch(`/admin/products/variants/${variantId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.closest('.variant-item').remove();
                    } else {
                        alert('Không thể xóa biến thể');
                    }
                })
                .catch(error => {
                    alert('Lỗi: ' + error);
                });
            }
        }
        </script>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-2 bg-slate-900 text-white rounded-lg font-medium hover:bg-slate-800 transition">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="px-6 py-2 border border-slate-300 text-slate-900 rounded-lg font-medium hover:bg-slate-50 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
