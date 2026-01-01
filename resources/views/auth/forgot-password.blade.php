@extends('layouts.app')

@section('title', 'Quên Mật Khẩu - HANZO')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900">HANZO</h1>
            <p class="text-slate-600 mt-2">Khôi phục tài khoản của bạn</p>
        </div>

        <!-- Forgot Password Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Quên Mật Khẩu?</h2>
                <p class="text-slate-600 text-sm">Nhập email của bạn và chúng tôi sẽ gửi mã OTP để đặt lại mật khẩu</p>
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

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('send.otp') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">
                        Địa chỉ Email
                    </label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition"
                            placeholder="your@email.com"
                            required
                        >
                        <svg class="absolute right-3 top-3.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Chúng tôi sẽ gửi mã OTP (6 chữ số) đến email này</p>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105 active:scale-95"
                >
                    Gửi Mã OTP
                </button>
            </form>

            <!-- Divider -->
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-slate-500">hoặc</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center space-y-3">
                <p class="text-slate-600 text-sm">
                    Nhớ mật khẩu?
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:underline">
                        Đăng nhập
                    </a>
                </p>
                <p class="text-slate-600 text-sm">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                        Đăng ký ngay
                    </a>
                </p>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-900 text-sm mb-1">Mất bao lâu?</h4>
                    <p class="text-blue-800 text-xs">Mã OTP sẽ hết hạn sau 10 phút. Bạn có 5 lần nhập sai trước khi phải yêu cầu mã mới.</p>
                </div>
            </div>
        </div>

        <!-- Footer Text -->
        <div class="text-center mt-8 text-slate-600 text-xs">
            <p>&copy; 2025 HANZO. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Đảm bảo các overlay tìm kiếm/mobile không che phủ trang quên mật khẩu
        const searchOverlay = document.getElementById('hz-search-overlay');
        const searchModal = document.getElementById('hz-search-modal');
        const mobileOverlay = document.getElementById('hz-mobile-overlay');
        const mobileDrawer = document.getElementById('hz-mobile-drawer');

        if (searchOverlay) {
            searchOverlay.classList.remove('active');
            searchOverlay.style.display = 'none';
        }
        if (searchModal) {
            searchModal.classList.remove('active');
            searchModal.style.display = 'none';
        }
        if (mobileOverlay) {
            mobileOverlay.classList.remove('active');
            mobileOverlay.style.display = 'none';
        }
        if (mobileDrawer) {
            mobileDrawer.classList.remove('active');
            mobileDrawer.style.display = 'none';
        }
        document.body.classList.remove('overflow-hidden');
        document.body.style.overflow = '';
    });
</script>
@endpush

@endsection
