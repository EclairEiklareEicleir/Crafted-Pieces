@props(['product'])

@php
    $image = $product->image;

    $imageUrl = $image
        ? (Str::startsWith($image, 'http')
            ? $image
            : asset('storage/' . $image))
        : 'https://placehold.co/600x600/png';
@endphp

<div class="group overflow-hidden rounded-[1.75rem] border border-brand-border bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

    <a href="{{ route('product.show', $product->slug) }}">

        <img src="{{ $imageUrl }}"
             class="h-48 w-full object-cover"
             alt="{{ $product->name }}">

        <div class="p-4">

            <h3 class="font-semibold text-brand-primary">
                {{ $product->name }}
            </h3>

            <p class="text-sm text-brand-ink/70">
                PHP {{ number_format($product->price) }}
            </p>

            <p class="mt-1 text-xs text-brand-secondary">
                {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
            </p>

        </div>

    </a>

    {{-- ADD TO CART --}}
    <div class="p-4 pt-0">

        @if ($product->stock > 0)
            <form method="POST" action="{{ route('cart.add', $product->slug) }}">
                @csrf

                <button type="submit"
                        class="brand-btn-primary w-full py-2">
                    Add to Cart
                </button>
            </form>
        @else
            <button disabled
                    class="w-full rounded-full bg-brand-ink/20 py-2 text-white cursor-not-allowed">
                Out of Stock
            </button>
        @endif

    </div>

</div>