{{-- Modal xác nhận tùy chỉnh --}}
<div id="hz-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" id="hz-confirm-backdrop"></div>

    {{-- Dialog --}}
    <div class="relative bg-white rounded-xl shadow-2xl max-w-sm w-[90%] overflow-hidden transform transition-all">
        {{-- Icon & Title --}}
        <div class="px-6 pt-6 pb-4 bg-gradient-to-r from-slate-50 to-slate-100 border-b border-slate-200">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0a9 9 0 11-18 0 9 9 0 0118 0" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-slate-900" id="hz-confirm-title">Xác nhận</h3>
                    <p class="text-sm text-slate-600 mt-1" id="hz-confirm-message">Bạn chắc chắn không?</p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-4" id="hz-confirm-body"></div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex gap-3 justify-end">
            <button type="button" id="hz-confirm-cancel" class="px-4 py-2 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors">
                Hủy
            </button>
            <button type="button" id="hz-confirm-action" class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors">
                Xác nhận
            </button>
        </div>
    </div>
</div>

<script>
class ConfirmModal {
    constructor() {
        this.modal = document.getElementById('hz-confirm-modal');
        this.backdrop = document.getElementById('hz-confirm-backdrop');
        this.cancelBtn = document.getElementById('hz-confirm-cancel');
        this.actionBtn = document.getElementById('hz-confirm-action');
        this.titleEl = document.getElementById('hz-confirm-title');
        this.messageEl = document.getElementById('hz-confirm-message');
        this.bodyEl = document.getElementById('hz-confirm-body');
        
        this.actionBtn.addEventListener('click', () => this.confirm());
        this.cancelBtn.addEventListener('click', () => this.close());
        this.backdrop.addEventListener('click', () => this.close());
        
        this.callback = null;
        this.form = null;
    }

    show({ title = 'Xác nhận', message = 'Bạn chắc chắn không?', actionText = 'Xác nhận', callback = null, form = null }) {
        this.titleEl.textContent = title;
        this.messageEl.textContent = message;
        this.actionBtn.textContent = actionText;
        this.callback = callback;
        this.form = form;
        this.bodyEl.innerHTML = '';
        
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Animation
        setTimeout(() => {
            this.modal.querySelector('div:nth-child(2)').classList.add('scale-100', 'opacity-100');
            this.modal.querySelector('div:nth-child(2)').classList.remove('scale-95', 'opacity-0');
        }, 0);
    }

    confirm() {
        if (this.callback) {
            this.callback();
        } else if (this.form) {
            this.form.submit();
        }
        this.close();
    }

    close() {
        this.modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

window.confirmModal = new ConfirmModal();
</script>

<style>
#hz-confirm-modal:not(.hidden) div:nth-child(2) {
    @apply scale-95 opacity-0;
}
</style>
