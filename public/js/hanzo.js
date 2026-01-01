// public/js/hanzo.js

document.addEventListener("DOMContentLoaded", () => {
    // ============================================
    // SEARCH MODAL - Small overlay dropdown
    // ============================================
(function setupSearchModal() {
  const modal = document.getElementById("hz-search-modal");
  const overlay = document.getElementById("hz-search-overlay");
  const openBtn = document.getElementById("hz-search-modal-open");
  const searchInput = document.getElementById("hz-search-input");
  const categoryBtns = document.querySelectorAll(".hz-search-category");

  let selectedKeyword = "";

  if (!modal || !openBtn || !overlay) return;

  const open = () => {
    modal.classList.add("active");
    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
    setTimeout(() => searchInput?.focus(), 0);
  };

  const close = () => {
    modal.classList.remove("active");
    overlay.classList.remove("active");
    document.body.style.overflow = "";
  };

  // mở
  openBtn.addEventListener("click", (e) => {
    e.preventDefault();
    open();
  });

  // click ra ngoài là đóng
  overlay.addEventListener("click", close);

  // ESC là đóng
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("active")) close();
  });

  // click trong modal không đóng
  modal.addEventListener("click", (e) => e.stopPropagation());

  // keyword giống IconDenim
  categoryBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      categoryBtns.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      selectedKeyword = btn.dataset.keyword || "";
      searchInput.value = selectedKeyword;
      searchInput.focus();
    });
  });
})();


    // ============================================
    // MAIN IMAGE: Error fallback to placeholder
    // ============================================
    (function setupMainImageFallback() {
        const mainImage = document.getElementById('hz-main-image');
        if (!mainImage) return;
        mainImage.addEventListener('error', () => {
            mainImage.src = '/images/placeholder.jpg';
        });
    })();

    // ============================================
    // LOADING SKELETON: Remove shimmer when images load
    // ============================================
    (function setupSkeletons() {
        const wrappers = document.querySelectorAll('.hz-skeleton');

        wrappers.forEach(wrapper => {
            const img = wrapper.querySelector('img');
            if (!img) {
                // Không có img, xóa skeleton state
                wrapper.classList.add('loaded');
                wrapper.classList.remove('hz-skeleton');
                return;
            }

            const markLoaded = () => {
                wrapper.classList.add('loaded');
                wrapper.classList.remove('hz-skeleton');
                console.log('[HANZO] Image loaded:', img.src);
            };

            if (img.complete && img.naturalWidth > 0) {
                markLoaded();
            } else {
                img.addEventListener('load', markLoaded);
                img.addEventListener('error', () => {
                    console.warn('[HANZO] Image load error:', img.src);
                    wrapper.classList.add('loaded');
                    wrapper.classList.remove('hz-skeleton');
                });
            }
        });
    })();

    // ============================================
    // ANNOUNCEMENT BAR: Close Button
    // ============================================
    (function setupAnnouncementBar() {
        const topbar = document.getElementById('hz-topbar');
        const closeBtn = document.getElementById('hz-topbar-close');
        
        if (closeBtn && topbar) {
            closeBtn.addEventListener('click', () => {
                topbar.classList.add('hidden');
                // Save state to localStorage
                localStorage.setItem('hz-topbar-hidden', 'true');
            });
            
            // Check if topbar was previously closed
            if (localStorage.getItem('hz-topbar-hidden') === 'true') {
                topbar.classList.add('hidden');
            }
        }
    })();

    // ============================================
    // HEADER: Search Bar Toggle & Sticky Effect
    // ============================================
    (function setupHeader() {
        const header = document.getElementById('hz-header');
        const searchToggle = document.getElementById('hz-search-toggle');
        const searchBar = document.getElementById('hz-search-bar');
        const searchClose = document.getElementById('hz-search-close');
        const searchInput = searchBar?.querySelector('input');

        // Search Bar Toggle
        if (searchToggle && searchBar) {
            searchToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                searchBar.classList.add('active');
                setTimeout(() => searchInput?.focus(), 100);
            });

            searchClose?.addEventListener('click', () => {
                searchBar.classList.remove('active');
                searchInput.value = '';
            });

            // Close when click outside
            document.addEventListener('click', (e) => {
                if (!searchBar.contains(e.target) && !searchToggle.contains(e.target)) {
                    searchBar.classList.remove('active');
                }
            });

            // Search on Enter
            searchInput?.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const query = searchInput.value.trim();
                    if (query) {
                        window.location.href = `/products?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }

        // Sticky Header with Scroll Effect
        if (header) {
            let lastScroll = 0;
            window.addEventListener('scroll', () => {
                const currentScroll = window.pageYOffset;
                
                if (currentScroll > 100) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
                
                lastScroll = currentScroll;
            });
        }
    })();

        // ============================================
        // USER DROPDOWN TOGGLE (Header)
        // Keeps menu open when cursor moves into it
        // ============================================
        (function setupUserDropdown() {
            const trigger = document.getElementById('hz-user-dropdown');
            const menu = document.getElementById('user-dropdown-menu');

            if (!trigger || !menu) return;

            function open() {
                menu.classList.remove('hidden');
            }

            function close() {
                menu.classList.add('hidden');
            }

            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                if (menu.classList.contains('hidden')) {
                    open();
                } else {
                    close();
                }
            });

            // Keep open when moving cursor into menu
            menu.addEventListener('mouseenter', () => open());
            menu.addEventListener('mouseleave', () => close());

            // Close on outside click
            document.addEventListener('click', (e) => {
                const inside = menu.contains(e.target) || trigger.contains(e.target);
                if (!inside) close();
            });

            // Close on Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') close();
            });
        })();

    // ============================================
    // STICKY ADD TO CART (Mobile - Product Detail)
    // ============================================
    (function setupStickyCart() {
        const bar = document.getElementById('hz-sticky-add-cart');
        const btn = document.getElementById('hz-sticky-add-cart-btn');
        const form = document.querySelector('form[action*="cart.add"]');

        if (!bar || !btn || !form) return;

        function updateVisibility() {
            const isMobile = window.innerWidth < 768;
            const shouldShow = isMobile && window.scrollY > 260;
            bar.classList.toggle('show', shouldShow);
        }

        btn.addEventListener('click', () => {
            form.requestSubmit ? form.requestSubmit() : form.submit();
        });

        window.addEventListener('scroll', updateVisibility, { passive: true });
        window.addEventListener('resize', updateVisibility);
        updateVisibility();
    })();

    // ============================================

(function setupProductDetail() {
  const form = document.getElementById('hz-product-form');
  if (!form) return; // chỉ chạy ở trang chi tiết sp

  // --------- Load variants from JSON script tag ----------
  let variants = [];
  try {
    const el = document.getElementById('hz-variants-json');
    if (el?.textContent) variants = JSON.parse(el.textContent);
  } catch (e) {
    console.warn('[HANZO] variants json parse error', e);
  }

  let selectedColor = null;
  let selectedSize = null;

  const colorBtns = Array.from(document.querySelectorAll('.hz-color-btn'));
  const sizeBtns = Array.from(document.querySelectorAll('.hz-size-btn'));
  const variantInput = document.getElementById('hz-selected-variant');
  const priceDisplay = document.getElementById('hz-product-price');

  // Helpers
  const setActive = (list, target) => {
    list.forEach(b => b.classList.remove('active'));
    if (target) target.classList.add('active');
  };

  const setDisabled = (btn, disabled) => {
    btn.disabled = !!disabled;
    btn.classList.toggle('disabled', !!disabled);
  };

  const formatVND = (n) => {
    try {
      return new Intl.NumberFormat('vi-VN').format(n) + '₫';
    } catch {
      return n + '₫';
    }
  };

  function updateAvailability() {
    if (!sizeBtns.length) return;

    sizeBtns.forEach(btn => {
      const size = btn.dataset.size;
      const variant = variants.find(v =>
        (!selectedColor || v.color === selectedColor) && v.size === size
      );
      
      const hasStock = variant && (variant.stock ?? 0) > 0;

      setDisabled(btn, !hasStock);

      // nếu size đang active mà nay không hợp lệ => bỏ chọn
      if (!hasVariant && btn.classList.contains('active')) {
        btn.classList.remove('active');
        selectedSize = null;
        const sizeHint = document.getElementById('hz-size-selected');
        if (sizeHint) sizeHint.textContent = '';
        if (variantInput) variantInput.value = '';
      }
    });
  }

    function findVariant() {
        const requiresSize = sizeBtns.length > 0;
        // Nếu có size option thì mới bắt buộc chọn size, còn không thì chọn theo màu (hoặc chọn luôn biến thể đầu tiên)
        if (requiresSize && !selectedSize) return;

        const variant = variants.find(v =>
            (!selectedColor || v.color === selectedColor) &&
            (!requiresSize || v.size === selectedSize)
        ) || variants[0];

    if (!variant) return;

    // set hidden input variant_id
    if (variantInput) variantInput.value = variant.id;

    // update price if variant has price
    if (variant.price && priceDisplay) {
      priceDisplay.textContent = formatVND(variant.price);
    }

    // update SKU (nếu bạn có element này)
    const skuDisplay = document.getElementById('hz-product-sku');
    if (variant.sku && skuDisplay) skuDisplay.textContent = variant.sku;

    // update stock badge
    updateStockBadge(variant);
    
    // update max quantity
    if (qtyInput) {
      const maxQty = Math.max(1, variant.stock ?? 999);
      qtyInput.setAttribute('max', maxQty);
      
      // Nếu qty hiện tại vượt quá stock, giảm xuống
      const currentQty = parseInt(qtyInput.value || '1', 10);
      if (currentQty > maxQty) {
        qtyInput.value = String(maxQty);
      }
    }
  }

  function updateStockBadge(variant) {
    const stockBadge = document.getElementById('hz-stock-badge');
    if (!stockBadge) return;

    const stock = variant?.stock ?? 0;
    
    // Tìm tất cả submit buttons trong form
    const submitBtns = form?.querySelectorAll('button[type="submit"]');
    
        if (stock === 0) {
            stockBadge.innerHTML = '<span class="hz-stock-badge out-of-stock">⚠️ Hết hàng</span>';
      submitBtns?.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.style.cursor = 'not-allowed';
      });
    } else {
      submitBtns?.forEach(btn => {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
      });
            // Không hiển thị số lượng tồn cụ thể
            if (stock <= 5) {
                stockBadge.innerHTML = '<span class="hz-stock-badge low-stock">⚡ Sắp hết hàng</span>';
            } else {
                stockBadge.innerHTML = '<span class="hz-stock-badge in-stock">✓ Còn hàng</span>';
            }
    }
  }

  // --------- Color clicks ----------
  if (colorBtns.length) {
    colorBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        setActive(colorBtns, btn);
        selectedColor = btn.dataset.color || null;
        
        // Update hint text
        const colorHint = document.getElementById('hz-color-selected');
        if (colorHint) colorHint.textContent = selectedColor || '';

        updateAvailability();

        // nếu đổi màu làm size đang chọn bị invalid => reset size
        if (selectedSize) {
          const ok = variants.some(v => v.color === selectedColor && v.size === selectedSize);
          if (!ok) {
            selectedSize = null;
            sizeBtns.forEach(b => b.classList.remove('active'));
            const sizeHint = document.getElementById('hz-size-selected');
            if (sizeHint) sizeHint.textContent = '';
            if (variantInput) variantInput.value = '';
          }
        }

        findVariant();
      });
    });

    // Nếu có nút màu nào đã active từ server thì lấy làm default
    const preActive = colorBtns.find(b => b.classList.contains('active'));
    if (preActive) selectedColor = preActive.dataset.color || null;
  }

  // --------- Size clicks ----------
  if (sizeBtns.length) {
    sizeBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        if (btn.disabled) return;
        setActive(sizeBtns, btn);
        selectedSize = btn.dataset.size || null;
        
        // Update hint text
        const sizeHint = document.getElementById('hz-size-selected');
        if (sizeHint) sizeHint.textContent = selectedSize || '';
        
        findVariant();
      });
    });

    // init disable đúng theo selectedColor hiện tại
    updateAvailability();
  }

  // --------- Quantity buttons ----------
  const qtyInput = document.getElementById('hz-qty-input');
  const qtyMinus = document.getElementById('hz-qty-minus');
  const qtyPlus = document.getElementById('hz-qty-plus');

  qtyMinus?.addEventListener('click', () => {
    const val = Math.max(1, parseInt(qtyInput?.value || '1', 10) || 1);
    if (qtyInput) qtyInput.value = String(Math.max(1, val - 1));
  });

  qtyPlus?.addEventListener('click', () => {
    const val = Math.max(1, parseInt(qtyInput?.value || '1', 10) || 1);
    const max = parseInt(qtyInput?.getAttribute('max') || '999', 10);
    if (qtyInput && val < max) {
      qtyInput.value = String(val + 1);
    }
  });

  // --------- Gallery thumb active (match is-active) ----------
  const mainImg = document.getElementById('hz-main-image');
  const thumbs = Array.from(document.querySelectorAll('.hz-gallery-thumb'));
  const prevBtn = document.getElementById('hz-gallery-prev');
  const nextBtn = document.getElementById('hz-gallery-next');
  let currentIndex = 0;

  const updateThumbActive = () => {
    thumbs.forEach((t, i) => {
      if (i === currentIndex) {
        t.classList.remove('border-slate-200');
        t.classList.add('border-slate-900');
      } else {
        t.classList.remove('border-slate-900');
        t.classList.add('border-slate-200');
      }
    });
  };

  if (mainImg && thumbs.length) {
    thumbs.forEach((thumb, index) => {
      thumb.addEventListener('click', () => {
        const url = thumb.dataset.image;
        if (url) mainImg.src = url;
        currentIndex = index;
        updateThumbActive();
      });
    });

    prevBtn?.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + thumbs.length) % thumbs.length;
      const url = thumbs[currentIndex]?.dataset.image;
      if (url) mainImg.src = url;
      updateThumbActive();
    });

    nextBtn?.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % thumbs.length;
      const url = thumbs[currentIndex]?.dataset.image;
      if (url) mainImg.src = url;
      updateThumbActive();
    });

    // init
    updateThumbActive();
  }

  // --------- Size guide modal ----------
  const sizeGuideBtn = document.getElementById('hz-size-guide-btn');
  const sizeGuideModal = document.getElementById('hz-size-guide-modal');
  const sizeGuideClose = document.getElementById('hz-size-guide-close');

  if (sizeGuideBtn && sizeGuideModal) {
    sizeGuideBtn.addEventListener('click', () => sizeGuideModal.classList.add('active'));
    sizeGuideClose?.addEventListener('click', () => sizeGuideModal.classList.remove('active'));
    sizeGuideModal.addEventListener('click', (e) => {
      if (e.target === sizeGuideModal) sizeGuideModal.classList.remove('active');
    });
  }

  // --------- Form validation ----------
  form.addEventListener('submit', (e) => {
    const variantId = variantInput?.value;
    const needsVariant = (colorBtns.length > 0 || sizeBtns.length > 0);
    
    if (needsVariant && !variantId) {
      e.preventDefault();
      alert('Vui lòng chọn phiên bản (màu/size) trước khi mua');
      return;
    }

    // Kiểm tra stock
    const selectedVariant = variants.find(v => v.id == variantId);
    const qtyValue = parseInt(qtyInput?.value || '1', 10);
    const stock = selectedVariant?.stock ?? 0;

    if (stock === 0) {
      e.preventDefault();
      alert('Sản phẩm này hiện đã hết hàng!');
      return;
    }

        if (qtyValue > stock) {
            e.preventDefault();
            alert('Số lượng yêu cầu vượt quá tồn kho. Vui lòng giảm số lượng!');
            return;
        }
  });

    // Thiết lập variant mặc định nếu không có lựa chọn nào hoặc chỉ có màu
    findVariant();

  console.log('[HANZO] Product detail setup done');
})();


    // Parallax hover removed

    // ============================================
    // MOBILE DRAWER MENU
    // ============================================
    (function setupMobileMenu() {
        const openBtn = document.getElementById('hz-mobile-open');
        const closeBtn = document.getElementById('hz-mobile-close');
        const drawer = document.getElementById('hz-mobile-drawer');
        const overlay = document.getElementById('hz-mobile-overlay');
        const body = document.body;

        function openMenu() {
            drawer.classList.add('active');
            overlay.classList.add('active');
            body.classList.add('overflow-hidden');
        }

        function closeMenu() {
            drawer.classList.remove('active');
            overlay.classList.remove('active');
            body.classList.remove('overflow-hidden');
        }

        openBtn?.addEventListener('click', openMenu);
        closeBtn?.addEventListener('click', closeMenu);
        overlay?.addEventListener('click', closeMenu);

        // Accordion
        document.querySelectorAll('[data-mobile-accordion]').forEach(btn => {
            const key = btn.dataset.mobileAccordion;
            const panel = document.querySelector(`[data-accordion-panel="${key}"]`);
            if (!panel) return;

            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
                panel.classList.toggle('hidden', expanded);
                panel.classList.toggle('open', !expanded);
            });
        });
    })();

    // ============================================
    // CATEGORY SHOWCASE - Hỗ trợ nhiều sections (shirts & pants)
    // Mỗi section có data-category-group để phân biệt
    // ============================================
    (function setupCategoryShowcase() {
        // Config cho từng category group
        const configs = {
            shirts: {
                thun: {
                    img: '/images/banner/banner_aothun.jpg',
                    title: 'ÁO THUN',
                    link: '/products?category=ao-thun'
                },
                somi: {
                    img: '/images/banner/banner_aosomi.jpg',
                    title: 'ÁO SƠMI',
                    link: '/products?category=ao-somi'
                },
                polo: {
                    img: '/images/banner/banner_aopolo.jpg',
                    title: 'ÁO POLO',
                    link: '/products?category=ao-polo'
                }
            },
            pants: {
                short: {
                    img: '/images/banner/banner_quanshort.jpg',
                    title: 'QUẦN SHORT',
                    link: '/products?category=quan-short'
                },
                jean: {
                    img: '/images/banner/banner_quanjean.jpg',
                    title: 'QUẦN JEAN',
                    link: '/products?category=quan-jean'
                },
                tay: {
                    img: '/images/banner/banner_quantay.jpg',
                    title: 'QUẦN TÂY',
                    link: '/products?category=quan-tay'
                }
            }
        };

        // Setup cho từng section
        document.querySelectorAll('[data-category-group]').forEach(section => {
            const group = section.dataset.categoryGroup;
            const config = configs[group];
            if (!config) return;

            const tabs = section.querySelectorAll('.cat-tab[data-group="' + group + '"]');
            // QUAN TRỌNG: Chỉ lấy panels TRONG section này, không lấy panels của section khác
            const panels = section.querySelectorAll('.tab-panel');
            const leftHero = section.querySelector('.left-hero-' + group);
            
            if (!tabs.length || !leftHero) {
                console.warn('[HANZO] Setup failed for group:', group, 'tabs:', tabs.length, 'leftHero:', !!leftHero);
                return;
            }

            const leftHeroImg = leftHero.querySelector('img');
            const leftHeroTitle = leftHero.querySelector('h4');
            const leftHeroLink = leftHero.querySelector('a');

            console.log('[HANZO] Setup group:', group, 'tabs:', tabs.length, 'panels:', panels.length);

            function setActiveTab(key) {
                const cfg = config[key];
                if (!cfg) {
                    console.warn('[HANZO] Config not found for key:', key, 'in group:', group);
                    return;
                }

                console.log('[HANZO] setActiveTab called:', group, key, 'panels:', panels.length);

                // Update tabs - remove all active states first
                tabs.forEach(t => {
                    const isActive = t.dataset.tab === key;
                    if (isActive) {
                        t.classList.remove('text-slate-500', 'border-transparent');
                        t.classList.add('text-slate-900', 'border-slate-900');
                    } else {
                        t.classList.remove('text-slate-900', 'border-slate-900');
                        t.classList.add('text-slate-500', 'border-transparent');
                    }
                });

                // Update panels - show/hide
                let panelFound = false;
                panels.forEach(p => {
                    const panelKey = p.dataset.panel;
                    console.log('[HANZO] Checking panel:', panelKey, 'against key:', key, 'match:', panelKey === key);
                    if (panelKey === key) {
                        p.classList.remove('hidden');
                        panelFound = true;
                    } else {
                        p.classList.add('hidden');
                    }
                });

                if (!panelFound) {
                    console.error('[HANZO] Panel not found for key:', key, 'in group:', group);
                }

                // Update left hero
                if (leftHeroImg && cfg.img) {
                    leftHeroImg.src = cfg.img;
                    leftHeroImg.alt = cfg.title;
                }
                if (leftHeroTitle) leftHeroTitle.textContent = cfg.title;
                if (leftHeroLink) leftHeroLink.href = cfg.link;

                console.log('[HANZO] Tab switched:', group, key, 'panel found:', panelFound);
            }

            // Event listeners
            tabs.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    setActiveTab(btn.dataset.tab);
                });
            });

            // Initialize - NO need to call setActiveTab since HTML already has correct state
            console.log('[HANZO] Category showcase initialized:', group);
        });
    })();

    // ============================================
    // HIGHLIGHT TABS (Hàng mới / Thu Đông / Bán chạy)
    // data-highlight-group wrapper, .highlight-tab buttons, .highlight-panel panels
    // ============================================
    (function setupHighlightTabs() {
  const section = document.querySelector('[data-highlight-group]');
  if (!section) {
      console.log('[HANZO] No highlight group found');
      return;
  }

  const tabs = Array.from(section.querySelectorAll('.highlight-tab'));
    const panels = Array.from(section.querySelectorAll('.highlight-panel'));
    const ctas = Array.from(section.querySelectorAll('[data-highlight-cta]'));
  
  console.log('[HANZO] Highlight tabs:', tabs.map(t => t.dataset.highlightTab));
  console.log('[HANZO] Highlight panels:', panels.map(p => p.dataset.highlightPanel));

  function activate(key) {
    console.log('[HANZO] Activating tab:', key);
    tabs.forEach((t) => {
      const isActive = t.dataset.highlightTab === key;

      // Không đụng style tab disabled (giữ đúng text-slate-400/opacity-50 bạn set ở Blade)
      if (t.disabled) return;

      // Active
      t.classList.toggle('text-black', isActive);
      t.classList.toggle('border-black', isActive);

      // Inactive (đúng với Blade của bạn đang dùng text-slate-400)
      t.classList.toggle('text-slate-400', !isActive);
      t.classList.toggle('border-transparent', !isActive);

      // Optional: hover chỉ khi inactive
      t.classList.toggle('hover:text-slate-600', !isActive);
    });

    // Chuyển banner trái
    const banners = Array.from(section.querySelectorAll('[data-highlight-banner]'));
    banners.forEach((banner) => {
      const shouldShow = banner.dataset.highlightBanner === key;
      banner.classList.toggle('hidden', !shouldShow);
      console.log('[HANZO] Banner', banner.dataset.highlightBanner, ':', shouldShow ? 'shown' : 'hidden');
    });

        panels.forEach((p) => {
            const shouldShow = p.dataset.highlightPanel === key;
            p.classList.toggle('hidden', !shouldShow);
            console.log('[HANZO] Panel', p.dataset.highlightPanel, ':', shouldShow ? 'shown' : 'hidden');
        });

        // Chuyển CTA "Xem tất cả" tương ứng tab
        ctas.forEach((cta) => {
            const shouldShow = cta.dataset.highlightCta === key;
            cta.classList.toggle('hidden', !shouldShow);
        });
  }

  tabs.forEach((t) => {
    t.addEventListener('click', (e) => {
      if (t.disabled) return;
      e.preventDefault();
      activate(t.dataset.highlightTab);
    });
  });

  // init
  const newTab = section.querySelector('.highlight-tab[data-highlight-tab="new"]');
  const defaultKey = newTab ? 'new' : tabs.find(t => !t.disabled)?.dataset.highlightTab;
  if (defaultKey) {
      console.log('[HANZO] Activating default tab:', defaultKey);
      activate(defaultKey);
  }
})();

    // 0. CSRF TOKEN (dùng cho AJAX Laravel)
    // ============================================
    const csrfToken =
        document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "";

    // Helper gửi POST JSON đơn giản
    async function postJson(url, data = {}) {
        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                ...(csrfToken ? { "X-CSRF-TOKEN": csrfToken } : {}),
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            throw new Error("Request failed " + res.status);
        }
        return await res.json().catch(() => ({}));
    }

    // ============================================
    // SEARCH MODAL + KEYWORD NAVIGATION
    // ============================================
    (function setupSearch() {
        const searchIcon = document.querySelector('[data-action="search"]');
        const modal = document.getElementById('hz-search-modal');
        const overlay = document.getElementById('hz-search-overlay');
        const searchInput = document.getElementById('hz-search-input');
        const body = document.body;
        const categoryBtns = Array.from(document.querySelectorAll('.hz-search-category'));

        // Toggle search modal
        if (searchIcon && modal && overlay) {
            searchIcon.addEventListener('click', (e) => {
                e.stopPropagation();
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
                body.style.overflow = 'hidden';
                if (searchInput) searchInput.focus();
            });

            // Close search when clicking overlay
            overlay.addEventListener('click', () => {
                modal.classList.add('opacity-0', 'pointer-events-none');
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                body.style.overflow = '';
            });

            // Close search with ESC key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.classList.contains('opacity-100')) {
                    modal.classList.add('opacity-0', 'pointer-events-none');
                    modal.classList.remove('opacity-100', 'pointer-events-auto');
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                    overlay.classList.remove('opacity-100', 'pointer-events-auto');
                    body.style.overflow = '';
                }
            });
        }

        // Keyword button navigation to search
        categoryBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const keyword = btn.dataset.keyword || '';
                const searchUrl = keyword 
                    ? `{{ route('products.index') }}?search=${encodeURIComponent(keyword)}`
                    : `{{ route('products.index') }}`;
                window.location.href = searchUrl;
            });
        });
    })();

    // ============================================
    // 1. TOGGLE MENU MOBILE (nếu có)
    //    Nút:  data-toggle="mobile-menu"
    //    Menu: data-target="mobile-menu"
    // ============================================
    const mobileToggle = document.querySelector('[data-toggle="mobile-menu"]');
    const mobileMenu = document.querySelector('[data-target="mobile-menu"]');

    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // ============================================
    // 2. TOAST THÔNG BÁO NHỎ (thêm giỏ hàng, lỗi...)
    // ============================================
    function showToast(message, type = "success") {
        let box = document.getElementById("hz-toast");
        if (!box) {
            box = document.createElement("div");
            box.id = "hz-toast";
            box.style.position = "fixed";
            box.style.right = "16px";
            box.style.bottom = "16px";
            box.style.zIndex = "9999";
            document.body.appendChild(box);
        }

        const item = document.createElement("div");
        item.textContent = message;
        item.style.padding = "10px 14px";
        item.style.marginTop = "8px";
        item.style.borderRadius = "999px";
        item.style.fontSize = "13px";
        item.style.color = "#fff";
        item.style.boxShadow = "0 8px 20px rgba(0,0,0,0.25)";
        item.style.display = "flex";
        item.style.alignItems = "center";
        item.style.gap = "6px";

        if (type === "error") {
            item.style.background = "#dc2626"; // đỏ
        } else {
            item.style.background = "#16a34a"; // xanh
        }

        box.appendChild(item);

        setTimeout(() => {
            item.style.opacity = "0";
            item.style.transform = "translateY(6px)";
            item.style.transition = "all .2s ease";
            setTimeout(() => item.remove(), 250);
        }, 2200);
    }

    // ============================================
    // 3. ADD TO CART AJAX
    //    Nút: data-add-to-cart  data-product-id  data-url
    // ============================================
    document.addEventListener("click", async (e) => {
        const addBtn = e.target.closest("[data-add-to-cart]");
        if (!addBtn) return;

        e.preventDefault();

        const productId = addBtn.dataset.productId;
        const url = addBtn.dataset.url;

        if (!productId || !url) return;

        addBtn.disabled = true;
        const oldText = addBtn.innerText;
        addBtn.innerText = "Đang thêm...";

        try {
            await postJson(url, { product_id: productId, quantity: 1 });
            showToast("Đã thêm sản phẩm vào giỏ!", "success");

            // Cập nhật badge số lượng giỏ hàng nếu có
            const badge = document.querySelector("[data-cart-count]");
            if (badge) {
                const current = parseInt(badge.textContent || "0", 10) || 0;
                badge.textContent = current + 1;
            }
        } catch (err) {
            console.error(err);
            showToast("Thêm giỏ hàng thất bại!", "error");
        } finally {
            addBtn.disabled = false;
            addBtn.innerText = oldText;
        }
    });

    // ============================================
    // 4. QUICK VIEW MODAL
    //    Nút mở: data-quick-view data-url
    //    Modal: #hz-quickview
    // ============================================
    const qvModal = document.getElementById("hz-quickview");
    const qvBody = qvModal?.querySelector("[data-hz-qv-body]");
    const qvCloseBtn = qvModal?.querySelector("[data-hz-qv-close]");

    function openQuickView() {
        if (!qvModal) return;
        qvModal.classList.add("hz-qv-open");
        document.body.classList.add("hz-modal-open");
    }

    function closeQuickView() {
        if (!qvModal) return;
        qvModal.classList.remove("hz-qv-open");
        document.body.classList.remove("hz-modal-open");
    }

    if (qvCloseBtn) {
        qvCloseBtn.addEventListener("click", closeQuickView);
    }

    if (qvModal) {
        qvModal.addEventListener("click", (e) => {
            if (e.target === qvModal) {
                closeQuickView();
            }
        });
    }

    document.addEventListener("click", async (e) => {
        const qvBtn = e.target.closest("[data-quick-view]");
        if (!qvBtn) return;

        e.preventDefault();
        if (!qvModal || !qvBody) return;

        const url = qvBtn.dataset.url;
        if (!url) return;

        openQuickView();
        qvBody.innerHTML = `<div style="padding:20px;font-size:13px;">Đang tải...</div>`;

        try {
            const res = await fetch(url, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            });
            const html = await res.text();
            qvBody.innerHTML = html;
        } catch (err) {
            console.error(err);
            qvBody.innerHTML =
                "<div style='padding:20px;font-size:13px;color:#dc2626'>Không tải được thông tin sản phẩm.</div>";
        }
    });

    // ============================================
    // 5. HERO SLIDER: mũi tên + dots + auto-play
    //    Wrapper: .js-hero-slider
    //    Slide:   .js-hero-slide
    // ============================================
    (function setupHeroSlider() {
        const slider = document.querySelector(".js-hero-slider");
        if (!slider) return;

        const slides = Array.from(slider.querySelectorAll(".js-hero-slide"));
        if (slides.length === 0) return;

        let current = 0;
        const prevBtn = slider.querySelector("[data-hero-prev]");
        const nextBtn = slider.querySelector("[data-hero-next]");
        const dotsWrap = slider.querySelector("[data-hero-dots]");
        let autoTimer = null;
        const AUTO_DELAY = 5000;

        // Tạo dots theo số slide
        let dots = [];
        if (dotsWrap) {
            dots = slides.map((_, idx) => {
                const dot = document.createElement("button");
                dot.type = "button";
                dot.className = "hero-dot" + (idx === 0 ? " is-active" : "");
                dot.dataset.index = String(idx);
                dotsWrap.appendChild(dot);
                return dot;
            });
        }

        function goTo(index) {
            if (index < 0) index = slides.length - 1;
            if (index >= slides.length) index = 0;
            current = index;

            slides.forEach((s, i) => {
                s.classList.toggle("is-active", i === current);
            });
            dots.forEach((d, i) => {
                d.classList.toggle("is-active", i === current);
            });
        }

        function next() { goTo(current + 1); }
        function prev() { goTo(current - 1); }

        // Auto-play
        function startAuto() {
            stopAuto();
            autoTimer = setInterval(next, AUTO_DELAY);
        }
        function stopAuto() {
            if (autoTimer) clearInterval(autoTimer);
            autoTimer = null;
        }

        // Event mũi tên
        if (nextBtn) {
            nextBtn.addEventListener("click", () => {
                next();
                startAuto();
            });
        }
        if (prevBtn) {
            prevBtn.addEventListener("click", () => {
                prev();
                startAuto();
            });
        }

        // Event dots
        dots.forEach((dot) => {
            dot.addEventListener("click", () => {
                const i = parseInt(dot.dataset.index || "0", 10);
                goTo(i);
                startAuto();
            });
        });

        // Bắt đầu
        goTo(0);
        startAuto();
    })();

    // ============================================
    // 6. SWIPER INIT FOR RETRO SPORTS
    //    Carousel selector: .retroSportsSwiper
    // ============================================
    if (typeof Swiper !== 'undefined') {
        try {
            const retro = new Swiper('.retroSportsSwiper', {
                slidesPerView: 2,
                spaceBetween: 12,
                navigation: {
                    nextEl: '.retro-sports-next',
                    prevEl: '.retro-sports-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                    1280: { slidesPerView: 5 },
                },
            });
        } catch (err) {
            console.warn('Retro sports swiper init failed', err);
        }
    }

    // ============================================
    // 7. SWIPER INIT FOR JEANS
    //    Carousel selector: .jeansSwiper
    // ============================================
    if (typeof Swiper !== 'undefined') {
        try {
            const jeans = new Swiper('.jeansSwiper', {
                slidesPerView: 2,
                spaceBetween: 12,
                navigation: {
                    nextEl: '.jeans-next',
                    prevEl: '.jeans-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2 },
                    768: { slidesPerView: 3 },
                    1024: { slidesPerView: 4 },
                    1280: { slidesPerView: 5 },
                },
            });
        } catch (err) {
            console.warn('Jeans swiper init failed', err);
        }
    }

    // ============================================
    // 8. SWIPER INIT FOR CATEGORIES (thun/somi/polo)
    // ============================================

// 8. SWIPER INIT FOR CATEGORIES (thun/somi/polo)
// 8. SWIPER INIT FOR CATEGORIES (thun/somi/polo)
if (typeof Swiper !== 'undefined') {
    try {
        const thun = new Swiper('.thunSwiper', {
            slidesPerView: 2,
            spaceBetween: 0,
            navigation: { nextEl: '.thun-next', prevEl: '.thun-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 }   // desktop: 4 card
            }
        });

        const somi = new Swiper('.somiSwiper', {
            slidesPerView: 2,
            spaceBetween: 0,
            navigation: { nextEl: '.somi-next', prevEl: '.somi-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 }
            }
        });

        const polo = new Swiper('.poloSwiper', {
            slidesPerView: 2,
            spaceBetween: 0,
            navigation: { nextEl: '.polo-next', prevEl: '.polo-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 }
            }
        });
    } catch (err) {
        console.warn('Category swipers init failed', err);
    }
}




    // ============================================
    // 9. Tabs for category showcase
    // ============================================
    // SWIPER CHO QUẦN (short / jean / tay)
if (typeof Swiper !== 'undefined') {
    try {
        const shortSwiper = new Swiper('.shortSwiper', {
            slidesPerView: 2,
            spaceBetween: 12,
            navigation: { nextEl: '.short-next', prevEl: '.short-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
                1280: { slidesPerView: 4 },
            },
        });

        const jeanSwiper = new Swiper('.jeanSwiper', {
            slidesPerView: 2,
            spaceBetween: 12,
            navigation: { nextEl: '.jean-next', prevEl: '.jean-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
                1280: { slidesPerView: 4 },
            },
        });

        const taySwiper = new Swiper('.taySwiper', {
            slidesPerView: 2,
            spaceBetween: 12,
            navigation: { nextEl: '.tay-next', prevEl: '.tay-prev' },
            breakpoints: {
                640:  { slidesPerView: 2 },
                768:  { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
                1280: { slidesPerView: 4 },
            },
        });
    } catch (err) {
        console.warn('Bottom category swipers init failed', err);
    }
}


});
