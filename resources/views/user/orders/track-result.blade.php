@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl py-14 px-4">

    <div class="rounded-2xl border border-brand-border bg-white p-6">

        {{-- HEADER (same as receipt) --}}
        <div class="mb-4 border-b pb-4 text-center">
            <h1 class="text-2xl font-semibold text-brand-primary">
                Order Receipt #{{ $order->id }}
            </h1>

            <p class="text-sm text-brand-ink/70">
                {{ $order->created_at->format('F d, Y h:i A') }}
            </p>
        </div>

        {{-- CUSTOMER --}}
        <div class="mb-4 space-y-1 text-sm text-brand-ink/70">

            <p><strong>Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>

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

    {{-- DOWNLOAD RECEIPT --}}
    <div class="mt-6 flex justify-end">

        <a href="{{ route('orders.receipt.download', $order->id) }}"
        class="brand-btn-primary inline-flex items-center px-6 py-2 text-sm">

            Download Receipt

        </a>

    </div>

</section>

@endsection