@extends('layouts.store')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Catalog
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">
                Shop Crochet Pieces
            </h1>

            <p class="mt-4 max-w-2xl text-[#6f5a51]">
                Browse ready stock, made-to-order items, and custom crochet pieces.
            </p>
        </div>

        <a href="{{ route('custom-order') }}"
           class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5">
            Request a quote
        </a>

    </div>

    {{-- FLASH MESSAGE (FIX #3) --}}
    @if (session('status'))
        <div class="mt-6 rounded-2xl border border-[#eadfd7] bg-white p-4 text-sm text-[#5d342b]">
            {{ session('status') }}
        </div>
    @endif

    {{-- Categories --}}
    <div class="mt-8 flex flex-wrap gap-3">

        <a href="{{ route('shop') }}"
           class="rounded-full border px-4 py-2 text-sm font-semibold
           {{ !$activeCategory ? 'border-[#b8745f] bg-[#b8745f] text-white' : 'border-[#eadfd7] bg-white text-[#5d4a43]' }}">
            All
        </a>

        @foreach ($categories as $category)
            <a href="{{ route('shop', ['category' => $category->slug]) }}"
               class="rounded-full border px-4 py-2 text-sm font-semibold
               {{ $activeCategory === $category->slug ? 'border-[#b8745f] bg-[#b8745f] text-white' : 'border-[#eadfd7] bg-white text-[#5d4a43]' }}">
                {{ $category->name }}
            </a>
        @endforeach

    </div>

    {{-- Products --}}
    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="rounded-2xl border border-[#eadfd7] bg-white p-6 text-sm text-[#6f5a51]">
                No products found for this category.
            </div>
        @endforelse

    </div>

</section>
@endsection