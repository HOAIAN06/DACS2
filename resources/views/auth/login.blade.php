@extends('layouts.app')

@section('title', 'Đăng Nhập - HANZO')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16 bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-10 animate-fade-in">
            <div class="inline-block">
                <h1 class="text-5xl font-bold bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 bg-clip-text text-transparent mb-2">HANZO</h1>
                <div class="h-1 bg-gradient-to-r from-transparent via-slate-900 to-transparent rounded-full"></div>
            </div>
            <p class="text-slate-600 mt-3 font-medium">Thời trang nam hiện đại</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/50 p-8 space-y-6 animate-slide-up">
            <!-- Tab Navigation -->
            <div class="flex gap-2 bg-gradient-to-r from-slate-100 to-slate-50 p-1.5 rounded-xl shadow-inner">
                <button class="flex-1 py-3 px-4 rounded-lg font-semibold text-white bg-gradient-to-r from-slate-900 to-slate-700 shadow-lg shadow-slate-900/30 transition-all duration-300" id="login-tab">
                    Đăng Nhập
                </button>
                <a href="{{ route('register') }}" class="flex-1 py-3 px-4 rounded-lg font-medium text-slate-600 hover:text-slate-900 text-center transition-all duration-300 hover:bg-white/50">
                    Đăng Ký
                </a>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-800 text-sm">Lỗi xác thực</h3>
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
                    <p class="text-green-800 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5" id="login-form">
                @csrf

                <!-- Email Input -->
                <div class="group">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Địa chỉ Email
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 focus:bg-white transition-all duration-300"
                            
                            required
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div class="group">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                        Mật Khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 focus:bg-white transition-all duration-300"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors toggle-password" data-target="password">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="w-4 h-4 border-2 border-slate-300 rounded focus:ring-2 focus:ring-slate-900 text-slate-900 cursor-pointer transition-all"
                        >
                        <label for="remember" class="text-sm text-slate-600 cursor-pointer hover:text-slate-900 transition-colors">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <a href="{{ route('forgot.password') }}" class="text-sm text-slate-900 font-semibold hover:underline transition-all">
                        Quên mật khẩu?
                    </a>
                </div>

                <!-- Login Button -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-slate-900 to-slate-700 hover:from-slate-800 hover:to-slate-600 text-white font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl shadow-lg shadow-slate-900/30 active:scale-95"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Đăng Nhập
                    </span>
                </button>
            </form>

            <!-- Social Login removed as requested -->

            <!-- Footer -->
            <div class="text-center pt-4 border-t border-slate-200">
                <p class="text-slate-600 text-sm">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="text-slate-900 font-semibold hover:underline">
                        Đăng ký ngay
                    </a>
                </p>
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
        // Đảm bảo các overlay tìm kiếm/mobile không che phủ trang đăng nhập
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
