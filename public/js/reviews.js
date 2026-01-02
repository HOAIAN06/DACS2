document.addEventListener('DOMContentLoaded', () => {
  const scrollToFormLink = document.querySelector('a[href="#hz-review-form"]');
  const reviewForm = document.getElementById('hz-review-form');

  if (scrollToFormLink && reviewForm) {
    scrollToFormLink.addEventListener('click', (e) => {
      e.preventDefault();
      reviewForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }

  const ratingGroup = document.querySelector('[data-hz-rating]');
  if (ratingGroup) {
    ratingGroup.addEventListener('change', () => {
      ratingGroup.classList.add('is-selected');
      setTimeout(() => ratingGroup.classList.remove('is-selected'), 500);
    });
  }

  // Handle image upload with drag-drop and preview
  const uploadZone = document.getElementById('hz-review-upload-zone');
  const imageInput = document.getElementById('hz-review-images');
  const previewContainer = document.getElementById('hz-review-previews');

  if (uploadZone && imageInput && previewContainer) {
    uploadZone.addEventListener('click', () => imageInput.click());

    // Drag-drop
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
          Toast.warning('Tối đa 5 ảnh!', 3000);
        }
        return;
      }

      files.forEach((file) => {
        if (file.size > 5120 * 1024) {
          if (typeof Toast !== 'undefined') {
            Toast.error(`${file.name} vượt quá 5MB`, 3500);
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

