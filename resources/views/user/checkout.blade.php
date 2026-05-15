@extends('layouts.store')

@section('content')

@php
    $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->price);
@endphp

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    {{-- SUCCESS STATE (FLASH SAFE) --}}
    @if (session('checkout_success'))
        <div class="mb-8 rounded-3xl border border-green-200 bg-green-50 p-6 text-green-800">

            <h2 class="text-xl font-semibold">
                🎉 Order Placed Successfully!
            </h2>

            <p class="mt-2 text-sm">
                Your order #{{ session('checkout_success.order_id') }} has been received.
            </p>

            <p class="mt-1 text-sm">
                Total Paid: PHP {{ number_format(session('checkout_success.total')) }}
            </p>

            <a href="{{ route('shop') }}"
               class="mt-4 inline-block rounded-full bg-[#5d342b] px-5 py-2 text-white">
                Continue Shopping
            </a>

        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- LEFT --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Checkout
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">
                Complete Your Order
            </h1>

            @if ($cartItems->isEmpty())
                <p class="mt-6 text-sm text-[#6f5a51]">
                    Your cart is empty.
                </p>
            @else

                <div class="mt-8 divide-y divide-[#efe3da]">

                    @foreach ($cartItems as $item)
                        <div class="flex items-center justify-between py-4">

                            <div>
                                <p class="font-semibold text-[#4d3028]">
                                    {{ $item->product->name }}
                                </p>

                                <p class="text-sm text-[#6f5a51]">
                                    Qty: {{ $item->quantity }}
                                </p>
                            </div>

                            <p class="font-semibold text-[#8d5848]">
                                PHP {{ number_format($item->quantity * $item->price) }}
                            </p>

                        </div>
                    @endforeach

                </div>

            @endif

        </div>

        {{-- RIGHT --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-[#fcfaf8] p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Summary
            </h2>

            <div class="mt-6 rounded-3xl bg-white p-5">

                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span>PHP {{ number_format($subtotal) }}</span>
                </div>

            </div>

            {{-- FORM ONLY IF NO SUCCESS --}}
            @if (!session('checkout_success') && !$cartItems->isEmpty())

                <form class="mt-6 space-y-4" method="POST" action="{{ route('checkout.submit') }}">
                    @csrf

                    <input class="w-full rounded-2xl border px-4 py-3"
                           name="full_name"
                           placeholder="Full name"
                           required>

                    <input class="w-full rounded-2xl border px-4 py-3"
                           name="email"
                           placeholder="Email address"
                           required>

                    <textarea class="w-full rounded-2xl border px-4 py-3"
                              name="shipping_address"
                              rows="4"
                              placeholder="Shipping address"
                              required></textarea>

                    <select class="w-full rounded-2xl border px-4 py-3"
                            name="payment_method"
                            required>

                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>
                        <option value="Bank Transfer">Bank Transfer</option>

                    </select>

                    <button type="submit"
                            class="w-full rounded-full bg-[#5d342b] py-3 text-white">
                        Place Order
                    </button>

                </form>

            @endif

        </div>

    </div>

</section>

@endsection