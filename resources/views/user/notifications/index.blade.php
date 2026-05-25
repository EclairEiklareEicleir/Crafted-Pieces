@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="rounded-4xl border border-brand-border bg-white/90 p-6 shadow-sm sm:p-8">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Notifications
                </p>
                <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    Your inbox
                </h1>
            </div>

            <p class="text-sm text-brand-ink/70">
                Click a notification to mark it as viewed.
            </p>
        </div>

        <div class="mt-8 grid gap-3">
            @forelse ($notifications as $notification)
                <a href="{{ route('notifications.show', $notification) }}"
                   class="rounded-2xl border px-4 py-4 transition hover:-translate-y-0.5 hover:shadow-md {{ $notification->is_read ? 'border-brand-border bg-white' : 'border-brand-secondary/30 bg-brand-light/40' }}">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="font-semibold text-brand-primary">
                                {{ $notification->title }}
                            </p>
                            <p class="mt-1 text-sm leading-6 text-brand-ink/70">
                                {{ $notification->message }}
                            </p>
                        </div>

                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $notification->is_read ? 'bg-brand-light text-brand-primary' : 'bg-brand-secondary text-white' }}">
                            {{ $notification->is_read ? 'Viewed' : 'Unread' }}
                        </span>
                    </div>

                    <p class="mt-3 text-xs uppercase tracking-[0.16em] text-brand-ink/45">
                        {{ $notification->created_at?->format('M d, Y h:i A') }}
                    </p>
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-brand-border bg-brand-light/20 p-8 text-center text-brand-ink/65">
                    You do not have any notifications yet.
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection