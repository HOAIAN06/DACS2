@extends('layouts.app')

@section('title', 'Xác Minh OTP - HANZO')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900">HANZO</h1>
            <p class="text-slate-600 mt-2">Xác minh mã OTP</p>
        </div>

        <!-- Verify OTP Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Xác Minh Mã OTP</h2>
                <p class="text-slate-600 text-sm">Chúng tôi đã gửi mã 6 chữ số đến <br><span class="font-semibold">{{ $email }}</span></p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-red-700 text-sm">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <p class="text-green-800 text-sm flex-1">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Verify OTP Form -->
            <form method="POST" action="{{ route('verify.otp') }}" class="space-y-6" id="otp-form">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Input Group -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">
                        Nhập Mã OTP (6 chữ số)
                    </label>
                    <div class="flex gap-2 justify-center" id="otp-inputs">
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                        <input type="text" maxlength="1" class="otp-digit w-12 h-12 text-center text-2xl font-bold border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition" inputmode="numeric" required>
                    </div>
                    <input type="hidden" name="otp" id="otp-hidden">
                </div>

                <!-- Timer -->
                <div class="text-center">
                    <p class="text-sm text-slate-600">
                        Mã OTP sẽ hết hạn trong <span id="timer" class="font-bold text-red-600">10:00</span>
                    </p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105 active:scale-95"
                    id="submit-btn"
                >
                    Xác Minh OTP
                </button>
            </form>

            <!-- Resend OTP -->
            <div class="text-center pt-4 border-t border-slate-200">
                <p class="text-slate-600 text-sm mb-3">
                    Không nhận được mã?
                </p>
                <a href="{{ route('forgot.password') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                    Yêu cầu mã OTP mới
                </a>
            </div>

            <!-- Help Info -->
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex items-start gap-2 text-xs text-purple-900">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                    </svg>
                    <p>Nhập mã OTP bạn nhận được qua email. Mã này chỉ có hiệu lực trong 10 phút.</p>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8 text-slate-600 text-xs">
            <p>&copy; 2025 HANZO. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-digit');
    const otpHidden = document.getElementById('otp-hidden');
    const form = document.getElementById('otp-form');
    
    // Auto-focus first input
    otpInputs[0].focus();
    
    // Handle input and auto-advance
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            
            // Only allow numbers
            if (!/^\d$/.test(value)) {
                e.target.value = '';
                return;
            }
            
            // Auto-advance to next input
            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            
            // Update hidden field
            updateOtpHidden();
        });
        
        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
        
        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').trim();
            
            if (/^\d{6}$/.test(pastedData)) {
                pastedData.split('').forEach((char, i) => {
                    if (otpInputs[i]) {
                        otpInputs[i].value = char;
                    }
                });
                otpInputs[5].focus();
                updateOtpHidden();
            }
        });
    });
    
    // Update hidden OTP field
    function updateOtpHidden() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpHidden.value = otp;
    }
    
    // Ensure OTP is updated before form submission
    form.addEventListener('submit', function(e) {
        updateOtpHidden();
        
        if (otpHidden.value.length !== 6) {
            e.preventDefault();
            alert('Vui lòng nhập đầy đủ 6 chữ số OTP');
            return false;
        }
    });
    
    // Timer countdown (10 minutes)
    let timeLeft = 600; // 10 minutes in seconds
    const timerElement = document.getElementById('timer');
    
    const countdown = setInterval(function() {
        timeLeft--;
        
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        
        timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            timerElement.textContent = '0:00';
            alert('Mã OTP đã hết hạn. Vui lòng yêu cầu mã mới.');
        }
    }, 1000);
});
</script>

@endsection
