@extends('layouts.admin')

@section('content')

@php
    $status = strtolower($request->status);
    $quoteStatus = $request->quote_status ?? 'pending';

    $isLocked = in_array($status, [
        \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT,
        \App\Models\CustomOrderRequest::STATUS_PAID,
        \App\Models\CustomOrderRequest::STATUS_REJECTED,
        \App\Models\CustomOrderRequest::STATUS_IN_PROGRESS,
        \App\Models\CustomOrderRequest::STATUS_COMPLETED,
    ]);
    $canMoveToPayment = (float) ($request->final_price ?? 0) > 0
        && in_array($status, ['pending', 'quoted']);
@endphp

<div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">

    <div class="lg:col-span-2">
        <x-back-button href="{{ route('admin.custom.index') }}" label="Back to Custom Orders" />
    </div>

    {{-- LEFT INFO --}}
    <div class="space-y-6">

        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Request Details
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                {{ $request->item_type }}
            </h1>

            <div class="mt-6 space-y-4 text-sm text-brand-ink/70">

                <p><strong>Customer:</strong> {{ $request->name }}</p>
                <p><strong>Email:</strong> {{ $request->email }}</p>
                <p><strong>Theme:</strong> {{ $request->design_theme }}</p>
                <p><strong>Preferred Size:</strong> {{ $request->preferred_size }}</p>
                <p><strong>Payment Method:</strong> {{ $request->payment_method ?? '—' }}</p>

                <p><strong>Quote Status:</strong> <x-status-badge :status="$quoteStatus" context="quote" /></p>

                <div id="payment-details" class="flex flex-wrap items-center gap-3">
                    <strong>Payment Status:</strong>
                    <x-status-badge :status="$request->payment_status ?? 'unpaid'" context="payment" />
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <strong>Status:</strong>
                    <x-status-badge :status="$request->status" context="custom" />
                </div>

                <p>
                    <strong>Estimated Price:</strong>
                    PHP {{ number_format($request->estimated_price, 2) }}
                </p>

                @if ($request->final_price)
                    <p>
                        <strong>Final Price:</strong>
                        PHP {{ number_format($request->final_price, 2) }}
                    </p>
                @endif

                @if ($request->paymongo_checkout_id)
                    <p><strong>PayMongo Checkout ID:</strong> {{ $request->paymongo_checkout_id }}</p>
                @endif

                @if ($request->paymongo_payment_id)
                    <p><strong>PayMongo Payment ID:</strong> {{ $request->paymongo_payment_id }}</p>
                @endif

                @if ($request->paid_at)
                    <p><strong>Paid At:</strong> {{ $request->paid_at->format('M d, Y h:i A') }}</p>
                @endif

                @if ($request->reference_image_url)
                    <div class="rounded-3xl border border-brand-border bg-brand-light/30 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                            Reference Image
                        </p>

                        <img src="{{ $request->reference_image_url }}"
                             alt="Reference image"
                             class="mt-3 max-h-80 w-full rounded-3xl border border-brand-border object-contain bg-white p-2">
                    </div>
                @else
                    <p class="rounded-3xl border border-dashed border-brand-border bg-brand-light/20 px-4 py-3 text-sm text-brand-ink/60">
                        No reference image uploaded.
                    </p>
                @endif

            </div>

            <div class="mt-6 rounded-3xl bg-brand-light/35 p-4 text-sm text-brand-ink/70">
                {{ $request->description }}
            </div>

            {{-- FLOW ACTIONS --}}
            @if (!$isLocked && $canMoveToPayment)

                <div class="mt-6 rounded-2xl border border-brand-border bg-brand-light/35 p-4">

                    <p class="text-sm font-semibold text-brand-primary">
                        Seller Decision Required
                    </p>

                    <p class="mt-1 text-xs text-brand-ink/70">
                        After discussion and quotation, choose whether to proceed.
                    </p>

                    <div class="mt-4 flex gap-3">

                        {{-- ACCEPT --}}
                        <form method="POST"
                              action="{{ route('admin.custom.accept', $request->id) }}">
                            @csrf
                            <button class="brand-btn-primary px-5 py-2">
                                Accept & Proceed
                            </button>
                        </form>

                        {{-- REJECT --}}
                        <form method="POST"
                              action="{{ route('admin.custom.reject', $request->id) }}">
                            @csrf
                            <button class="rounded-full bg-brand-secondary px-5 py-2 text-white hover:bg-brand-primary">
                                Decline
                            </button>
                        </form>

                    </div>

                </div>

            @elseif (!$isLocked)

                <div class="mt-6 rounded-2xl border border-brand-border bg-brand-light/25 p-4 text-sm text-brand-ink/60">
                    Send and save a valid quotation first, then accept it to open the payment window.
                </div>

            @else

                <div class="mt-6 rounded-2xl border border-brand-border bg-brand-light/25 p-4 text-sm text-brand-ink/60">
                    Actions locked for this order status.
                </div>

            @endif

        </div>

        {{-- QUOTE FORM --}}
        <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Send Quotation
            </h2>

            @if (!$isLocked)

                <form method="POST"
                      action="{{ route('admin.custom.quote', $request->id) }}"
                      class="mt-6 space-y-4">

                    @csrf

                    <input
                        type="number"
                        step="0.01"
                        name="final_price"
                        value="{{ old('final_price', $request->final_price) }}"
                        placeholder="Final quotation price"
                        class="brand-input"
                        required
                    >

                    <textarea hidden
                        name="admin_notes"
                        rows="4"
                        placeholder="Notes for customer"
                        class="brand-input"
                    >{{ old('admin_notes', $request->admin_notes) }}</textarea>

                    <button class="brand-btn-primary w-full py-3">
                        Send Quotation
                    </button>

                </form>

            @else

                <p class="mt-4 text-sm text-brand-ink/60">
                    Quotation is locked for this order.
                </p>

            @endif

        </div>

    </div>

    {{-- RIGHT CHAT --}}
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm lg:flex lg:min-h-176 lg:flex-col">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Conversation
        </h2>

        <div class="mt-5 flex min-h-0 flex-1 flex-col">
            <x-custom-order-thread
                :messages="$request->messages"
                thread-id="admin-message-thread"
                :viewer-id="auth()->id()"
                :customer-id="$request->user_id"
                viewer-label="You"
                customer-label="Customer"
                owner-label="Admin Owner"
            />
        </div>

        {{-- MESSAGE FORM --}}
        @if (!$isLocked)

            <form method="POST"
                action="{{ route('admin.custom.message', $request->id) }}"
                class="mt-6 rounded-[1.75rem] border border-brand-border bg-brand-light/30 p-4 shadow-sm backdrop-blur sm:p-5">

                @csrf

                <label class="mb-3 block text-sm font-semibold text-brand-primary">
                    Write a reply
                </label>

                <select id="admin-reply-template" class="brand-input mb-3">
                    <option value="">Choose a template</option>
                    <option value="Please wait for the seller’s response before proceeding to the next step. Once your custom order details and price are confirmed, you will be guided to the payment or next transaction process.">Default follow-up</option>
                    <option value="Thank you for the update. We are reviewing your custom order and will get back to you shortly.">Thank you / review update</option>
                </select>

                <textarea
                    name="message"
                    rows="4"
                    id="admin-reply-textarea"
                    placeholder="Reply to customer..."
                    class="brand-input min-h-28 resize-none rounded-3xl border border-[#e7bfce] bg-white/95"
                    required></textarea>

                <button class="mt-4 brand-btn-primary px-6 py-3">
                    Send Reply
                </button>

            </form>

        @else

            <p class="mt-6 text-sm text-brand-ink/60">
                Chat locked for this order status.
            </p>

        @endif

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const bindTemplate = (selectId, textareaId) => {
        const select = document.getElementById(selectId);
        const textarea = document.getElementById(textareaId);

        if (!select || !textarea) return;

        select.addEventListener('change', () => {
            if (select.value) {
                textarea.value = select.value;
            }
        });
    };

    bindTemplate('custom-message-template', 'custom-message-textarea');
    bindTemplate('admin-reply-template', 'admin-reply-textarea');
});
</script>

@endsection
