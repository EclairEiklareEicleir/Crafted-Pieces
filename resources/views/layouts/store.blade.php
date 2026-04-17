<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? config('app.name', 'Crafted Pieces') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f6efe8] text-[#3f2a22] antialiased">
    <div class="absolute inset-x-0 top-0 h-[28rem] bg-[radial-gradient(circle_at_top,rgba(208,170,147,0.28),transparent_62%)] pointer-events-none"></div>

    @include('partials.store-nav')

    <main class="relative">
        @yield('content')
    </main>

    @include('partials.store-footer')
    @include('partials.chatbot-widget')
</body>
</html>
