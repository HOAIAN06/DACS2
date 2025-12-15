// public/js/hanzo.js

document.addEventListener("DOMContentLoaded", () => {
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
        if (!section) return;

        const tabs = section.querySelectorAll('.highlight-tab');
        const panels = section.querySelectorAll('.highlight-panel');

        function activate(key) {
            tabs.forEach(t => {
                const active = t.dataset.highlightTab === key;
                t.classList.toggle('text-black', active);
                t.classList.toggle('border-black', active);
                t.classList.toggle('text-slate-500', !active);
                t.classList.toggle('border-transparent', !active);
            });

            panels.forEach(p => {
                const show = p.dataset.highlightPanel === key;
                p.classList.toggle('hidden', !show);
            });
        }

        tabs.forEach(t => {
            t.addEventListener('click', (e) => {
                if (t.disabled) return;
                e.preventDefault();
                activate(t.dataset.highlightTab);
            });
        });

        // init: ưu tiên hàng mới, fallback panel đầu tiên
        const defaultKey = section.querySelector('.highlight-tab[data-highlight-tab="new"]') ? 'new' : (tabs[0]?.dataset.highlightTab);
        if (defaultKey) activate(defaultKey);
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
