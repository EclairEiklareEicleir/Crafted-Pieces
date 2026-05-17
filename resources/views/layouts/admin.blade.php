<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? 'Admin' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f5f1ec] text-[#3f2a22] antialiased">

<div class="flex min-h-screen">

    {{-- SIDEBAR (placeholder for now) --}}
    <aside class="w-64 border-r bg-white p-4">
        <h2 class="font-bold text-[#5d342b]">Admin Panel</h2>

        <nav class="mt-6 space-y-3 text-sm">

            <a href="{{ route('admin.dashboard') }}" class="block">
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" class="block">
                Products
            </a>

            <a href="{{ route('admin.custom.index') }}" class="block">
                Custom Requests
            </a>

            <a href="{{ route('admin.orders.index') }}" class="block">
                Orders
            </a>

            <a href="{{ route('home') }}" class="block">
                Back to Store
            </a>

        </nav>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1">

        <header class="border-b bg-white px-6 py-4">
            <h1 class="text-xl font-semibold">
                {{ $pageTitle ?? 'Dashboard' }}
            </h1>
        </header>

        <main class="p-6">
            @yield('content')
        </main>

    </div>

</div>

</body>
</html>