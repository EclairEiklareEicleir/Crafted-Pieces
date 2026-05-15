@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Analytics
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">
                Admin Dashboard
            </h1>
        </div>

        {{-- FILTERS --}}
        <div class="flex gap-2">

            <a href="{{ route('admin.dashboard', ['filter' => 'month']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold
               {{ $filter === 'month'
                    ? 'bg-[#5d342b] text-white'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b]' }}">

                This Month

            </a>

            <a href="{{ route('admin.dashboard', ['filter' => 'year']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold
               {{ $filter === 'year'
                    ? 'bg-[#5d342b] text-white'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b]' }}">

                This Year

            </a>

            <a href="{{ route('admin.dashboard', ['filter' => 'all']) }}"
               class="rounded-full px-5 py-2 text-sm font-semibold
               {{ $filter === 'all'
                    ? 'bg-[#5d342b] text-white'
                    : 'border border-[#eadfd7] bg-white text-[#5d342b]' }}">

                All Time

            </a>

        </div>

    </div>

    {{-- STATS --}}
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Revenue
            </p>

            <p class="mt-3 text-3xl font-semibold text-[#4d3028]">
                PHP {{ number_format($totalRevenue, 2) }}
            </p>

        </div>

        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Orders
            </p>

            <p class="mt-3 text-3xl font-semibold text-[#4d3028]">
                {{ $totalOrders }}
            </p>

        </div>

        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Commission Requests
            </p>

            <p class="mt-3 text-3xl font-semibold text-[#4d3028]">
                {{ $totalCommissions }}
            </p>

        </div>

        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-sm text-[#8f7a70]">
                Confirmed Commissions
            </p>

            <p class="mt-3 text-3xl font-semibold text-[#4d3028]">
                {{ $confirmedCommissions }}
            </p>

        </div>

    </div>

    {{-- RECENT DATA --}}
    <div class="grid gap-6 lg:grid-cols-2">

        {{-- RECENT ORDERS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Recent Orders
            </h2>

            <div class="mt-6 space-y-4">

                @forelse ($recentOrders as $order)

                    <div class="rounded-3xl bg-[#fcfaf8] p-4">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="font-semibold text-[#4d3028]">
                                    Order #{{ $order->id }}
                                </p>

                                <p class="text-sm text-[#6f5a51]">
                                    {{ $order->full_name }}
                                </p>
                            </div>

                            <div class="text-right">

                                <p class="font-semibold text-[#8d5848]">
                                    PHP {{ number_format($order->total_amount, 2) }}
                                </p>

                                <p class="text-xs text-[#8f6a5d]">
                                    {{ ucfirst($order->status) }}
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

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Recent Commission Requests
            </h2>

            <div class="mt-6 space-y-4">

                @forelse ($recentRequests as $request)

                    <a href="{{ route('admin.custom.show', $request->id) }}"
                       class="block rounded-3xl bg-[#fcfaf8] p-4 transition hover:shadow-md">

                        <div class="flex items-center justify-between">

                            <div>
                                <p class="font-semibold text-[#4d3028]">
                                    {{ $request->item_type }}
                                </p>

                                <p class="text-sm text-[#6f5a51]">
                                    {{ $request->name }}
                                </p>
                            </div>

                            <div class="text-right">

                                <p class="font-semibold text-[#8d5848]">
                                    PHP {{ number_format($request->estimated_price, 2) }}
                                </p>

                                <p class="text-xs text-[#8f6a5d]">
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