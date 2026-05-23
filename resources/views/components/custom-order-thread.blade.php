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
