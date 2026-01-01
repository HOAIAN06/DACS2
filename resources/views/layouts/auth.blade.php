<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'HANZO - Thời trang nam')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Auth CSS --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    
    @yield('content')
    
    {{-- Auth JS --}}
    <script src="{{ asset('js/auth.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
