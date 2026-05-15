@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-20 text-center">

    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-10 shadow-sm">

        <div class="text-5xl">🎉</div>

        <h1 class="mt-4 font-display text-4xl font-semibold text-[#4d3028]">
            Order Successful
        </h1>

        <p class="mt-3 text-[#6f5a51]">
            Thank you for your order. We’ve received it and are now processing it.
        </p>

        <div class="mt-6 rounded-2xl bg-[#fcfaf8] p-5 text-sm text-[#6f5a51]">

            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Total:</strong> PHP {{ number_format($order->total_amount) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

        </div>

        <div class="mt-8 flex justify-center gap-3">

            <a href="{{ route('shop') }}"
               class="rounded-full bg-[#5d342b] px-6 py-3 text-white">
                Continue Shopping
            </a>

        </div>

    </div>

</section>

@endsection