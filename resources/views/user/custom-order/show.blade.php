@extends('layouts.store')

@section('content')

<style>
    .custom-thread-scroll {
        scroll-behavior: smooth;
        scrollbar-width: thin;
        scrollbar-color: #b83a68 #fbe8ef;
    }

    .custom-thread-scroll::-webkit-scrollbar {
        width: 9px;
    }

    .custom-thread-scroll::-webkit-scrollbar-track {
        background: #fbe8ef;
        border-radius: 999px;
    }

    .custom-thread-scroll::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #c94a79 0%, #650c2a 100%);
        border-radius: 999px;
        border: 2px solid #fbe8ef;
    }

    .custom-thread-scroll::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #b33966 0%, #4f0921 100%);
    }
</style>

@php
    $statusMap = [
        'pending' => [
            'label' => 'Discussion',
            'class' => 'bg-[#fce6ed] text-[#8f3153] ring-1 ring-[#f3bfd0]',
        ],
        'quoted' => [
            'label' => 'Quoted',
            'class' => 'bg-[#f7dfe9] text-[#9a355e] ring-1 ring-[#efc3d4]',
        ],
        'awaiting_payment' => [
            'label' => 'Accepted',
            'class' => 'bg-[#fcefd5] text-[#9c6b16] ring-1 ring-[#f0dda8]',
        ],
        'paid' => [
            'label' => 'Accepted',
            'class' => 'bg-[#ecf7ee] text-[#2d7a47] ring-1 ring-[#c6e8d0]',
        ],
        'in_progress' => [
            'label' => 'In Progress',
            'class' => 'bg-[#eef1ff] text-[#4756a4] ring-1 ring-[#cfd6ff]',
        ],
        'completed' => [
            'label' => 'Completed',
            'class' => 'bg-[#e9f6f1] text-[#2f7f64] ring-1 ring-[#cde9df]',
        ],
        'rejected' => [
            'label' => 'Cancelled',
            'class' => 'bg-[#f4f1ef] text-[#7a5f54] ring-1 ring-[#dfd3cd]',
        ],
    ];

    $status = $statusMap[$order->status] ?? [
        'label' => ucfirst(str_replace('_', ' ', $order->status ?? 'pending')),
        'class' => 'bg-brand-light text-brand-primary ring-1 ring-brand-border',
    ];

    $isLocked = $order->status === \App\Models\CustomOrderRequest::STATUS_PAID;
    $hasPaymentWindow = $order->status === \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT;
    $isExpired = $order->payment_due_at
        ? \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($order->payment_due_at))
        : false;
    $quoteAmount = $order->final_price ?? $order->estimated_price ?? ($pricing['base_price'] ?? 0);
@endphp

<section class="relative min-h-screen overflow-hidden">

    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 top-8 h-72 w-72 rounded-full bg-brand-accent/30 blur-3xl"></div>
        <div class="absolute -right-24 top-28 h-96 w-96 rounded-full bg-white/60 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-brand-secondary/15 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="brand-pill bg-white/80 ring-1 ring-brand-border backdrop-blur">
                    Custom Order Ticket
                </span>

                <h1 class="mt-4 font-display text-4xl font-semibold tracking-tight text-brand-primary sm:text-5xl">
                    Ticket #{{ $order->id }}
                </h1>

                <p class="mt-3 max-w-3xl text-sm leading-6 text-brand-ink/65 sm:text-base">
                    Follow the request details, review the quote, and continue the conversation in a calm private thread.
                </p>
            </div>

            @if ($order->status === \App\Models\CustomOrderRequest::STATUS_PAID)
                <a href="{{ route('custom-order.receipt', $order->id) }}" class="brand-btn-secondary w-full justify-center sm:w-auto">
                    Download Receipt
                </a>
            @endif
        </div>

        <div class="rounded-[2.5rem] border border-brand-border bg-white/88 p-5 shadow-[0_30px_90px_-50px_rgba(101,12,42,0.45)] backdrop-blur-xl sm:p-8 lg:p-10">

            <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">

                <div class="space-y-6">

                    <div class="rounded-4xl border border-brand-border bg-brand-light/28 p-5 shadow-sm sm:p-6">

                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $status['class'] }}">
                                {{ $status['label'] }}
                            </span>

                            @if ($order->created_at)
                                <span class="brand-pill bg-white/90 ring-1 ring-brand-border">
                                    Created {{ $order->created_at->format('M d, Y') }}
                                </span>
                            @endif

                            @if ($order->payment_due_at)
                                <span class="brand-pill bg-white/90 ring-1 ring-brand-border">
                                    Due {{ \Carbon\Carbon::parse($order->payment_due_at)->format('M d, Y') }}
                                </span>
                            @endif
                        </div>

                        <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-brand-primary">
                            {{ $order->item_type }}
                        </h2>

                        <p class="mt-3 text-sm leading-7 text-brand-ink/70 sm:text-base">
                            {{ $order->description }}
                        </p>

                        @if ($order->admin_notes)
                            <div class="mt-5 rounded-3xl border border-brand-border bg-white/90 p-4 text-sm leading-6 text-brand-ink/70">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                    Notes from Crafted Pieces
                                </p>

                                <p class="mt-2">
                                    {{ $order->admin_notes }}
                                </p>
                            </div>
                        @endif

                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">

                        <div class="rounded-[1.75rem] border border-brand-border bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink/50">
                                Preferred size
                            </p>

                            <p class="mt-2 text-lg font-semibold text-brand-primary">
                                {{ $order->preferred_size ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-[1.75rem] border border-brand-border bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink/50">
                                Theme
                            </p>

                            <p class="mt-2 text-lg font-semibold text-brand-primary">
                                {{ $order->design_theme ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-[1.75rem] border border-brand-border bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink/50">
                                Quote
                            </p>

                            <p class="mt-2 text-lg font-semibold text-brand-primary">
                                PHP {{ number_format((float) $quoteAmount, 2) }}
                            </p>

                            <p class="mt-1 text-xs text-brand-ink/55">
                                {{ $order->final_price ? 'Final quotation' : 'Estimated quotation' }}
                            </p>
                        </div>

                        <div class="rounded-[1.75rem] border border-brand-border bg-white p-5 shadow-sm">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink/50">
                                Contact
                            </p>

                            <p class="mt-2 text-lg font-semibold text-brand-primary">
                                {{ $order->name }}
                            </p>

                            <p class="mt-1 text-sm text-brand-ink/60">
                                {{ $order->email }}
                            </p>
                        </div>

                    </div>

                </div>

                <div class="rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                        Summary
                    </p>

                    <dl class="mt-5 space-y-4 text-sm text-brand-ink/70">

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Ticket number</dt>
                            <dd class="font-semibold text-brand-primary">#{{ $order->id }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Status</dt>
                            <dd>
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $status['class'] }}">
                                    {{ $status['label'] }}
                                </span>
                            </dd>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Item</dt>
                            <dd class="text-right font-semibold text-brand-primary">{{ $order->item_type }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Requested size</dt>
                            <dd class="text-right font-semibold text-brand-primary">{{ $order->preferred_size ?: 'Not specified' }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Quote amount</dt>
                            <dd class="text-right font-semibold text-brand-primary">PHP {{ number_format((float) $quoteAmount, 2) }}</dd>
                        </div>

                        @if ($order->quoted_at)
                            <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                                <dt class="font-medium text-brand-ink/60">Quoted on</dt>
                                <dd class="text-right font-semibold text-brand-primary">{{ \Carbon\Carbon::parse($order->quoted_at)->format('M d, Y h:i A') }}</dd>
                            </div>
                        @endif

                        @if ($order->paid_at)
                            <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                                <dt class="font-medium text-brand-ink/60">Paid on</dt>
                                <dd class="text-right font-semibold text-brand-primary">{{ \Carbon\Carbon::parse($order->paid_at)->format('M d, Y h:i A') }}</dd>
                            </div>
                        @endif

                    </dl>
                </div>

            </div>

            {{-- PAYMENT SECTION --}}
            @if ($hasPaymentWindow)
                @if (!$isExpired && $order->paymentIsValid())
                    <div class="mt-6 rounded-4xl border border-brand-secondary/25 bg-brand-light/35 p-5 shadow-sm sm:p-6">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                    Payment required
                                </p>

                                <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                                    Your quote is ready
                                </h2>

                                <p class="mt-2 max-w-2xl text-sm leading-6 text-brand-ink/70">
                                    Your custom request has been approved. Please complete payment before the deadline to keep the order active.
                                </p>
                            </div>

                            <div class="rounded-3xl bg-white p-4 shadow-sm sm:min-w-[16rem] sm:text-right">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-ink/50">
                                    Final price
                                </p>

                                <p class="mt-2 text-3xl font-semibold tracking-tight text-brand-primary">
                                    PHP {{ number_format((float) $quoteAmount, 2) }}
                                </p>
                            </div>
                        </div>

                        @if ($order->payment_due_at)
                            <p class="mt-4 text-sm font-medium text-brand-secondary">
                                Payment due {{ \Carbon\Carbon::parse($order->payment_due_at)->format('F d, Y h:i A') }}
                            </p>
                        @endif

                        <a
                            href="{{ route('user.payment', ['type' => 'custom-order', 'id' => $order->id]) }}"
                            class="brand-btn-primary mt-6 w-full justify-center rounded-full py-3.5 text-base shadow-lg shadow-brand-primary/15 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-primary/20 sm:w-auto"
                        >
                            Pay Now
                        </a>

                    </div>
                @else
                    <div class="mt-6 rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                            Payment expired
                        </p>

                        <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                            This request is no longer payable
                        </h2>

                        <p class="mt-2 max-w-2xl text-sm leading-6 text-brand-ink/70">
                            This custom order was automatically closed because payment was not completed before the deadline.
                        </p>
                    </div>
                @endif
            @endif

            {{-- PAID STATUS --}}
            @if ($order->status === \App\Models\CustomOrderRequest::STATUS_PAID)
                <div class="mt-6 rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                        Payment completed
                    </p>

                    <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                        Your payment has been received
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-brand-ink/70">
                        Production may begin soon. You can keep using this ticket if you need to ask follow-up questions.
                    </p>
                </div>
            @endif

            {{-- CHAT --}}
            <div class="mt-6 rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                            Conversation
                        </p>

                        <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                            Message thread
                        </h2>
                    </div>

                    <p class="text-sm text-brand-ink/55">
                        Customer and owner replies appear in their own bubble styles.
                    </p>
                </div>

                <div
                    id="customer-message-thread"
                    class="custom-thread-scroll mt-6 rounded-[1.75rem] border border-brand-border/80 bg-brand-light/15 p-4 sm:p-5 {{ $order->messages->isEmpty() ? 'max-h-56' : 'max-h-130' }} overflow-y-auto"
                >

                    <div class="space-y-4">

                        @forelse ($order->messages as $msg)
                            @php
                                $isMine = $msg->user_id === auth()->id();
                                $isOwner = ($msg->user->role ?? null) === 'owner';
                                $senderLabel = $isMine ? 'You' : ($isOwner ? 'Admin Owner' : ($msg->user->name ?? 'Customer'));
                            @endphp

                            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                                <article class="max-w-[92%] rounded-[1.75rem] px-4 py-3.5 shadow-[0_12px_30px_-20px_rgba(101,12,42,0.55)] sm:max-w-[80%] md:max-w-[72%] {{ $isMine ? 'rounded-br-md bg-brand-primary text-white' : 'rounded-bl-md border border-[#f0d2de] bg-white text-brand-ink' }}">

                                    <div class="flex items-center justify-between gap-4 text-[11px] {{ $isMine ? 'text-white/75' : 'text-brand-ink/50' }}">
                                        <span class="font-semibold uppercase tracking-[0.12em]">
                                            {{ $senderLabel }}
                                        </span>

                                        <span>
                                            {{ $msg->created_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>

                                    <p class="mt-2.5 whitespace-pre-wrap wrap-break-word text-sm leading-6 {{ $isMine ? 'text-white/95' : 'text-brand-ink/80' }}">
                                        {{ $msg->message }}
                                    </p>

                                </article>
                            </div>
                        @empty
                            <div class="rounded-4xl border border-dashed border-brand-border bg-brand-light/25 p-8 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-brand-primary ring-1 ring-brand-border">
                                    <span class="text-xl">✦</span>
                                </div>

                                <p class="mt-4 font-semibold text-brand-primary">
                                    No messages yet
                                </p>

                                <p class="mt-2 text-sm leading-6 text-brand-ink/65">
                                    No messages yet. Start the conversation by sending a message.
                                </p>
                            </div>
                        @endforelse

                    </div>

                </div>

                {{-- MESSAGE FORM --}}
                @if (!$isLocked)
                    <form
                        method="POST"
                        action="{{ route('custom-order.message', $order->id) }}"
                        class="sticky bottom-0 mt-6 rounded-[1.75rem] border border-brand-border bg-brand-light/30 p-4 shadow-sm backdrop-blur sm:p-5"
                    >

                        @csrf

                        <label class="mb-3 block text-sm font-semibold text-brand-primary">
                            Write a message
                        </label>

                        <textarea
                            name="message"
                            rows="4"
                            class="brand-input min-h-28 resize-none rounded-3xl border border-[#e7bfce] bg-white/95"
                            placeholder="Type your message here..."
                        >{{ old('message') }}</textarea>

                        @error('message')
                            <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-xs text-brand-ink/55">
                                Keep replies short and include any size, color, or timing updates.
                            </p>

                            <button class="brand-btn-primary w-full justify-center rounded-full px-6 py-3 text-sm shadow-lg shadow-brand-primary/15 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-primary/20 sm:w-auto">
                                Send Message
                            </button>
                        </div>

                    </form>
                @else
                    <div class="mt-6 rounded-[1.75rem] border border-brand-border bg-brand-light/25 p-4 text-sm text-brand-ink/60">
                        Chat is locked after payment completion.
                    </div>
                @endif

            </div>

        </div>

    </div>

</section>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const thread = document.getElementById('customer-message-thread');

        if (!thread) {
            return;
        }

        thread.scrollTop = thread.scrollHeight;
    });
</script>

@endsection