@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl py-14 px-4">

    <div class="mb-6 flex items-center justify-between gap-3">
        <x-back-button href="{{ route('orders.track.form') }}" label="Back to Tracking" />

        <a href="{{ $receiptUrl }}"
              data-no-loading="true"
           class="brand-btn-primary inline-flex items-center px-6 py-2 text-sm">

            Download Receipt

        </a>
    </div>

    <div class="rounded-2xl border border-brand-border bg-white p-6">

        {{-- HEADER (same as receipt) --}}
        <div class="mb-4 border-b pb-4 text-center">
            <h1 class="text-2xl font-semibold text-brand-primary">
                Order Receipt {{ $order->public_reference }}
            </h1>

            <p class="text-sm text-brand-ink/70">
                {{ $order->created_at->format('F d, Y h:i A') }}
            </p>
        </div>

        {{-- CUSTOMER --}}
        <div class="mb-4 space-y-1 text-sm text-brand-ink/70">

            <p><strong>Reference:</strong> {{ $order->public_reference }}</p>
            <p><strong>Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($order->payment_method ?? 'PayMongo') }}</p>
            <p><strong>Payment Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}</p>

        </div>

        <hr class="my-4">

        {{-- ITEMS (SAME AS PDF) --}}
        <div class="space-y-2">

            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm text-brand-ink/70">
                    <span>
                        {{ $item->product->name }} × {{ $item->quantity }}
                    </span>

                    <span>
                        PHP {{ number_format($item->quantity * $item->price, 2) }}
                    </span>
                </div>

                @if ($item->yarnColor?->name || $item->variant_name)
                    <div class="mt-1 text-xs text-brand-ink/55">
                        Yarn color: {{ $item->yarnColor?->name ?? $item->variant_name }}
                    </div>
                @endif
            @endforeach

        </div>

        <hr class="my-4">

        {{-- PRICING (SAME SOURCE AS RECEIPT) --}}
        <div class="space-y-2 text-sm text-brand-ink/70">

            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>PHP {{ number_format($pricing['subtotal'], 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Platform Fee</span>
                <span>PHP {{ number_format($pricing['platform_fee'], 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>Delivery Fee</span>
                <span>PHP {{ number_format($pricing['delivery_fee'], 2) }}</span>
            </div>

            <div class="flex justify-between">
                <span>VAT</span>
                <span>PHP {{ number_format($pricing['vat'], 2) }}</span>
            </div>

            <hr>

            <div class="flex justify-between text-base font-semibold text-brand-primary">
                <span>Total Paid</span>
                <span>PHP {{ number_format($pricing['total'], 2) }}</span>
            </div>

        </div>

    </div>

</section>

@endsection
