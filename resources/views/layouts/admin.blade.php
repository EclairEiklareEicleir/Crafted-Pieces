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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 11.5 12 4l8 7.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 10.5V20h11V10.5" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.products.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5 12 3l7.5 4.5v9L12 21l-7.5-4.5v-9Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 12v9" />
                </svg>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.orders.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 6.5h15l-1.4 11.2a2 2 0 0 1-2 1.8H7.9a2 2 0 0 1-2-1.8L4.5 6.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 10.5a3 3 0 1 1 6 0" />
                </svg>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.custom.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.custom.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5h12A1.5 1.5 0 0 1 19.5 6v12A1.5 1.5 0 0 1 18 19.5H6A1.5 1.5 0 0 1 4.5 18V6A1.5 1.5 0 0 1 6 4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 9h8M8 12.5h8M8 16h5" />
                </svg>
                <span>Custom Orders</span>
            </a>

            <a href="{{ route('admin.about.edit') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.about.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5a7.5 7.5 0 1 1 0 15 7.5 7.5 0 0 1 0-15Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5v3h3" />
                </svg>
                <span>About</span>
            </a>

            <a href="{{ route('admin.faq.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.faq.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                </svg>
                <span>FAQ</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="brand-admin-nav-link {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.4 13.5-.2-2 .2-2-2-.9a7.5 7.5 0 0 0-.9-2l.8-2-1.4-1.4-2 .8a7.5 7.5 0 0 0-2-.9l-.9-2h-2l-.9 2a7.5 7.5 0 0 0-2 .9l-2-.8-1.4 1.4.8 2a7.5 7.5 0 0 0-.9 2l-2 .9.2 2-.2 2 2 .9a7.5 7.5 0 0 0 .9 2l-.8 2 1.4 1.4 2-.8a7.5 7.5 0 0 0 2 .9l.9 2h2l.9-2a7.5 7.5 0 0 0 2-.9l2 .8 1.4-1.4-.8-2a7.5 7.5 0 0 0 .9-2l2-.9Z" />
                </svg>
                <span>Settings</span>
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
                    <details class="relative xl:hidden">
                        <summary class="brand-icon-button list-none cursor-pointer">
                            <span class="sr-only">Open admin menu</span>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                            </svg>
                        </summary>

                        <div class="brand-dropdown-panel left-0 w-[min(20rem,calc(100vw-2rem))] p-4">
                            <div class="flex items-center gap-3 rounded-3xl bg-brand-light p-4">
                                <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-12 w-auto rounded-xl object-contain">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Crafted Pieces</p>
                                    <p class="text-sm text-brand-ink/70">Admin navigation</p>
                                </div>
                            </div>

                            <nav class="mt-4 grid gap-2 text-sm">
                                <a href="{{ route('admin.dashboard') }}" class="brand-dropdown-link {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light text-brand-primary' : '' }}">Dashboard</a>
                                <a href="{{ route('admin.products.index') }}" class="brand-dropdown-link {{ request()->routeIs('admin.products.*') ? 'bg-brand-light text-brand-primary' : '' }}">Products</a>
                                <a href="{{ route('admin.orders.index') }}" class="brand-dropdown-link {{ request()->routeIs('admin.orders.*') ? 'bg-brand-light text-brand-primary' : '' }}">Orders</a>
                                <a href="{{ route('admin.custom.index') }}" class="brand-dropdown-link {{ request()->routeIs('admin.custom.*') ? 'bg-brand-light text-brand-primary' : '' }}">Custom Orders</a>
                                <a href="{{ route('admin.about.edit') }}" class="brand-dropdown-link {{ request()->routeIs('admin.about.*') ? 'bg-brand-light text-brand-primary' : '' }}">About</a>
                                <a href="{{ route('admin.faq.index') }}" class="brand-dropdown-link {{ request()->routeIs('admin.faq.*') ? 'bg-brand-light text-brand-primary' : '' }}">FAQ</a>
                                <a href="{{ route('admin.settings.index') }}" class="brand-dropdown-link {{ request()->routeIs('admin.settings.*') ? 'bg-brand-light text-brand-primary' : '' }}">Settings</a>
                                <a href="{{ route('home') }}" class="brand-dropdown-link">Back to Store</a>
                            </nav>
                        </div>
                    </details>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Crafted Pieces</p>
                        <h1 class="text-lg font-semibold text-brand-primary sm:text-xl">
                            {{ $pageTitle ?? 'Dashboard' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="hidden sm:inline-flex brand-btn-secondary px-4 py-2 text-sm">
                        Store
                    </a>

                    <details class="relative">
                        <summary class="brand-icon-button list-none cursor-pointer" aria-label="Open admin profile menu" title="Profile menu">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                            </svg>
                        </summary>

                        <div class="brand-dropdown-panel right-0 w-64 p-3">
                            <div class="rounded-3xl bg-brand-light p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Admin account</p>
                                <p class="mt-2 text-sm font-semibold text-brand-primary">{{ $adminUser?->name ?? 'Admin' }}</p>
                                <p class="text-xs text-brand-ink/70">{{ $adminUser?->email }}</p>
                            </div>

                            <div class="mt-3 grid gap-1">
                                <a href="{{ route('admin.dashboard') }}" class="brand-dropdown-link {{ request()->routeIs('admin.dashboard') ? 'bg-brand-light text-brand-primary' : '' }}">Dashboard</a>
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