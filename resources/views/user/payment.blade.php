@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-16">

    <h1 class="text-3xl font-semibold text-[#4d3028]">
        Payment Summary
    </h1>

    <div class="mt-6 rounded-2xl border border-[#eadfd7] bg-white p-6">

        <p class="text-sm text-[#6f5a51]">
            You are paying for:
        </p>

        <p class="mt-2 text-xl font-semibold text-[#4d3028]">
            {{ ucfirst(str_replace('-', ' ', $type)) }}
        </p>

        <hr class="my-4">

        {{-- ITEM INFO --}}
        <div class="text-sm text-[#6f5a51] space-y-1">

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
        <div class="space-y-2 text-sm">

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

            <p class="text-lg">
                Final Amount Payable:
                <strong>₱{{ number_format($pricing['total'], 2) }}</strong>
            </p>

        </div>

        <form method="POST"
              action="{{ route('user.payment.process', ['type' => $type, 'id' => $item->id]) }}"
              class="mt-6">

            @csrf

            <button class="w-full rounded-full bg-[#5d342b] py-3 text-white">
                Pay Now
            </button>

        </form>

    </div>

</section>

@endsection