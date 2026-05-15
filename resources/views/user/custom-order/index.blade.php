@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-4 py-14">

    <h1 class="text-2xl font-semibold text-[#4d3028]">
        My Custom Orders
    </h1>

    <div class="mt-6 space-y-4">

        @forelse ($orders as $order)

            <a href="{{ route('custom-order.show', $order->id) }}"
               class="block rounded-2xl border bg-white p-5 hover:bg-[#fcfaf8]">

                <div class="font-semibold text-[#4d3028]">
                    {{ $order->item_type }}
                </div>

                <div class="text-sm text-[#6f5a51]">
                    Status: {{ ucfirst($order->status) }}
                </div>

                <div class="text-sm text-[#6f5a51]">
                    PHP {{ number_format($order->estimated_price, 2) }}
                </div>

            </a>

        @empty

            <p class="text-sm text-gray-500">
                No custom orders yet.
            </p>

        @endforelse

    </div>

</section>

@endsection