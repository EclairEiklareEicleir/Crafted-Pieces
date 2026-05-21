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
    class="brand-thread-scroll mt-6 max-h-[34rem] overflow-y-auto rounded-[1.75rem] border border-brand-border/80 bg-brand-light/15 p-4 sm:p-5"
    role="log"
    aria-live="polite"
>
    <div class="space-y-4">
        @forelse ($messages as $message)
            @php
                $isMine = (int) $message->user_id === (int) $viewerId;
                $isCustomer = $customerId !== null && (int) $message->user_id === (int) $customerId;
                $senderRole = $message->user->role ?? null;

                $senderLabel = $isMine
                    ? $viewerLabel
                    : ($isCustomer
                        ? $customerLabel
                        : ($senderRole === 'owner' ? $ownerLabel : ($message->user->name ?? $customerLabel)));
            @endphp

            <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <article class="brand-chat-bubble {{ $isMine ? 'brand-chat-bubble--mine' : 'brand-chat-bubble--other' }} inline-flex flex-col">
                    <div class="brand-chat-meta {{ $isMine ? 'text-white/75' : 'text-brand-ink/50' }}">
                        <span class="font-semibold {{ $isMine ? 'text-white/90' : 'text-brand-primary' }}">
                            {{ $senderLabel }}
                        </span>

                        <time datetime="{{ $message->created_at?->toAtomString() }}">
                            {{ $message->created_at?->format('M d, Y h:i A') }}
                        </time>
                    </div>

                    <p class="brand-chat-message {{ $isMine ? 'text-white/95' : 'text-brand-ink/80' }}">
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
                    {{ $emptyTitle }}
                </p>

                <p class="mt-2 text-sm leading-6 text-brand-ink/65">
                    {{ $emptyMessage }}
                </p>
            </div>
        @endforelse
    </div>
</div>
