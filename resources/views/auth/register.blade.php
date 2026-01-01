@extends('layouts.app')

@section('title', 'Đăng Ký - HANZO')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16 bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-10 animate-fade-in">
            <div class="inline-block">
                <h1 class="text-5xl font-bold bg-gradient-to-r from-slate-900 via-slate-700 to-slate-900 bg-clip-text text-transparent mb-2">HANZO</h1>
                <div class="h-1 bg-gradient-to-r from-transparent via-slate-900 to-transparent rounded-full"></div>
            </div>
            <p class="text-slate-600 mt-3 font-medium">Gia nhập cộng đồng thời trang nam</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-200/50 p-8 space-y-6 animate-slide-up">
            <!-- Tab Navigation -->
            <div class="flex gap-2 bg-gradient-to-r from-slate-100 to-slate-50 p-1.5 rounded-xl shadow-inner">
                <a href="{{ route('login') }}" class="flex-1 py-3 px-4 rounded-lg font-medium text-slate-600 hover:text-slate-900 text-center transition-all duration-300 hover:bg-white/50">
                    Đăng Nhập
                </a>
                <button class="flex-1 py-3 px-4 rounded-lg font-semibold text-white bg-gradient-to-r from-slate-900 to-slate-700 shadow-lg shadow-slate-900/30 transition-all duration-300" id="register-tab">
                    Đăng Ký
                </button>
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

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5" id="register-form" data-validate="true">
                @csrf

                <!-- Name Input -->
                <div class="group">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Họ và Tên
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 focus:bg-white transition-all duration-300"
                            placeholder="Nguyễn Văn A"
                            required
                        >
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

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
                            placeholder="your@email.com"
                            required
                        >
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <!-- Password strength hints removed per request -->
                </div>

                <!-- Confirm Password Input -->
                <div class="group">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                        Xác Nhận Mật Khẩu
                    </label>
                    <div class="relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-slate-900 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full pl-12 pr-12 py-3.5 bg-slate-50 border-2 border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 focus:bg-white transition-all duration-300"
                            placeholder="••••••••"
                            required
                        >
                        <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-900 transition-colors toggle-password" data-target="password_confirmation">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <p id="confirm-check" class="text-red-500 text-xs mt-1.5 hidden flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Mật khẩu xác nhận không khớp
                    </p>
                </div>

                <!-- Terms & Conditions removed as requested -->

                <!-- Register Button -->
                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-slate-900 to-slate-700 hover:from-slate-800 hover:to-slate-600 text-white font-bold py-4 rounded-xl transition-all duration-300 transform hover:scale-[1.02] hover:shadow-xl shadow-lg shadow-slate-900/30 active:scale-95"
                    id="submit-btn"
                >
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Đăng Ký
                    </span>
                </button>
            </form>

            <!-- Social Register removed as requested -->

            <!-- Footer -->
            <div class="text-center pt-4 border-t border-slate-200">
                <p class="text-slate-600 text-sm">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-slate-900 font-semibold hover:underline">
                        Đăng nhập
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
        // Đảm bảo các overlay tìm kiếm/mobile không che phủ trang đăng ký
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
