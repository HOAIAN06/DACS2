@extends('layouts.app')

@section('title', 'Chỉnh sửa đánh giá - ' . $product->name . ' - HANZO')

@section('content')
<div class="max-w-4xl mx-auto px-4 md:px-6 lg:px-8 py-8 md:py-12">
    <div class="mb-8">
        <a href="{{ route('product.show', $product->slug) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">← Quay lại sản phẩm</a>
        <h1 class="text-3xl font-bold text-slate-900 mt-4 mb-2">Chỉnh sửa đánh giá</h1>
        <p class="text-slate-600">Sản phẩm: <span class="font-semibold">{{ $product->name }}</span></p>
    </div>

    <div class="bg-white rounded-lg border border-slate-200 p-6 md:p-8">
        @if($errors->any())
            <div class="hz-alert hz-alert--error mb-6">
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('product.reviews.update', [$product->id, $review->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Đánh giá sao --}}
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-3">Đánh giá của bạn</label>
                <div class="hz-rating-input" data-hz-rating>
                    @for($i = 1; $i <= 5; $i++)
                        <label title="{{ $i }} sao">
                            <input type="radio" name="rating" value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                            <span>★</span>
                        </label>
                    @endfor
                </div>
            </div>

            {{-- Tiêu đề và nội dung --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Tiêu đề (tùy chọn)</label>
                    <input type="text" name="title" value="{{ old('title', $review->title) }}" class="hz-input" placeholder="Ví dụ: Chất vải mát, form chuẩn">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-2">Nội dung</label>
                    <textarea name="content" rows="4" class="hz-textarea" placeholder="Chia sẻ trải nghiệm sử dụng...">{{ old('content', $review->content) }}</textarea>
                </div>
            </div>

            {{-- Ảnh --}}
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-3">Ảnh đánh giá</label>

                {{-- Ảnh hiện tại --}}
                @if($review->images && count($review->images) > 0)
                    <div class="mb-4">
                        <p class="text-sm text-slate-600 mb-2">Ảnh hiện tại:</p>
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-2" id="hz-current-images">
                            @foreach($review->images as $index => $imageUrl)
                                <div class="relative aspect-square rounded-lg overflow-hidden bg-slate-100 group" data-image-index="{{ $index }}">
                                    <img src="{{ $imageUrl }}" alt="Review image" class="w-full h-full object-cover">
                                    <button type="button" class="hz-delete-image absolute top-1 right-1 bg-red-600 text-white w-6 h-6 rounded-full opacity-0 group-hover:opacity-100 transition flex items-center justify-center hover:bg-red-700" data-image-url="{{ $imageUrl }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Hidden inputs để lưu ảnh bị xóa --}}
                <div id="hz-deleted-images"></div>

                {{-- Upload ảnh mới --}}
                <div class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center cursor-pointer hover:border-slate-400 transition" id="hz-review-upload-zone">
                    <input type="file" name="images[]" id="hz-review-images" multiple accept="image/*" class="sr-only">
                    <div>
                        <svg class="mx-auto h-8 w-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <p class="text-sm text-slate-600">Kéo ảnh vào đây hoặc <span class="text-blue-600 font-medium">chọn từ máy tính</span></p>
                        <p class="text-xs text-slate-500 mt-1">Tối đa 5 ảnh, mỗi ảnh không quá 5MB</p>
                    </div>
                </div>
                <div id="hz-review-previews" class="mt-3 grid grid-cols-3 md:grid-cols-5 gap-2"></div>
                @error('images')
                    <p class="hz-err mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nút action --}}
            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                    💾 Lưu thay đổi
                </button>
                <a href="{{ route('product.show', $product->slug) }}" class="px-6 py-3 bg-slate-200 text-slate-900 rounded-lg font-semibold hover:bg-slate-300 transition">
                    ← Hủy
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const ratingGroup = document.querySelector('[data-hz-rating]');
  if (ratingGroup) {
    ratingGroup.addEventListener('change', () => {
      ratingGroup.classList.add('is-selected');
      setTimeout(() => ratingGroup.classList.remove('is-selected'), 500);
    });
  }

  // Handle deleting existing images
  const deletedImagesContainer = document.getElementById('hz-deleted-images');
  document.addEventListener('click', (e) => {
    if (e.target.closest('.hz-delete-image')) {
      const btn = e.target.closest('.hz-delete-image');
      const imageContainer = btn.closest('[data-image-index]');
      const imageUrl = btn.dataset.imageUrl;
      
      // Add hidden input to mark this image for deletion
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'delete_images[]';
      input.value = imageUrl;
      deletedImagesContainer.appendChild(input);
      
      // Remove from DOM
      imageContainer.remove();
      
      // Check if no images left
      const currentImagesContainer = document.getElementById('hz-current-images');
      if (currentImagesContainer && currentImagesContainer.children.length === 0) {
        currentImagesContainer.parentElement.remove();
      }
    }
  });

  // Handle image upload
  const uploadZone = document.getElementById('hz-review-upload-zone');
  const imageInput = document.getElementById('hz-review-images');
  const previewContainer = document.getElementById('hz-review-previews');

  if (uploadZone && imageInput && previewContainer) {
    uploadZone.addEventListener('click', () => imageInput.click());

    uploadZone.addEventListener('dragover', (e) => {
      e.preventDefault();
      uploadZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    uploadZone.addEventListener('dragleave', () => {
      uploadZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    uploadZone.addEventListener('drop', (e) => {
      e.preventDefault();
      uploadZone.classList.remove('border-blue-400', 'bg-blue-50');
      const files = e.dataTransfer.files;
      imageInput.files = files;
      updatePreviews();
    });

    imageInput.addEventListener('change', updatePreviews);

    function updatePreviews() {
      previewContainer.innerHTML = '';
      const files = Array.from(imageInput.files);

      if (files.length > 5) {
        imageInput.value = '';
        if (typeof Toast !== 'undefined') {
          Toast.warning('Tối đa 5 ảnh!');
        } else {
          alert('Tối đa 5 ảnh!');
        }
        return;
      }

      files.forEach((file) => {
        if (file.size > 5120 * 1024) {
          if (typeof Toast !== 'undefined') {
            Toast.error(`${file.name} vượt quá 5MB`);
          } else {
            alert(`${file.name} vượt quá 5MB`);
          }
          return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
          const div = document.createElement('div');
          div.className = 'relative group aspect-square rounded-lg overflow-hidden bg-slate-100';
          div.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition"></div>
          `;
          previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
      });
    }
  }
});
</script>
@endpush
@endsection
