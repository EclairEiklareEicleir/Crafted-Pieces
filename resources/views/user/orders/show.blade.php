@extends('layouts.store')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | SOURCE OF TRUTH (ORDER SNAPSHOT)
    |--------------------------------------------------------------------------
    | No recalculation, no service calls.
    | This ensures receipts NEVER change after checkout.
    */
    $pricing = [
        'subtotal' => $order->subtotal,
        'platform_fee' => $order->platform_fee,
        'vat' => $order->vat_amount,
        'delivery_fee' => $order->delivery_fee,
        'total' => $order->total_amount,
    ];
@endphp

<section class="mx-auto max-w-4xl px-4 py-14">

    <div class="mb-6 flex items-center justify-between gap-3">
        <x-back-button href="{{ route('orders') }}" label="Back to Orders" />

        <a href="{{ $order->customOrderRequest ? route('custom-order.receipt', $order->customOrderRequest) : route('orders.receipt.download', $order->id) }}" data-no-loading="true" class="brand-btn-primary px-5 py-3 text-sm">
            Download Receipt
        </a>
    </div>

    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

        {{-- HEADER --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="font-display text-3xl font-semibold text-[#4d3028]">
                    Order #{{ $order->id }}
                </h1>

                <p class="text-sm text-[#6f5a51]">
                    Placed on {{ $order->created_at->format('M d, Y - h:i A') }}
                </p>
            </div>

            <x-status-badge :status="$order->status" context="order" />

        </div>

        {{-- CUSTOMER INFO --}}
        <div class="mt-6 rounded-2xl bg-[#fcfaf8] p-5 text-sm text-[#6f5a51]">

            <p><strong>Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Type:</strong> {{ $order->order_type_label }}</p>
            <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Payment:</strong> {{ $order->payment_method }}</p>

            @if ($order->customOrderRequest)
                <p class="mt-2 text-brand-secondary">
                    Linked custom order #{{ $order->customOrderRequest->id }}
                    <a href="{{ route('custom-order.show', $order->customOrderRequest) }}" class="font-semibold underline">
                        open thread
                    </a>
                </p>
            @endif

        </div>

        {{-- ITEMS --}}
        <div class="mt-8 divide-y divide-[#efe3da]">

            @foreach ($order->items as $item)

                @php
                    $product = $item->product;
                    $itemImage = $item->productVariant?->floating_image_url
                        ?: \App\Support\ProductImage::floatingUrl($item->variant_image_path)
                        ?: ($product?->floating_image_url ?? 'https://placehold.co/600x600/png');
                @endphp

                <div class="flex items-center justify-between py-5">

                    <div class="flex items-center gap-4">

                        <img
                            src="{{ $itemImage }}"
                            class="h-16 w-16 rounded-xl border border-brand-border bg-brand-surface object-contain p-1.5"
                            alt="{{ $product?->name ?? 'Product image' }}"
                        >

                        <div>

                            <p class="font-semibold text-[#4d3028]">
                                {{ $product->name ?? 'Deleted Product' }}
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

                            <p class="text-sm text-[#6f5a51]">
                                Qty: {{ $item->quantity }}
                            </p>

                            <p class="text-xs text-[#8d5848]">
                                PHP {{ number_format($item->price, 2) }} each
                            </p>

                        </div>

                    </div>

                    <div class="font-semibold text-[#8d5848]">
                        PHP {{ number_format($item->quantity * $item->price, 2) }}
                    </div>

                </div>

            @endforeach

            @if ($order->items->isEmpty())
                <div class="py-3 text-sm text-brand-ink/60">
                    No product line items are attached to this order.
                    @if ($order->customOrderRequest)
                        Use the linked custom order thread for status updates and notes.
                    @endif
                </div>
            @endif

        </div>

        {{-- TOTAL BREAKDOWN --}}
        <div class="mt-6 border-t border-[#efe3da] pt-5 space-y-2">

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Subtotal</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($pricing['subtotal'], 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Platform Fee</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($pricing['platform_fee'], 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>VAT</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($pricing['vat'], 2) }}
                </span>
            </div>

            <div class="flex justify-between text-sm text-[#6f5a51]">
                <span>Delivery Fee</span>
                <span class="font-semibold text-[#4d3028]">
                    PHP {{ number_format($pricing['delivery_fee'], 2) }}
                </span>
            </div>

            <div class="flex justify-between font-semibold text-[#4d3028] pt-2 border-t border-[#efe3da]">
                <span>Total</span>
                <span>
                    PHP {{ number_format($pricing['total'], 2) }}
                </span>
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <p class="text-sm text-brand-ink/60">
                Keep this receipt for your records.
            </p>

            <div class="flex flex-wrap gap-3">
                <x-back-button href="{{ route('orders') }}" label="Back to Orders" />

                    <a href="{{ $order->customOrderRequest ? route('custom-order.receipt', $order->customOrderRequest) : route('orders.receipt.download', $order->id) }}"
                         data-no-loading="true"
                     class="brand-btn-primary px-5 py-3 text-sm">
                    Download Receipt
                </a>
            </div>

        </div>

    </div>

</section>

@endsection
