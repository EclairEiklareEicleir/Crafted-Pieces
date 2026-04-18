@extends('layouts.store')

@section('content')
    @php
        $currentUser = auth()->user();
        $canSubmitCheckout = $currentUser && $currentUser->role === 'customer';
        $subtotal = array_sum(array_map(fn ($item) => $item['quantity'] * $item['price'], $cartItems ?? []));
    @endphp

    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Checkout</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">Complete Your Order</h1>
                <div class="mt-8 divide-y divide-[#efe3da]">
                    @forelse ($cartItems as $item)
                        <div class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0">
                            <div>
                                <p class="font-semibold text-[#4d3028]">{{ $item['name'] }}</p>
                                <p class="text-sm text-[#6f5a51]">Quantity: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-semibold text-[#8d5848]">PHP {{ number_format($item['quantity'] * $item['price']) }}</p>
                        </div>
                    @empty
                        <p class="py-2 text-sm text-[#6f5a51]">Your cart is empty. Add products before checking out.</p>
                    @endforelse
                </div>
            </div>
            <div class="rounded-[2rem] border border-[#eadfd7] bg-[#fcfaf8] p-6 shadow-sm">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Summary</h2>
                @if (! $canSubmitCheckout)
                    <p class="mt-3 text-sm text-[#6f5a51]">Log in as a customer to place an order.</p>
                @endif
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
                <form class="mt-6 space-y-4" method="POST" action="{{ $canSubmitCheckout ? route('checkout.submit') : '#' }}">
                    @csrf
                    <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" name="full_name" value="{{ old('full_name', $currentUser?->name) }}" placeholder="Full name">
                    @error('full_name')
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    @enderror

                    <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" name="email" value="{{ old('email', $currentUser?->email) }}" placeholder="Email address">
                    @error('email')
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    @enderror

                    <textarea class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" name="shipping_address" rows="4" placeholder="Shipping address">{{ old('shipping_address') }}</textarea>
                    @error('shipping_address')
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    @enderror

                    <select class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" name="payment_method">
                        <option value="GCash" {{ old('payment_method') === 'GCash' ? 'selected' : '' }}>GCash</option>
                        <option value="Maya" {{ old('payment_method') === 'Maya' ? 'selected' : '' }}>Maya</option>
                        <option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                    @error('payment_method')
                        <p class="text-sm text-red-700">{{ $message }}</p>
                    @enderror

                    @if ($canSubmitCheckout)
                        <button type="submit" class="w-full rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">Place order</button>
                    @else
                        <button type="button" data-auth-modal-open class="w-full rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">Place order</button>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
