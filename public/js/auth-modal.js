/**
 * Auth Modal Handler
 * Xử lý hiển thị/ẩn login/register modal trong header
 */

(function() {
    'use strict';

    // =====================================
    // Modal Open/Close
    // =====================================
    const modal = document.getElementById('hz-auth-modal');
    
    // Chỉ chạy nếu modal tồn tại (tránh crash)
    if (!modal) return;
    
    const openBtn = document.getElementById('hz-login-modal-open');
    const closeBtn = document.getElementById('hz-auth-modal-close');
    const tabButtons = document.querySelectorAll('.hz-auth-tab');
    const forms = document.querySelectorAll('.hz-auth-form');

    if (openBtn) {
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    // Close modal khi click vào overlay
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // =====================================
    // Tab Switching
    // =====================================
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');

            // Update active tab button
            tabButtons.forEach(b => {
                b.classList.remove('text-slate-900', 'bg-white', 'shadow-sm');
                b.classList.add('text-slate-600');
            });
            this.classList.remove('text-slate-600');
            this.classList.add('text-slate-900', 'bg-white', 'shadow-sm');

            // Show/hide forms
            forms.forEach(form => {
                if (form.getAttribute('data-form') === tab) {
                    form.classList.remove('hidden');
                } else {
                    form.classList.add('hidden');
                }
            });
        });
    });

    // =====================================
    // Password Visibility Toggle in Modal
    // =====================================
    const togglePasswordBtns = document.querySelectorAll('.toggle-password-modal');

    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);

            if (input.type === 'password') {
                input.type = 'text';
                this.classList.add('text-slate-600');
            } else {
                input.type = 'password';
                this.classList.remove('text-slate-600');
            }
        });
    });

    // =====================================
    // Form Submission (closes modal on success)
    // =====================================
    const loginForm = document.getElementById('hz-login-form');
    const registerForm = document.getElementById('hz-register-form');

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            // Form akan submit bình thường, server sẽ redirect
        });
    }

    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Form validation được xử lý ở trang register
        });
    }

    // =====================================
    // Close modal with Escape key
    // =====================================
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    console.log('✓ Auth modal initialized');
})();
