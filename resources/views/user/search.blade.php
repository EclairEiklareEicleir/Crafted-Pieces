@extends('layouts.store')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white/90 shadow-[0_24px_80px_rgba(101,12,42,0.10)]">
        <div class="grid gap-8 px-6 py-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:px-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Search
                </p>

                <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                    Search results for: <span class="break-words">{{ $searchTerm }}</span>
                </h1>

                <p class="mt-4 max-w-2xl text-brand-ink/70">
                    Browse products that match your keyword, category, description, or price.
                </p>
            </div>

            <a href="{{ route('shop') }}"
               class="brand-btn-secondary px-5 py-3 text-sm shadow-sm hover:-translate-y-0.5">
                Back to shop
            </a>
        </div>
    </div>

    <div class="mt-8 flex items-center justify-between gap-4">
        <p class="text-sm font-medium text-brand-ink/70">
            {{ $products->count() }} result{{ $products->count() === 1 ? '' : 's' }} found
        </p>
    </div>

    <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($products as $product)
            <x-product-card :product="$product" />
        @empty
            <div class="rounded-[1.75rem] border border-brand-border bg-white p-8 text-center shadow-sm md:col-span-2 xl:col-span-3">
                <p class="text-lg font-semibold text-brand-primary">
                    No products found.
                </p>

                <p class="mt-2 text-sm text-brand-ink/70">
                    Try a different keyword or return to the shop catalog.
                </p>

                <a href="{{ route('shop') }}"
                   class="brand-btn-primary mt-6 px-5 py-3 text-sm">
                    Browse all products
                </a>
            </div>
        @endforelse
    </div>

</section>
@endsection
