@extends('layouts.admin')

@section('content')

<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">

    <div class="lg:col-span-2">
        <x-back-button href="{{ route('admin.orders.index') }}" label="Back to Orders" />
    </div>

    {{-- ORDER DETAILS --}}
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Order #{{ $order->id }}
        </h2>

        <p class="mt-2 text-sm text-brand-ink/70">
            Customer: {{ $order->full_name }} ({{ $order->email }})
        </p>

        <p class="text-sm text-brand-ink/70">
            Address: {{ $order->shipping_address }}
        </p>

        <p class="text-sm text-brand-ink/70">
            Payment: {{ $order->payment_method }}
        </p>

        <div id="payment-details" class="mt-2 flex flex-wrap items-center gap-3 text-sm text-brand-ink/70">
            <span>Payment Status:</span>
            <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
        </div>

        @if ($order->paymongo_checkout_id)
            <p class="text-sm text-brand-ink/70">
                PayMongo Checkout ID: {{ $order->paymongo_checkout_id }}
            </p>
        @endif

        @if ($order->paymongo_payment_id)
            <p class="text-sm text-brand-ink/70">
                PayMongo Payment ID: {{ $order->paymongo_payment_id }}
            </p>
        @endif

        @if ($order->paid_at)
            <p class="text-sm text-brand-ink/70">
                Paid At: {{ $order->paid_at->format('M d, Y h:i A') }}
            </p>
        @endif

        <hr class="my-6 border-brand-border">

        <h3 class="font-semibold text-brand-primary">Items</h3>

        <div class="mt-4 space-y-3">

            @foreach ($order->items as $item)

                <div class="flex justify-between text-sm">

                    <div>
                        <p class="font-medium text-brand-primary">
                            {{ $item->product->name ?? 'Deleted Product' }}
                        </p>

                        @if ($item->yarnColor?->name || $item->variant_name)
                            <p class="text-xs text-brand-ink/70">
                                Yarn color: {{ $item->yarnColor?->name ?? $item->variant_name }}
                            </p>
                        @endif

                        <p class="text-brand-ink/70">
                            Qty: {{ $item->quantity }}
                        </p>
                    </div>

                    <p class="font-semibold text-brand-secondary">
                        PHP {{ number_format($item->price * $item->quantity) }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>

    {{-- STATUS PANEL --}}
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

        <h3 class="font-display text-xl font-semibold text-brand-primary">
            Order Status
        </h3>

        <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-brand-ink/70">
            <span>Current:</span>
            <x-status-badge :status="$order->status" context="order" />
        </div>

        <form method="POST"
              action="{{ route('admin.orders.status', $order->id) }}"
              class="mt-6 space-y-3">

            @csrf

            <select name="status"
                    class="brand-input">

                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                    Processing
                </option>

                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>
                    Shipped
                </option>

                <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>
                    Out for Delivery
                </option>

                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>
                    Delivered
                </option>

                <option value="received" {{ $order->status === 'received' ? 'selected' : '' }}>
                    Received
                </option>

                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

            </select>

            <button type="submit"
                    class="brand-btn-primary w-full py-3">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12l4 4L19 6" />
                </svg>

                <span>Update Status</span>
            </button>

        </form>

        {{-- QUICK ACTION BUTTONS (optional UX upgrade) --}}
        <div class="mt-6 space-y-2">

            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                @csrf
                <input type="hidden" name="status" value="shipped">
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-secondary py-2 text-white hover:bg-brand-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5h11.5l3 3H21v6h-1.5a2.5 2.5 0 0 0-5 0h-6a2.5 2.5 0 0 0-5 0H2V10a2.5 2.5 0 0 1 1-2.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.5 10.5V7.5" />
                        <circle cx="7" cy="16.5" r="2" />
                        <circle cx="16" cy="16.5" r="2" />
                    </svg>

                    <span>Mark as Shipped</span>
                </button>
            </form>

            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                @csrf
                <input type="hidden" name="status" value="delivered">
                <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-brand-primary py-2 text-white hover:bg-brand-secondary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 9.5 17.5 4 12" />
                    </svg>

                    <span>Mark as Delivered</span>
                </button>
            </form>

        </div>

    </div>

</div>

@endsection
