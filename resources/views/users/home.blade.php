@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 pb-4 pt-14 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white/85 shadow-[0_24px_80px_rgba(90,53,33,0.10)]">
            <div class="grid gap-10 px-6 py-14 lg:grid-cols-[1.15fr_0.85fr] lg:px-12">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-[#a86b57]">Handmade Crochet Art</p>
                    <h1 class="mt-4 max-w-2xl font-display text-4xl font-semibold leading-tight text-[#4d3028] sm:text-5xl lg:text-6xl">Every Stitch, Crafted with Love</h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-[#6f5a51]">Welcome to the_crafted_pieces, your destination for handmade crochet creations and custom pieces.</p>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('shop') }}" class="rounded-full bg-[#b8745f] px-6 py-3 text-sm font-semibold text-white shadow-md shadow-[#b8745f]/20 transition hover:-translate-y-0.5 hover:bg-[#a96550]">Shop Crochet</a>
                        <a href="{{ route('custom-order') }}" class="rounded-full border border-[#e7d6cb] bg-white px-6 py-3 text-sm font-semibold text-[#5d342b] shadow-sm transition hover:-translate-y-0.5 hover:border-[#d8b6a2]">Request Custom Crochet</a>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="rounded-[1.75rem] bg-gradient-to-br from-[#f4e4d9] to-[#f8f1eb] p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Bestsellers</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-[#4d3028]">Mini pieces with strong character.</p>
                    </div>
                    <div class="rounded-[1.75rem] bg-gradient-to-br from-[#efe8ff] to-[#f7f3ff] p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#8a6ad1]">Custom Work</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-[#4d3028]">Quotations for one-of-one crochet gifts.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Shop by category</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Browse collections</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-sm font-semibold text-[#a86b57] transition hover:text-[#8d5848]">View all</a>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($categories as $category)
                <a href="{{ route('shop', ['category' => $category['slug']]) }}" class="rounded-3xl border border-[#eadfd7] bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <p class="font-semibold text-[#4d3028]">{{ $category['name'] }}</p>
                    <p class="mt-1 text-xs text-[#847166]">{{ $category['count'] }} items</p>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Featured</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Bestsellers</h2>
            </div>
            <a href="{{ route('shop') }}" class="text-sm font-semibold text-[#a86b57] transition hover:text-[#8d5848]">Shop all</a>
        </div>
        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    @if (count($newArrivals))
        <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">New arrivals</p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Fresh pieces</h2>
            </div>
            <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($newArrivals as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </section>
    @endif

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">How it works</p>
            <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">Ordering steps</h2>
            <div class="mt-8 grid gap-5 md:grid-cols-4">
                @foreach ($steps as $step)
                    <div class="rounded-3xl bg-[#fbf7f3] p-5">
                        <h3 class="font-semibold text-[#4d3028]">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-[#6f5a51]">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Testimonials</p>
            <h2 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">What customers say</h2>
            <div class="mt-8 grid gap-5 md:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <div class="rounded-3xl border border-[#f0e4db] bg-[#fcfaf8] p-5">
                        <div class="flex gap-1 text-[#d6a74a]">
                            @for ($i = 0; $i < $testimonial['rating']; $i++)
                                <span>*</span>
                            @endfor
                        </div>
                        <p class="mt-4 text-sm leading-6 text-[#5d4a43]">{{ $testimonial['text'] }}</p>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] text-[#8f6a5d]">{{ $testimonial['name'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
