@extends('layouts.store')

@section('content')

@php
    $subtotal = $cartItems->sum(fn ($item) => $item->quantity * $item->price);
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

            @if (session('status'))
                <p class="mt-4 text-sm text-[#6f5a51]">
                    {{ session('status') }}
                </p>
            @endif

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

            <div class="mt-6 rounded-3xl bg-white p-5">

                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span>PHP {{ number_format($subtotal) }}</span>
                </div>

            </div>

            <a href="{{ route('checkout') }}"
               class="mt-6 block w-full rounded-full bg-[#5d342b] py-3 text-center text-white">
                Checkout
            </a>

        </div>

    </div>

</section>

@endsection