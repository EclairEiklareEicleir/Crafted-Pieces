@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-4xl px-4 py-14">

    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        {{-- HEADER --}}
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">

            <div>
                <h1 class="font-display text-3xl font-semibold text-[#4d3028]">
                    Order #{{ $order->id }}
                </h1>

                <p class="text-sm text-[#6f5a51]">
                    Placed on {{ $order->created_at->format('M d, Y - h:i A') }}
                </p>
            </div>

            <div class="text-sm font-semibold text-[#a86b57]">
                Status: {{ ucfirst($order->status) }}
            </div>

        </div>

        {{-- CUSTOMER INFO --}}
        <div class="mt-6 rounded-2xl bg-[#fcfaf8] p-5 text-sm text-[#6f5a51]">

            <p><strong>Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Shipping:</strong> {{ $order->shipping_address }}</p>
            <p><strong>Payment:</strong> {{ $order->payment_method }}</p>

        </div>

        {{-- ITEMS --}}
        <div class="mt-8 divide-y divide-[#efe3da]">

            @foreach ($order->items as $item)

                <div class="flex items-center justify-between py-5">

                    <div class="flex items-center gap-4">

                        {{-- optional product image --}}
                        @if ($item->product?->image)
                            <img src="{{ $item->product->image }}"
                                 class="h-16 w-16 rounded-xl object-cover border border-[#eadfd7]">
                        @endif

                        <div>

                            <p class="font-semibold text-[#4d3028]">
                                {{ $item->product->name ?? 'Deleted Product' }}
                            </p>

                            <p class="text-sm text-[#6f5a51]">
                                Qty: {{ $item->quantity }}
                            </p>

                            <p class="text-xs text-[#8d5848]">
                                PHP {{ number_format($item->price) }} each
                            </p>

                        </div>

                    </div>

                    <div class="font-semibold text-[#8d5848]">
                        PHP {{ number_format($item->quantity * $item->price) }}
                    </div>

                </div>

            @endforeach

        </div>

        {{-- TOTAL --}}
        <div class="mt-6 flex items-center justify-between border-t border-[#efe3da] pt-5">

            <span class="font-semibold text-[#4d3028]">
                Total
            </span>

            <span class="font-semibold text-[#4d3028]">
                PHP {{ number_format($order->total_amount) }}
            </span>

        </div>

        {{-- BACK BUTTON --}}
        <div class="mt-8">
            <a href="{{ route('orders') }}"
               class="inline-block rounded-full bg-[#5d342b] px-6 py-3 text-sm font-semibold text-white">
                Back to Orders
            </a>
        </div>

    </div>

</section>

@endsection