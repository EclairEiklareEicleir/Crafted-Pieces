@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    @if (session('checkout_success'))
        <div class="mb-8 rounded-3xl border border-green-200 bg-green-50 p-6 text-green-800">

            <h2 class="text-xl font-semibold">🎉 Order Placed Successfully!</h2>

            <p class="mt-2 text-sm">
                Your order #{{ session('checkout_success.order_id') }} has been received.
            </p>

            <p class="mt-1 text-sm">
                Total Paid: PHP {{ number_format(session('checkout_success.total'), 2) }}
            </p>

            <a href="{{ route('shop') }}"
               class="mt-4 inline-block rounded-full bg-[#5d342b] px-5 py-2 text-white">
                Continue Shopping
            </a>

        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">

        {{-- LEFT --}}
        <div class="rounded-[2rem] border border-brand-border bg-brand-light/35 p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Checkout
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Complete Your Order
            </h1>

            @if ($cartItems->isEmpty())

                <p class="mt-6 text-sm text-brand-ink/70">
                    Your cart is empty.
                </p>

            @else

                {{-- RECEIPT SUMMARY --}}
                <div class="mt-8 rounded-2xl bg-white p-6 text-sm text-brand-primary shadow-sm">

                    {{-- ITEMS HEADER --}}
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-secondary">
                        Items
                    </p>

                    {{-- ITEMS LIST --}}
                    <div class="mt-4 space-y-3">

                        @foreach ($cartItems as $item)
                            <div class="flex justify-between">
                                <div>
                                    <p class="font-semibold">
                                        {{ $item->product->name }}
                                    </p>
                                    <p class="text-xs text-brand-ink/55">
                                        {{ $item->quantity }} × PHP {{ number_format($item->price, 2) }}
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
                    <div class="flex justify-between text-brand-ink/70">
                        <span>Subtotal</span>
                        <span class="font-semibold text-brand-primary">
                            PHP {{ number_format($pricing['subtotal'], 2) }}
                        </span>
                    </div>

                    {{-- SEPARATOR --}}
                    <div class="my-5 border-t border-dashed border-brand-border"></div>

                    {{-- FEES --}}
                    <div class="space-y-2 text-brand-ink/70">

                        @isset($pricing['platform_fee'])
                        <div class="flex justify-between">
                            <span>Platform Fee</span>
                            <span class="font-medium text-brand-primary">
                                PHP {{ number_format($pricing['platform_fee'], 2) }}
                            </span>
                        </div>
                        @endisset

                        @isset($pricing['delivery_fee'])
                        <div class="flex justify-between">
                            <span>Delivery Fee</span>
                            <span class="font-medium text-brand-primary">
                                PHP {{ number_format($pricing['delivery_fee'], 2) }}
                            </span>
                        </div>
                        @endisset

                        @isset($pricing['vat'])
                        <div class="flex justify-between">
                            <span>VAT</span>
                            <span class="font-medium text-brand-primary">
                                PHP {{ number_format($pricing['vat'], 2) }}
                            </span>
                        </div>
                        @endisset

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

            @endif

        </div>

        {{-- RIGHT --}}
        <div class="rounded-[2rem] border border-brand-border bg-brand-light/35 p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Payment Detail
            </h2>

            @if (!session('checkout_success') && !$cartItems->isEmpty())

                <form class="mt-6 space-y-4" method="POST" action="{{ route('checkout.submit') }}">
                    @csrf

                          <input class="brand-input"
                           name="full_name"
                           placeholder="Full name"
                           required>

                          <input class="brand-input"
                           name="email"
                           placeholder="Email address"
                           required>

                    <textarea class="brand-input"
                              name="shipping_address"
                              rows="4"
                              placeholder="Shipping address"
                              required></textarea>

                        <select class="brand-input"
                            name="payment_method"
                            required>

                        <option value="GCash">GCash</option>
                        <option value="Maya">Maya</option>

                    </select>

                        <button type="submit"
                            class="brand-btn-primary w-full py-3">
                        Place Order
                    </button>

                </form>

            @endif

        </div>

    </div>

</section>

@endsection