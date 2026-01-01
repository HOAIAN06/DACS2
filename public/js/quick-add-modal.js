/* =========================
   HANZO Quick Add Modal
   ========================= */

// Helper selectors
const $ = (sel) => document.querySelector(sel);
const $$ = (sel) => document.querySelectorAll(sel);

// Global function: open modal from product card button
function openQuickAddModal(btn) {
  const productId = btn?.dataset?.productId;
  if (!productId) return;

  // 1) Prefill UI instantly from dataset (fast, no waiting API)
  const fallbackProduct = {
    id: productId,
    name: btn.dataset.name || "",
    category: btn.dataset.category || "",
    image: btn.dataset.image || "", // ✅ main/thumbnail from card
    price: btn.dataset.price ? Number(btn.dataset.price) : 0,
    old_price: btn.dataset.oldPrice ? Number(btn.dataset.oldPrice) : null,
    variants: [] // will be replaced by API
  };

  HZQuickAdd.open(fallbackProduct, { keepSelections: false });

  // 2) Fetch full product detail (variants, correct image, etc.)
  fetch(`/api/products/${productId}`, { headers: { "Accept": "application/json" } })
    .then(async (r) => {
      // Nếu API trả lỗi (404/500), vẫn dùng fallback
      if (!r.ok) throw new Error(`API error ${r.status}`);
      return r.json();
    })
    .then((data) => {
      // Merge: ưu tiên API nhưng giữ ảnh card nếu API thiếu ảnh
      const merged = {
        ...fallbackProduct,
        ...data,
        image: data?.image || fallbackProduct.image
      };
      HZQuickAdd.open(merged, { keepSelections: false });
    })
    .catch((err) => {
      console.error("QuickAdd API error:", err);
      // Không alert nữa cho đỡ khó chịu. Modal vẫn mở với ảnh card.
      // alert('Có lỗi khi tải dữ liệu sản phẩm');
    });
}

window.HZQuickAdd = {
  variants: [],
  selectedColor: null,
  selectedSize: null,

  open(product, opts = {}) {
    const { keepSelections = false } = opts;

    const modal = $("#hz-quick-add-modal");
    if (!modal) return;

    // Cache elements
    const elProductId = $("#hz-qa-product-id");
    const elName = $("#hz-qa-name");
    const elCategory = $("#hz-qa-category");
    const elImage = $("#hz-qa-image");
    const elPrice = $("#hz-qa-price");
    const elOldPrice = $("#hz-qa-old-price");
    const elQty = $("#hz-qa-qty");
    const elVariantId = $("#hz-qa-variant-id");

    // Basic info
    if (elProductId) elProductId.value = product?.id ?? "";
    if (elName) elName.textContent = product?.name ?? "";
    if (elCategory) elCategory.textContent = product?.category ?? "";

    // Image: always keep something
    if (elImage) {
      elImage.src = product?.image || elImage.src || "";
      elImage.onerror = () => {
        // fallback placeholder if you have one
        // elImage.src = "/images/placeholder.png";
      };
    }

    // Price
    if (elPrice) elPrice.textContent = this.formatPrice(product?.price ?? 0);

    // Old price
    if (elOldPrice) {
      if (product?.old_price && Number(product.old_price) > Number(product.price || 0)) {
        elOldPrice.textContent = this.formatPrice(product.old_price);
        elOldPrice.classList.remove("hidden");
      } else {
        elOldPrice.classList.add("hidden");
      }
    }

    // Qty reset
    if (elQty) elQty.value = 1;

    // Variants
    this.variants = Array.isArray(product?.variants) ? product.variants : [];

    // Reset state
    if (!keepSelections) {
      this.selectedColor = null;
      this.selectedSize = null;
      if (elVariantId) elVariantId.value = "";
      this.clearActiveButtons();
    }

    // Render options
    this.renderColorsAndSizes();

    // Show modal
    modal.classList.remove("hidden");

    // Reset action input to default add_to_cart each time open
    const form = $("#hz-qa-form");
    if (form) {
      const actionInput = form.querySelector('input[name="action"]');
      if (actionInput) actionInput.value = "add_to_cart";
    }
  },

  clearActiveButtons() {
    $$(".hz-qa-color-btn").forEach((b) => b.classList.remove("active"));
    $$(".hz-qa-size-btn").forEach((b) => b.classList.remove("active"));
  },

  // Decide whether product uses variants with color/size
  renderColorsAndSizes() {
    const colorSection = $("#hz-qa-color-section");
    const sizeSection = $("#hz-qa-size-section");

    const hasVariants = Array.isArray(this.variants) && this.variants.length > 0;

    // If no variants -> hide both sections
    if (!hasVariants) {
      if (colorSection) colorSection.classList.add("hidden");
      if (sizeSection) sizeSection.classList.add("hidden");
      return;
    }

    // Determine if variants have color/size fields
    const hasColor = this.variants.some((v) => (v?.color ?? "").toString().trim() !== "");
    const hasSize = this.variants.some((v) => (v?.size ?? "").toString().trim() !== "");

    // Render color if exists
    if (hasColor) {
      this.renderColors();
    } else {
      if (colorSection) colorSection.classList.add("hidden");
    }

    // ALWAYS render sizes (don't wait for color selection)
    // Sizes will auto-filter based on selected color in renderSizes
    if (hasSize) {
      this.renderSizes(this.selectedColor || null); // Pass current selectedColor for filtering
    } else {
      if (sizeSection) sizeSection.classList.add("hidden");
    }
  },

  renderColors() {
    const colorSection = $("#hz-qa-color-section");
    const container = $("#hz-qa-colors");
    if (!container || !colorSection) return;

    // Unique colors
    const colors = Array.from(
      new Set(this.variants.map((v) => (v?.color ?? "").toString().trim()).filter(Boolean))
    );

    if (colors.length === 0) {
      colorSection.classList.add("hidden");
      return;
    }

    colorSection.classList.remove("hidden");
    container.innerHTML = "";

    colors.forEach((color) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.textContent = color;
      btn.className = "hz-qa-color-btn";
      btn.dataset.color = color;

      btn.addEventListener("click", (e) => {
        e.preventDefault();
        $$(".hz-qa-color-btn").forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");

        this.selectedColor = color;
        this.selectedSize = null; // reset size when change color
        const elVariantId = $("#hz-qa-variant-id");
        if (elVariantId) elVariantId.value = "";
        $$(".hz-qa-size-btn").forEach((b) => b.classList.remove("active"));

        // Re-render sizes filtered by this color
        this.renderSizes(color);
      });

      container.appendChild(btn);
    });
  },

  renderSizes(colorOrNull) {
    const sizeSection = $("#hz-qa-size-section");
    const container = $("#hz-qa-sizes");
    if (!container || !sizeSection) return;

    // Filter variants by color if provided
    let list = this.variants;

    if (colorOrNull) {
      list = list.filter((v) => {
        const vColor = (v?.color ?? "").toString().trim();
        return vColor === colorOrNull;
      });
    }

    // Extract unique sizes
    const sizeSet = new Set();
    list.forEach((v) => {
      if (v == null) return;
      // Only include if stock > 0 (or stock field missing = assume available)
      if (typeof v.stock === "number" && v.stock <= 0) {
        return;
      }
      const size = (v?.size ?? "").toString().trim();
      if (size) sizeSet.add(size);
    });

    const sizes = Array.from(sizeSet);

    if (sizes.length === 0) {
      sizeSection.classList.add("hidden");
      return;
    }

    sizeSection.classList.remove("hidden");
    container.innerHTML = "";

    sizes.forEach((size) => {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.textContent = size;
      btn.className = "hz-qa-size-btn";
      btn.dataset.size = size;

      btn.addEventListener("click", (e) => {
        e.preventDefault();
        $$(".hz-qa-size-btn").forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");

        this.selectedSize = size;
        this.updateVariantId();
      });

      container.appendChild(btn);
    });
  },

  updateVariantId() {
    const elVariantId = $("#hz-qa-variant-id");
    if (!elVariantId) return;

    const warningEl = $("#hz-qa-warning");
    const warningFieldsEl = $("#hz-qa-warning-fields");

    // Determine what fields product has
    const hasColor = this.variants.some((v) => (v?.color ?? "").toString().trim() !== "");
    const hasSize = this.variants.some((v) => (v?.size ?? "").toString().trim() !== "");

    // Case 1: Requires both color AND size
    if (hasColor && hasSize) {
      if (!this.selectedColor || !this.selectedSize) {
        elVariantId.value = "";
        if (warningEl) {
          warningEl.classList.remove("hidden");
          if (warningFieldsEl) {
            if (!this.selectedColor && !this.selectedSize) {
              warningFieldsEl.textContent = "màu và kích thước";
            } else if (!this.selectedColor) {
              warningFieldsEl.textContent = "màu";
            } else {
              warningFieldsEl.textContent = "kích thước";
            }
          }
        }
        return;
      }

      const variant = this.variants.find(
        (v) =>
          (v?.color ?? "").toString().trim() === this.selectedColor &&
          (v?.size ?? "").toString().trim() === this.selectedSize
      );

      if (variant) {
        elVariantId.value = variant.id;
        if (warningEl) warningEl.classList.add("hidden");
      } else {
        elVariantId.value = "";
        if (warningEl) warningEl.classList.remove("hidden");
      }
      return;
    }

    // Case 2: Only size (no color)
    if (!hasColor && hasSize) {
      if (!this.selectedSize) {
        elVariantId.value = "";
        if (warningEl) {
          warningEl.classList.remove("hidden");
          if (warningFieldsEl) warningFieldsEl.textContent = "kích thước";
        }
        return;
      }
      const variant = this.variants.find((v) => (v?.size ?? "").toString().trim() === this.selectedSize);
      if (variant) {
        elVariantId.value = variant.id;
        if (warningEl) warningEl.classList.add("hidden");
      } else {
        elVariantId.value = "";
        if (warningEl) warningEl.classList.remove("hidden");
      }
      return;
    }

    // Case 3: Only color (no size)
    if (hasColor && !hasSize) {
      if (!this.selectedColor) {
        elVariantId.value = "";
        if (warningEl) {
          warningEl.classList.remove("hidden");
          if (warningFieldsEl) warningFieldsEl.textContent = "màu";
        }
        return;
      }
      const variant = this.variants.find((v) => (v?.color ?? "").toString().trim() === this.selectedColor);
      if (variant) {
        elVariantId.value = variant.id;
        if (warningEl) warningEl.classList.add("hidden");
      } else {
        elVariantId.value = "";
        if (warningEl) warningEl.classList.remove("hidden");
      }
      return;
    }

    // Case 4: No variants or variants without color/size => use first variant
    if (this.variants.length > 0) {
      elVariantId.value = this.variants[0].id;
      if (warningEl) warningEl.classList.add("hidden");
    } else {
      elVariantId.value = "";
      if (warningEl) warningEl.classList.remove("hidden");
    }
  },

  formatPrice(price) {
    const n = Number(price || 0);
    return new Intl.NumberFormat("vi-VN").format(n) + "₫";
  }
};

// Init controls (qty, submit, close)
document.addEventListener("DOMContentLoaded", function () {
  const qtyInput = $("#hz-qa-qty");
  const btnMinus = $("#hz-qa-qty-minus");
  const btnPlus = $("#hz-qa-qty-plus");

  if (btnMinus && qtyInput) {
    btnMinus.addEventListener("click", function () {
      const val = parseInt(qtyInput.value, 10) || 1;
      if (val > 1) qtyInput.value = val - 1;
    });
  }

  if (btnPlus && qtyInput) {
    btnPlus.addEventListener("click", function () {
      const val = parseInt(qtyInput.value, 10) || 1;
      qtyInput.value = val + 1;
    });
  }

  const form = $("#hz-qa-form");
  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      // sync qty
      const qty = $("#hz-qa-qty")?.value || 1;
      const qtyHidden = $("#hz-qa-qty-input");
      if (qtyHidden) qtyHidden.value = qty;

      // validate variant only when variants exist AND require selections
      const variantIdEl = $("#hz-qa-variant-id");
      const variantId = variantIdEl ? variantIdEl.value : "";

      const hasVariants = Array.isArray(window.HZQuickAdd.variants) && window.HZQuickAdd.variants.length > 0;
      if (hasVariants) {
        // auto-update variantId in case user selected but not updated
        window.HZQuickAdd.updateVariantId();
      }

      const finalVariantId = variantIdEl ? variantIdEl.value : "";

      if (hasVariants && !finalVariantId) {
        alert("⚠️ Vui lòng chọn màu/kích thước");
        return;
      }

      this.submit();
    });
  }

  const buyNowBtn = $("#hz-qa-buy-now");
  if (buyNowBtn) {
    buyNowBtn.addEventListener("click", function () {
      const form = $("#hz-qa-form");
      if (!form) return;

      // sync qty
      const qty = $("#hz-qa-qty")?.value || 1;
      const qtyHidden = $("#hz-qa-qty-input");
      if (qtyHidden) qtyHidden.value = qty;

      // ensure variant id updated
      window.HZQuickAdd.updateVariantId();

      const hasVariants = Array.isArray(window.HZQuickAdd.variants) && window.HZQuickAdd.variants.length > 0;
      const variantId = $("#hz-qa-variant-id")?.value || "";

      if (hasVariants && !variantId) {
        alert("⚠️ Vui lòng chọn màu/kích thước");
        return;
      }

      const actionInput = form.querySelector('input[name="action"]');
      if (actionInput) actionInput.value = "buy_now";

      form.submit();

      // reset action back (avoid stuck)
      if (actionInput) actionInput.value = "add_to_cart";
    });
  }

  // Close modal on backdrop click
  const modal = $("#hz-quick-add-modal");
  if (modal) {
    modal.addEventListener("click", function (e) {
      if (e.target === this) this.classList.add("hidden");
    });
  }

  // Close modal on ESC
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      const modal = $("#hz-quick-add-modal");
      if (modal && !modal.classList.contains("hidden")) modal.classList.add("hidden");
    }
  });
});
