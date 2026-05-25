@extends('layouts.admin')

@section('content')

@php
    $statusLabel = fn ($status) => ucwords(str_replace('_', ' ', $status));
    $customerName = $order->full_name ?: ($order->user?->name ?? 'Guest Customer');
    $customerEmail = $order->email ?: ($order->user?->email ?? 'No email');
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-back-button href="{{ route('admin.orders.index') }}" label="Back to Orders" />
        </div>

        <div class="text-left sm:text-right">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                {{ $order->order_type_label }}
            </p>

            <h2 class="mt-1 font-display text-3xl font-semibold text-brand-primary">
                Order #{{ $order->id }}
            </h2>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="space-y-6">
            <section class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="font-display text-xl font-semibold text-brand-primary">
                            Customer & Payment
                        </h3>

                        @if ($order->public_reference)
                            <p class="mt-1 text-sm text-brand-ink/55">
                                {{ $order->public_reference }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="brand-pill">{{ $order->order_type_label }}</span>
                        <x-status-badge :status="$order->status" context="order" />
                        <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
                    </div>
                </div>

                <dl class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-3xl bg-brand-surface px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            Customer
                        </dt>
                        <dd class="mt-1 text-sm font-semibold text-brand-primary">
                            {{ $customerName }}
                        </dd>
                    </div>

                    <div class="rounded-3xl bg-brand-surface px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            Email
                        </dt>
                        <dd class="mt-1 wrap-break-word text-sm text-brand-ink/75">
                            {{ $customerEmail }}
                        </dd>
                    </div>

                    <div class="rounded-3xl bg-brand-surface px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            Payment Method
                        </dt>
                        <dd class="mt-1 text-sm text-brand-ink/75">
                            {{ $order->payment_method }}
                        </dd>
                    </div>

                    @if ($order->customOrderRequest)
                        <div class="rounded-3xl bg-brand-surface px-4 py-3 sm:col-span-2">
                            <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                Linked Custom Order
                            </dt>
                            <dd class="mt-2 flex flex-wrap items-center gap-3 text-sm text-brand-ink/75">
                                <span>#{{ $order->customOrderRequest->id }}</span>
                                <span class="brand-pill">Custom Order</span>
                                <a href="{{ route('admin.custom.show', $order->customOrderRequest) }}" class="font-semibold text-brand-secondary hover:text-brand-primary">
                                    Open thread
                                </a>
                            </dd>
                        </div>
                    @endif

                    <div class="rounded-3xl bg-brand-surface px-4 py-3">
                        <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            Payment Status
                        </dt>
                        <dd class="mt-2">
                            <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
                        </dd>
                    </div>

                    <div class="rounded-3xl bg-brand-surface px-4 py-3 sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            Shipping Address
                        </dt>
                        <dd class="mt-1 whitespace-pre-line text-sm leading-6 text-brand-ink/75">
                            {{ $order->shipping_address }}
                        </dd>
                    </div>

                    @if ($order->paymongo_checkout_id)
                        <div class="rounded-3xl bg-brand-surface px-4 py-3 sm:col-span-2">
                            <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                PayMongo Checkout ID
                            </dt>
                            <dd class="mt-1 wrap-break-word text-sm text-brand-ink/75">
                                {{ $order->paymongo_checkout_id }}
                            </dd>
                        </div>
                    @endif

                    @if ($order->paymongo_payment_id)
                        <div class="rounded-3xl bg-brand-surface px-4 py-3 sm:col-span-2">
                            <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                PayMongo Payment ID
                            </dt>
                            <dd class="mt-1 wrap-break-word text-sm text-brand-ink/75">
                                {{ $order->paymongo_payment_id }}
                            </dd>
                        </div>
                    @endif

                    @if ($order->paid_at)
                        <div class="rounded-3xl bg-brand-surface px-4 py-3">
                            <dt class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                Paid At
                            </dt>
                            <dd class="mt-1 text-sm text-brand-ink/75">
                                {{ $order->paid_at->format('M d, Y h:i A') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="font-display text-xl font-semibold text-brand-primary">
                        Items
                    </h3>

                    <p class="text-sm font-semibold text-brand-secondary">
                        Total: PHP {{ number_format((float) $order->total_amount, 2) }}
                    </p>
                </div>

                <div class="mt-5 divide-y divide-brand-border">
                    @forelse ($order->items as $item)
                        <div class="flex flex-col gap-3 py-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-semibold text-brand-primary">
                                    {{ $item->product->name ?? 'Deleted Product' }}
                                </p>

                                @if ($item->productVariant?->name || $item->variant_name)
                                    <p class="mt-1 text-xs text-brand-ink/60">
                                        Variant: {{ $item->productVariant?->name ?? $item->variant_name }}
                                    </p>
                                @endif

                                @if ($item->productVariant?->sku)
                                    <p class="mt-1 text-xs text-brand-ink/60">
                                        SKU: {{ $item->productVariant?->sku }}
                                    </p>
                                @endif

                                <p class="mt-1 text-sm text-brand-ink/70">
                                    Qty: {{ $item->quantity }} x PHP {{ number_format((float) $item->price, 2) }}
                                </p>
                            </div>

                            <p class="font-semibold text-brand-secondary">
                                PHP {{ number_format((float) $item->price * $item->quantity, 2) }}
                            </p>
                        </div>
                    @empty
                        <div class="py-6 text-sm text-brand-ink/60">
                            No product line items were added for this order.
                            @if ($order->customOrderRequest)
                                This is linked to a custom order thread instead of a product cart checkout.
                            @endif
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm lg:self-start">
            <h3 class="font-display text-xl font-semibold text-brand-primary">
                Order Status
            </h3>

            <div class="mt-3 flex flex-wrap items-center gap-3 text-sm text-brand-ink/70">
                <span>Current:</span>
                <x-status-badge :status="$order->status" context="order" />
            </div>

            <form method="POST"
                  action="{{ route('admin.orders.status', $order->id) }}"
                  class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="order-status" class="mb-2 block text-sm font-semibold text-brand-primary">
                        Update status
                    </label>

                    <select id="order-status"
                            name="status"
                            class="brand-input"
                            required>
                        @foreach ($orderStatuses as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>
                                {{ $statusLabel($status) }}
                            </option>
                        @endforeach
                    </select>

                    @error('status')
                        <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="brand-btn-primary w-full py-3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6" />
                    </svg>

                    <span>Update Status</span>
                </button>
            </form>

            @if (($order->order_type ?? 'online_order') === 'walk_in_order')
                <div class="mt-6 border-t border-brand-border pt-6">
                    <h4 class="text-sm font-semibold text-brand-primary">
                        Payment Status
                    </h4>

                    <p class="mt-2 text-sm text-brand-ink/70">
                        Manually mark this walk-in order as pending or paid.
                    </p>

                    <form method="POST" action="{{ route('admin.orders.payment-status', $order) }}" class="mt-4 space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label for="payment-status" class="mb-2 block text-sm font-semibold text-brand-primary">
                                Update payment status
                            </label>

                            <select id="payment-status" name="payment_status" class="brand-input" required>
                                <option value="pending" @selected(($order->payment_status ?? 'pending') === 'pending')>Pending</option>
                                <option value="paid" @selected(($order->payment_status ?? '') === 'paid')>Paid</option>
                            </select>

                            @error('payment_status')
                                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="brand-btn-secondary w-full py-3">
                            Update Payment Status
                        </button>
                    </form>
                </div>
            @endif

        </aside>
    </div>
</div>

@endsection
