<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Crafted Pieces') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/crafted-pieces-logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/crafted-pieces-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/crafted-pieces-logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-brand-ink antialiased">

@php
    $adminUser = auth()->user();

    $adminNotifications = auth()->check()
        ? \App\Models\Notification::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get()
        : collect();

    $adminUnreadCount = auth()->check()
        ? \App\Models\Notification::where('user_id', auth()->id())
            ->unread()
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5 12 4l9 7.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.5V20h11V10.5" /></svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.products.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5 12 3l7.5 4.5-7.5 4.5L4.5 7.5Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5V16.5L12 21l7.5-4.5V7.5" /></svg>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6h2.5l1.5 9.5h9.75l1.5-6.5H8.15" /><circle cx="10" cy="19" r="1.5" /><circle cx="17" cy="19" r="1.5" /></svg>
                <span>Active Orders</span>
            </a>

            <a href="{{ route('admin.history.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.history.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 2" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5A9 9 0 1 1 4.5 16.5" /><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5v3h3" /></svg>
                <span>Order History</span>
            </a>

            <a href="{{ route('admin.custom.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.custom.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4.75h10A2.25 2.25 0 0 1 19.25 7v10A2.25 2.25 0 0 1 17 19.25H7A2.25 2.25 0 0 1 4.75 17V7A2.25 2.25 0 0 1 7 4.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M8 8.5h8M8 12h8M8 15.5h5" /></svg>
                <span>Custom Orders</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.users.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 20.25v-1.5A4.75 4.75 0 0 0 11.75 14H7.5a4.75 4.75 0 0 0-4.75 4.75v1.5" /><circle cx="9.75" cy="8.5" r="3.25" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 11.25h4.5M18.75 9v4.5" /></svg>
                <span>User Management</span>
            </a>

            <a href="{{ route('admin.about.edit') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.about.*') || request()->routeIs('admin.faq.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 17v-5" /><path stroke-linecap="round" stroke-linejoin="round" d="M12 8.25h.01" /><circle cx="12" cy="12" r="8.25" /></svg>
                <span>About</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 4.5h3M7.5 7.5l-1.2-1.2M16.5 7.5l1.2-1.2M4.5 10.5v3M19.5 10.5v3M7.5 16.5l-1.2 1.2M16.5 16.5l1.2 1.2M10.5 19.5h3" /><circle cx="12" cy="12" r="3.5" /></svg>
                <span>Settings</span>
            </a>

            <a href="{{ route('admin.chatbot.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.chatbot.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 18.75 4.5 20l1.25-3A8.25 8.25 0 1 1 7.5 18.75Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M12 12h.01M15 12h.01" /></svg>
                <span>Chatbot</span>
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

                            <a href="{{ route('notifications.index') }}"
                               class="mt-3 block rounded-xl border border-brand-border px-3 py-2 text-center text-sm font-medium text-brand-primary transition hover:bg-brand-light">
                                View all notifications
                            </a>
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
            <div class="pointer-events-none fixed inset-x-0 top-20 z-[80] mx-auto grid w-full max-w-lg gap-3 px-4">
                @if (session('success'))
                    <div class="pointer-events-auto flex items-start justify-between gap-4 rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 shadow-sm transition duration-300"
                         data-flash-alert
                         data-flash-delay="4200">
                        <div>{{ session('success') }}</div>
                        <button type="button" class="text-lg leading-none text-emerald-700 transition hover:text-emerald-950" data-flash-dismiss aria-label="Dismiss notification">
                            &times;
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="pointer-events-auto flex items-start justify-between gap-4 rounded-3xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm transition duration-300"
                         data-flash-alert
                         data-flash-delay="7000">
                        <div>{{ session('error') }}</div>
                        <button type="button" class="text-lg leading-none text-rose-700 transition hover:text-rose-950" data-flash-dismiss aria-label="Dismiss notification">
                            &times;
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="pointer-events-auto flex items-start justify-between gap-4 rounded-3xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 shadow-sm transition duration-300"
                         data-flash-alert
                         data-flash-auto-dismiss="false">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="text-lg leading-none text-rose-700 transition hover:text-rose-950" data-flash-dismiss aria-label="Dismiss notification">
                            &times;
                        </button>
                    </div>
                @endif
            </div>

            @yield('content')
        </main>
    </div>

</div>

<x-brand-loader />

</body>
</html>
