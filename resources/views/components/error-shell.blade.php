@props([
    'code',
    'title',
    'message',
    'primaryLabel' => 'Go Home',
    'primaryHref' => null,
    'secondaryLabel' => 'Go Back',
    'secondaryHref' => null,
])

@php
    $primaryTarget = $primaryHref ?: route('home');
    $secondaryTarget = $secondaryHref ?: url()->previous();

    $icon = match ((string) $code) {
        '403' => 'shield',
        '404' => 'search',
        '500' => 'spark',
        '503' => 'clock',
        default => 'heart',
    };
@endphp

<section class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-12 sm:px-6 lg:px-8">
    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 top-8 h-72 w-72 rounded-full bg-brand-accent/20 blur-3xl"></div>
        <div class="absolute right-[-6rem] top-20 h-80 w-80 rounded-full bg-white/70 blur-3xl"></div>
        <div class="absolute bottom-[-6rem] left-1/2 h-72 w-72 rounded-full bg-brand-secondary/15 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-2xl rounded-[2rem] border border-brand-border bg-white/92 p-6 shadow-[0_30px_100px_-50px_rgba(101,12,42,0.42)] backdrop-blur-xl sm:p-8 lg:p-10">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl border border-brand-border bg-brand-light/60 shadow-sm">
            <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-10 w-auto object-contain">
        </div>

        <div class="mt-6 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-secondary">Error {{ $code }}</p>

            <h1 class="mt-3 font-display text-4xl font-semibold tracking-tight text-brand-primary sm:text-5xl">
                {{ $title }}
            </h1>

            <p class="mx-auto mt-4 max-w-xl text-sm leading-7 text-brand-ink/70 sm:text-base">
                {{ $message }}
            </p>
        </div>

        <div class="mt-8 rounded-[1.75rem] border border-brand-border bg-brand-light/35 p-5 sm:p-6">
            <div class="flex flex-col items-center gap-3 text-center sm:flex-row sm:text-left">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-brand-primary shadow-sm ring-1 ring-brand-border">
                    @if ($icon === 'shield')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3 19 6v5c0 4.5-3 8.5-7 10-4-1.5-7-5.5-7-10V6l7-3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 12.2 11 13.7l3.5-4" />
                        </svg>
                    @elseif ($icon === 'search')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35" />
                            <circle cx="10.5" cy="10.5" r="6.5" />
                        </svg>
                    @elseif ($icon === 'clock')
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                            <circle cx="12" cy="12" r="8.5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5v4l2.5 1.5" />
                        </svg>
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-7.5-4.4-9.5-10.2A5.5 5.5 0 0 1 12 5.4a5.5 5.5 0 0 1 9.5 5.4C19.5 16.6 12 21 12 21Z" />
                        </svg>
                    @endif
                </div>

                <div class="flex-1">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand-secondary">Crafted Pieces</p>
                    <p class="mt-1 text-sm leading-6 text-brand-ink/65">
                        If this page was opened from a broken link or expired session, use the buttons below to continue.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ $primaryTarget }}" class="brand-btn-primary px-6 py-3 text-sm">
                {{ $primaryLabel }}
            </a>

            <a href="{{ $secondaryTarget }}" class="brand-btn-secondary px-6 py-3 text-sm">
                {{ $secondaryLabel }}
            </a>
        </div>
    </div>
</section>