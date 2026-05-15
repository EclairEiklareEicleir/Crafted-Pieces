<header class="sticky top-0 z-40 border-b bg-white/80 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="font-semibold text-lg">
            the_crafted_pieces
        </a>

        {{-- Navigation --}}
        <nav class="hidden gap-6 md:flex">

            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'font-bold' : '' }}">
                Home
            </a>

            <a href="{{ route('shop') }}"
               class="{{ request()->routeIs('shop') ? 'font-bold' : '' }}">
                Shop
            </a>

            <a href="{{ route('about') }}"
               class="{{ request()->routeIs('about') ? 'font-bold' : '' }}">
                About
            </a>

            {{-- CART --}}
            <a href="{{ route('cart') }}"
               class="{{ request()->routeIs('cart') ? 'font-bold' : '' }}">
                Cart
            </a>

            {{-- 🧵 CUSTOM ORDER --}}
            <a href="{{ route('custom-order') }}"
               class="{{ request()->routeIs('custom-order') ? 'font-bold' : '' }}">
                Custom Order
            </a>

            {{-- MY ORDERS DROPDOWN --}}
            @auth
            <div class="relative group">

                <button class="flex items-center gap-1 hover:text-[#a86b57]">
                    My Orders
                    <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd" />
                    </svg>
                </button>

                {{-- DROPDOWN --}}
                <div class="absolute left-0 mt-2 hidden w-48 rounded-xl border bg-white shadow-md group-hover:block">

                    <a href="{{ route('orders') }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-50
                       {{ request()->routeIs('orders') ? 'font-bold' : '' }}">
                        Orders
                    </a>

                    <a href="{{ route('custom-order.index') }}"
                       class="block px-4 py-2 text-sm hover:bg-gray-50
                       {{ request()->routeIs('custom-order.index') ? 'font-bold' : '' }}">
                        Custom Orders
                    </a>

                </div>
            </div>
            @endauth

            {{-- TRACK ORDER (GUEST ONLY fallback option) --}}
            @guest
                <a href="{{ route('orders.track.form') }}"
                   class="{{ request()->routeIs('orders.track.*') ? 'font-bold' : '' }}">
                    Track Order
                </a>
            @endguest

        </nav>

        {{-- Actions --}}
        <div class="flex items-center gap-4 text-sm text-[#5d342b]">

            @guest
                <button type="button"
                        onclick="openAuthModal()"
                        class="font-medium hover:text-[#a86b57]">
                    Login / Register
                </button>
            @endguest

            @auth
                @php $user = auth()->user(); @endphp

                <a href="{{ route('account') }}"
                   class="font-medium hover:text-[#a86b57]">
                    {{ $user->name }}
                </a>

                @if ($user->role === 'owner')
                    <a href="{{ route('admin.dashboard') }}"
                       class="font-semibold text-red-700 hover:underline">
                        Admin Panel
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="font-semibold text-[#6f5a51] hover:text-[#a86b57]">
                        Logout
                    </button>
                </form>
            @endauth

        </div>

    </div>
</header>