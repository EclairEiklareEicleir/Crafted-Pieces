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

        </nav>

        {{-- Actions --}}
        <div class="flex items-center gap-4">

            @guest
                <button type="button"
                        onclick="openAuthModal()"
                        class="text-sm font-medium text-[#5d342b] hover:text-[#a86b57]">
                    Login / Register
                </button>
            @endguest

            @auth
                @php
                    $user = auth()->user();
                @endphp

                {{-- USER --}}
                @if ($user->role === 'user')
                    <a href="{{ route('cart') }}" class="text-sm hover:underline">
                        Cart
                    </a>

                    <a href="{{ route('account') }}" class="text-sm hover:underline">
                        Account
                    </a>
                @endif

                {{-- OWNER / ADMIN --}}
                @if ($user->role === 'owner')
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-sm font-semibold text-red-700 hover:underline">
                        Admin Panel
                    </a>
                @endif

                {{-- USERNAME + LOGOUT --}}
                <div class="flex items-center gap-3 text-sm text-[#5d342b]">

                    <span class="font-semibold">
                        {{ $user->name }}
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-[#6f5a51] hover:text-[#a86b57]">
                            Logout
                        </button>
                    </form>

                </div>
            @endauth

        </div>

    </div>
</header>