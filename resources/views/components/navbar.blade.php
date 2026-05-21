<header class="sticky top-0 z-40 border-b border-brand-border bg-brand-light/95 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">

        @php
            $user = auth()->user();
            $isOwner = $user?->role === 'owner';
        @endphp

        <div class="grid items-center gap-4 lg:grid-cols-[1fr_auto_1fr]">
            <div class="flex items-center gap-2 justify-self-start">
                <a href="{{ route('shop') }}"
                   class="brand-icon-button"
                   aria-label="Browse shop"
                   title="Browse shop">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                    </svg>
                </a>

                <details class="relative lg:hidden">
                    <summary class="brand-icon-button list-none cursor-pointer">
                        <span class="sr-only">Open menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </summary>

                    <div class="brand-dropdown-panel left-0 w-[min(20rem,calc(100vw-2rem))] p-4">
                        <div class="flex items-center gap-3 rounded-3xl bg-brand-light p-4">
                            <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-12 w-auto rounded-xl object-contain">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Crafted Pieces</p>
                                <p class="text-sm text-brand-ink/70">Navigation</p>
                            </div>
                        </div>

                        <nav class="mt-4 grid gap-2">
                            <a href="{{ route('home') }}" class="brand-dropdown-link {{ request()->routeIs('home') ? 'bg-brand-light text-brand-primary' : '' }}">Home</a>

                            <a href="{{ route('shop') }}" class="brand-dropdown-link {{ request()->routeIs('shop') ? 'bg-brand-light text-brand-primary' : '' }}">
                                Shop
                            </a>

                            <a href="{{ route('about') }}" class="brand-dropdown-link {{ request()->routeIs('about') ? 'bg-brand-light text-brand-primary' : '' }}">
                                About
                            </a>

                            <a href="{{ route('custom-order') }}" class="brand-dropdown-link whitespace-nowrap {{ request()->routeIs('custom-order') ? 'bg-brand-light text-brand-primary' : '' }}">
                                Custom Order
                            </a>

                            @auth
                                <div class="grid gap-1 rounded-2xl border border-brand-border p-2">
                                    <p class="px-3 pt-1 text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                        My Orders
                                    </p>

                                    <a href="{{ route('orders') }}"
                                       class="brand-dropdown-link {{ request()->routeIs('orders') ? 'bg-brand-light text-brand-primary' : '' }}">
                                        Orders
                                    </a>

                                    <a href="{{ route('custom-order.index') }}"
                                       class="brand-dropdown-link whitespace-nowrap {{ request()->routeIs('custom-order.index') ? 'bg-brand-light text-brand-primary' : '' }}">
                                        Custom Orders
                                    </a>
                                </div>

                                @if (Route::has('account'))
                                    <a href="{{ route('account') }}" class="brand-dropdown-link {{ request()->routeIs('account') ? 'bg-brand-light text-brand-primary' : '' }}">
                                        Account
                                    </a>
                                @endif
                            @endauth

                            @guest
                                <a href="{{ route('orders.track.form') }}"
                                   class="brand-dropdown-link {{ request()->routeIs('orders.track.*') ? 'bg-brand-light text-brand-primary' : '' }}">
                                    Track Order
                                </a>
                            @endguest
                        </nav>

                        <div class="mt-4 flex flex-wrap items-center gap-3 border-t border-brand-border pt-4">
                            @guest
                                <button type="button"
                                        onclick="if (typeof openAuthModal === 'function') { openAuthModal(); if (typeof showLogin === 'function') { showLogin(); } }"
                                        class="brand-btn-primary px-4 py-2 text-sm">
                                    Login / Register
                                </button>
                            @endguest

                            <a href="{{ route('cart') }}" class="brand-btn-secondary px-4 py-2 text-sm">
                                Cart
                                @if ($cartCount > 0)
                                    <span class="brand-badge ml-2">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>
                </details>
            </div>

            <div class="flex flex-col items-center gap-3 justify-self-center text-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/crafted_pieces_logo.png') }}"
                         alt="Crafted Pieces"
                         class="h-12 w-auto rounded-xl object-contain sm:h-14 lg:h-16">

                    <span class="hidden text-sm font-semibold uppercase tracking-[0.22em] text-brand-primary sm:inline-flex">
                        Crafted Pieces
                    </span>
                </a>

                <nav class="hidden items-center justify-center gap-8 text-sm font-medium lg:flex">
                    <a href="{{ route('home') }}"
                       class="{{ request()->routeIs('home') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                        Home
                    </a>

                    <a href="{{ route('shop') }}"
                       class="{{ request()->routeIs('shop') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                        Shop
                    </a>

                    <a href="{{ route('about') }}"
                       class="{{ request()->routeIs('about') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                        About
                    </a>

                    <a href="{{ route('custom-order') }}"
                       class="whitespace-nowrap {{ request()->routeIs('custom-order') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                        Custom Order
                    </a>

                    @auth
                        <div class="relative group">
                            <button type="button"
                                    class="{{ request()->routeIs('orders') || request()->routeIs('custom-order.index')
                                        ? 'text-brand-primary'
                                        : 'text-brand-ink/75 hover:text-brand-primary' }} flex items-center gap-1">

                                My Orders

                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="absolute left-1/2 top-full z-50 hidden w-36 -translate-x-1/2 pt-2 group-hover:block group-focus-within:block">
                                <div class="overflow-hidden rounded-xl border border-brand-border bg-white shadow-xl">

                                    <a href="{{ route('orders') }}"
                                       class="block px-3 py-2 text-left text-sm transition hover:bg-brand-light whitespace-nowrap
                                       {{ request()->routeIs('orders') ? 'text-brand-primary font-medium' : 'text-brand-ink/80' }}">
                                        Orders
                                    </a>

                                    <a href="{{ route('custom-order.index') }}"
                                       class="block px-3 py-2 text-left text-sm transition hover:bg-brand-light whitespace-nowrap
                                       {{ request()->routeIs('custom-order.index') ? 'text-brand-primary font-medium' : 'text-brand-ink/80' }}">
                                        Custom Orders
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <a href="{{ route('orders.track.form') }}"
                           class="{{ request()->routeIs('orders.track.*') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                            Track Order
                        </a>
                    @endguest
                </nav>
            </div>

            <div class="flex items-center justify-end gap-2 justify-self-end">

                <a href="{{ route('cart') }}" class="brand-icon-button relative" aria-label="Cart" title="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.4 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L21 8H7.2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM17.5 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                    </svg>

                    @if ($cartCount > 0)
                        <span class="brand-badge absolute -right-1 -top-1">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <details class="relative">
                        <summary class="brand-icon-button list-none cursor-pointer" aria-label="Open profile menu" title="Profile menu">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                            </svg>
                        </summary>

                        <div class="brand-dropdown-panel right-0 w-64 p-3">
                            <div class="rounded-3xl bg-brand-light p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                                    Signed in
                                </p>

                                <p class="mt-2 text-sm font-semibold text-brand-primary">
                                    {{ $user?->name }}
                                </p>

                                <p class="text-xs text-brand-ink/70">
                                    {{ $user?->email }}
                                </p>
                            </div>

                            <div class="mt-3 grid gap-1">
                                @if ($isOwner)
                                    <a href="{{ route('admin.dashboard') }}" class="brand-dropdown-link">
                                        Admin Dashboard
                                    </a>
                                @endif

                                <a href="{{ route('orders') }}"
                                   class="brand-dropdown-link {{ request()->routeIs('orders') ? 'bg-brand-light text-brand-primary' : '' }}">
                                    Orders
                                </a>

                                <a href="{{ route('custom-order.index') }}"
                                   class="brand-dropdown-link whitespace-nowrap {{ request()->routeIs('custom-order.index') ? 'bg-brand-light text-brand-primary' : '' }}">
                                    Custom Orders
                                </a>

                                @if (Route::has('account'))
                                    <a href="{{ route('account') }}"
                                       class="brand-dropdown-link {{ request()->routeIs('account') ? 'bg-brand-light text-brand-primary' : '' }}">
                                        Account
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="brand-dropdown-link w-full text-left">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </details>
                @endauth

                @guest
                    <button type="button"
                            onclick="if (typeof openAuthModal === 'function') { openAuthModal(); if (typeof showLogin === 'function') { showLogin(); } }"
                            class="brand-icon-button"
                            aria-label="Login or register"
                            title="Login or register">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 1 0-16 0" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                        </svg>
                    </button>
                @endguest

            </div>
        </div>
    </div>
</header>