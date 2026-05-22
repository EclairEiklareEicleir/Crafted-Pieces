@extends('layouts.admin')

@section('content')

<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Orders
        </h2>

        {{-- MANUAL ORDER CREATION --}}
        <a href="{{ route('admin.orders.create') }}"
           class="brand-btn-primary px-5 py-2 text-sm whitespace-nowrap">
            + Create Order
        </a>

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
              class="flex items-center gap-3">

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

            <button class="brand-btn-primary px-5 py-2 text-sm whitespace-nowrap">
                Apply
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

                        <td class="py-4 pr-4 text-brand-ink/70">

                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}

                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">

                            {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}

                        </td>

                        {{-- ACTIONS --}}
                        <td class="py-4 pr-4">

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