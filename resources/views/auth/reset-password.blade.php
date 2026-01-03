@extends('layouts.app')

@section('title', 'Đặt Lại Mật Khẩu - HANZO')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md">
        <!-- Logo / Brand -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-slate-900">HANZO</h1>
            <p class="text-slate-600 mt-2">Đặt lại mật khẩu mới</p>
        </div>

        <!-- Reset Password Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">
            <!-- Header -->
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Đặt Lại Mật Khẩu</h2>
                <p class="text-slate-600 text-sm">Tạo mật khẩu mới cho tài khoản của bạn</p>
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

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('reset.password') }}" class="space-y-4" id="reset-form" data-validate="true">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- New Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                        Mật Khẩu Mới
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">
                        Xác Nhận Mật Khẩu
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent transition"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-lg transition duration-300 transform hover:scale-105 active:scale-95"
                    id="submit-btn"
                >
                    Đặt Lại Mật Khẩu
                </button>
            </form>

            <!-- Help Info -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start gap-2 text-xs text-green-900">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 9a1 1 0 100-2 1 1 0 000 2zm5-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                    </svg>
                    <p>Mật khẩu mới phải mạnh và dễ nhớ. Không chia sẻ mật khẩu của bạn với bất kỳ ai.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-4 border-t border-slate-200">
                <p class="text-slate-600 text-sm">
                    <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">
                        ← Quay lại đăng nhập
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
@endsection
