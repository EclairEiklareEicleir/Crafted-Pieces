@extends('layouts.store')

@section('content')

@php
    $statusMap = [
        'pending' => [
            'label' => 'Pending',
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
@endphp

<section class="relative min-h-screen overflow-hidden">

    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-28 top-12 h-80 w-80 rounded-full bg-brand-accent/30 blur-3xl"></div>
        <div class="absolute -right-24 top-32 h-96 w-96 rounded-full bg-white/60 blur-3xl"></div>
        <div class="absolute -bottom-28 left-1/2 h-72 w-72 rounded-full bg-brand-secondary/15 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <span class="brand-pill bg-white/80 ring-1 ring-brand-border backdrop-blur">
                    My Custom Orders
                </span>

                <h1 class="mt-4 font-display text-3xl font-semibold tracking-tight text-brand-primary sm:text-4xl">
                    Your commission tickets
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-brand-ink/65 sm:text-base">
                    Review every custom request, follow the status, and open the ticket whenever you want to continue the conversation.
                </p>
            </div>

            <a href="{{ route('custom-order') }}" class="brand-btn-primary w-full justify-center sm:w-auto">
                Request a Quote
            </a>
        </div>

        <div class="mt-10 space-y-5">

            @forelse ($orders as $order)

                @php
                    $status = $statusMap[$order->status] ?? [
                        'label' => ucfirst(str_replace('_', ' ', $order->status ?? 'pending')),
                        'class' => 'bg-brand-light text-brand-primary ring-1 ring-brand-border',
                    ];

                    $quoteAmount = $order->final_price ?? $order->estimated_price ?? 0;
                    $createdAt = $order->created_at?->format('M d, Y');
                    $descriptionPreview = \Illuminate\Support\Str::limit($order->description, 140);
                @endphp

                <a href="{{ route('custom-order.show', $order) }}" class="group block">
                    <article class="rounded-4xl border border-brand-border bg-white/85 p-5 shadow-[0_24px_60px_-40px_rgba(101,12,42,0.35)] backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_80px_-45px_rgba(101,12,42,0.42)] sm:p-6">

                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">

                            <div class="min-w-0 space-y-4">

                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="brand-pill bg-brand-light/80 ring-1 ring-brand-border">
                                        Ticket #{{ $order->id }}
                                    </span>

                                    <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $status['class'] }}">
                                        {{ $status['label'] }}
                                    </span>
                                </div>

                                <div>
                                    <h2 class="font-display text-2xl font-semibold tracking-tight text-brand-primary sm:text-[1.75rem]">
                                        {{ $order->item_type }}
                                    </h2>

                                    <p class="mt-2 max-w-3xl text-sm leading-7 text-brand-ink/68 sm:text-base">
                                        {{ $descriptionPreview }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @if ($order->preferred_size)
                                        <span class="inline-flex items-center rounded-full border border-brand-border bg-brand-surface px-3 py-1 text-xs font-medium text-brand-primary">
                                            Size: {{ $order->preferred_size }}
                                        </span>
                                    @endif

                                    @if ($order->design_theme)
                                        <span class="inline-flex items-center rounded-full border border-brand-border bg-brand-surface px-3 py-1 text-xs font-medium text-brand-primary">
                                            Theme: {{ $order->design_theme }}
                                        </span>
                                    @endif

                                    @if ($createdAt)
                                        <span class="inline-flex items-center rounded-full border border-brand-border bg-brand-surface px-3 py-1 text-xs font-medium text-brand-primary">
                                            Created {{ $createdAt }}
                                        </span>
                                    @endif
                                </div>

                            </div>

                            <div class="flex shrink-0 flex-col gap-4 lg:items-end">

                                <div class="rounded-3xl bg-brand-light/35 px-5 py-4 text-left shadow-sm lg:min-w-56 lg:text-right">
                                    <p class="text-[0.7rem] font-semibold uppercase tracking-[0.22em] text-brand-ink/55">
                                        Quote
                                    </p>

                                    <p class="mt-2 text-2xl font-semibold tracking-tight text-brand-primary">
                                        PHP {{ number_format((float) $quoteAmount, 2) }}
                                    </p>

                                    <p class="mt-1 text-xs text-brand-ink/55">
                                        {{ $order->final_price ? 'Final quotation' : 'Estimated price' }}
                                    </p>
                                </div>

                                <span class="brand-btn-primary w-full justify-center px-5 py-3 text-sm transition group-hover:shadow-xl group-hover:shadow-brand-primary/20 lg:w-auto">
                                    Open Ticket
                                </span>

                            </div>

                        </div>

                    </article>
                </a>

            @empty

                <div class="rounded-[2.25rem] border border-dashed border-brand-border bg-white/82 p-10 text-center shadow-[0_20px_60px_-45px_rgba(101,12,42,0.35)] backdrop-blur-xl sm:p-14">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-brand-light text-brand-primary ring-1 ring-brand-border">
                        <span class="text-2xl">♡</span>
                    </div>

                    <h2 class="mt-5 font-display text-2xl font-semibold text-brand-primary">
                        No custom orders yet
                    </h2>

                    <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-brand-ink/65 sm:text-base">
                        Once you send a request, your commission tickets will appear here with live status updates and conversation history.
                    </p>

                    <a href="{{ route('custom-order') }}" class="brand-btn-primary mt-6 w-full justify-center sm:w-auto">
                        Start a Request
                    </a>
                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection