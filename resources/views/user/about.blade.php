@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.8fr]">
            <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">About us</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">A small studio with a handmade focus</h1>
                <p class="mt-4 leading-7 text-brand-ink/70">Crafted Pieces creates crochet gifts, accessories, and custom pieces with a careful process and consistent quality.</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-brand-light/60 p-4">
                        <p class="text-3xl font-display font-semibold text-brand-primary">100%</p>
                        <p class="mt-1 text-sm text-brand-ink/70">Handmade work</p>
                    </div>
                    <div class="rounded-3xl bg-brand-light/60 p-4">
                        <p class="text-3xl font-display font-semibold text-brand-primary">3-10</p>
                        <p class="mt-1 text-sm text-brand-ink/70">Days for custom builds</p>
                    </div>
                    <div class="rounded-3xl bg-brand-light/60 p-4">
                        <p class="text-3xl font-display font-semibold text-brand-primary">24/7</p>
                        <p class="mt-1 text-sm text-brand-ink/70">Quotation intake</p>
                    </div>
                </div>
            </div>
            <div id="contact" class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">
                <h2 class="font-display text-2xl font-semibold text-brand-primary">Contact</h2>
                <div class="mt-5 space-y-3 text-sm text-brand-ink/70">
                    <p>Email: hello@thecraftedpieces.com</p>
                    <p>Instagram: @the_crafted_pieces</p>
                    <p>Facebook: The Crafted Pieces</p>
                </div>
            </div>
        </div>

        <div id="faq" class="mt-8 rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">FAQ</p>
            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">Common questions</h2>
            <div class="mt-6 space-y-4">
                @foreach ($faq as $item)
                    <details class="rounded-3xl border border-brand-border bg-brand-light/35 p-4">
                        <summary class="cursor-pointer font-semibold text-brand-primary">{{ $item['q'] }}</summary>
                        <p class="mt-3 text-sm leading-6 text-brand-ink/70">{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
