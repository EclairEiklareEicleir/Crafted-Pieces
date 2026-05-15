@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-4 py-14">

    <h1 class="text-3xl font-semibold text-[#4d3028]">
        My Orders
    </h1>

    <div class="mt-8 space-y-4">

        @forelse ($orders as $order)

            <a href="{{ route('orders.show', $order->id) }}"
               class="block rounded-2xl border border-[#eadfd7] bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex justify-between">

                    <div>
                        <p class="font-semibold text-[#4d3028]">
                            Order #{{ $order->id }}
                        </p>

                        <p class="text-sm text-[#6f5a51]">
                            {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>

                    <div class="text-right">

                        <p class="font-semibold text-[#4d3028]">
                            PHP {{ number_format($order->total_amount) }}
                        </p>

                        <p class="text-sm text-[#a86b57]">
                            {{ ucfirst($order->status) }}
                        </p>

                    </div>

                </div>

            </a>

        @empty

            <p class="text-sm text-[#6f5a51]">
                No orders yet.
            </p>

        @endforelse

    </div>

</section>

@endsection