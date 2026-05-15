@extends('layouts.admin')

@section('content')

<div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">

    {{-- ORDER DETAILS --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
            Order #{{ $order->id }}
        </h2>

        <p class="mt-2 text-sm text-[#6f5a51]">
            Customer: {{ $order->full_name }} ({{ $order->email }})
        </p>

        <p class="text-sm text-[#6f5a51]">
            Address: {{ $order->shipping_address }}
        </p>

        <p class="text-sm text-[#6f5a51]">
            Payment: {{ $order->payment_method }}
        </p>

        <hr class="my-6 border-[#efe3da]">

        <h3 class="font-semibold text-[#4d3028]">Items</h3>

        <div class="mt-4 space-y-3">

            @foreach ($order->items as $item)

                <div class="flex justify-between text-sm">

                    <div>
                        <p class="font-medium text-[#4d3028]">
                            {{ $item->product->name ?? 'Deleted Product' }}
                        </p>

                        <p class="text-[#6f5a51]">
                            Qty: {{ $item->quantity }}
                        </p>
                    </div>

                    <p class="font-semibold text-[#8d5848]">
                        PHP {{ number_format($item->price * $item->quantity) }}
                    </p>

                </div>

            @endforeach

        </div>

    </div>

    {{-- STATUS PANEL --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        <h3 class="font-display text-xl font-semibold text-[#4d3028]">
            Order Status
        </h3>

        <p class="mt-2 text-sm text-[#6f5a51]">
            Current: <strong>{{ ucfirst($order->status) }}</strong>
        </p>

        <form method="POST"
              action="{{ route('admin.orders.status', $order->id) }}"
              class="mt-6 space-y-3">

            @csrf

            <select name="status"
                    class="w-full rounded-2xl border px-4 py-3">

                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>
                    Processing
                </option>

                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>
                    Completed
                </option>

                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>

            </select>

            <button type="submit"
                    class="w-full rounded-full bg-[#5d342b] py-3 text-white">
                Update Status
            </button>

        </form>

    </div>

</div>

@endsection