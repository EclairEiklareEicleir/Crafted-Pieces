@extends('layouts.store')

@section('content')

@php
    // SINGLE SOURCE OF TRUTH: DATABASE SNAPSHOT ONLY
    $subtotal = $order->subtotal;
    $platformFee = $order->platform_fee;
    $deliveryFee = $order->delivery_fee;
    $vat = $order->vat_amount;
    $total = $order->total_amount;
@endphp

<section class="mx-auto max-w-3xl px-4 py-16">

    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-8 shadow-sm">

        <div class="text-center">

            <div class="text-5xl">🎉</div>

            <h1 class="mt-4 font-display text-3xl font-semibold text-[#4d3028]">
                Order Receipt
            </h1>

            <p class="mt-2 text-sm text-[#6f5a51]">
                Thank you for your purchase. Here is your official order summary.
            </p>

        </div>

        {{-- RECEIPT META --}}
        <div class="mt-8 rounded-2xl border border-[#eadfd7] bg-[#fcfaf8] p-6 text-sm text-[#6f5a51]">

            <div class="flex justify-between">
                <span>Order ID</span>
                <span class="font-semibold text-[#4d3028]">#{{ $order->id }}</span>
            </div>

            <div class="mt-2 flex justify-between">
                <span>Status</span>
                <span class="font-semibold text-[#4d3028]">
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <div class="mt-2 flex justify-between">
                <span>Date</span>
                <span class="font-semibold text-[#4d3028]">
                    {{ $order->created_at->format('M d, Y') }}
                </span>
            </div>

        </div>

        {{-- ITEMS --}}
        <div class="mt-6">

            <h2 class="text-sm font-semibold uppercase tracking-[0.15em] text-[#8f7a70]">
                Items
            </h2>

            <div class="mt-3 divide-y divide-[#efe3da]">

                @foreach ($order->items as $item)

                    @php $product = $item->product; @endphp

                    <div class="flex justify-between py-3 text-sm">

                        <div>
                            <p class="font-semibold text-[#4d3028]">
                                {{ $product->name ?? 'Deleted Product' }}
                            </p>

                            <p class="text-xs text-[#8f7a70]">
                                Qty: {{ $item->quantity }}
                            </p>
                        </div>

                        <p class="font-semibold text-[#4d3028]">
                            PHP {{ number_format($item->quantity * $item->price, 2) }}
                        </p>

                    </div>

                @endforeach

            </div>
        </div>

        {{-- SEPARATOR --}}
        <div class="my-4 border-t border-dashed border-[#e7d6cd]"></div>

        {{-- TOTAL BREAKDOWN (CLEAN RECEIPT STYLE) --}}
        <div class="mt-6 space-y-2">

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Subtotal</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($subtotal, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Platform Fee</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($platformFee, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Delivery Fee</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($deliveryFee, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>VAT</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($vat, 2) }}
                </span>
            </div>

            <div class="flex justify-between font-semibold text-[#4d3028] pt-2 border-t border-[#efe3da]">
                <span>Total Paid</span>
                <span>
                    PHP {{ number_format($total, 2) }}
                </span>
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">

            <a href="{{ route('shop') }}"
               class="flex-1 rounded-full bg-[#5d342b] px-6 py-3 text-center text-white">
                Continue Shopping
            </a>

            <a href="{{ route('orders.receipt.download', $order->id) }}"
               class="flex-1 rounded-full border border-[#eadfd7] bg-white px-6 py-3 text-center text-[#5d342b]">
                Download Receipt
            </a>

        </div>

    </div>

</section>

@endsection