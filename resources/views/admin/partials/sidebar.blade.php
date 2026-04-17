<aside class="hidden w-72 shrink-0 border-r border-[#e7dad0] bg-white/80 p-5 backdrop-blur xl:block">
    <a href="{{ route('admin.dashboard') }}" class="font-display text-xl font-semibold text-[#5d342b]">Admin Panel</a>
    <nav class="mt-8 space-y-2 text-sm font-medium text-[#6f5a51]">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="dashboard" size="w-5 h-5" />
            <span>Dashboard</span>
        </a>
        <a href="{{ route('admin.products') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.products') || request()->routeIs('admin.products.create') || request()->routeIs('admin.products.edit') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="products" size="w-5 h-5" />
            <span>Products</span>
        </a>
        <a href="{{ route('admin.categories') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.categories') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="categories" size="w-5 h-5" />
            <span>Categories</span>
        </a>
        <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.orders') || request()->routeIs('admin.orders.show') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="orders" size="w-5 h-5" />
            <span>Orders</span>
        </a>
        <a href="{{ route('admin.quotations') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.quotations') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="quotations" size="w-5 h-5" />
            <span>Quotations</span>
        </a>
        <a href="{{ route('admin.payments') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.payments') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="payments" size="w-5 h-5" />
            <span>Payments</span>
        </a>
        <a href="{{ route('admin.delivery') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.delivery') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="delivery" size="w-5 h-5" />
            <span>Delivery</span>
        </a>
        <a href="{{ route('admin.support') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.support') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="support" size="w-5 h-5" />
            <span>Support / FAQs</span>
        </a>
        <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('admin.settings') ? 'bg-[#f4e4d9] text-[#8d5848]' : 'hover:bg-[#f9f5f1] hover:text-[#8d5848]' }}">
            <x-icon name="settings" size="w-5 h-5" />
            <span>Settings</span>
        </a>
        <a href="{{ route('home') }}" class="mt-8 flex items-center gap-3 rounded-2xl px-4 py-3 transition hover:bg-[#f9f5f1] hover:text-[#8d5848]">
            <x-icon name="back" size="w-5 h-5" />
            <span>Back to Store</span>
        </a>
    </nav>
</aside>
