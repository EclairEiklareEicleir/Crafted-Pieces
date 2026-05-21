@extends('layouts.admin')

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
    $status = strtolower($request->status);

    $isLocked = in_array($status, [
        \App\Models\CustomOrderRequest::STATUS_AWAITING_PAYMENT,
        \App\Models\CustomOrderRequest::STATUS_PAID,
        \App\Models\CustomOrderRequest::STATUS_REJECTED,
        \App\Models\CustomOrderRequest::STATUS_IN_PROGRESS,
        \App\Models\CustomOrderRequest::STATUS_COMPLETED,
    ]);
@endphp

<div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">

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

                <p>
                    <strong>Status:</strong>
                    {{ ucfirst($request->status) }}
                </p>

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

            </div>

            <div class="mt-6 rounded-3xl bg-brand-light/35 p-4 text-sm text-brand-ink/70">
                {{ $request->description }}
            </div>

            {{-- FLOW ACTIONS --}}
            @if (!$isLocked && in_array($status, ['pending', 'awaiting_confirmation', 'quoted']))

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
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

        <h2 class="font-display text-2xl font-semibold text-brand-primary">
            Conversation
        </h2>

        <div
            id="admin-message-thread"
            class="custom-thread-scroll mt-6 rounded-[1.75rem] border border-brand-border/80 bg-brand-light/15 p-4 sm:p-5 {{ $request->messages->isEmpty() ? 'max-h-56' : 'max-h-130' }} overflow-y-auto"
        >

            <div class="space-y-4">

                @forelse ($request->messages as $message)
                    @php
                        $isMine = $message->user_id === auth()->id();
                        $isCustomer = $message->user_id === $request->user_id;
                        $senderLabel = $isMine
                            ? 'You'
                            : ($isCustomer ? 'Customer' : (($message->user->role ?? null) === 'owner' ? 'Admin Owner' : ($message->user->name ?? 'User')));
                    @endphp

                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <article class="max-w-[92%] rounded-[1.75rem] px-4 py-3.5 shadow-[0_12px_30px_-20px_rgba(101,12,42,0.55)] sm:max-w-[80%] md:max-w-[72%] {{ $isMine ? 'rounded-br-md bg-brand-primary text-white' : 'rounded-bl-md border border-[#f0d2de] bg-white text-brand-ink' }}">

                            <div class="flex items-center justify-between gap-4 text-[11px] {{ $isMine ? 'text-white/75' : 'text-brand-ink/50' }}">

                                <p class="font-semibold uppercase tracking-[0.12em] {{ $isMine ? 'text-white/90' : 'text-brand-primary' }}">
                                    {{ $senderLabel }}
                                </p>

                                <p>
                                    {{ $message->created_at->format('M d, Y h:i A') }}
                                </p>

                            </div>

                            <p class="mt-2.5 whitespace-pre-wrap wrap-break-word text-sm leading-6 {{ $isMine ? 'text-white/95' : 'text-brand-ink/80' }}">
                                {{ $message->message }}
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

            <form method="POST"
                  action="{{ route('admin.custom.message', $request->id) }}"
                  class="sticky bottom-0 mt-6 rounded-[1.75rem] border border-brand-border bg-brand-light/30 p-4 shadow-sm backdrop-blur sm:p-5">

                @csrf

                <label class="mb-3 block text-sm font-semibold text-brand-primary">
                    Write a reply
                </label>

                <textarea
                    name="message"
                    rows="4"
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
    window.addEventListener('DOMContentLoaded', () => {
        const thread = document.getElementById('admin-message-thread');

        if (!thread) {
            return;
        }

        thread.scrollTop = thread.scrollHeight;
    });
</script>

@endsection