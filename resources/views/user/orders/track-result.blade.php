@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-3xl px-4 py-14">

    <div class="rounded-2xl border bg-white p-6">

        <h1 class="text-2xl font-semibold">
            Order #{{ $order->id }}
        </h1>

        <p class="text-sm text-[#6f5a51]">
            Status: {{ ucfirst($order->status) }}
        </p>

        <p class="mt-2 text-sm">
            Total: PHP {{ number_format($order->total_amount) }}
        </p>

        <div class="mt-6 space-y-2">
            @foreach ($order->items as $item)
                <div class="flex justify-between text-sm">
                    <span>{{ $item->product->name }}</span>
                    <span>x{{ $item->quantity }}</span>
                </div>
            @endforeach
        </div>

    </div>

</section>

@endsection