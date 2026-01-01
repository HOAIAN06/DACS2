@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân - HANZO')

@section('content')
<div class="bg-gradient-to-b from-slate-50 to-white min-h-screen py-8">
    <div class="hanzo-container px-3">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 mb-2">Hồ sơ cá nhân</h1>
            <p class="text-slate-600">Quản lý thông tin tài khoản của bạn</p>
        </div>

        {{-- Grid Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-slate-200 overflow-hidden sticky top-8">
                    {{-- Profile Section --}}
                    <div class="p-6 bg-gradient-to-br from-slate-900 to-slate-800 text-white">
                        <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold mb-3">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <p class="font-semibold text-lg mb-1">{{ Auth::user()->name }}</p>
                        <p class="text-white/70 text-sm truncate">{{ Auth::user()->email }}</p>
                    </div>

                    {{-- Navigation --}}
                    <nav class="divide-y divide-slate-200">
                        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/dashboard.png') }}" alt="Dashboard" class="w-5 h-5">
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('user.profile') }}" class="flex items-center gap-3 px-6 py-3 text-slate-900 font-medium bg-slate-50 border-l-4 border-slate-900">
                            <img src="{{ asset('icons/hoso.png') }}" alt="Hồ sơ" class="w-5 h-5">
                            <span>Hồ sơ cá nhân</span>
                        </a>
                        <a href="{{ route('user.orders') }}" class="flex items-center gap-3 px-6 py-3 text-slate-700 hover:bg-slate-50">
                            <img src="{{ asset('icons/donhang.png') }}" alt="Đơn hàng" class="w-5 h-5">
                            <span>Đơn hàng</span>
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-3">
                {{-- Alerts --}}
                @if ($errors->any())
                    <div class="alert alert--error mb-6">
                        <p class="font-medium mb-2">❌ Có lỗi xảy ra:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert--success mb-6">
                        <p class="font-medium">✓ {{ session('success') }}</p>
                    </div>
                @endif

                <div class="space-y-6">
                    {{-- Edit Profile Card --}}
                    <div class="form-section">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Thông tin cá nhân</h2>

                        <form action="{{ route('user.update-profile') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                                        <img src="{{ asset('icons/hoso.png') }}" alt="Tên" class="w-4 h-4 inline mr-2"> Họ và tên
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition">
                                </div>

                                <div class="form-group">
                                    <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                                        <img src="{{ asset('icons/email.png') }}" alt="Email" class="w-4 h-4 inline mr-2"> Email
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="block text-sm font-semibold text-slate-900 mb-2">
                                    <img src="{{ asset('icons/phone.png') }}" alt="SĐT" class="w-4 h-4 inline mr-2"> Số điện thoại
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ Auth::user()->phone ?? '' }}" placeholder="0912345678"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition">
                                <p class="text-xs text-slate-500 mt-1">Định dạng: 0912345678</p>
                            </div>

                            <div class="pt-4 flex gap-3">
                                <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-semibold">
                                    Lưu thay đổi
                                </button>
                                <a href="{{ route('user.dashboard') }}" class="px-6 py-3 bg-slate-100 text-slate-900 rounded-lg hover:bg-slate-200 transition font-semibold">
                                    ← Quay lại
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Change Password Card --}}
                    <div class="form-section">
                        <h2 class="text-2xl font-bold text-slate-900 mb-6">Bảo mật - Đổi mật khẩu</h2>

                        <form action="{{ route('user.change-password') }}" method="POST" class="space-y-4">
                            @csrf

                            <div class="form-group">
                                <label for="current_password" class="block text-sm font-semibold text-slate-900 mb-2">
                                    🔑 Mật khẩu hiện tại
                                </label>
                                <input type="password" id="current_password" name="current_password" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition"
                                    placeholder="Nhập mật khẩu hiện tại">
                                @error('current_password')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="password" class="block text-sm font-semibold text-slate-900 mb-2">
                                        <img src="{{ asset('icons/doimk.png') }}" alt="MK" class="w-4 h-4 inline mr-2"> Mật khẩu mới
                                    </label>
                                    <input type="password" id="password" name="password" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition"
                                        placeholder="Tối thiểu 6 ký tự">
                                    @error('password')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-900 mb-2">
                                        Xác nhận mật khẩu
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-900/10 transition"
                                        placeholder="Nhập lại mật khẩu mới">
                                </div>
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-blue-800">
                                    <strong>💡 Mẹo:</strong> Sử dụng mật khẩu mạnh gồm chữ hoa, chữ thường, số và ký tự đặc biệt để bảo vệ tài khoản.
                                </p>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-3 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-semibold">
                                    🔄 Đổi mật khẩu
                                </button>
                                <a href="{{ route('user.dashboard') }}" class="px-6 py-3 bg-slate-100 text-slate-900 rounded-lg hover:bg-slate-200 transition font-semibold">
                                    ← Quay lại
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
