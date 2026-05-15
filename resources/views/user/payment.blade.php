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

            <p>
                Base Amount:
                <strong>₱{{ number_format($breakdown['base_amount'], 2) }}</strong>
            </p>

            <p>
                Platform Fee (5%):
                <strong>₱{{ number_format($breakdown['platform_fee'], 2) }}</strong>
            </p>

            <p class="text-lg">
                Total:
                <strong>₱{{ number_format($breakdown['total_amount'], 2) }}</strong>
            </p>

            <hr>

            <p class="text-green-700">
                Deposit Required (50%):
                <strong>₱{{ number_format($breakdown['deposit'], 2) }}</strong>
            </p>

            <p class="text-[#6f5a51]">
                Remaining Balance:
                <strong>₱{{ number_format($breakdown['balance'], 2) }}</strong>
            </p>

        </div>

        <form method="POST"
              action="{{ route('user.payment.process', ['type' => $type, 'id' => $item->id]) }}"
              class="mt-6">

            @csrf

            <button class="w-full rounded-full bg-[#5d342b] py-3 text-white">
                Pay Deposit (Mock)
            </button>

        </form>

    </div>

</section>

@endsection