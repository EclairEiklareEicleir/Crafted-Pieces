@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-md px-4 py-20">

    <h1 class="text-3xl font-semibold text-brand-primary">
        Track Your Order
    </h1>

    <p class="mt-2 text-sm text-brand-ink/70">
        Use the order reference shown on your payment page or confirmation email.
    </p>

    <form method="POST" action="{{ route('orders.track') }}" class="mt-8 space-y-4">
        @csrf

        <input
            name="order_reference"
            type="text"
            placeholder="Order reference"
            class="brand-input"
            required
        >

        <input
            name="email"
            type="email"
            placeholder="Email used in order"
            class="brand-input"
            required
        >

        @error('order_reference')
            <p class="text-sm text-brand-secondary">{{ $message }}</p>
        @enderror

        <button class="brand-btn-primary w-full py-3">
            Track Order
        </button>

    </form>

</section>

@endsection