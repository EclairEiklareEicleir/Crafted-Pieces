@extends('layouts.admin')

@section('content')

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Completed Records
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Order History
            </h2>

            <p class="mt-2 text-sm text-brand-ink/60">
                Review delivered, received, cancelled, and completed orders in one place.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
            <a href="{{ route('admin.orders.index') }}" class="brand-btn-secondary px-5 py-2 text-sm">Active Orders</a>
        </div>
    </div>

    <form method="GET" id="filterForm" class="mt-6 grid gap-3 lg:grid-cols-5">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search by name, email, or order ID"
               class="brand-input py-3 lg:col-span-2"
               oninput="submitFilter()">

        <select name="type" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Types</option>
            <option value="product" {{ request('type') === 'product' ? 'selected' : '' }}>Product Orders</option>
            <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Custom Orders</option>
        </select>

        <select name="status" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Statuses</option>
            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="received" {{ request('status') === 'received' ? 'selected' : '' }}>Received</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <select name="payment_status" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Payments</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="unpaid" {{ request('payment_status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
            <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            <option value="expired" {{ request('payment_status') === 'expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('payment_status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <input type="date" name="from" value="{{ request('from') }}" class="brand-input py-3" onchange="submitFilter()">
        <input type="date" name="to" value="{{ request('to') }}" class="brand-input py-3" onchange="submitFilter()">
    </form>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">
                <tr>
                    <th class="py-3 pr-4">Order ID</th>
                    <th class="py-3 pr-4">Customer</th>
                    <th class="py-3 pr-4">Type</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Payment</th>
                    <th class="py-3 pr-4">Total</th>
                    <th class="py-3 pr-4">Date Ordered</th>
                    <th class="py-3 pr-4">Last Updated</th>
                    <th class="py-3 pr-4">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-brand-border">
                @forelse ($history as $entry)
                    <tr>
                        <td class="py-4 pr-4 font-medium text-brand-primary">
                            #{{ $entry['record_id'] }}
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/75">
                            <div class="flex flex-col">
                                <span class="font-medium text-brand-primary">{{ $entry['customer_name'] }}</span>
                                <span class="text-xs text-brand-ink/55">{{ $entry['customer_email'] }}</span>
                            </div>
                        </td>

                        <td class="py-4 pr-4">
                            <span class="brand-pill">{{ $entry['type_label'] }}</span>
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$entry['status']" :context="$entry['type'] === 'custom' ? 'custom' : 'order'" />
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$entry['payment_status']" context="payment" />
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/75">
                            PHP {{ number_format($entry['total_amount'], 2) }}
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">
                            {{ $entry['ordered_at']?->format('M d, Y') ?? '—' }}
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">
                            {{ $entry['updated_at']?->format('M d, Y h:i A') ?? '—' }}
                        </td>

                        <td class="py-4 pr-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ $entry['details_url'] }}" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                                    View Details
                                </a>

                                <a href="{{ $entry['payment_url'] }}" data-no-loading="true" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-primary transition hover:border-brand-primary hover:bg-brand-light/60">
                                    View Receipt
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-brand-ink/60">
                            No order history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $history->links() }}
    </div>
</div>

<script>
let filterTimeout

function submitFilter() {
    clearTimeout(filterTimeout)
    filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit()
    }, 250)
}
</script>

@endsection