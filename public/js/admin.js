/**
 * Admin Dashboard & Management JavaScript
 * Xử lý các tương tác cho admin pages (dashboard, orders, products, categories)
 */

class AdminDashboardManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeCharts();
        this.initializeDataTables();
    }

    /**
     * Setup event listeners for admin actions
     */
    setupEventListeners() {
        // Confirm before delete
        const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
        console.log('Found delete buttons:', deleteButtons.length);
        
        deleteButtons.forEach(el => {
            el.addEventListener('click', (e) => {
                console.log('Delete button clicked');
                this.handleDeleteConfirm(e);
            });
        });

        // Status update buttons
        document.querySelectorAll('[data-update-status]').forEach(el => {
            el.addEventListener('click', (e) => this.handleStatusUpdate(e));
        });

        // Quick actions
        document.querySelectorAll('[data-quick-action]').forEach(el => {
            el.addEventListener('click', (e) => this.handleQuickAction(e));
        });

        // Filter controls
        document.querySelectorAll('[data-filter-btn]').forEach(btn => {
            btn.addEventListener('click', (e) => this.applyFilters(e));
        });

        // Form validation
        document.querySelectorAll('form[data-admin-form]').forEach(form => {
            form.addEventListener('submit', (e) => this.validateAdminForm(e));
        });
    }

    /**
     * Handle delete confirmation
     */
    handleDeleteConfirm(e) {
        console.log('handleDeleteConfirm called');
        e.preventDefault();
        e.stopPropagation();
        
        const button = e.target;
        const message = button.dataset.confirmDelete || 'Bạn chắc chắn muốn xóa mục này?';
        const form = button.closest('form');
        
        console.log('Message:', message);
        console.log('Form:', form);
        
        this.showConfirmDialog(message, () => {
            console.log('Confirmed - submitting form');
            if (form) {
                // Create a hidden input to bypass the event listener
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_confirmed';
                input.value = '1';
                form.appendChild(input);
                form.submit();
            }
        });
    }

    /**
     * Handle status update
     */
    handleStatusUpdate(e) {
        e.preventDefault();
        const status = e.target.dataset.updateStatus;
        const orderId = e.target.dataset.orderId;

        if (!status || !orderId) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/orders/${orderId}/status`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="status" value="${status}">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Handle quick actions
     */
    handleQuickAction(e) {
        const action = e.target.dataset.quickAction;
        const data = e.target.dataset;

        switch(action) {
            case 'toggle-active':
                this.toggleProductActive(data.productId);
                break;
            case 'edit':
                window.location.href = e.target.href;
                break;
            case 'view':
                window.location.href = e.target.href;
                break;
        }
    }

    /**
     * Toggle product active status
     */
    toggleProductActive(productId) {
        if (!confirm('Bạn muốn đổi trạng thái sản phẩm này?')) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}/toggle`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="POST">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Validate admin forms before submit
     */
    validateAdminForm(e) {
        const form = e.target;
        const inputs = form.querySelectorAll('[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                this.showFieldError(input, 'Trường này không được để trống');
                isValid = false;
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
        const formGroup = input.closest('.admin-form-group');
        if (!formGroup) return;

        let errorEl = formGroup.querySelector('.field-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'field-error text-red-600 text-xs mt-1';
            formGroup.appendChild(errorEl);
        }
        errorEl.textContent = message;
        input.classList.add('border-red-500');
    }

    /**
     * Clear field error
     */
    clearFieldError(input) {
        const formGroup = input.closest('.admin-form-group');
        if (!formGroup) return;

        const errorEl = formGroup.querySelector('.field-error');
        if (errorEl) errorEl.remove();
        input.classList.remove('border-red-500');
    }

    /**
     * Apply filters to admin tables
     */
    applyFilters(e) {
        const filterForm = e.target.closest('form[data-filter-form]');
        if (filterForm) {
            filterForm.submit();
        }
    }

    /**
     * Initialize charts (e.g., revenue chart)
     */
    initializeCharts() {
        // Chart initialization handled by specific pages via inline JS
    }

    /**
     * Initialize data tables with sorting
     */
    initializeDataTables() {
        document.querySelectorAll('[data-sortable-table]').forEach(table => {
            this.makeTableSortable(table);
        });
    }

    /**
     * Make table sortable by clicking headers
     */
    makeTableSortable(table) {
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                const columnIndex = Array.from(headers).indexOf(header);
                this.sortTable(table, columnIndex);
            });
        });
    }

    /**
     * Sort table by column
     */
    sortTable(table, columnIndex) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.sort((a, b) => {
            const aValue = a.cells[columnIndex].textContent.trim();
            const bValue = b.cells[columnIndex].textContent.trim();

            if (!isNaN(aValue) && !isNaN(bValue)) {
                return parseFloat(aValue) - parseFloat(bValue);
            }
            return aValue.localeCompare(bValue);
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    /**
     * Show notification
     */
    static showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `admin-alert admin-alert--${type}`;
        notification.textContent = message;
        document.body.insertBefore(notification, document.body.firstChild);

        setTimeout(() => notification.remove(), 5000);
    }

    /**
     * Show custom confirmation dialog
     */
    showConfirmDialog(message, onConfirm, onCancel = null) {
        const dialog = document.createElement('div');
        dialog.className = 'admin-confirm-overlay';
        dialog.innerHTML = `
            <div class="admin-confirm-dialog">
                <div class="admin-confirm-header">
                    <div class="admin-confirm-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </div>
                </div>
                <div class="admin-confirm-content">
                    <h3 class="admin-confirm-title">Xác nhận hành động</h3>
                    <p class="admin-confirm-message">${message}</p>
                </div>
                <div class="admin-confirm-actions">
                    <button class="admin-btn admin-btn-cancel">Hủy</button>
                    <button class="admin-btn admin-btn-delete">Xác nhận xóa</button>
                </div>
            </div>
        `;

        document.body.appendChild(dialog);

        const btnCancel = dialog.querySelector('.admin-btn-cancel');
        const btnConfirm = dialog.querySelector('.admin-btn-delete');

        const closeDialog = () => {
            dialog.classList.add('fade-out');
            setTimeout(() => dialog.remove(), 300);
        };

        btnCancel.addEventListener('click', () => {
            closeDialog();
            if (onCancel) onCancel();
        });

        btnConfirm.addEventListener('click', () => {
            closeDialog();
            if (onConfirm) onConfirm();
        });

        dialog.addEventListener('click', (e) => {
            if (e.target === dialog) closeDialog();
        });

        // Auto focus confirm button
        setTimeout(() => btnConfirm.focus(), 100);
    }
}

/**
 * Order Management
 */
class AdminOrderManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupOrderActions();
    }

    setupOrderActions() {
        document.querySelectorAll('[data-order-action]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleOrderAction(e));
        });
    }

    handleOrderAction(e) {
        const action = e.target.dataset.orderAction;
        const orderId = e.target.dataset.orderId;

        if (action === 'view') {
            window.location.href = `/admin/orders/${orderId}`;
        }
    }
}

/**
 * Product Management
 */
class AdminProductManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupProductActions();
        this.setupImageUpload();
    }

    setupProductActions() {
        document.querySelectorAll('[data-product-action]').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleProductAction(e));
        });
    }

    handleProductAction(e) {
        const action = e.target.dataset.productAction;
        const productId = e.target.dataset.productId;

        switch(action) {
            case 'edit':
                window.location.href = `/admin/products/${productId}/edit`;
                break;
            case 'delete':
                if (confirm('Xóa sản phẩm này? Hành động không thể hoàn tác!')) {
                    this.deleteProduct(productId);
                }
                break;
        }
    }

    deleteProduct(productId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/products/${productId}`;
        form.innerHTML = `
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);
        form.submit();
    }

    setupImageUpload() {
        const imageInputs = document.querySelectorAll('[data-image-upload]');
        imageInputs.forEach(input => {
            input.addEventListener('change', (e) => this.handleImageUpload(e));
        });
    }

    handleImageUpload(e) {
        const preview = e.target.dataset.imagePreview;
        const previewEl = document.querySelector(preview);
        if (!previewEl) return;

        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (event) => {
            previewEl.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboardManager();
    new AdminOrderManager();
    new AdminProductManager();

    // Add convenience methods to window
    window.AdminUtils = {
        showNotification: AdminDashboardManager.showNotification
    };
});
