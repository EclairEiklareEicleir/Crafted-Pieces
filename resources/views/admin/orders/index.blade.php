@extends('layouts.admin')

@section('content')

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

    {{-- HEADER --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Orders
        </h2>

        <div class="flex flex-wrap items-center gap-3">
            <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />

            <a href="{{ route('admin.orders.create') }}"
               class="brand-btn-primary whitespace-nowrap px-5 py-2 text-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                </svg>

                <span>Create Order</span>
            </a>
        </div>

    </div>

    {{-- FILTER + BULK BAR --}}
    <div class="mt-6 flex flex-wrap items-center gap-3">

        {{-- SEARCH + FILTER --}}
        <form method="GET"
              id="filterForm"
              class="flex flex-1 items-center gap-3 min-w-0">

            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name, email, or order ID"
                class="brand-input flex-1 min-w-0 py-2"
                oninput="submitFilter()"
            >

            {{-- FILTER --}}
            <select
                name="status"
                onchange="submitFilter()"
                class="brand-input rounded-2xl px-4 py-2 whitespace-nowrap">

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
            class="flex flex-wrap items-center gap-3">

            @csrf

            <select
                name="action"
                class="brand-input rounded-2xl px-4 py-2 whitespace-nowrap">

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

            <button class="brand-btn-primary whitespace-nowrap px-5 py-2 text-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6" />
                </svg>

                <span>Apply</span>
            </button>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="mt-6 overflow-x-auto">

        <table class="w-full text-left text-sm">

            <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">

                <tr>

                    <th class="py-3 pr-4">
                        <input type="checkbox" id="selectAll">
                    </th>

                    <th class="py-3 pr-4">
                        Order
                    </th>

                    <th class="py-3 pr-4">
                        Customer
                    </th>

                    <th class="py-3 pr-4">
                        Total
                    </th>

                    <th class="py-3 pr-4">
                        Status
                    </th>

                    <th class="py-3 pr-4">
                        Payment
                    </th>

                    <th class="py-3 pr-4">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody class="divide-y divide-brand-border">

                @forelse ($orders as $order)

                    <tr>

                        <td class="py-4 pr-4">

                            <input type="checkbox"
                                   name="orders[]"
                                   value="{{ $order->id }}">

                        </td>

                        <td class="py-4 pr-4 font-medium text-brand-primary">

                            #{{ $order->id }}

                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">

                            {{ $order->full_name }}

                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">

                            PHP {{ number_format($order->total_amount) }}

                        </td>

                        <td class="py-4 pr-4">

                            <x-status-badge :status="$order->status" context="order" />

                        </td>

                        <td class="py-4 pr-4">

                            <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />

                        </td>

                        {{-- ACTIONS --}}
                        <td class="py-4 pr-4">

                            <div class="flex flex-wrap items-center gap-2">

                                {{-- VIEW --}}
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
                                    </svg>

                                    <span>View</span>
                                </a>

                                {{-- SHIPPED --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.status', $order->id) }}">

                                    @csrf

                                    <input type="hidden"
                                           name="status"
                                           value="shipped">

                                    <button class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h11.5l3 3H21v6h-1.5a2.5 2.5 0 0 0-5 0h-6a2.5 2.5 0 0 0-5 0H2V10a2.5 2.5 0 0 1 1-2.5Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 10.5V7.5" />
                                            <circle cx="7" cy="16.5" r="2" />
                                            <circle cx="16" cy="16.5" r="2" />
                                        </svg>

                                        <span>Mark Shipped</span>
                                    </button>

                                </form>

                                {{-- DELIVERED --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.status', $order->id) }}">

                                    @csrf

                                    <input type="hidden"
                                           name="status"
                                           value="delivered">

                                    <button class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-primary transition hover:border-brand-primary hover:bg-brand-light/60">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 9.5 17.5 4 12" />
                                        </svg>

                                        <span>Mark Delivered</span>
                                    </button>

                                </form>

                                {{-- DELETE --}}
                                <form method="POST"
                                      action="{{ route('admin.orders.destroy', $order->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button class="inline-flex items-center gap-2 rounded-full border border-[#f0c5cf] bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 7h14" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7V5.75A1.75 1.75 0 0 1 10.75 4h2.5A1.75 1.75 0 0 1 15 5.75V7m-6 0 .5 12a1.5 1.5 0 0 0 1.5 1.5h2a1.5 1.5 0 0 0 1.5-1.5L15 7" />
                                        </svg>

                                        <span>Delete</span>
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6"
                            class="py-6 text-center text-gray-500">

                            No orders found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-6">

        {{ $orders->links() }}

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