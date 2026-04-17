@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[1fr_0.8fr]">
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">About us</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">A small studio with a handmade focus</h1>
                <p class="mt-4 leading-7 text-[#6f5a51]">the_crafted_pieces creates crochet gifts, accessories, and custom pieces with a careful process and consistent quality.</p>
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl bg-[#fbf7f3] p-4">
                        <p class="text-3xl font-display font-semibold text-[#4d3028]">100%</p>
                        <p class="mt-1 text-sm text-[#6f5a51]">Handmade work</p>
                    </div>
                    <div class="rounded-3xl bg-[#fbf7f3] p-4">
                        <p class="text-3xl font-display font-semibold text-[#4d3028]">3-10</p>
                        <p class="mt-1 text-sm text-[#6f5a51]">Days for custom builds</p>
                    </div>
                    <div class="rounded-3xl bg-[#fbf7f3] p-4">
                        <p class="text-3xl font-display font-semibold text-[#4d3028]">24/7</p>
                        <p class="mt-1 text-sm text-[#6f5a51]">Quotation intake</p>
                    </div>
                </div>
            </div>
            <div id="contact" class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Contact</h2>
                <div class="mt-5 space-y-3 text-sm text-[#6f5a51]">
                    <p>Email: hello@thecraftedpieces.com</p>
                    <p>Instagram: @the_crafted_pieces</p>
                    <p>Facebook: The Crafted Pieces</p>
                </div>
            </div>
        </div>

        <div id="faq" class="mt-8 rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">FAQ</p>
            <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Common questions</h2>
            <div class="mt-6 space-y-4">
                @foreach ($faq as $item)
                    <details class="rounded-3xl border border-[#f0e4db] bg-[#fcfaf8] p-4">
                        <summary class="cursor-pointer font-semibold text-[#4d3028]">{{ $item['q'] }}</summary>
                        <p class="mt-3 text-sm leading-6 text-[#6f5a51]">{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
