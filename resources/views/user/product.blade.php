@extends('layouts.store')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr]">

        {{-- PRODUCT IMAGE --}}
        <div class="overflow-hidden rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">
            <div class="rounded-[1.75rem] bg-gradient-to-br from-brand-light/70 to-white p-6">

                @php
                    $image = $product->image;

                    // if stored file exists in storage → use storage path
                    $imageUrl = $image
                        ? (Str::startsWith($image, 'http')
                            ? $image
                            : asset('storage/' . $image))
                        : 'https://placehold.co/600x600/png';
                @endphp

                <img src="{{ $imageUrl }}"
                     alt="{{ $product->name }}"
                     class="aspect-square w-full rounded-3xl object-cover">
            </div>
        </div>

        {{-- PRODUCT DETAILS --}}
        <div>

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                {{ $product->category->name ?? 'Uncategorized' }}
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                {{ $product->name }}
            </h1>

            <p class="mt-4 text-base leading-7 text-brand-ink/70">
                {{ $product->description }}
            </p>

            {{-- STATS --}}
            <div class="mt-8 grid gap-4 sm:grid-cols-3">

                <div class="rounded-3xl border border-brand-border bg-white p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">Price</p>
                    <p class="mt-2 text-2xl font-semibold text-brand-primary">
                        PHP {{ number_format($product->price) }}
                    </p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-white p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">Stock</p>
                    <p class="mt-2 text-2xl font-semibold text-brand-primary">
                        {{ $product->stock }}
                    </p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-white p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">
                        Product Type
                    </p>

                    <p class="mt-2 text-lg font-semibold capitalize text-brand-primary">
                        {{ $product->product_type }}
                    </p>
                </div>

            </div>

            {{-- ACTIONS --}}
            <div class="mt-8 rounded-[1.75rem] border border-brand-border bg-white p-6">

                @php
                    $currentUser = auth()->user();
                    $canAddToCart = $currentUser && $currentUser->role === 'user';
                @endphp

                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    Order notes
                </h2>

                <p class="mt-3 text-sm leading-6 text-brand-ink/70">
                    Need adjustments? Request a custom version for this design and we can tailor size and colors.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">

                    @if ($canAddToCart)
                        <form method="POST" action="{{ route('cart.add', $product->slug) }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit"
                                    class="brand-btn-primary px-5 py-3 text-sm shadow-md hover:-translate-y-0.5">
                                Add to cart
                            </button>
                        </form>
                    @else
                        <button type="button"
                                onclick="openAuthModal()"
                                class="brand-btn-primary px-5 py-3 text-sm shadow-md hover:-translate-y-0.5">
                            Add to cart
                        </button>
                    @endif

                    <a href="{{ route('custom-order') }}"
                       class="brand-btn-secondary px-5 py-3 text-sm hover:-translate-y-0.5">
                        Request custom version
                    </a>

                </div>

            </div>

        </div>
    </div>
</section>
@endsection