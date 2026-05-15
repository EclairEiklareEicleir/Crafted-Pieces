@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-md px-4 py-20">

    <h1 class="text-3xl font-semibold text-[#4d3028]">
        Track Your Order
    </h1>

    <form method="POST" action="{{ route('orders.track') }}" class="mt-8 space-y-4">
        @csrf

        <input
            name="order_id"
            type="number"
            placeholder="Order ID"
            class="w-full rounded-2xl border px-4 py-3"
            required
        >

        <input
            name="email"
            type="email"
            placeholder="Email used in order"
            class="w-full rounded-2xl border px-4 py-3"
            required
        >

        @error('track')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <button class="w-full rounded-full bg-[#5d342b] py-3 text-white">
            Track Order
        </button>

    </form>

</section>

@endsection