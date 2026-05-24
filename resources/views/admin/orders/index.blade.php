@extends('layouts.admin')

@section('content')

<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">

    @php
        $grandTotal = $orders->sum('total_amount');
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Orders
        </h2>

        <div class="flex flex-wrap items-center gap-3">

            {{-- MANUAL ORDER CREATION --}}
            <a href="{{ route('admin.orders.create') }}"
               class="brand-btn-primary px-5 py-2 text-sm whitespace-nowrap">
                + Create Order
            </a>

            {{-- EXPORT --}}
            <button
                type="button"
                class="rounded-2xl border border-brand-border bg-white px-5 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white whitespace-nowrap">
                Export Orders
            </button>

        </div>

    </div>

    {{-- FILTER + BULK BAR --}}
    <div class="mt-6 flex flex-wrap items-center gap-3">

        {{-- SEARCH + FILTER --}}
        <form method="GET"
              id="filterForm"
              class="flex flex-wrap flex-1 items-center gap-3 min-w-0">

            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email, or order ID"
                class="brand-input flex-1 min-w-[420px] rounded-2xl px-5 py-3 text-sm"
                oninput="submitFilter()"
            >

            {{-- STATUS FILTER --}}
            <select
                name="status"
                onchange="submitFilter()"
                class="brand-input rounded-2xl px-3 py-2 text-sm whitespace-nowrap w-[170px]">

                <option value="">All Status</option>

                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>
                    Processing
                </option>

                <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>
                    Shipped
                </option>

                <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>
                    Out for Delivery
                </option>

                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>
                    Delivered
                </option>

                <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>
                    Received
                </option>

                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

            </select>

        </form>

        {{-- SEPARATOR --}}
        <span class="font-semibold text-brand-ink/40">|</span>

        {{-- BULK ACTIONS --}}
        <form method="POST"
              action="{{ route('admin.orders.bulk') }}"
              class="flex items-center gap-2">

            @csrf

            <select
                name="action"
                class="brand-input rounded-2xl px-3 py-2 text-sm whitespace-nowrap">

                <option value="">Bulk Action</option>

                <option value="shipped">
                    Mark Shipped
                </option>

                <option value="delivered">
                    Mark Delivered
                </option>

                <option value="received">
                    Mark Received
                </option>

                <option value="delete">
                    Delete
                </option>

            </select>

            <button class="brand-btn-primary px-3 py-2 text-xs whitespace-nowrap">
                Apply
            </button>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="mt-6 overflow-x-auto rounded-2xl border border-brand-border">

        <table class="w-full text-sm">

            <thead class="bg-brand-surface/40 text-xs uppercase tracking-[0.16em] text-brand-ink/55">

                <tr>

                    <th class="py-4 px-4 text-center">
                        <input type="checkbox" id="selectAll">
                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=order&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Order

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=order_type&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Order Type

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=customer&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Customer

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=status&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Status

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=payment_status&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Payment State

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    <th class="py-4 px-4 text-center">

                        <a href="?sort=amount&direction={{ request('direction') === 'asc' ? 'desc' : 'asc' }}"
                           class="inline-flex items-center gap-1 whitespace-nowrap">

                            Amount

                            <span class="text-[10px]">
                                ↑↓
                            </span>

                        </a>

                    </th>

                    {{-- SEPARATOR --}}
                    <th class="w-[1px] bg-brand-border p-0"></th>

                    <th class="py-4 pl-6 pr-4 text-left">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-brand-border">

                @forelse ($orders as $order)

                    <tr class="hover:bg-brand-surface/20 transition">

                        <td class="py-4 px-4 text-center">

                            <input type="checkbox"
                                   name="orders[]"
                                   value="{{ $order->id }}">

                        </td>

                        <td class="py-4 px-4 text-center font-medium text-brand-primary">

                            #{{ $order->id }}

                        </td>

                        <td class="py-4 px-4 text-center text-brand-ink/70">

                            {{ ucfirst($order->order_type ?? 'Walk-in') }}

                        </td>

                        <td class="py-4 px-4 text-center text-brand-ink/70">

                            {{ $order->full_name }}

                        </td>

                        <td class="py-4 px-4 text-center text-brand-ink/70">

                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}

                        </td>

                        <td class="py-4 px-4 text-center text-brand-ink/70">

                            {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}

                        </td>

                        <td class="py-4 px-4 text-center font-semibold text-brand-primary whitespace-nowrap">

                            PHP {{ number_format($order->total_amount) }}

                        </td>

                        {{-- SEPARATOR --}}
                        <td class="w-[1px] bg-brand-border p-0"></td>

                        {{-- ACTIONS --}}
                        <td class="py-4 pl-6 pr-4 text-left">

                            <div class="flex flex-wrap items-center gap-3">

                                {{-- VIEW --}}
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="font-semibold text-brand-secondary">
                                    View
                                </a>

                                {{-- SHIPPED --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.status', $order->id) }}">

                                    @csrf

                                    <input type="hidden"
                                           name="status"
                                           value="shipped">

                                    <button class="text-sm text-brand-secondary">
                                        Mark Shipped
                                    </button>

                                </form>

                                {{-- DELIVERED --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.status', $order->id) }}">

                                    @csrf

                                    <input type="hidden"
                                           name="status"
                                           value="delivered">

                                    <button class="text-sm text-brand-primary">
                                        Mark Delivered
                                    </button>

                                </form>

                                {{-- DELETE --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.destroy', $order->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="text-sm text-red-600">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9"
                            class="py-8 text-center text-gray-500">

                            No orders found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

            {{-- GRAND TOTAL --}}
            <tfoot>

                <tr class="border-t border-brand-border bg-brand-surface/40">

                    <td colspan="6"
                        class="py-4 pr-4 text-right font-semibold text-brand-primary">

                        Total Revenue

                    </td>

                    <td class="py-4 px-4 text-center font-bold text-brand-primary whitespace-nowrap">

                        PHP {{ number_format($grandTotal) }}

                    </td>

                    <td class="w-[1px] bg-brand-border p-0"></td>

                    <td></td>

                </tr>

            </tfoot>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-6 flex items-center justify-between">

        <div>

            @if(request('show') !== 'all')

                <a href="{{ request()->url() . '?' . http_build_query(array_merge(request()->except('page'), ['show' => 'all'])) }}"
                   class="rounded-2xl border border-brand-border bg-white px-5 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white whitespace-nowrap">

                    Show All

                </a>

            @else

                <a href="{{ request()->url() . '?' . http_build_query(array_merge(request()->except(['show', 'page']), [])) }}"
                   class="rounded-2xl border border-brand-border bg-white px-5 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white whitespace-nowrap">

                    Paginate

                </a>

            @endif

        </div>

        @if(request('show') !== 'all')

            <div class="flex flex-1 justify-center">

                <div class="flex items-center gap-2">

                    {{-- PREVIOUS --}}
                    @if ($orders->onFirstPage())

                        <span class="rounded-2xl border border-brand-border bg-gray-100 px-4 py-2 text-sm text-gray-400 cursor-not-allowed">
                            <
                        </span>

                    @else

                        <a href="{{ $orders->previousPageUrl() }}"
                           class="rounded-2xl border border-brand-border bg-white px-4 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white">
                            <
                        </a>

                    @endif

                    {{-- PAGE NUMBERS --}}
                    @php
                        $start = max($orders->currentPage() - 1, 1);
                        $end = min($start + 2, $orders->lastPage());

                        if (($end - $start) < 2) {
                            $start = max($end - 2, 1);
                        }
                    @endphp

                    @for ($i = $start; $i <= $end; $i++)

                        @if ($i == $orders->currentPage())

                            <span class="rounded-2xl bg-brand-primary px-4 py-2 text-sm font-semibold text-white">
                                {{ $i }}
                            </span>

                        @else

                            <a href="{{ $orders->url($i) }}"
                               class="rounded-2xl border border-brand-border bg-white px-4 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white">
                                {{ $i }}
                            </a>

                        @endif

                    @endfor

                    {{-- NEXT --}}
                    @if ($orders->hasMorePages())

                        <a href="{{ $orders->nextPageUrl() }}"
                           class="rounded-2xl border border-brand-border bg-white px-4 py-2 text-sm font-medium text-brand-primary transition hover:bg-brand-primary hover:text-white">
                            >
                        </a>

                    @else

                        <span class="rounded-2xl border border-brand-border bg-gray-100 px-4 py-2 text-sm text-gray-400 cursor-not-allowed">
                            >
                        </span>

                    @endif

                </div>

            </div>

        @endif

    </div>

</div>

<script>

document.getElementById('selectAll').addEventListener('click', function () {

    let checkboxes = document.querySelectorAll('input[name="orders[]"]')

    checkboxes.forEach(cb => cb.checked = this.checked)

})

let filterTimeout

function submitFilter() {

    clearTimeout(filterTimeout)

    filterTimeout = setTimeout(() => {

        document.getElementById('filterForm').submit()

    }, 300)

}

</script>

@endsection
