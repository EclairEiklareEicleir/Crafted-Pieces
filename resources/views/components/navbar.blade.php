<header class="sticky top-0 z-40 border-b border-brand-border/80 bg-brand-surface/78 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">

        @php
            $user = auth()->user();
            $isOwner = $user?->role === 'owner';

            $recentNotifications = auth()->check()
                ? \App\Models\Notification::where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get()
                : collect();

            $unreadCount = auth()->check()
                ? \App\Models\Notification::where('user_id', auth()->id())
                    ->unread()
                    ->count()
                : 0;
        @endphp

        <div class="grid items-center gap-4 lg:grid-cols-[1fr_auto_1fr]">
            <div class="flex items-center gap-2 justify-self-start">

                <button type="button"
                        class="brand-icon-button"
                        aria-label="Search products"
                        title="Search products"
                        data-search-open>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                    </svg>
                </button>

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

                            @auth
                                <a href="{{ route('custom-order') }}"
                                   class="brand-dropdown-link whitespace-nowrap {{ request()->routeIs('custom-order') ? 'bg-brand-light text-brand-primary' : '' }}">
                                    Custom Order
                                </a>
                            @endauth

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

                    @auth
                        <a href="{{ route('custom-order') }}"
                           class="whitespace-nowrap {{ request()->routeIs('custom-order') ? 'text-brand-primary' : 'text-brand-ink/75 hover:text-brand-primary' }}">
                            Custom Order
                        </a>
                    @endauth

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

                {{-- CART --}}
                <a href="{{ route('cart') }}" class="brand-icon-button relative" aria-label="Cart" title="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4h2l2.4 10.2a2 2 0 0 0 2 1.6h7.8a2 2 0 0 0 2-1.5L21 8H7.2" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2ZM17.5 20.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" />
                    </svg>

                    @if ($cartCount > 0)
                        <span class="brand-badge absolute -right-1 -top-1">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- NOTIFICATIONS --}}
                @auth
                    <details class="relative">
                        <summary class="brand-icon-button relative list-none cursor-pointer"
                                 aria-label="Notifications"
                                 title="Notifications">

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a3 3 0 0 0 6 0" />
                            </svg>

                            @if ($unreadCount > 0)
                                <span class="brand-badge absolute -right-1 -top-1">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </summary>

                        <div class="brand-dropdown-panel right-0 w-72 p-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                                Notifications
                            </p>

                            <div class="mt-2 grid gap-2">
                                @forelse ($recentNotifications as $notif)
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
                                        No new notifications.
                                    </p>
                                @endforelse
                            </div>

                            <a href="{{ route('notifications.index') }}"
                               class="mt-3 block rounded-xl border border-brand-border px-3 py-2 text-center text-sm font-medium text-brand-primary transition hover:bg-brand-light">
                                View all notifications
                            </a>
                        </div>
                    </details>
                @endauth

                {{-- PROFILE --}}
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

        <div data-search-modal
             class="fixed inset-0 z-[60] hidden items-start justify-center bg-brand-primary/30 px-4 pb-6 pt-20 backdrop-blur-sm sm:pt-24"
             aria-hidden="true">

            <button type="button"
                    class="absolute inset-0 cursor-default"
                    data-search-close
                    aria-label="Close search overlay"></button>

            <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-[2rem] border border-brand-border bg-white shadow-2xl shadow-brand-primary/20">

                <div class="flex items-start justify-between gap-4 border-b border-brand-border bg-brand-surface px-5 py-4 sm:px-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                            Search products
                        </p>

                        <h2 class="mt-1 text-xl font-semibold text-brand-primary">
                            Find crochet pieces, yarn colors, and more
                        </h2>
                    </div>

                    <button type="button"
                            class="brand-icon-button h-10 w-10 shrink-0"
                            data-search-close
                            aria-label="Close search">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="GET"
                      action="{{ route('products.search') }}"
                      class="space-y-4 px-5 py-5 sm:px-6"
                      data-search-form>

                    <label class="sr-only" for="storefront-search-input">Search products</label>

                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-brand-secondary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                            </svg>
                        </span>

                        <input id="storefront-search-input"
                               type="search"
                               name="q"
                               value=""
                               placeholder="Search by name, category, description, or price"
                               class="brand-input h-14 rounded-full pl-12 pr-36 text-base"
                               autocomplete="off"
                               maxlength="120"
                               required
                               data-search-input>

                        <div class="absolute inset-y-0 right-2 flex items-center gap-2">
                            <button type="button"
                                    class="hidden rounded-full px-3 py-2 text-sm font-semibold text-brand-ink/60 transition hover:text-brand-primary sm:inline-flex"
                                    data-search-close>
                                Cancel
                            </button>

                            <button type="submit"
                                    class="brand-btn-primary h-10 rounded-full px-4 py-0 text-sm shadow-none">
                                Search
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                Live suggestions
                            </p>

                            <p class="text-xs text-brand-ink/55" data-search-feedback>
                                Start typing to see products.
                            </p>
                        </div>

                        <div class="max-h-72 overflow-y-auto rounded-[1.5rem] border border-brand-border bg-brand-surface/70 p-2 shadow-sm"
                             data-search-results>
                            <p class="rounded-[1.25rem] px-4 py-3 text-sm text-brand-ink/60">
                                Type at least 2 characters to see suggestions.
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @once
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.querySelector('[data-search-modal]');

                if (!modal) {
                    return;
                }

                const openButtons = document.querySelectorAll('[data-search-open]');
                const closeButtons = modal.querySelectorAll('[data-search-close]');
                const form = modal.querySelector('[data-search-form]');
                const input = modal.querySelector('[data-search-input]');
                const results = modal.querySelector('[data-search-results]');
                const feedback = modal.querySelector('[data-search-feedback]');
                const suggestionsUrl = @json(route('products.search.suggestions'));

                let debounceTimer = null;
                let activeController = null;

                const setFeedback = (message) => {
                    if (!feedback) {
                        return;
                    }

                    feedback.textContent = message;
                };

                const setResultsMessage = (message) => {
                    if (!results) {
                        return;
                    }

                    results.innerHTML = '';

                    const text = document.createElement('p');
                    text.className = 'rounded-[1.25rem] px-4 py-3 text-sm text-brand-ink/60';
                    text.textContent = message;

                    results.appendChild(text);
                };

                const renderSuggestions = (items) => {
                    if (!results) {
                        return;
                    }

                    results.innerHTML = '';

                    if (!items.length) {
                        setResultsMessage('No matching products found.');
                        return;
                    }

                    items.forEach((item) => {
                        const link = document.createElement('a');
                        link.href = item.url;
                        link.className = 'flex items-center gap-3 rounded-[1.25rem] border border-transparent bg-white px-3 py-2.5 transition hover:border-brand-secondary hover:bg-brand-light/40';

                        const image = document.createElement('img');
                        image.src = item.image_url;
                        image.alt = item.name;
                        image.className = 'h-12 w-12 shrink-0 rounded-2xl object-cover bg-brand-surface';
                        image.loading = 'lazy';

                        const textWrap = document.createElement('div');
                        textWrap.className = 'min-w-0 flex-1';

                        const title = document.createElement('p');
                        title.className = 'truncate text-sm font-semibold text-brand-primary';
                        title.textContent = item.name;

                        const meta = document.createElement('p');
                        meta.className = 'truncate text-xs text-brand-ink/60';
                        meta.textContent = [item.category_name, `PHP ${item.price}`].filter(Boolean).join(' • ');

                        textWrap.append(title, meta);
                        link.append(image, textWrap);
                        results.appendChild(link);
                    });
                };

                const openModal = () => {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');

                    if (typeof updateModalLock === 'function') {
                        updateModalLock();
                    } else {
                        document.body.classList.add('overflow-hidden');
                    }

                    window.setTimeout(() => {
                        input?.focus();
                        input?.select();
                    }, 50);
                };

                const closeModal = () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    modal.setAttribute('aria-hidden', 'true');

                    if (typeof updateModalLock === 'function') {
                        updateModalLock();
                    } else {
                        document.body.classList.remove('overflow-hidden');
                    }
                };

                const fetchSuggestions = async () => {
                    const term = (input?.value || '').trim();

                    if (activeController) {
                        activeController.abort();
                    }

                    if (term.length < 2) {
                        setFeedback('Start typing to see products.');
                        setResultsMessage('Type at least 2 characters to see suggestions.');
                        return;
                    }

                    activeController = new AbortController();
                    setFeedback('Searching...');
                    setResultsMessage('Searching products...');

                    try {
                        const url = new URL(suggestionsUrl, window.location.origin);
                        url.searchParams.set('q', term);

                        const response = await fetch(url.toString(), {
                            headers: {
                                Accept: 'application/json',
                            },
                            signal: activeController.signal,
                        });

                        if (!response.ok) {
                            throw new Error('Search suggestions request failed.');
                        }

                        const payload = await response.json();
                        const items = Array.isArray(payload.data) ? payload.data : [];

                        setFeedback(items.length ? `${items.length} suggestion${items.length === 1 ? '' : 's'} found.` : 'No matching products found.');
                        renderSuggestions(items);
                    } catch (error) {
                        if (error?.name === 'AbortError') {
                            return;
                        }

                        setFeedback('Search suggestions unavailable.');
                        setResultsMessage('Type a keyword and press Search to open the results page.');
                    }
                };

                openButtons.forEach((button) => {
                    button.addEventListener('click', openModal);
                });

                closeButtons.forEach((button) => {
                    button.addEventListener('click', closeModal);
                });

                modal.addEventListener('click', (event) => {
                    if (event.target === modal) {
                        closeModal();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                        closeModal();
                    }
                });

                input?.addEventListener('input', () => {
                    const term = input.value.trim();

                    if (debounceTimer) {
                        window.clearTimeout(debounceTimer);
                    }

                    if (term.length < 2) {
                        setFeedback('Start typing to see products.');
                        setResultsMessage('Type at least 2 characters to see suggestions.');
                        return;
                    }

                    debounceTimer = window.setTimeout(fetchSuggestions, 250);
                });

                form?.addEventListener('submit', (event) => {
                    const trimmedValue = (input?.value || '').trim();

                    if (!trimmedValue) {
                        event.preventDefault();
                        input?.focus();
                        return;
                    }

                    if (input) {
                        input.value = trimmedValue;
                    }

                    closeModal();
                });
            });
        </script>
    @endonce
</header>