@extends('layouts.admin')

@section('content')

@php
    $statusLabel = fn ($status) => ucwords(str_replace('_', ' ', $status));
    $statusOptions = ['delivered', 'received', 'completed', 'cancelled', 'rejected', 'refunded', 'failed'];
    $paymentOptions = ['paid', 'unpaid', 'pending', 'awaiting_payment', 'failed', 'refunded', 'expired', 'cancelled'];

    // FIXED: paginator-safe + supports array/object
    $totalRevenue = collect($history->items() ?? $history)->sum(function ($item) {
        if (is_array($item)) {
            return (float) ($item['total_amount'] ?? 0);
        }

        return (float) ($item->total_amount ?? 0);
    });

@endphp

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

    <div class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_auto] xl:items-start">
        <div class="min-w-0">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Archived Records
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Order History
            </h2>

            <p class="mt-2 text-sm text-brand-ink/60">
                Review delivered-and-paid, received, completed, cancelled, refunded, rejected, and failed order records in one place.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3 xl:justify-end">
            <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
            <a href="{{ route('admin.orders.index') }}" class="brand-btn-secondary px-5 py-2 text-sm">Active Orders</a>
            <a href="{{ route('admin.history.export', request()->query()) }}" data-no-loading="true" class="brand-btn-primary px-5 py-2 text-sm">Export CSV</a>
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
            @foreach ($statusOptions as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ $statusLabel($status) }}
                </option>
            @endforeach
        </select>

        <select name="payment_status" class="brand-input py-3" onchange="submitFilter()">
            <option value="">All Payments</option>
            @foreach ($paymentOptions as $paymentStatus)
                <option value="{{ $paymentStatus }}" {{ request('payment_status') === $paymentStatus ? 'selected' : '' }}>
                    {{ $statusLabel($paymentStatus) }}
                </option>
            @endforeach
        </select>

        <input type="date" name="from" value="{{ request('from') }}" class="brand-input py-3" onchange="submitFilter()">
        <input type="date" name="to" value="{{ request('to') }}" class="brand-input py-3" onchange="submitFilter()">
    </form>

    <div id="bulkHistoryPanel" class="mt-4 hidden rounded-3xl border border-brand-border bg-brand-light/30 p-4">
        <form method="POST"
              action="{{ route('admin.history.bulk-destroy') }}"
              id="bulkHistoryForm"
              class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
              data-confirm-title="Delete Selected Order History Records?"
              data-confirm-message="Are you sure you want to delete the selected order history records?"
              data-confirm-final-title="Final Confirmation"
              data-confirm-final-message="You are about to delete {count} order history records. Are you absolutely sure?"
              data-confirm-action="Continue"
              data-confirm-final-action="Yes, Delete Selected"
              data-confirm-count-selector="[data-history-checkbox]:checked"
              data-confirm-require-selector="[data-history-checkbox]:checked"
              data-confirm-error-selector="#bulkHistoryError">
            @csrf
            @method('DELETE')

            <div>
                <p id="bulkHistorySelectionCount" class="text-sm font-semibold text-brand-primary">
                    0 records selected
                </p>

                <p id="bulkHistoryError" class="mt-1 hidden text-sm text-brand-secondary">
                    Please select at least one order history record.
                </p>
            </div>

            <button type="submit"
                    id="bulkHistoryDeleteButton"
                    class="inline-flex items-center justify-center rounded-full border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50 disabled:cursor-not-allowed disabled:opacity-50"
                    disabled>
                Delete Selected
            </button>
        </form>
    </div>

    <div class="mt-6 overflow-x-auto">
        <table class="w-full min-w-[72rem] text-left text-sm">
            <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">
                <tr>
                    <th class="w-10 py-3 pl-3 pr-4">
                        <input type="checkbox"
                               id="historySelectAll"
                               class="h-4 w-4 rounded border-brand-border text-brand-primary"
                               aria-label="Select all order history records">
                    </th>
                    <th class="py-3 pr-4">Order ID</th>
                    <th class="py-3 pr-4">Customer</th>
                    <th class="py-3 pr-4">Type</th>
                    <th class="py-3 pr-4">Order Status</th>
                    <th class="py-3 pr-4">Payment Status</th>

                    <!-- MUST be directly before Actions -->
                    <th class="py-3 pr-4">Total</th>

                    <th class="py-3 pr-4">Date Ordered</th>
                    <th class="py-3 pr-4">Last Updated</th>
                    <th class="py-3 pr-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-brand-border">
                @forelse ($history as $entry)
                    <tr>
                        <td class="py-4 pl-3 pr-4">
                            <input type="checkbox"
                                   name="records[]"
                                   value="{{ $entry['record_key'] }}"
                                   form="bulkHistoryForm"
                                   class="h-4 w-4 rounded border-brand-border text-brand-primary"
                                   data-history-checkbox
                                   aria-label="Select history record #{{ $entry['record_id'] }}">
                        </td>

                        <td class="py-4 pr-4 font-medium text-brand-primary">
                            <div>#{{ $entry['record_id'] }}</div>
                            @if (! empty($entry['reference']))
                                <div class="mt-1 text-xs text-brand-ink/50">
                                    {{ $entry['reference'] }}
                                </div>
                            @endif
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
                            {{ $entry['ordered_at']?->format('M d, Y') ?? 'No date' }}
                        </td>

                        <td class="py-4 pr-4 text-brand-ink/70">
                            {{ $entry['updated_at']?->format('M d, Y h:i A') ?? 'No date' }}
                        </td>

                        <td class="py-4 pr-4">
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <a href="{{ $entry['details_url'] }}" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                                    View Details
                                </a>

                                <a href="{{ $entry['payment_url'] }}" data-no-loading="true" class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-primary transition hover:border-brand-primary hover:bg-brand-light/60">
                                    Download Receipt
                                </a>

                                <form method="POST"
                                      action="{{ $entry['delete_url'] }}"
                                      data-confirm-title="Delete Order History Record?"
                                      data-confirm-message="Are you sure you want to delete this order history record?"
                                      data-confirm-final-title="Final Confirmation"
                                      data-confirm-final-message="This action may remove this record from Order History. Are you absolutely sure?"
                                      data-confirm-action="Continue"
                                      data-confirm-final-action="Yes, Delete">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-white px-3 py-2 text-xs font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="py-8 text-center text-brand-ink/60">
                            No order history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <!-- FIXED FOOTER ALIGNMENT -->
            <tfoot class="border-t border-brand-border bg-brand-light/20 font-semibold text-brand-primary">
                <tr>
                    <!-- LEFT SIDE LABEL -->
                    <td colspan="6" class="py-4 pl-3 pr-4 text-left">
                        Total Revenue
                    </td>

                    <!-- UNDER TOTAL COLUMN -->
                    <td class="py-4 pr-4 text-left">
                        PHP {{ number_format($totalRevenue, 2) }}
                    </td>

                    <!-- REMAINING COLUMNS -->
                    <td colspan="3"></td>
                </tr>
            </tfoot>

        </table>
    </div>

    <x-admin-pagination :paginator="$history" label="Order history pagination" />
</div>

<script>
let filterTimeout

function submitFilter() {
    clearTimeout(filterTimeout)
    filterTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit()
    }, 250)
}

document.addEventListener('DOMContentLoaded', () => {
    const selectAll = document.getElementById('historySelectAll')
    const checkboxes = Array.from(document.querySelectorAll('[data-history-checkbox]'))
    const bulkPanel = document.getElementById('bulkHistoryPanel')
    const bulkForm = document.getElementById('bulkHistoryForm')
    const bulkDeleteButton = document.getElementById('bulkHistoryDeleteButton')
    const bulkSelectionCount = document.getElementById('bulkHistorySelectionCount')
    const bulkError = document.getElementById('bulkHistoryError')

    const syncBulkState = () => {
        const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length
        const hasSelection = selectedCount > 0

        bulkPanel?.classList.toggle('hidden', ! hasSelection)
        bulkDeleteButton?.toggleAttribute('disabled', ! hasSelection)
        bulkError?.classList.add('hidden')

        if (bulkSelectionCount) {
            bulkSelectionCount.textContent = `${selectedCount} record${selectedCount === 1 ? '' : 's'} selected`
        }

        if (selectAll) {
            selectAll.checked = checkboxes.length > 0 && selectedCount === checkboxes.length
            selectAll.indeterminate = selectedCount > 0 && selectedCount < checkboxes.length
        }
    }

    selectAll?.addEventListener('change', () => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = selectAll.checked
        })

        syncBulkState()
    })

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', syncBulkState)
    })

    bulkForm?.addEventListener('submit', (event) => {
        const selectedCount = checkboxes.filter((checkbox) => checkbox.checked).length

        if (selectedCount === 0) {
            event.preventDefault()
            bulkError?.classList.remove('hidden')
        }
    })

    syncBulkState()
})
</script>

@endsection