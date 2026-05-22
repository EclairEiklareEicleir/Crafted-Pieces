<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? 'Admin' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-brand-ink antialiased">

@php
    $adminUser = auth()->user();

    // 🔔 NOTIFICATIONS (ADDED)
    $adminNotifications = auth()->check()
        ? \App\Models\Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get()
        : collect();

    $adminUnreadCount = auth()->check()
        ? \App\Models\Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count()
        : 0;
@endphp

<div class="min-h-screen xl:grid xl:grid-cols-[18rem_minmax(0,1fr)]">

    <aside class="hidden border-r border-brand-border/80 bg-white/82 shadow-sm backdrop-blur-xl xl:flex xl:flex-col xl:sticky xl:top-0 xl:h-screen">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 border-b border-brand-border px-5 py-5">
            <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-14 w-auto rounded-xl object-contain">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Crafted Pieces</p>
                <h2 class="text-sm font-semibold text-brand-primary">Admin Panel</h2>
            </div>
        </a>

        <nav class="space-y-2 px-4 py-5 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light text-brand-primary' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.products.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                Products
            </a>

            <a href="{{ route('admin.orders.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                Orders
            </a>

            <a href="{{ route('admin.custom.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.custom.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                Custom Orders
            </a>

            <a href="{{ route('admin.about.edit') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.about.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                About
            </a>

            <a href="{{ route('admin.faq.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.faq.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                FAQ
            </a>

            <a href="{{ route('admin.settings.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                Settings
            </a>
        </nav>

        <div class="mt-auto border-t border-brand-border p-4">
            <a href="{{ route('home') }}" class="brand-btn-secondary w-full px-4 py-3 text-sm">
                Back to Store
            </a>
        </div>
    </aside>

    <div class="flex min-w-0 flex-col">
        <header class="sticky top-0 z-30 border-b border-brand-border/80 bg-brand-surface/78 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">

                <div class="flex items-center gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">
                            Crafted Pieces
                        </p>
                        <h1 class="text-lg font-semibold text-brand-primary sm:text-xl">
                            {{ $pageTitle ?? 'Dashboard' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">

                    {{-- 🔔 NOTIFICATIONS --}}
                    <details class="relative">
                        <summary class="brand-icon-button relative list-none cursor-pointer"
                                 aria-label="Notifications"
                                 title="Notifications">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 0 0 6 0" />
                            </svg>

                            @if ($adminUnreadCount > 0)
                                <span class="brand-badge absolute -right-1 -top-1">
                                    {{ $adminUnreadCount }}
                                </span>
                            @endif
                        </summary>

                        <div class="brand-dropdown-panel right-0 w-72 p-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                                Notifications
                            </p>

                            <div class="mt-2 grid gap-2">
                                @forelse ($adminNotifications as $notif)
                                    <a href="{{ route('notifications.show', $notif) }}"
                                       class="block rounded-xl border border-brand-border bg-white p-2 hover:bg-brand-light">

                                        <p class="text-sm font-semibold text-brand-primary">
                                            {{ $notif->title }}
                                        </p>

                                        <p class="text-xs text-brand-ink/70 line-clamp-2">
                                            {{ $notif->message }}
                                        </p>
                                    </a>
                                @empty
                                    <p class="text-sm text-brand-ink/60">
                                        No notifications.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </details>

                    {{-- PROFILE --}}
                    <details class="relative">
                        <summary class="brand-icon-button list-none cursor-pointer">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                            </svg>
                        </summary>

                        <div class="brand-dropdown-panel right-0 w-64 p-3">
                            <div class="rounded-3xl bg-brand-light p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                                    Admin account
                                </p>

                                <p class="mt-2 text-sm font-semibold text-brand-primary">
                                    {{ $adminUser?->name ?? 'Admin' }}
                                </p>

                                <p class="text-xs text-brand-ink/70">
                                    {{ $adminUser?->email }}
                                </p>
                            </div>

                            <div class="mt-3 grid gap-1">
                                <a href="{{ route('admin.dashboard') }}" class="brand-dropdown-link">Dashboard</a>
                                <a href="{{ route('home') }}" class="brand-dropdown-link">View Store</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="brand-dropdown-link w-full text-left">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </details>

                </div>
            </div>
        </header>

        <main class="p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>
    </div>

</div>

<x-brand-loader />

</body>
</html>