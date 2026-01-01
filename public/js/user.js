/**
 * User Account & Dashboard JavaScript
 * Xử lý các tương tác cho trang user dashboard, profile, đơn hàng
 */

class UserAccountManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeDataTables();
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Confirm dialogs
        document.querySelectorAll('[data-confirm]').forEach(el => {
            el.addEventListener('click', (e) => this.handleConfirm(e));
        });

        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => this.validateForm(e));
        });

        // Toggle password visibility
        document.querySelectorAll('[data-toggle-password]').forEach(btn => {
            btn.addEventListener('click', (e) => this.togglePasswordVisibility(e));
        });

        // Tab switching
        document.querySelectorAll('[data-tab]').forEach(tab => {
            tab.addEventListener('click', (e) => this.switchTab(e));
        });

        // Filter/Sort controls
        document.querySelectorAll('[data-filter-btn]').forEach(btn => {
            btn.addEventListener('click', (e) => this.applyFilters(e));
        });
    }

    /**
     * Handle confirmation dialogs
     */
    handleConfirm(e) {
        const message = e.target.dataset.confirm;
        if (!confirm(message)) {
            e.preventDefault();
        }
    }

    /**
     * Validate forms before submit
     */
    validateForm(e) {
        const form = e.target;
        const inputs = form.querySelectorAll('[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                this.showFieldError(input, 'Trường này không được để trống');
            } else {
                this.clearFieldError(input);
            }

            // Email validation
            if (input.type === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    isValid = false;
                    this.showFieldError(input, 'Email không hợp lệ');
                }
            }

            // Phone validation
            if (input.name === 'phone' && input.value) {
                const phoneRegex = /^[0-9]{9,11}$/;
                if (!phoneRegex.test(input.value.replace(/\s/g, ''))) {
                    isValid = false;
                    this.showFieldError(input, 'Số điện thoại không hợp lệ');
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    }

    /**
     * Show field error
     */
    showFieldError(input, message) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;

        // Remove existing error message
        const existingError = formGroup.querySelector('.error-message');
        if (existingError) existingError.remove();

        // Add error styles
        input.style.borderColor = '#ef4444';
        input.style.boxShadow = '0 0 0 3px rgba(239, 68, 68, 0.1)';

        // Add error message
        const errorEl = document.createElement('p');
        errorEl.className = 'error-message';
        errorEl.textContent = message;
        errorEl.style.cssText = 'color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem;';
        formGroup.appendChild(errorEl);
    }

    /**
     * Clear field error
     */
    clearFieldError(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;

        input.style.borderColor = '';
        input.style.boxShadow = '';

        const error = formGroup.querySelector('.error-message');
        if (error) error.remove();
    }

    /**
     * Toggle password visibility
     */
    togglePasswordVisibility(e) {
        const btn = e.currentTarget;
        const input = btn.closest('.form-group')?.querySelector('input[type="password"], input[type="text"]');
        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            btn.textContent = '👁️ Ẩn';
        } else {
            input.type = 'password';
            btn.textContent = '👁️ Hiện';
        }
    }

    /**
     * Switch tabs
     */
    switchTab(e) {
        const tabName = e.currentTarget.dataset.tab;
        
        // Hide all tabs
        document.querySelectorAll('[data-tab-content]').forEach(tab => {
            tab.style.display = 'none';
        });

        // Remove active class from buttons
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.classList.remove('active');
        });

        // Show selected tab
        const activeTab = document.querySelector(`[data-tab-content="${tabName}"]`);
        if (activeTab) {
            activeTab.style.display = 'block';
            e.currentTarget.classList.add('active');
        }
    }

    /**
     * Apply filters
     */
    applyFilters(e) {
        const form = e.currentTarget.closest('form');
        if (form) {
            form.submit();
        }
    }

    /**
     * Initialize data tables with sorting
     */
    initializeDataTables() {
        document.querySelectorAll('.order-table').forEach(table => {
            this.makeTableSortable(table);
        });
    }

    /**
     * Make table sortable by clicking on headers
     */
    makeTableSortable(table) {
        const headers = table.querySelectorAll('th');
        
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.style.userSelect = 'none';
            header.addEventListener('click', () => this.sortTable(table, index));
        });
    }

    /**
     * Sort table by column
     */
    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const isNumeric = rows.every(row => {
            const cell = row.cells[columnIndex];
            return !isNaN(parseFloat(cell.textContent));
        });

        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();

            if (isNumeric) {
                return parseFloat(aValue) - parseFloat(bValue);
            }
            return aValue.localeCompare(bValue);
        });

        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    /**
     * Format currency display
     */
    static formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    /**
     * Format date display
     */
    static formatDate(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('vi-VN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }

    /**
     * Show notification
     */
    static showNotification(message, type = 'info') {
        const notificationEl = document.createElement('div');
        notificationEl.className = `alert alert--${type}`;
        notificationEl.textContent = message;
        notificationEl.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';

        document.body.appendChild(notificationEl);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notificationEl.style.transition = 'opacity 0.3s';
            notificationEl.style.opacity = '0';
            setTimeout(() => notificationEl.remove(), 300);
        }, 3000);
    }

    /**
     * Load more data via AJAX
     */
    static async loadMore(url) {
        try {
            const response = await fetch(url);
            const html = await response.text();
            return html;
        } catch (error) {
            console.error('Error loading more data:', error);
            this.showNotification('Lỗi khi tải dữ liệu', 'error');
        }
    }
}

/**
 * Order Status Manager
 */
class OrderStatusManager {
    constructor() {
        this.statusMap = {
            'pending': { label: 'Chờ xác nhận', icon: '⏳', color: '#f59e0b' },
            'processing': { label: 'Đang xử lý', icon: '⚙️', color: '#3b82f6' },
            'shipping': { label: 'Đang giao', icon: '🚚', color: '#8b5cf6' },
            'completed': { label: 'Hoàn thành', icon: '✓', color: '#10b981' },
            'canceled': { label: 'Đã hủy', icon: '✕', color: '#ef4444' }
        };

        this.paymentMap = {
            'paid': { label: 'Đã thanh toán', icon: '✓', color: '#10b981' },
            'unpaid': { label: 'Chưa thanh toán', icon: '⏳', color: '#ef4444' },
            'refunded': { label: 'Đã hoàn tiền', icon: '↩️', color: '#3b82f6' }
        };
    }

    getStatusInfo(status) {
        return this.statusMap[status] || { label: 'Không xác định', icon: '?', color: '#6b7280' };
    }

    getPaymentInfo(status) {
        return this.paymentMap[status] || { label: 'Không xác định', icon: '?', color: '#6b7280' };
    }

    canCancelOrder(status) {
        return ['pending', 'processing'].includes(status);
    }
}

/**
 * Profile Form Manager
 */
class ProfileFormManager {
    constructor() {
        this.form = document.querySelector('form[action*="update-profile"]');
        if (this.form) {
            this.init();
        }
    }

    init() {
        this.form.addEventListener('change', () => this.enableSave());
    }

    enableSave() {
        const saveBtn = this.form.querySelector('button[type="submit"]');
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.style.opacity = '1';
        }
    }

    disableSave() {
        const saveBtn = this.form.querySelector('button[type="submit"]');
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.style.opacity = '0.5';
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new UserAccountManager();
    new OrderStatusManager();
    new ProfileFormManager();

    // Add convenience methods to window
    window.UserAccount = {
        showNotification: UserAccountManager.showNotification,
        formatCurrency: UserAccountManager.formatCurrency,
        formatDate: UserAccountManager.formatDate,
        loadMore: UserAccountManager.loadMore
    };
});
