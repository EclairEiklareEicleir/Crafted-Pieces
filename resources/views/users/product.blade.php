@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr]">
            <div class="overflow-hidden rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <div class="rounded-[1.75rem] bg-gradient-to-br from-[#f7ece5] to-[#f0d7cb] p-6">
                    <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="aspect-square w-full rounded-3xl object-cover">
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">{{ $product['category'] }}</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">{{ $product['name'] }}</h1>
                <p class="mt-4 text-base leading-7 text-[#6f5a51]">{{ $product['description'] }}</p>
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($product['tags'] as $tag)
                        <span class="rounded-full bg-[#f7ede7] px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#a86b57]">{{ $tag }}</span>
                    @endforeach
                </div>
                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-3xl border border-[#eadfd7] bg-white p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-[#8f6a5d]">Price</p>
                        <p class="mt-2 text-2xl font-semibold text-[#4d3028]">PHP {{ number_format($product['price']) }}</p>
                    </div>
                    <div class="rounded-3xl border border-[#eadfd7] bg-white p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-[#8f6a5d]">Stock</p>
                        <p class="mt-2 text-2xl font-semibold text-[#4d3028]">{{ $product['stock'] }}</p>
                    </div>
                    <div class="rounded-3xl border border-[#eadfd7] bg-white p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-[#8f6a5d]">Fulfillment</p>
                        <p class="mt-2 text-lg font-semibold text-[#4d3028]">{{ str_replace('-', ' ', $product['fulfillment']) }}</p>
                    </div>
                </div>
                <div class="mt-8 rounded-[1.75rem] border border-[#eadfd7] bg-white p-6">
                    <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Order notes</h2>
                    <p class="mt-3 text-sm leading-6 text-[#6f5a51]">Need adjustments? Request a custom version for this design and we can tailor size and colors.</p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <a href="{{ route('cart') }}" class="rounded-full bg-[#b8745f] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">Add to cart</a>
                        <a href="{{ route('custom-order') }}" class="rounded-full border border-[#eadfd7] bg-white px-5 py-3 text-sm font-semibold text-[#5d342b] transition hover:-translate-y-0.5">Request custom version</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
