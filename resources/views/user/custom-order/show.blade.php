@extends('layouts.store')

@section('content')

@php
    $isLocked = $order->status === \App\Models\CustomOrderRequest::STATUS_PAID;
@endphp

<section class="mx-auto max-w-5xl px-4 py-14">

    {{-- HEADER --}}
    <div class="rounded-2xl border border-brand-border bg-white p-6 flex items-start justify-between">

        <div>
            <h1 class="text-2xl font-semibold text-brand-primary">
                Commission Ticket #{{ $order->id }}
            </h1>

            <p class="mt-2 text-sm text-brand-ink/70">
                Status:
                <span class="font-semibold text-brand-secondary">
                    {{ $order->status_label }}
                </span>
            </p>

            <p class="mt-2 text-sm text-brand-ink/70">
                Item: {{ $order->item_type }}
                |
                Size: {{ $order->preferred_size }}
            </p>
        </div>

        {{-- RECEIPT BUTTON --}}
        @if ($order->status === \App\Models\CustomOrderRequest::STATUS_PAID)

            <a href="{{ route('custom-order.receipt', $order->id) }}"
            class="brand-btn-secondary inline-flex items-center px-5 py-2 text-sm font-medium">

                Download Receipt
            </a>

        @endif

    </div>

    {{-- PAYMENT SECTION --}}
    @if ($order->status === \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT)

        @php
            $isExpired = $order->payment_due_at
                ? \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($order->payment_due_at))
                : false;
        @endphp

        @if (!$isExpired && $order->paymentIsValid())

            <div class="mt-6 rounded-2xl border border-brand-secondary bg-brand-light/40 p-6">

                <h2 class="text-xl font-semibold text-brand-primary">
                    Payment Required
                </h2>

                <p class="mt-2 text-sm text-brand-ink/70">
                    Your custom request has been approved by the owner.
                    Please complete payment before the deadline.
                </p>

                <div class="mt-5 rounded-xl border border-brand-border bg-white p-4">

                    <p class="text-sm text-brand-ink/70">Final Price</p>

                    <p class="mt-1 text-2xl font-semibold text-brand-primary">
                        PHP {{ number_format($pricing['base_price'], 2) }}
                    </p>

                </div>

                <p class="mt-2 text-sm font-medium text-brand-secondary">
                    Note: This is the FINAL negotiated price. Fees are added during checkout.
                </p>

                @if ($order->payment_due_at)
                    <div class="mt-4 text-sm text-yellow-900">
                        <strong>Payment Due:</strong>
                        {{ \Carbon\Carbon::parse($order->payment_due_at)->format('F d, Y h:i A') }}
                    </div>
                @endif

                <a
                    href="{{ route('user.payment', ['type' => 'custom-order', 'id' => $order->id]) }}"
                    class="brand-btn-primary mt-6 inline-flex px-6 py-3 text-sm font-medium"
                >
                    Pay Now
                </a>

            </div>

        @else

            <div class="mt-6 rounded-2xl border border-brand-secondary bg-brand-light/40 p-6">

                <h2 class="text-xl font-semibold text-brand-primary">
                    Payment Expired
                </h2>

                <p class="mt-2 text-sm text-brand-ink/70">
                    This custom order was automatically cancelled because
                    payment was not completed before the deadline.
                </p>

            </div>

        @endif

    @endif

    {{-- PAID STATUS --}}
    @if ($order->status === \App\Models\CustomOrderRequest::STATUS_PAID)

        <div class="mt-6 rounded-2xl border border-brand-border bg-brand-light/35 p-6">

            <h2 class="text-xl font-semibold text-brand-primary">
                Payment Completed
            </h2>

            <p class="mt-2 text-sm text-brand-ink/70">
                Your payment has been received successfully.
                Production may begin soon.
            </p>

        </div>

    @endif

    {{-- CHAT --}}
    <div class="mt-6 rounded-2xl border border-brand-border bg-white p-6">

        <h2 class="font-semibold text-brand-primary">
            Conversation
        </h2>

        <div class="mt-4 space-y-3">

            @forelse ($order->messages as $msg)

                <div class="rounded-xl bg-brand-light/35 p-3">

                    <div class="text-xs text-brand-ink/55">
                        {{ $msg->user->name ?? 'Unknown' }}
                        • {{ $msg->created_at->diffForHumans() }}
                    </div>

                    <div class="mt-1 text-sm text-brand-primary">
                        {{ $msg->message }}
                    </div>

                </div>

            @empty
                <p class="text-sm text-brand-ink/60">No messages yet.</p>
            @endforelse

        </div>

        {{-- MESSAGE FORM --}}
        @if (!$isLocked)

            <form method="POST"
                  action="{{ route('custom-order.message', $order->id) }}"
                  class="mt-4 flex gap-2">

                @csrf

                <input type="text"
                       name="message"
                      class="brand-input flex-1 py-2"
                       placeholder="Type message...">

                  <button class="brand-btn-primary px-5 py-2">
                    Send
                </button>

            </form>

        @else

            <div class="mt-4 rounded-xl border border-brand-border bg-brand-light/25 p-3 text-sm text-brand-ink/60">
                Chat locked after payment completion.
            </div>

        @endif

    </div>

</section>

@endsection