@extends('layouts.store')

@section('content')

@php
    $backButtonHref = $type === 'custom-order'
        ? route('custom-order.show', $item)
        : route('orders.show', $item);

    $backButtonLabel = $type === 'custom-order'
        ? 'Back to Ticket'
        : 'Back to Order';
@endphp

<section class="mx-auto max-w-3xl px-4 py-16">

    <div class="mb-6 flex items-center justify-between gap-3">
        <x-back-button href="{{ $backButtonHref }}" label="{{ $backButtonLabel }}" />
    </div>

    <h1 class="text-3xl font-semibold text-brand-primary">
        Payment Summary
    </h1>

    <div class="mt-6 rounded-2xl border border-brand-border bg-white p-6">

        <p class="text-sm text-brand-ink/70">
            You are paying for:
        </p>

        <p class="mt-2 text-xl font-semibold text-brand-primary">
            {{ ucfirst(str_replace('-', ' ', $type)) }}
        </p>

        <hr class="my-4">

        {{-- ITEM INFO --}}
        <div class="space-y-1 text-sm text-brand-ink/70">

            @if ($type === 'custom-order')
                <p><strong>Item:</strong> {{ $item->item_type }}</p>
                <p><strong>Status:</strong> {{ ucfirst($item->status) }}</p>
            @endif

            @if ($type === 'order')
                <p><strong>Order ID:</strong> #{{ $item->id }}</p>
            @endif

        </div>

        <hr class="my-4">

        {{-- BREAKDOWN --}}
        <div class="space-y-2 text-sm text-brand-ink/70">

            @if ($type === 'custom-order')

                {{-- CUSTOM ORDER = FIXED PRICE --}}
                <p>
                    Base Price:
                    <strong>₱{{ number_format($pricing['base_price'], 2) }}</strong>
                </p>

            @else

                {{-- CART ORDER = DETAILED BREAKDOWN --}}
                <p>
                    Subtotal:
                    <strong>₱{{ number_format($pricing['subtotal'], 2) }}</strong>
                </p>

            @endif

            <p>
                Platform Fee:
                <strong>₱{{ number_format($pricing['platform_fee'], 2) }}</strong>
            </p>

            <p>
                Delivery Fee:
                <strong>₱{{ number_format($pricing['delivery_fee'], 2) }}</strong>
            </p>

            <p>
                VAT:
                <strong>₱{{ number_format($pricing['vat'], 2) }}</strong>
            </p>

            <hr>

            <p class="text-lg text-brand-primary">
                Final Amount Payable:
                <strong>₱{{ number_format($pricing['total'], 2) }}</strong>
            </p>

        </div>

        <form method="POST"
              action="{{ route('user.payment.process', ['type' => $type, 'id' => $item->id]) }}"
              class="mt-6">

            @csrf

            <button class="brand-btn-primary w-full py-3">
                Pay Now
            </button>

        </form>

    </div>

</section>

@endsection