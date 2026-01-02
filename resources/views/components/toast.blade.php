{{-- Toast Notification System --}}
<div id="hz-toast-container" class="fixed top-6 right-6 z-50 space-y-3 pointer-events-none"></div>

<script>
class Toast {
    constructor(message, type = 'success', duration = 3000) {
        this.message = message;
        this.type = type;
        this.duration = duration;
        this.element = null;
        this.show();
    }

    show() {
        const container = document.getElementById('hz-toast-container');
        this.element = document.createElement('div');
        
        const typeConfig = {
            success: {
                bgColor: 'bg-green-50',
                borderColor: 'border-green-200',
                textColor: 'text-green-800',
                icon: '✓',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-600',
            },
            error: {
                bgColor: 'bg-red-50',
                borderColor: 'border-red-200',
                textColor: 'text-red-800',
                icon: '✕',
                iconBg: 'bg-red-100',
                iconColor: 'text-red-600',
            },
            warning: {
                bgColor: 'bg-amber-50',
                borderColor: 'border-amber-200',
                textColor: 'text-amber-800',
                icon: '⚠',
                iconBg: 'bg-amber-100',
                iconColor: 'text-amber-600',
            },
            info: {
                bgColor: 'bg-blue-50',
                borderColor: 'border-blue-200',
                textColor: 'text-blue-800',
                icon: 'ℹ',
                iconBg: 'bg-blue-100',
                iconColor: 'text-blue-600',
            }
        };

        const config = typeConfig[this.type] || typeConfig.success;

        this.element.className = `
            ${config.bgColor} border ${config.borderColor} rounded-lg px-4 py-3
            flex items-center gap-3 shadow-lg pointer-events-auto
            transform transition-all duration-300 ease-in-out
            translate-x-0 opacity-100
        `;

        this.element.innerHTML = `
            <div class="flex-shrink-0 w-6 h-6 rounded-full ${config.iconBg} flex items-center justify-center text-sm font-bold ${config.iconColor}">
                ${config.icon}
            </div>
            <p class="flex-1 text-sm font-medium ${config.textColor}">${this.message}</p>
            <button type="button" class="flex-shrink-0 ${config.iconColor} hover:opacity-70 transition">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        `;

        container.appendChild(this.element);

        // Close button
        this.element.querySelector('button').addEventListener('click', () => this.close());

        // Auto close
        if (this.duration > 0) {
            setTimeout(() => this.close(), this.duration);
        }
    }

    close() {
        if (!this.element) return;
        
        this.element.classList.add('translate-x-96', 'opacity-0');
        setTimeout(() => {
            if (this.element && this.element.parentNode) {
                this.element.parentNode.removeChild(this.element);
            }
        }, 300);
    }

    static success(message, duration = 3000) {
        return new Toast(message, 'success', duration);
    }

    static error(message, duration = 4000) {
        return new Toast(message, 'error', duration);
    }

    static warning(message, duration = 3500) {
        return new Toast(message, 'warning', duration);
    }

    static info(message, duration = 3000) {
        return new Toast(message, 'info', duration);
    }
}

window.Toast = Toast;
</script>
