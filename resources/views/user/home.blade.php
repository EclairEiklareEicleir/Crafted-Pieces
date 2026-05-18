@extends('layouts.store')

@section('content')

    {{-- HERO --}}
    <section class="mx-auto max-w-7xl px-4 pb-4 pt-14 sm:px-6 lg:px-8">

        <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white/90 shadow-[0_24px_80px_rgba(101,12,42,0.10)]">

            <div class="grid gap-10 px-6 py-14 lg:grid-cols-[1.15fr_0.85fr] lg:px-12">

                <div>

                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-brand-secondary">
                        Handmade Crochet Art
                    </p>

                    <h1 class="mt-4 max-w-2xl font-display text-4xl font-semibold leading-tight text-brand-primary sm:text-5xl lg:text-6xl">
                        Every Stitch, Crafted with Love
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-7 text-brand-ink/75">
                        Welcome to Crafted Pieces, your destination for handmade crochet creations and custom pieces.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">

                        <a href="{{ route('shop') }}"
                           class="brand-btn-primary px-6 py-3 text-sm shadow-md shadow-brand-primary/20 hover:-translate-y-0.5">
                            Shop Crochet
                        </a>

                        <a href="{{ route('custom-order') }}"
                           class="brand-btn-secondary px-6 py-3 text-sm shadow-sm hover:-translate-y-0.5">
                            Request Custom Crochet
                        </a>

                    </div>

                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">

                    <div class="rounded-[1.75rem] bg-gradient-to-br from-brand-light to-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Bestsellers</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-brand-primary">
                            Handmade pieces with strong character.
                        </p>
                    </div>

                    <div class="rounded-[1.75rem] bg-gradient-to-br from-white to-brand-light p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-primary">Custom Work</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-brand-primary">
                            One-of-one crochet gifts made to order.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- SHOP ENTRY (CATEGORIES) --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between gap-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Start exploring
                </p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    Browse categories
                </h2>
            </div>

            <a href="{{ route('shop') }}"
               class="text-sm font-semibold text-brand-secondary transition hover:text-brand-primary">
                View all
            </a>

        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">

            @foreach ($categories as $category)

                <a href="{{ route('shop', ['category' => $category['slug']]) }}"
                   class="rounded-3xl border border-brand-border bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                    <p class="font-semibold text-brand-primary">
                        {{ $category['name'] }}
                    </p>

                    <p class="mt-1 text-xs text-brand-ink/60">
                        {{ $category['count'] }} items
                    </p>

                </a>

            @endforeach

        </div>

    </section>

    {{-- FEATURED PRODUCTS --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between gap-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Featured
                </p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    Bestsellers
                </h2>
            </div>

            <a href="{{ route('shop') }}"
               class="text-sm font-semibold text-brand-secondary transition hover:text-brand-primary">
                Shop all
            </a>

        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">

            @foreach ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach

        </div>

    </section>

    {{-- TRUST SECTION (COMBINED: STEPS + TESTIMONIALS) --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                How it works
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Ordering & Reviews
            </h2>

            {{-- STEPS --}}
            <div class="mt-8 grid gap-5 md:grid-cols-4">

                @foreach ($steps as $step)

                    <div class="rounded-3xl bg-brand-light/60 p-5">

                        <h3 class="font-semibold text-brand-primary">
                            {{ $step['title'] }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-brand-ink/70">
                            {{ $step['desc'] }}
                        </p>

                    </div>

                @endforeach

            </div>

            {{-- TESTIMONIALS --}}
            <div class="mt-10 grid gap-5 md:grid-cols-3">

                @foreach ($testimonials as $testimonial)

                    <div class="rounded-3xl border border-brand-border bg-white p-5">

                        <div class="flex gap-1 text-brand-secondary">
                            @for ($i = 0; $i < $testimonial['rating']; $i++)
                                <span>*</span>
                            @endfor
                        </div>

                        <p class="mt-4 text-sm leading-6 text-brand-ink/70">
                            {{ $testimonial['text'] }}
                        </p>

                        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                            {{ $testimonial['name'] }}
                        </p>

                    </div>

                @endforeach

            </div>

        </div>

    </section>

@endsection