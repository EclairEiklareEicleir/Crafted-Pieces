@extends('layouts.admin')

@section('content')

@php
    $statusLabel = fn ($status) => ucwords(str_replace('_', ' ', $status));
    $hasFilters = request()->filled('search')
        || request()->filled('status')
        || request()->filled('payment_status');
@endphp

<div class="rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Product Orders
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Active Orders
            </h2>

            <p class="mt-2 text-sm text-brand-ink/60">
                Manage orders that still need fulfillment action or payment follow-up.
            </p>
        </div>

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

    <form method="GET" id="filterForm" class="mt-6 grid gap-3 lg:grid-cols-[minmax(18rem,1fr)_13rem_13rem_auto]">
        <div>
            <label for="orders-search" class="sr-only">Search orders</label>

            <div class="relative">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-brand-ink/45" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                </svg>

                <input
                    id="orders-search"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search order ID, customer, email, status, or payment"
                    class="brand-input py-3 pl-10"
                >
            </div>
        </div>

        <div>
            <label for="orders-status" class="sr-only">Order status</label>

            <select
                id="orders-status"
                name="status"
                class="brand-input py-3"
                onchange="this.form.submit()"
            >
                <option value="">All Statuses</option>

                @foreach ($orderStatuses as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ $statusLabel($status) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="orders-payment-status" class="sr-only">Payment status</label>

            <select
                id="orders-payment-status"
                name="payment_status"
                class="brand-input py-3"
                onchange="this.form.submit()"
            >
                <option value="">All Payments</option>

                @foreach ($paymentStatuses as $paymentStatus)
                    <option value="{{ $paymentStatus }}" @selected(request('payment_status') === $paymentStatus)>
                        {{ $statusLabel($paymentStatus) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="submit" class="brand-btn-primary px-5 py-3 text-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                    <circle cx="11" cy="11" r="6.5" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16 16 4 4" />
                </svg>

                <span>Search</span>
            </button>

            @if ($hasFilters)
                <a href="{{ route('admin.orders.index') }}" class="brand-btn-secondary px-5 py-3 text-sm">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div id="bulkPanel" class="mt-4 hidden rounded-3xl border border-brand-border bg-brand-light/30 p-4">
        <form method="POST"
              action="{{ route('admin.orders.bulk') }}"
              id="bulkOrdersForm"
              class="grid gap-3 md:grid-cols-[1fr_16rem_auto] md:items-center">
            @csrf

            <div>
                <p id="bulkSelectionCount" class="text-sm font-semibold text-brand-primary">
                    0 orders selected
                </p>

                <p id="bulkError" class="mt-1 hidden text-sm text-brand-secondary">
                    Select at least one order before applying a bulk action.
                </p>
            </div>

            <select
                name="action"
                id="bulkAction"
                class="brand-input py-3"
                required
                disabled
            >
                <option value="">Choose bulk action</option>

                @foreach ($bulkStatuses as $status)
                    <option value="{{ $status }}">
                        Mark as {{ $statusLabel($status) }}
                    </option>
                @endforeach

            </select>

            <button type="submit"
                    id="bulkApplyButton"
                    class="brand-btn-primary px-5 py-3 text-sm"
                    disabled>
                Apply
            </button>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full min-w-[58rem] text-left text-sm">
            <thead class="border-y border-brand-border bg-brand-surface/70 text-xs uppercase tracking-[0.14em] text-brand-ink/55">
                <tr>
                    <th class="w-10 py-3 pl-3 pr-4">
                        <input type="checkbox"
                               id="selectAll"
                               class="h-4 w-4 rounded border-brand-border text-brand-primary"
                               aria-label="Select all orders">
                    </th>

                    <th class="py-3 pr-4">Order</th>
                    <th class="py-3 pr-4">Customer</th>
                    <th class="py-3 pr-4">Type</th>
                    <th class="py-3 pr-4">Total</th>
                    <th class="py-3 pr-4">Status</th>
                    <th class="py-3 pr-4">Payment</th>
                    <th class="py-3 pr-4">Date</th>
                    <th class="py-3 pr-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-brand-border">
                @forelse ($orders as $order)
                    <tr class="align-top transition hover:bg-brand-surface/50">
                        <td class="py-4 pl-3 pr-4">
                            <input type="checkbox"
                                   name="orders[]"
                                   value="{{ $order->id }}"
                                   form="bulkOrdersForm"
                                   class="h-4 w-4 rounded border-brand-border text-brand-primary"
                                   data-order-checkbox
                                   aria-label="Select order #{{ $order->id }}">
                        </td>

                        <td class="py-4 pr-4">
                            <div class="font-semibold text-brand-primary">
                                #{{ $order->id }}
                            </div>

                            @if ($order->public_reference)
                                <div class="mt-1 text-xs text-brand-ink/50">
                                    {{ $order->public_reference }}
                                </div>
                            @endif
                        </td>

                        <td class="py-4 pr-4">
                            <div class="font-medium text-brand-primary">
                                {{ $order->full_name ?: ($order->user?->name ?? 'Guest Customer') }}
                            </div>

                            <div class="mt-1 text-xs text-brand-ink/55">
                                {{ $order->email ?: ($order->user?->email ?? 'No email') }}
                            </div>
                        </td>

                        <td class="py-4 pr-4">
                            <span class="brand-pill">{{ $order->order_type_label }}</span>
                        </td>

                        <td class="py-4 pr-4 font-medium text-brand-ink/75">
                            PHP {{ number_format((float) $order->total_amount, 2) }}
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$order->status" context="order" />
                        </td>

                        <td class="py-4 pr-4">
                            <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/60">
                            {{ $order->created_at?->format('M d, Y') ?? 'No date' }}
                        </td>

                        <td class="py-4 pr-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-brand-border bg-white text-brand-primary transition hover:border-brand-secondary hover:bg-brand-light hover:text-brand-secondary"
                                   title="View order"
                                   aria-label="View order #{{ $order->id }}">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.75a3.75 3.75 0 1 0 0-7.5 3.75 3.75 0 0 0 0 7.5Z" />
                                    </svg>
                                </a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center">
                            <p class="font-semibold text-brand-primary">
                                {{ $hasFilters ? 'No orders match your filters.' : 'No orders found.' }}
                            </p>

                            <p class="mt-1 text-sm text-brand-ink/60">
                                {{ $hasFilters ? 'Try a different search term or clear the filters.' : 'New product orders will appear here.' }}
                            </p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin-pagination :paginator="$orders" label="Active orders pagination" />

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = Array.from(document.querySelectorAll('[data-order-checkbox]'));
    const bulkPanel = document.getElementById('bulkPanel');
    const bulkForm = document.getElementById('bulkOrdersForm');
    const bulkAction = document.getElementById('bulkAction');
    const bulkApplyButton = document.getElementById('bulkApplyButton');
    const bulkSelectionCount = document.getElementById('bulkSelectionCount');
    const bulkError = document.getElementById('bulkError');

    const syncBulkState = () => {
        const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length;
        const hasSelection = selectedCount > 0;

        bulkPanel?.classList.toggle('hidden', ! hasSelection);
        bulkAction?.toggleAttribute('disabled', ! hasSelection);
        bulkApplyButton?.toggleAttribute('disabled', ! hasSelection);
        bulkError?.classList.add('hidden');

        if (bulkSelectionCount) {
            bulkSelectionCount.textContent = `${selectedCount} order${selectedCount === 1 ? '' : 's'} selected`;
        }

        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && selectedCount === checkboxes.length;
            selectAll.indeterminate = selectedCount > 0 && selectedCount < checkboxes.length;
        }
    };

    selectAll?.addEventListener('change', () => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked;
        });

        syncBulkState();
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', syncBulkState);
    });

    bulkForm?.addEventListener('submit', (event) => {
        const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length;

        if (selectedCount === 0) {
            event.preventDefault();
            bulkError?.classList.remove('hidden');
            return;
        }

    });

    syncBulkState();
});
</script>

@endsection