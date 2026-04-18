<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin' }} | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f5f1ec] text-[#3f2a22] antialiased">
    <div class="flex min-h-screen">
        @include('admin.partials.sidebar')

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-[#e7dad0] bg-white/70 px-4 py-4 backdrop-blur sm:px-6 lg:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8f6a5d]">Admin</p>
                        <h1 class="font-display text-2xl font-semibold text-[#5d342b]">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <p class="hidden text-sm font-medium text-[#6f5a51] sm:block">{{ auth()->user()->name }}</p>
                        <a href="{{ route('home') }}" class="rounded-full border border-[#e7d6cb] bg-white px-4 py-2 text-sm font-medium text-[#5d342b] shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">Open Store</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-[#e7d6cb] bg-white px-4 py-2 text-sm font-medium text-[#5d342b] shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">Log Out</button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
