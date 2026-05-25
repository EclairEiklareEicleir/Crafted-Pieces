@extends('layouts.store')

@section('content')

@php
    $pricing = app(\App\Services\PricingService::class)->calculate($cartItems);
@endphp

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="mb-6 flex items-center justify-between gap-3">
        <x-back-button href="{{ route('shop') }}" label="Back to Shop" />
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- CART ITEMS --}}
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Cart review
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Your Cart
            </h1>

            <div class="mt-8 divide-y divide-brand-border">

                @forelse ($cartItems as $item)
                    <x-cart-item :item="$item" />
                @empty
                    <p class="text-sm text-brand-ink/70">
                        Your cart is empty.
                    </p>
                @endforelse

            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="rounded-4xl border border-brand-border bg-brand-light/40 p-6">

            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Summary
            </h2>

            <div class="mt-6 rounded-2xl bg-white p-6 text-sm text-brand-primary shadow-sm">

                {{-- ITEMS --}}
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-secondary">
                    Items
                </p>

                <div class="mt-4 space-y-3">

                    @foreach ($cartItems as $item)
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">
                                    {{ $item->product?->name ?? 'Deleted Product' }}
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
                                    {{ $item->quantity }} × PHP {{ number_format($item->price) }}
                                </p>
                            </div>

                            <div class="font-medium">
                                PHP {{ number_format($item->quantity * $item->price, 2) }}
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- SEPARATOR --}}
                <div class="my-5 border-t border-dashed border-brand-border"></div>

                {{-- SUBTOTAL --}}
                <div class="flex justify-between">
                    <span class="text-brand-ink/70">Subtotal</span>
                    <span class="font-semibold">
                        PHP {{ number_format($pricing['subtotal'], 2) }}
                    </span>
                </div>

                {{-- SEPARATOR --}}
                <div class="my-5 border-t border-dashed border-brand-border"></div>

                {{-- FEES --}}
                <div class="space-y-2 text-brand-ink/70">

                    <div class="flex justify-between">
                        <span>Platform Fee</span>
                        <span class="font-medium text-brand-primary">
                            PHP {{ number_format($pricing['platform_fee'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Delivery Fee</span>
                        <span class="font-medium text-brand-primary">
                            PHP {{ number_format($pricing['delivery_fee'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>VAT</span>
                        <span class="font-medium text-brand-primary">
                            PHP {{ number_format($pricing['vat'], 2) }}
                        </span>
                    </div>

                </div>

                {{-- SEPARATOR --}}
                <div class="my-5 border-t border-dashed border-brand-border"></div>

                {{-- TOTAL --}}
                <div class="flex justify-between text-base">
                    <span class="font-semibold text-brand-primary">Total</span>
                    <span class="font-bold text-brand-primary">
                        PHP {{ number_format($pricing['total'], 2) }}
                    </span>
                </div>

            </div>

            {{-- CHECKOUT --}}
            <a href="{{ route('checkout') }}"
            class="brand-btn-primary mt-6 block w-full py-3 text-center">
                Checkout
            </a>

        </div>

    </div>

</section>

@endsection
