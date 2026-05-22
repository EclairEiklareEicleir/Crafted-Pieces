@extends('layouts.admin')

@section('content')

<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">

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

        <p class="text-sm text-brand-ink/70">
            Payment Status: {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}
        </p>

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

        <p class="mt-2 text-sm text-brand-ink/70">
            Current: <strong>{{ ucfirst($order->status) }}</strong>
        </p>

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
                Update Status
            </button>

        </form>

        {{-- QUICK ACTION BUTTONS (optional UX upgrade) --}}
        <div class="mt-6 space-y-2">

            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                @csrf
                <input type="hidden" name="status" value="shipped">
                <button class="w-full rounded-xl bg-brand-secondary py-2 text-white hover:bg-brand-primary">
                    Mark as Shipped
                </button>
            </form>

            <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
                @csrf
                <input type="hidden" name="status" value="delivered">
                <button class="w-full rounded-xl bg-brand-primary py-2 text-white hover:bg-brand-secondary">
                    Mark as Delivered
                </button>
            </form>

        </div>

    </div>

</div>

@endsection