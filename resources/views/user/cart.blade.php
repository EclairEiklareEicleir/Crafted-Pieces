@extends('layouts.store')

@section('content')

@php
    $pricing = app(\App\Services\PricingService::class)->calculate($cartItems);
@endphp

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- CART ITEMS --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Cart review
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">
                Your Cart
            </h1>

            <div class="mt-8 divide-y divide-[#efe3da]">

                @forelse ($cartItems as $item)
                    <x-cart-item :item="$item" />
                @empty
                    <p class="text-sm text-[#6f5a51]">
                        Your cart is empty.
                    </p>
                @endforelse

            </div>

        </div>


        {{-- SUMMARY --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-[#fcfaf8] p-6">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Summary
            </h2>

            <div class="mt-6 rounded-2xl bg-white p-6 text-sm text-[#4d3028] shadow-sm">

                {{-- ITEMS --}}
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-[#8f6a5d]">
                    Items
                </p>

                <div class="mt-4 space-y-3">

                    @foreach ($cartItems as $item)
                        <div class="flex justify-between">
                            <div>
                                <p class="font-semibold">
                                    {{ $item->name }}
                                </p>
                                <p class="text-xs text-[#8f7a70]">
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
                <div class="my-5 border-t border-dashed border-[#e7d6cd]"></div>

                {{-- SUBTOTAL --}}
                <div class="flex justify-between">
                    <span class="text-[#6f5a51]">Subtotal</span>
                    <span class="font-semibold">
                        PHP {{ number_format($pricing['subtotal'], 2) }}
                    </span>
                </div>

                {{-- SEPARATOR --}}
                <div class="my-5 border-t border-dashed border-[#e7d6cd]"></div>

                {{-- FEES --}}
                <div class="space-y-2 text-[#6f5a51]">

                    <div class="flex justify-between">
                        <span>Platform Fee</span>
                        <span class="text-[#4d3028] font-medium">
                            PHP {{ number_format($pricing['platform_fee'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Delivery Fee</span>
                        <span class="text-[#4d3028] font-medium">
                            PHP {{ number_format($pricing['delivery_fee'], 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>VAT</span>
                        <span class="text-[#4d3028] font-medium">
                            PHP {{ number_format($pricing['vat'], 2) }}
                        </span>
                    </div>

                </div>

                {{-- SEPARATOR --}}
                <div class="my-5 border-t border-dashed border-[#e7d6cd]"></div>

                {{-- TOTAL --}}
                <div class="flex justify-between text-base">
                    <span class="font-semibold text-[#4d3028]">Total</span>
                    <span class="font-bold text-[#4d3028]">
                        PHP {{ number_format($pricing['total'], 2) }}
                    </span>
                </div>

            </div>

            {{-- CHECKOUT --}}
            <a href="{{ route('checkout') }}"
            class="mt-6 block w-full rounded-full bg-[#5d342b] py-3 text-center text-white font-semibold">
                Checkout
            </a>

        </div>

    </div>

</section>

@endsection