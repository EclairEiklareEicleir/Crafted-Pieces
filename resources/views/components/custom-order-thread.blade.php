@props([
    'messages',
    'threadId',
    'viewerId' => auth()->id(),
    'customerId' => null,
    'viewerLabel' => 'You',
    'customerLabel' => 'Customer',
    'ownerLabel' => 'Admin Owner',
    'emptyTitle' => 'No messages yet',
    'emptyMessage' => 'No messages yet. Start the conversation by sending a message.',
])

@php
    $viewerId = $viewerId ?? auth()->id();
@endphp

<div
    id="{{ $threadId }}"
    data-auto-scroll-thread
    class="brand-thread-scroll mt-6 overflow-y-auto rounded-4xl border border-brand-border/80 bg-brand-light/15 p-4 sm:p-5"
    role="log"
    aria-live="polite"
>
    <div class="space-y-4 pr-1 sm:pr-2">
        @forelse ($messages as $message)
            @php
                $isMine = (int) $message->user_id === (int) $viewerId;
                $isCustomer = $customerId !== null && (int) $message->user_id === (int) $customerId;
                $senderRole = $message->user->role ?? null;
                $isQuoteMessage = ($message->message_type ?? null) === 'quote';
                $quotedPrice = data_get($message->meta, 'quoted_price');

                $senderLabel = $isMine
                    ? $viewerLabel
                    : ($isCustomer
                        ? $customerLabel
                        : ($senderRole === 'owner' ? $ownerLabel : ($message->user->name ?? $customerLabel)));

                $avatar = mb_strtoupper(mb_substr($senderLabel ?? 'U', 0, 1));
            @endphp

            <div class="flex items-end gap-3 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                @if (! $isMine)
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white text-sm font-semibold text-brand-primary ring-1 ring-brand-border shadow-sm">
                        {{ $avatar }}
                    </div>
                @endif

                <article class="brand-chat-bubble {{ $isMine ? 'brand-chat-bubble--mine' : 'brand-chat-bubble--other' }} flex flex-col">
                    <div class="brand-chat-meta {{ $isMine ? 'text-white/75' : 'text-brand-ink/50' }}">
                        <span class="font-semibold {{ $isMine ? 'text-white/90' : 'text-brand-primary' }}">
                            {{ $senderLabel }}
                        </span>

                        <time datetime="{{ $message->created_at?->toAtomString() }}" class="shrink-0 text-right">
                            {{ $message->created_at?->format('M d, Y h:i A') }}
                        </time>
                    </div>

                    <p class="brand-chat-message {{ $isMine ? 'text-white/95' : 'text-brand-ink/80' }}">
                        {{ $message->message }}
                    </p>

                    @if ($isQuoteMessage && (int) $viewerId === (int) $customerId && $quotedPrice !== null)
                        <div class="mt-4 rounded-3xl border border-white/20 bg-white/10 p-4 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-white/75">
                                Quoted Price
                            </p>

                            <p class="mt-2 text-2xl font-semibold">
                                PHP {{ number_format((float) $quotedPrice, 2) }}
                            </p>

                            <p class="mt-2 text-sm text-white/80">
                                The seller has quoted a price for your custom order.
                            </p>

                            <div class="mt-4 flex flex-wrap gap-3">
                                <form method="POST" action="{{ route('custom-order.quote.accept', $message->custom_order_request_id) }}">
                                    @csrf
                                    <button type="submit" class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-brand-primary">
                                        Accept Quoted Price
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('custom-order.quote.decline', $message->custom_order_request_id) }}">
                                    @csrf
                                    <button type="submit" class="rounded-full border border-white/40 px-4 py-2 text-xs font-semibold text-white">
                                        Decline Quoted Price
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </article>

                @if ($isMine)
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-primary text-sm font-semibold text-white shadow-sm ring-1 ring-brand-primary/10">
                        {{ $avatar }}
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-4xl border border-dashed border-brand-border bg-brand-light/25 p-8 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white text-brand-primary ring-1 ring-brand-border shadow-sm">
                    <span class="text-xl">✦</span>
                </div>

                <p class="mt-4 font-semibold text-brand-primary">
                    {{ $emptyTitle }}
                </p>

                <p class="mt-2 text-sm leading-6 text-brand-ink/65">
                    {{ $emptyMessage }}
                </p>
            </div>
        @endforelse
    </div>
</div>
