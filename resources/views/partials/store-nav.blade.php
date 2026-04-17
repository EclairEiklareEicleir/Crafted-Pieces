<header class="sticky top-0 z-40 border-b border-white/60 bg-white/70 backdrop-blur-xl">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="font-display text-lg font-semibold tracking-wide text-[#5d342b]">the_crafted_pieces</a>

        <nav class="hidden items-center gap-8 text-sm font-medium md:flex">
            <a href="{{ route('home') }}" class="flex items-center gap-2 transition-colors hover:text-[#a86b57] {{ request()->routeIs('home') ? 'text-[#a86b57]' : 'text-[#5d4a43]' }}">
                <x-icon name="home" size="w-4 h-4" />
                <span>Home</span>
            </a>
            <a href="{{ route('shop') }}" class="flex items-center gap-2 transition-colors hover:text-[#a86b57] {{ request()->routeIs('shop') || request()->routeIs('product.show') ? 'text-[#a86b57]' : 'text-[#5d4a43]' }}">
                <x-icon name="shop" size="w-4 h-4" />
                <span>Shop</span>
            </a>
            <a href="{{ route('custom-order') }}" class="flex items-center gap-2 transition-colors hover:text-[#a86b57] {{ request()->routeIs('custom-order') || request()->routeIs('custom-orders') ? 'text-[#a86b57]' : 'text-[#5d4a43]' }}">
                <x-icon name="custom" size="w-4 h-4" />
                <span>Custom</span>
            </a>
            <a href="{{ route('my-orders') }}" class="flex items-center gap-2 transition-colors hover:text-[#a86b57] {{ request()->routeIs('my-orders') ? 'text-[#a86b57]' : 'text-[#5d4a43]' }}">
                <x-icon name="orders" size="w-4 h-4" />
                <span>Orders</span>
            </a>
            <a href="{{ route('about') }}" class="flex items-center gap-2 transition-colors hover:text-[#a86b57] {{ request()->routeIs('about') ? 'text-[#a86b57]' : 'text-[#5d4a43]' }}">
                <x-icon name="about" size="w-4 h-4" />
                <span>About</span>
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('cart') }}" class="flex items-center gap-2 rounded-full border border-[#e7d6cb] bg-white px-4 py-2 text-sm font-medium text-[#5d342b] shadow-sm transition hover:-translate-y-0.5 hover:border-[#d8b6a2] hover:shadow-md">
                <x-icon name="cart" size="w-4 h-4" />
                <span>Cart</span>
            </a>
            <a href="{{ route('checkout') }}" class="flex items-center gap-2 rounded-full bg-[#b8745f] px-4 py-2 text-sm font-medium text-white shadow-md shadow-[#b8745f]/20 transition hover:-translate-y-0.5 hover:bg-[#a96550]">
                <x-icon name="checkout" size="w-4 h-4" />
                <span>Checkout</span>
            </a>
        </div>
    </div>
</header>
