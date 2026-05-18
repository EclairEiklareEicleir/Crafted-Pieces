@extends('layouts.store')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Catalog
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Shop Crochet Pieces
            </h1>

            <p class="mt-4 max-w-2xl text-brand-ink/70">
                Browse ready stock, made-to-order items, and custom crochet pieces.
            </p>
        </div>

        <a href="{{ route('custom-order') }}"
           class="brand-btn-primary px-5 py-3 text-sm shadow-md hover:-translate-y-0.5">
            Request a quote
        </a>

    </div>

    {{-- FLASH MESSAGE (FIX #3) --}}
    @if (session('status'))
        <div class="mt-6 rounded-2xl border border-brand-border bg-white p-4 text-sm text-brand-primary">
            {{ session('status') }}
        </div>
    @endif

    {{-- Categories --}}
    <div class="mt-8 flex flex-wrap gap-3">

        <a href="{{ route('shop') }}"
           class="rounded-full border px-4 py-2 text-sm font-semibold
           {{ !$activeCategory ? 'border-brand-primary bg-brand-primary text-white' : 'border-brand-border bg-white text-brand-ink/75' }}">
            All
        </a>

        @foreach ($categories as $category)
            <a href="{{ route('shop', ['category' => $category->slug]) }}"
               class="rounded-full border px-4 py-2 text-sm font-semibold
               {{ $activeCategory === $category->slug ? 'border-brand-primary bg-brand-primary text-white' : 'border-brand-border bg-white text-brand-ink/75' }}">
                {{ $category->name }}
            </a>
        @endforeach

    </div>

    {{-- Products --}}
    <div class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">

        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="rounded-2xl border border-brand-border bg-white p-6 text-sm text-brand-ink/70">
                No products found for this category.
            </div>
        @endforelse

    </div>

</section>
@endsection