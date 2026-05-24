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

    <div class="mb-6">
                    <p class="text-sm text-[#6f5a51]">
                        Qty: {{ $item->quantity }}
                    </p>

                    @if ($item->productVariant?->name || $item->variant_name)
                        <p class="text-xs text-[#8d5848]">
                            Variant: {{ $item->productVariant?->name ?? $item->variant_name }}
                        </p>
                    @endif

                    @if ($item->productVariant?->sku)
                        <p class="text-xs text-[#8d5848]">
                            SKU: {{ $item->productVariant?->sku }}
                        </p>
                    @endif

                    <p class="text-xs text-[#8d5848]">
                        PHP {{ number_format($item->price, 2) }} each
                    </p>

            <h1 class="mt-4 font-display text-3xl font-semibold text-brand-primary">
                Order Receipt
            </h1>

            <p class="mt-2 text-sm text-brand-ink/70">
                Thank you for your purchase. Here is your official order summary.
            </p>

        </div>

        {{-- RECEIPT META --}}
        <div class="mt-8 rounded-2xl border border-brand-border bg-brand-light/35 p-6 text-sm text-brand-ink/70">

            <div class="flex justify-between">
                <span>Order ID</span>
                <span class="font-semibold text-brand-primary">#{{ $order->id }}</span>
            </div>

            <div class="mt-2 flex justify-between">
                <span>Status</span>
                <x-status-badge :status="$order->status" context="order" />
            </div>

            <div class="mt-2 flex justify-between">
                <span>Payment Status</span>
                <x-status-badge :status="$order->payment_status ?? 'unpaid'" context="payment" />
            </div>

            <div class="mt-2 flex justify-between">
                <span>Date</span>
                <span class="font-semibold text-brand-primary">
                    {{ $order->created_at->format('M d, Y') }}
                </span>
            </div>

        </div>

        {{-- ITEMS --}}
        <div class="mt-6">

            <h2 class="text-sm font-semibold uppercase tracking-[0.15em] text-brand-secondary">
                Items
            </h2>

            <div class="mt-3 divide-y divide-brand-border">

                @foreach ($order->items as $item)

                    @php $product = $item->product; @endphp

                    <div class="flex justify-between py-3 text-sm">

                        <div>
                            <p class="font-semibold text-brand-primary">
                                {{ $product->name ?? 'Deleted Product' }}
                            </p>

                            @if ($item->productVariant?->name || $item->variant_name)
                                <p class="text-xs text-brand-ink/55">
                                    Variant: {{ $item->productVariant?->name ?? $item->variant_name }}
                                </p>
                            @endif

                            @if ($item->productVariant?->sku)
                                <p class="text-xs text-brand-ink/55">
                                    SKU: {{ $item->productVariant?->sku }}
                                </p>
                            @endif

                            <p class="text-xs text-brand-ink/55">
                                Qty: {{ $item->quantity }}
                            </p>
                        </div>

                        <p class="font-semibold text-brand-primary">
                            PHP {{ number_format($item->quantity * $item->price, 2) }}
                        </p>

                    </div>

                @endforeach

            </div>
        </div>

        {{-- SEPARATOR --}}
        <div class="my-4 border-t border-dashed border-brand-border"></div>

        {{-- TOTAL BREAKDOWN (CLEAN RECEIPT STYLE) --}}
        <div class="mt-6 space-y-2">

            <div class="flex justify-between text-sm text-brand-ink/70">
                <span>Subtotal</span>
                <span class="font-semibold text-brand-primary">
                    PHP {{ number_format($subtotal, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-brand-ink/70">
                <span>Platform Fee</span>
                <span class="font-semibold text-brand-primary">
                    PHP {{ number_format($platformFee, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-brand-ink/70">
                <span>Delivery Fee</span>
                <span class="font-semibold text-brand-primary">
                    PHP {{ number_format($deliveryFee, 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-brand-ink/70">
                <span>VAT</span>
                <span class="font-semibold text-brand-primary">
                    PHP {{ number_format($vat, 2) }}
                </span>
            </div>

            <div class="flex justify-between border-t border-brand-border pt-2 font-semibold text-brand-primary">
                <span>Total Paid</span>
                <span>
                    PHP {{ number_format($total, 2) }}
                </span>
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="mt-8 flex flex-col gap-3 sm:flex-row">

                <a href="{{ route('shop') }}"
                    class="brand-btn-primary flex-1 px-6 py-3 text-center">
                Continue Shopping
            </a>

            <a href="{{ route('orders.receipt.download', $order->id) }}"
                    data-no-loading="true"
                    class="brand-btn-secondary flex-1 px-6 py-3 text-center">
                Download Receipt
            </a>

        </div>

    </div>

</section>

@endsection
