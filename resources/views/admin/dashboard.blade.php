@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-[#4d3028]">
                Admin Dashboard
            </h1>
        </div>

        {{-- FILTERS --}}
        <div class="flex flex-wrap gap-2">

            <a href="{{ route('admin.dashboard', ['filter' => 'month']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold transition
               {{ $filter === 'month'
                    ? 'bg-[#5d342b] text-white shadow-sm'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b] hover:bg-[#fcfaf8]' }}">

                This Month

            </a>

            <a href="{{ route('admin.dashboard', ['filter' => 'year']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold transition
               {{ $filter === 'year'
                    ? 'bg-[#5d342b] text-white shadow-sm'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b] hover:bg-[#fcfaf8]' }}">

                This Year

            </a>

            <a href="{{ route('admin.dashboard', ['filter' => 'all']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold transition
               {{ $filter === 'all'
                    ? 'bg-[#5d342b] text-white shadow-sm'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b] hover:bg-[#fcfaf8]' }}">

                All Time

            </a>

        </div>

    </div>

    {{-- MAIN STATS --}}
    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

        {{-- REVENUE --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-gradient-to-br from-white to-[#fcfaf8] p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-[#8f7a70]">
                    Total Revenue
                </p>

                <div class="rounded-full bg-[#f5ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                    Sales
                </div>

            </div>

            <p class="mt-5 text-4xl font-bold text-[#4d3028]">
                ₱ {{ number_format($totalRevenue, 2) }}
            </p>

            <p class="mt-3 text-xs text-[#8f7a70]">
                Overall generated revenue from successful transactions.
            </p>

        </div>

        {{-- TOTAL ORDERS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-gradient-to-br from-white to-[#fcfaf8] p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-[#8f7a70]">
                    Total Orders
                </p>

                <div class="rounded-full bg-[#f5ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                    Orders
                </div>

            </div>

            <p class="mt-5 text-4xl font-bold text-[#4d3028]">
                {{ $totalOrders }}
            </p>

            <p class="mt-3 text-xs text-[#8f7a70]">
                Includes all customer transactions in the system.
            </p>

        </div>

        {{-- COMMISSIONS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-gradient-to-br from-white to-[#fcfaf8] p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-[#8f7a70]">
                    Commission Requests
                </p>

                <div class="rounded-full bg-[#f5ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                    Custom
                </div>

            </div>

            <p class="mt-5 text-4xl font-bold text-[#4d3028]">
                {{ $totalCommissions }}
            </p>

            <p class="mt-3 text-xs text-[#8f7a70]">
                Total submitted custom commission requests.
            </p>

        </div>

        {{-- CONFIRMED --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-gradient-to-br from-white to-[#fcfaf8] p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm font-medium text-[#8f7a70]">
                    Confirmed Projects
                </p>

                <div class="rounded-full bg-[#f5ebe6] px-3 py-1 text-xs font-semibold text-[#8d5848]">
                    Approved
                </div>

            </div>

            <p class="mt-5 text-4xl font-bold text-[#4d3028]">
                {{ $confirmedCommissions }}
            </p>

            <p class="mt-3 text-xs text-[#8f7a70]">
                Approved commission projects currently active or completed.
            </p>

        </div>

    </div>

    {{-- TREND ANALYTICS --}}
    <div class="grid gap-5 lg:grid-cols-3">

        {{-- TOP PRODUCT --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Most Popular Product
            </p>

            <h3 class="mt-4 text-2xl font-bold text-[#4d3028]">
                {{ $topProduct ?? 'N/A' }}
            </h3>

            <p class="mt-2 text-sm text-[#6f5a51]">
                Highest purchased product based on total order quantity.
            </p>

        </div>

        {{-- MONTHLY SALES --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Monthly Revenue
            </p>

            <h3 class="mt-4 text-2xl font-bold text-[#4d3028]">
                ₱ {{ number_format($monthlyRevenue ?? 0, 2) }}
            </h3>

            <p class="mt-2 text-sm text-[#6f5a51]">
                Revenue generated for the current month.
            </p>

        </div>

        {{-- COMPLETION RATE --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Completion Rate
            </p>

            <h3 class="mt-4 text-2xl font-bold text-[#4d3028]">
                {{ $completionRate ?? 0 }}%
            </h3>

            <p class="mt-2 text-sm text-[#6f5a51]">
                Percentage of orders successfully delivered.
            </p>

        </div>

    </div>

    {{-- SECONDARY ANALYTICS --}}
    <div class="grid gap-5 lg:grid-cols-3">

        {{-- ORDER INSIGHTS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Order Insights
            </h2>

            <div class="mt-6 space-y-4">

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Pending Orders</span>
                    <span class="font-semibold text-[#4d3028]">{{ $pendingOrders ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Orders In Transit</span>
                    <span class="font-semibold text-[#4d3028]">{{ $shippingOrders ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Delivered Orders</span>
                    <span class="font-semibold text-[#4d3028]">{{ $deliveredOrders ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Cancelled Orders</span>
                    <span class="font-semibold text-[#4d3028]">{{ $cancelledOrders ?? 0 }}</span>
                </div>

            </div>

        </div>

        {{-- COMMISSION INSIGHTS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Commission Insights
            </h2>

            <div class="mt-6 space-y-4">

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Awaiting Payment</span>
                    <span class="font-semibold text-[#4d3028]">{{ $awaitingPayment ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Paid Commissions</span>
                    <span class="font-semibold text-[#4d3028]">{{ $paidCommissions ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Rejected Requests</span>
                    <span class="font-semibold text-[#4d3028]">{{ $rejectedCommissions ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-[#fcfaf8] p-4">
                    <span class="text-sm text-[#6f5a51]">Active Discussions</span>
                    <span class="font-semibold text-[#4d3028]">{{ $activeChats ?? 0 }}</span>
                </div>

            </div>

        </div>

        {{-- QUICK ACTIONS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Quick Actions
            </h2>

            <div class="mt-6 grid gap-3">

                <a href="{{ route('admin.orders.create') }}"
                   class="rounded-2xl bg-[#5d342b] px-5 py-4 text-sm font-semibold text-white transition hover:opacity-90">
                    + Create Manual Order
                </a>

                <a href="{{ route('admin.orders.index') }}"
                   class="rounded-2xl border border-[#eadfd7] bg-[#fcfaf8] px-5 py-4 text-sm font-semibold text-[#5d342b] transition hover:bg-[#f8f2ee]">
                    Manage Orders
                </a>

                <a href="{{ route('admin.custom.index') }}"
                   class="rounded-2xl border border-[#eadfd7] bg-[#fcfaf8] px-5 py-4 text-sm font-semibold text-[#5d342b] transition hover:bg-[#f8f2ee]">
                    Review Commission Requests
                </a>

            </div>

        </div>

    </div>

    {{-- RECENT ACTIVITY --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- RECENT ORDERS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                    Recent Orders
                </h2>

                <a href="{{ route('admin.orders.index') }}"
                   class="text-sm font-semibold text-[#a86b57]">
                    View All
                </a>

            </div>

            <div class="mt-6 space-y-4">

                @forelse ($recentOrders as $order)

                    <div class="rounded-3xl border border-[#f1e6de] bg-[#fcfaf8] p-4">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="font-semibold text-[#4d3028]">
                                    Order #{{ $order->id }}
                                </p>

                                <p class="mt-1 text-sm text-[#6f5a51]">
                                    {{ $order->full_name }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-semibold text-[#8d5848]">
                                    PHP {{ number_format($order->total_amount, 2) }}
                                </p>

                                <p class="mt-1 text-xs text-[#8f6a5d]">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </p>
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-sm text-[#6f5a51]">
                        No recent orders.
                    </p>
                @endforelse

            </div>

        </div>

        {{-- RECENT COMMISSIONS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                    Recent Commission Requests
                </h2>

                <a href="{{ route('admin.custom.index') }}"
                   class="text-sm font-semibold text-[#a86b57]">
                    View All
                </a>

            </div>

            <div class="mt-6 space-y-4">

                @forelse ($recentRequests as $request)

                    <a href="{{ route('admin.custom.show', $request->id) }}"
                       class="block rounded-3xl border border-[#f1e6de] bg-[#fcfaf8] p-4 transition hover:shadow-md">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="font-semibold text-[#4d3028]">
                                    {{ $request->item_type }}
                                </p>

                                <p class="mt-1 text-sm text-[#6f5a51]">
                                    {{ $request->name }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-semibold text-[#8d5848]">
                                    PHP {{ number_format($request->estimated_price, 2) }}
                                </p>

                                <p class="mt-1 text-xs text-[#8f6a5d]">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </p>
                            </div>
                        </div>

                    </a>

                @empty
                    <p class="text-sm text-[#6f5a51]">
                        No commission requests yet.
                    </p>
                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection