@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-16">

    <div class="rounded-[2rem] border border-brand-border bg-white p-8 shadow-sm">

        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
            PayMongo Checkout
        </p>

        <h1 class="mt-3 font-display text-3xl font-semibold text-brand-primary">
            {{ $title }}
        </h1>

        <p class="mt-3 text-sm text-brand-ink/70">
            {{ $message }}
        </p>

        <div class="mt-8 rounded-2xl border border-brand-border bg-brand-light/35 p-6 text-sm text-brand-ink/75">
            <div class="flex justify-between gap-4">
                <span>Order ID</span>
                <span class="font-semibold text-brand-primary">#{{ $order->id }}</span>
            </div>

            <div class="mt-2 flex justify-between gap-4">
                <span>Payment Method</span>
                <span class="font-semibold text-brand-primary">{{ $order->payment_method }}</span>
            </div>

            <div class="mt-2 flex justify-between gap-4">
                <span>Payment Status</span>
                <span class="font-semibold text-brand-primary">{{ ucfirst($order->payment_status) }}</span>
            </div>

            @if ($order->paid_at)
                <div class="mt-2 flex justify-between gap-4">
                    <span>Paid At</span>
                    <span class="font-semibold text-brand-primary">{{ $order->paid_at->format('M d, Y h:i A') }}</span>
                </div>
            @endif
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('checkout') }}" class="brand-btn-primary px-5 py-3">
                Back to Checkout
            </a>

            <a href="{{ route('orders.show', $order) }}" class="rounded-full border border-brand-border px-5 py-3 text-brand-primary">
                View Order
            </a>
        </div>

    </div>

</section>

@endsection