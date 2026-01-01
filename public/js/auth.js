/**
 * HANZO Auth Page JavaScript
 * Handles form interactions, validations, and animations
 */

(function() {
    'use strict';

    // =====================================
    // Password Visibility Toggle
    // =====================================
    function initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.add('text-slate-600');
                    this.classList.remove('text-slate-400');
                } else {
                    input.type = 'password';
                    this.classList.remove('text-slate-600');
                    this.classList.add('text-slate-400');
                }
            });
        });
    }

    // =====================================
    // Password Strength Checker
    // =====================================
    function initPasswordStrength() {
        const passwordInput = document.getElementById('password');
        
        if (!passwordInput) return;

        passwordInput.addEventListener('input', function() {
            validatePasswordStrength(this.value);
        });

        // Initial check on page load
        if (passwordInput.value) {
            validatePasswordStrength(passwordInput.value);
        }
    }

    function validatePasswordStrength(password) {
        const checks = {
            length: {
                element: document.getElementById('length-check'),
                test: () => password.length >= 8
            },
            uppercase: {
                element: document.getElementById('uppercase-check'),
                test: () => /[A-Z]/.test(password)
            },
            lowercase: {
                element: document.getElementById('lowercase-check'),
                test: () => /[a-z]/.test(password)
            },
            number: {
                element: document.getElementById('number-check'),
                test: () => /[0-9]/.test(password)
            }
        };

        Object.values(checks).forEach(check => {
            if (check.element) {
                const isValid = check.test();
                if (isValid) {
                    check.element.classList.remove('text-red-500');
                    check.element.classList.add('text-green-500');
                    check.element.innerHTML = check.element.innerHTML.replace(/^[^✓]*/, '✓ ');
                } else {
                    check.element.classList.add('text-red-500');
                    check.element.classList.remove('text-green-500');
                    check.element.innerHTML = check.element.innerHTML.replace(/^✓ /, '');
                }
            }
        });
    }

    // =====================================
    // Password Confirmation Check
    // =====================================
    function initPasswordConfirmation() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const confirmCheck = document.getElementById('confirm-check');

        if (!confirmInput) return;

        const checkMatch = () => {
            if (!passwordInput.value || !confirmInput.value) return;

            const match = passwordInput.value === confirmInput.value;
            
            if (match) {
                confirmCheck.classList.add('hidden');
                confirmInput.classList.remove('border-red-500');
                confirmInput.classList.add('border-green-500');
            } else {
                confirmCheck.classList.remove('hidden');
                confirmInput.classList.add('border-red-500');
                confirmInput.classList.remove('border-green-500');
            }
        };

        passwordInput?.addEventListener('input', checkMatch);
        confirmInput.addEventListener('input', checkMatch);
    }

    // =====================================
    // Form Validation
    // =====================================
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });
        });
    }

    function validateForm(form) {
        // Check if it's a register form (có password_confirmation)
        const confirmPassword = form.querySelector('#password_confirmation');
        
        if (!confirmPassword) return true; // Login form is handled by Laravel validation
        
        const password = form.querySelector('#password');
        
        // Password match validation
        if (password.value !== confirmPassword.value) {
            showError('Mật khẩu xác nhận không khớp');
            return false;
        }

        // Không ràng buộc độ mạnh mật khẩu theo yêu cầu đồ án
        return true;
    }

    function isPasswordStrong(password) {
        return (
            password.length >= 8 &&
            /[A-Z]/.test(password) &&
            /[a-z]/.test(password) &&
            /[0-9]/.test(password)
        );
    }

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-50 border border-red-200 rounded-lg p-4 flex items-start gap-3';
        errorDiv.innerHTML = `
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p class="text-red-800 text-sm flex-1">${message}</p>
        `;

        const form = document.querySelector('form');
        form.insertBefore(errorDiv, form.firstChild);

        // Auto remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // =====================================
    // Input Focus Effects
    // =====================================
    function initInputEffects() {
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-slate-900');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-slate-900');
            });
        });
    }

    // =====================================
    // Email Validation
    // =====================================
    function initEmailValidation() {
        const emailInputs = document.querySelectorAll('input[type="email"]');
        
        emailInputs.forEach(input => {
            input.addEventListener('blur', function() {
                const email = this.value.trim();
                const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                
                if (email && !isValid) {
                    this.classList.add('border-red-500');
                    this.classList.remove('border-slate-300');
                } else {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-slate-300');
                }
            });
        });
    }

    // =====================================
    // Real-time Form Validation (Optional)
    // =====================================
    function initRealtimeValidation() {
        const form = document.getElementById('register-form');
        
        if (!form) return;

        const nameInput = form.querySelector('#name');
        const emailInput = form.querySelector('#email');
        const passwordInput = form.querySelector('#password');

        // Name validation
        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                if (this.value.trim().length < 3) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        }

        // Email validation on blur
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    this.classList.add('border-red-500');
                } else {
                    this.classList.remove('border-red-500');
                }
            });
        }
    }

    // =====================================
    // Show/Hide Loading State
    // =====================================
    function initSubmitButton() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Chỉ disable nếu form không bị prevent (validation pass)
                if (!e.defaultPrevented) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⏳</span>Đang xử lý...';
                    }
                }
            });
        });
    }

    // =====================================
    // Initialize All Functions
    // =====================================
    document.addEventListener('DOMContentLoaded', function() {
        initPasswordToggle();
        initPasswordStrength();
        initPasswordConfirmation();
        initFormValidation();
        initInputEffects();
        initEmailValidation();
        initRealtimeValidation();
        initSubmitButton();

        console.log('✓ Auth page initialized successfully');
    });

    // =====================================
    // OTP Input Handling (Verify OTP Page)
    // =====================================
    function initOtpHandling() {
        const inputs = document.querySelectorAll('.otp-digit');
        if (inputs.length === 0) return;

        const otpHidden = document.getElementById('otp-hidden');
        const form = document.getElementById('otp-form');
        const submitBtn = document.getElementById('submit-btn');

        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Chỉ cho phép số
                this.value = this.value.replace(/[^0-9]/g, '');

                // Tự động chuyển sang input tiếp theo
                if (this.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // Cập nhật hidden input
                updateOtpValue();
            });

            input.addEventListener('keydown', function(e) {
                // Xóa ký tự trước đó
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/[^0-9]/g, '').split('');
                
                digits.forEach((digit, i) => {
                    if (index + i < inputs.length) {
                        inputs[index + i].value = digit;
                    }
                });
                updateOtpValue();
            });
        });

        function updateOtpValue() {
            const otp = Array.from(inputs).map(i => i.value).join('');
            if (otpHidden) otpHidden.value = otp;
        }

        // Form Submit
        if (form) {
            form.addEventListener('submit', function(e) {
                const otp = Array.from(inputs).map(i => i.value).join('');
                if (otp.length !== 6) {
                    e.preventDefault();
                    alert('Vui lòng nhập đầy đủ 6 chữ số');
                }
            });
        }

        // Timer Countdown (10 minutes = 600 seconds)
        const timerElement = document.getElementById('timer');
        if (timerElement) {
            let timeLeft = 600;

            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft === 0) {
                    timerElement.classList.add('text-red-600');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                    clearInterval(timerInterval);
                }
                timeLeft--;
            }

            const timerInterval = setInterval(updateTimer, 1000);
        }

        // Focus first input on load
        inputs[0].focus();
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        console.log('Auth page unloading');
    });

    // Initialize OTP handling if on OTP page
    document.addEventListener('DOMContentLoaded', initOtpHandling);
})();
