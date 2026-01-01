<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Hanzo Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/categories.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quick-add-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ time() }}">
    <script src="{{ asset('js/hanzo.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/admin.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/quick-add-modal.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900">
    @php($adminNav = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
        ['label' => 'Đơn hàng', 'route' => 'admin.orders.index'],
        ['label' => 'Sản phẩm', 'route' => 'admin.products.index'],
        ['label' => 'Danh mục', 'route' => 'admin.categories.index'],
        ['label' => 'Khách hàng', 'route' => 'admin.users.index'],
    ])

    <header class="bg-white shadow-sm border-b border-slate-200">
        <div class="hanzo-container px-4 py-4 flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('icons/logohanzo.png') }}" alt="HANZO" class="h-9 w-auto">
                <div>
                    <div class="text-lg font-bold tracking-[0.24em] uppercase">Hanzo</div>
                    <div class="text-xs text-slate-500 font-semibold">Admin Console</div>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-5 text-sm font-semibold ml-6">
                @foreach($adminNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-lg transition {{ request()->routeIs($item['route'].'*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="ml-auto flex items-center gap-3">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">← Về trang cửa hàng</a>
                @auth
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-100 text-sm font-semibold text-slate-800">
                        <img src="{{ asset('icons/user.png') }}" alt="Admin" class="w-4 h-4">
                        <span>{{ Auth::user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Đăng xuất</button>
                    </form>
                @endauth
            </div>
        </div>

        <div class="md:hidden border-t border-slate-200">
            <div class="px-4 py-3 flex flex-wrap gap-2 text-xs font-semibold text-slate-700">
                @foreach($adminNav as $item)
                    <a href="{{ route($item['route']) }}"
                       class="px-3 py-2 rounded-full border {{ request()->routeIs($item['route'].'*') ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 hover:bg-slate-100' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </header>

    <main class="min-h-screen py-8">
        <div class="hanzo-container px-3">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm font-medium">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="hanzo-container px-4 py-6 text-xs text-slate-500 flex flex-col md:flex-row items-center justify-between gap-3">
            <span>© {{ date('Y') }} HANZO Admin</span>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900">Dashboard</a>
                <a href="{{ route('admin.orders.index') }}" class="hover:text-slate-900">Đơn hàng</a>
                <a href="{{ route('home') }}" class="hover:text-slate-900">Trang cửa hàng</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
