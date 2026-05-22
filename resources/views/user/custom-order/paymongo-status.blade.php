@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-16">

    <div class="rounded-4xl border border-brand-border bg-white p-8 shadow-sm">

        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
            Custom Order Payment
        </p>

        <h1 class="mt-3 font-display text-3xl font-semibold text-brand-primary">
            {{ $title }}
        </h1>

        <p class="mt-3 text-sm text-brand-ink/70">
            {{ $message }}
        </p>

        <div class="mt-8 rounded-2xl border border-brand-border bg-brand-light/35 p-6 text-sm text-brand-ink/75">
            <div class="flex justify-between gap-4">
                <span>Ticket ID</span>
                <span class="font-semibold text-brand-primary">#{{ $order->id }}</span>
            </div>

            <div class="mt-2 flex justify-between gap-4">
                <span>Payment Method</span>
                <span class="font-semibold text-brand-primary">{{ $order->payment_method ?? 'PayMongo' }}</span>
            </div>

            <div class="mt-2 flex justify-between gap-4">
                <span>Payment Status</span>
                <span class="font-semibold text-brand-primary">{{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'unpaid')) }}</span>
            </div>

            @if ($order->paid_at)
                <div class="mt-2 flex justify-between gap-4">
                    <span>Paid At</span>
                    <span class="font-semibold text-brand-primary">{{ $order->paid_at->format('M d, Y h:i A') }}</span>
                </div>
            @endif
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('custom-order.show', $order) }}" class="brand-btn-primary px-5 py-3">
                Back to Ticket
            </a>
        </div>

    </div>

</section>

@endsection