@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-5xl px-4 py-14">

    {{-- HEADER --}}
    <div class="rounded-2xl border bg-white p-6">

        <h1 class="text-2xl font-semibold text-[#4d3028]">
            Commission Ticket #{{ $order->id }}
        </h1>

        <p class="mt-2 text-sm text-[#6f5a51]">
            Status:
            <span class="font-semibold text-[#a86b57]">
                {{ $order->status_label }}
            </span>
        </p>

        <p class="mt-2 text-sm text-[#6f5a51]">
            Item: {{ $order->item_type }}
            |
            Size: {{ $order->preferred_size }}
        </p>

    </div>

    {{-- PAYMENT SECTION --}}
    @if ($order->status === \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT)

        {{-- SAFETY CHECK (extra layer in case controller misses it) --}}
        @php
            $isExpired = $order->payment_due_at
                ? \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($order->payment_due_at))
                : false;
        @endphp

        @if (!$isExpired && $order->paymentIsValid())

            <div class="mt-6 rounded-2xl border border-yellow-300 bg-yellow-50 p-6">

                <h2 class="text-xl font-semibold text-yellow-800">
                    Payment Required
                </h2>

                <p class="mt-2 text-sm text-yellow-700">
                    Your custom request has been approved by the owner.
                    Please complete payment before the deadline.
                </p>

                {{-- PRICE --}}
                <div class="mt-5 rounded-xl bg-white p-4 border border-yellow-200">

                    <p class="text-sm text-[#6f5a51]">
                        Final Price
                    </p>

                    <p class="mt-1 text-2xl font-semibold text-[#4d3028]">
                        PHP {{ number_format($order->final_price ?? $order->estimated_price, 2) }}
                    </p>

                </div>

                {{-- DEADLINE --}}
                @if ($order->payment_due_at)
                    <div class="mt-4 text-sm text-yellow-900">

                        <strong>Payment Due:</strong>

                        {{ \Carbon\Carbon::parse($order->payment_due_at)->format('F d, Y h:i A') }}

                    </div>
                @endif

                {{-- PAYMENT BUTTON --}}
                <a
                    href="{{ route('user.payment', ['type' => 'custom-order', 'id' => $order->id]) }}"
                    class="mt-6 inline-flex rounded-full bg-[#5d342b] px-6 py-3 text-sm font-medium text-white hover:bg-[#4a2922]"
                >
                    Pay Now
                </a>

            </div>

        @else

            {{-- EXPIRED --}}
            <div class="mt-6 rounded-2xl border border-red-300 bg-red-50 p-6">

                <h2 class="text-xl font-semibold text-red-700">
                    Payment Expired
                </h2>

                <p class="mt-2 text-sm text-red-600">
                    This custom order was automatically cancelled because
                    payment was not completed before the deadline.
                </p>

            </div>

        @endif

    @endif

    {{-- PAID STATUS --}}
    @if ($order->status === \App\Models\CustomOrderRequest::STATUS_PAID)

        <div class="mt-6 rounded-2xl border border-green-300 bg-green-50 p-6">

            <h2 class="text-xl font-semibold text-green-700">
                Payment Completed
            </h2>

            <p class="mt-2 text-sm text-green-700">
                Your payment has been received successfully.
                Production may begin soon.
            </p>

        </div>

    @endif

    {{-- CHAT --}}
    <div class="mt-6 rounded-2xl border bg-white p-6">

        <h2 class="font-semibold text-[#4d3028]">
            Conversation
        </h2>

        <div class="mt-4 space-y-3">

            @forelse ($order->messages as $msg)

                <div class="rounded-xl bg-[#fcfaf8] p-3">

                    <div class="text-xs text-gray-500">
                        {{ $msg->user->name ?? 'Unknown' }}
                        • {{ $msg->created_at->diffForHumans() }}
                    </div>

                    <div class="mt-1 text-sm text-[#4d3028]">
                        {{ $msg->message }}
                    </div>

                </div>

            @empty

                <p class="text-sm text-gray-500">
                    No messages yet.
                </p>

            @endforelse

        </div>

        {{-- MESSAGE FORM --}}
        <form method="POST"
              action="{{ route('custom-order.message', $order->id) }}"
              class="mt-4 flex gap-2">

            @csrf

            <input type="text"
                   name="message"
                   class="flex-1 rounded-xl border px-4 py-2"
                   placeholder="Type message...">

            <button class="rounded-xl bg-[#5d342b] px-5 py-2 text-white">
                Send
            </button>

        </form>

    </div>

</section>

@endsection