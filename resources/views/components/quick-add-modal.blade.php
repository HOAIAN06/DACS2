{{-- =========================
   Modal Quick Add to Cart (Luxury)
   Giữ nguyên ID + input name + structure hooks cho JS
   ========================= --}}

<div id="hz-quick-add-modal" class="hz-quick-add-modal hidden fixed inset-0 z-[9999]">
    {{-- Backdrop (click để đóng) --}}
    <button type="button"
            class="hz-qa-backdrop absolute inset-0 w-full h-full bg-black/55"
            aria-label="Close modal"
            onclick="document.getElementById('hz-quick-add-modal').classList.add('hidden')"></button>

    {{-- Dialog wrapper --}}
    <div class="relative z-[10000] w-full max-w-5xl mx-auto px-4 py-8 flex items-center justify-center">
        <div class="hz-quick-add-content relative bg-white rounded-[28px] shadow-2xl overflow-hidden w-full">

            {{-- Close button --}}
            <button type="button"
                    class="hz-modal-close absolute top-5 right-5 w-11 h-11 bg-white/90 backdrop-blur rounded-full flex items-center justify-center border border-slate-200 shadow-lg z-50"
                    aria-label="Close"
                    onclick="document.getElementById('hz-quick-add-modal').classList.add('hidden')">
                <svg class="w-5 h-5 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2">

                {{-- LEFT: Image --}}
                <div class="relative bg-gradient-to-br from-slate-50 to-slate-100 p-7 md:p-10 flex items-center justify-center min-h-[360px] md:min-h-[540px]">
                    <div class="w-full max-w-md">
                        <div class="rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">
                            <div class="aspect-[4/5] w-full flex items-center justify-center p-6">
                                <img id="hz-qa-image"
                                     src=""
                                     alt="Sản phẩm"
                                     class="w-full h-full object-contain"
                                     onerror="this.onerror=null; this.src='{{ asset('images/placeholder.png') }}';">
                            </div>
                        </div>

                
                    </div>
                </div>

                {{-- RIGHT: Info + Form --}}
                <div class="p-7 md:p-10 flex flex-col max-h-[85vh] overflow-y-auto">
                    <div class="flex-grow">

                        {{-- Category --}}
                        <p id="hz-qa-category"
                           class="text-[11px] text-slate-500 font-semibold uppercase tracking-[0.22em] mb-3"></p>

                        {{-- Name --}}
                        <h2 id="hz-qa-name"
                            class="text-2xl md:text-3xl font-extrabold text-slate-900 leading-tight mb-4"></h2>

                        {{-- Price --}}
                        <div class="pb-6 mb-6 border-b border-slate-200">
                            <div class="flex items-end gap-3">
                                <span id="hz-qa-price" class="text-3xl font-black text-slate-900"></span>
                                <span id="hz-qa-old-price" class="text-lg text-slate-400 line-through font-semibold hidden"></span>
                            </div>
                        </div>

                        {{-- Color --}}
                        <div id="hz-qa-color-section" class="mb-6 hidden">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-bold text-slate-900">
                                    Màu sắc <span class="text-red-500">*</span>
                                </label>
                                <span id="hz-qa-color-picked" class="text-xs text-slate-500"></span>
                            </div>
                            <div id="hz-qa-colors" class="flex flex-wrap gap-2"></div>
                        </div>

                        {{-- Size --}}
                        <div id="hz-qa-size-section" class="mb-6 hidden">
                            <div class="flex items-center justify-between mb-3">
                                <label class="text-sm font-bold text-slate-900">
                                    Kích thước <span class="text-red-500">*</span>
                                </label>
                                <span id="hz-qa-size-picked" class="text-xs text-slate-500"></span>
                            </div>
                            <div id="hz-qa-sizes" class="grid grid-cols-4 gap-2"></div>
                        </div>

                        {{-- Quantity --}}
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-900 mb-3">Số lượng</label>
                            <div class="inline-flex items-center rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                                <button type="button"
                                        id="hz-qa-qty-minus"
                                        class="w-12 h-12 grid place-items-center text-lg font-bold text-slate-900 hover:bg-slate-50">
                                    −
                                </button>

                                <input type="number"
                                       id="hz-qa-qty"
                                       value="1"
                                       min="1"
                                       class="w-16 h-12 text-center text-base font-bold text-slate-900 outline-none border-x border-slate-200">

                                <button type="button"
                                        id="hz-qa-qty-plus"
                                        class="w-12 h-12 grid place-items-center text-lg font-bold text-slate-900 hover:bg-slate-50">
                                    +
                                </button>
                            </div>
                        </div>

                        {{-- Warning --}}
                        <div id="hz-qa-warning"
                             class="hidden mb-5 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                            ⚠️ Vui lòng chọn <strong id="hz-qa-warning-fields">màu và kích thước</strong> trước khi thêm vào giỏ
                        </div>
                    </div>

                    {{-- Form --}}
                    <form id="hz-qa-form" method="POST" action="{{ route('cart.add') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="product_id" id="hz-qa-product-id">
                        <input type="hidden" name="variant_id" id="hz-qa-variant-id">
                        <input type="hidden" name="qty" id="hz-qa-qty-input">
                        <input type="hidden" name="action" value="add_to_cart">

                        <button type="submit"
                                class="w-full rounded-xl py-4 font-bold text-base bg-slate-900 text-white hover:bg-slate-800 active:scale-[0.99] transition shadow-md">
                            Thêm Vào Giỏ
                        </button>

                        <button type="button"
                                id="hz-qa-buy-now"
                                class="w-full rounded-xl py-4 font-bold text-base border-2 border-slate-900 text-slate-900 hover:bg-slate-900 hover:text-white active:scale-[0.99] transition">
                            Mua Ngay
                        </button>
                    </form>

                    {{-- Trust --}}
                    <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-center gap-4 text-xs text-slate-600">
                        <span class="inline-flex items-center gap-1"><span class="text-emerald-600">✓</span> Miễn phí vận chuyển</span>
                        <span class="opacity-40">•</span>
                        <span class="inline-flex items-center gap-1"><span class="text-emerald-600">✓</span> Đổi trả 15 ngày</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
