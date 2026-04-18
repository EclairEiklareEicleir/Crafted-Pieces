@extends('layouts.store')

@section('content')
    @php
        $subtotal = array_sum(array_map(fn ($item) => $item['quantity'] * $item['price'], $cartItems ?? []));
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Cart review</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">Your Cart</h1>
                @if (session('status'))
                    <p class="mt-4 text-sm text-[#6f5a51]">{{ session('status') }}</p>
                @endif
                @if ($errors->has('checkout'))
                    <p class="mt-4 text-sm text-red-700">{{ $errors->first('checkout') }}</p>
                @endif
                @if ($errors->has('quantity'))
                    <p class="mt-2 text-sm text-red-700">{{ $errors->first('quantity') }}</p>
                @endif
                <div class="mt-8 divide-y divide-[#efe3da]">
                    @forelse ($cartItems as $item)
                        <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                            <div>
                                <p class="font-semibold text-[#4d3028]">{{ $item['name'] }}</p>
                                <form method="POST" action="{{ route('cart.items.update', $item['id']) }}" class="mt-2 flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="quantity-{{ $item['id'] }}" class="text-sm text-[#6f5a51]">Qty</label>
                                    <input
                                        id="quantity-{{ $item['id'] }}"
                                        type="number"
                                        name="quantity"
                                        min="1"
                                        max="99"
                                        value="{{ $item['quantity'] }}"
                                        class="w-20 rounded-xl border border-[#eadfd7] px-3 py-1.5 text-sm"
                                    >
                                    <button type="submit" class="rounded-full border border-[#eadfd7] bg-white px-3 py-1.5 text-xs font-semibold text-[#5d342b] transition hover:-translate-y-0.5">Update</button>
                                </form>
                                <form method="POST" action="{{ route('cart.items.remove', $item['id']) }}" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-[#8d5848] transition hover:text-[#a86b57]">Remove</button>
                                </form>
                            </div>
                            <p class="font-semibold text-[#8d5848]">PHP {{ number_format($item['quantity'] * $item['price']) }}</p>
                        </div>
                    @empty
                        <p class="py-2 text-sm text-[#6f5a51]">Your cart is empty. Browse products and add items first.</p>
                    @endforelse
                </div>
            </div>
            <div class="rounded-[2rem] border border-[#eadfd7] bg-[#fcfaf8] p-6 shadow-sm">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Summary</h2>
                <div class="mt-6 rounded-3xl bg-white p-5">
                    <div class="flex items-center justify-between text-sm text-[#6f5a51]">
                        <span>Subtotal</span>
                        <span>PHP {{ number_format($subtotal) }}</span>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm text-[#6f5a51]">
                        <span>Shipping</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-[#efe3da] pt-4 text-base font-semibold text-[#4d3028]">
                        <span>Total</span>
                        <span>PHP {{ number_format($subtotal) }}</span>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="mt-6 block rounded-full bg-[#b8745f] px-5 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">Proceed to checkout</a>
            </div>
        </div>
    </section>
@endsection
