@extends('layouts.store')

@section('content')

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
    $canPayWithPayMongo = $order->canPayWithPayMongo();
    $isExpired = $order->payment_due_at
        ? \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($order->payment_due_at))
        : false;
    $quoteAmount = $order->final_price ?? $order->estimated_price ?? ($pricing['base_price'] ?? 0);
    $paymentStatus = strtolower($order->payment_status ?? 'unpaid');
    $quoteStatus = $order->quote_status ?? 'pending';
@endphp

<section class="relative min-h-screen overflow-hidden">

    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 top-8 h-72 w-72 rounded-full bg-brand-accent/30 blur-3xl"></div>
        <div class="absolute -right-24 top-28 h-96 w-96 rounded-full bg-white/60 blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 h-80 w-80 rounded-full bg-brand-secondary/15 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

        <div class="mb-6">
            <x-back-button href="{{ route('custom-order.index') }}" label="Back to Orders" />
        </div>

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
                <a href="{{ route('custom-order.receipt', $order->id) }}" data-no-loading="true" class="brand-btn-secondary w-full justify-center sm:w-auto">
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

                            <x-status-badge :status="$quoteStatus" context="quote" />

                            <span class="inline-flex items-center rounded-full border border-brand-border bg-white px-3 py-1 text-xs font-semibold text-brand-primary">
                                Payment: {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
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

                        @if ($order->reference_image_url)
                            <div class="mt-5 rounded-3xl border border-brand-border bg-white/90 p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                    Reference image
                                </p>

                                <img src="{{ $order->reference_image_url }}"
                                     alt="Reference image"
                                     class="mt-3 max-h-80 w-full rounded-3xl border border-brand-border object-contain bg-white p-2">
                            </div>
                        @else
                            <p class="mt-5 rounded-3xl border border-dashed border-brand-border bg-white/90 px-4 py-3 text-sm text-brand-ink/60">
                                No reference image uploaded.
                            </p>
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

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Payment status</dt>
                            <dd class="text-right font-semibold text-brand-primary">{{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}</dd>
                        </div>

                        <div class="flex items-start justify-between gap-4 rounded-[1.25rem] bg-brand-light/25 p-4">
                            <dt class="font-medium text-brand-ink/60">Quote status</dt>
                            <dd class="text-right font-semibold text-brand-primary">{{ $order->quote_status_label }}</dd>
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
            @if ($canPayWithPayMongo)
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

                        <div class="mt-6 rounded-[1.75rem] border border-pink-200 bg-white p-5 shadow-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full border border-brand-border bg-brand-light/60 px-3 py-1 text-xs font-semibold text-brand-primary">QRPh</span>
                                <span class="rounded-full border border-brand-border bg-brand-light/60 px-3 py-1 text-xs font-semibold text-brand-primary">Card</span>
                                <span class="rounded-full border border-brand-border bg-brand-light/60 px-3 py-1 text-xs font-semibold text-brand-primary">E-Wallet</span>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-brand-ink/70">
                                Securely complete your quotation through PayMongo hosted checkout. You can pay with QRPh, card, or supported e-wallets such as GCash.
                            </p>

                            <form method="POST" action="{{ route('custom-order.paymongo.checkout', $order) }}" class="mt-5">
                                @csrf
                                <button type="submit" class="brand-btn-primary w-full justify-center rounded-full py-3.5 text-base shadow-lg shadow-brand-primary/15 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-primary/20 sm:w-auto">
                                    Pay Quotation with PayMongo
                                </button>
                            </form>
                        </div>

                    </div>
            @elseif ($order->status === \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT)
                <div class="mt-6 rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                        Payment not ready
                    </p>

                    <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                        Waiting for admin to finalize your quotation
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-brand-ink/70">
                        The order is marked as awaiting payment, but a final quotation or payment deadline is still missing.
                    </p>

                </div>
            @elseif ($isExpired)
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
            @elseif ($order->status === \App\Models\CustomOrderRequest::STATUS_QUOTED || (float) ($order->final_price ?? 0) > 0)
                <div class="mt-6 rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                        Waiting for approval
                    </p>

                    <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                        Waiting for admin to finalize your quotation
                    </h2>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-brand-ink/70">
                        Your quotation amount has been saved, and payment will open once the owner moves this request to awaiting payment.
                    </p>

                </div>
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

                <div class="mt-5 flex min-h-0 flex-1 flex-col">
                    <x-custom-order-thread
                        :messages="$order->messages"
                        thread-id="customer-message-thread"
                        :viewer-id="auth()->id()"
                        :customer-id="auth()->id()"
                        viewer-label="You"
                        customer-label="Customer"
                        owner-label="Admin Owner"
                    />
                </div>

                {{-- MESSAGE FORM --}}
                @if (!$isLocked)
                    <form
                        method="POST"
                        action="{{ route('custom-order.message', $order->id) }}"
                        class="mt-6 rounded-[1.75rem] border border-brand-border bg-brand-light/30 p-4 shadow-sm backdrop-blur sm:p-5"
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

@endsection