@props(['product'])

<div class="group overflow-hidden rounded-[1.75rem] border bg-white shadow-sm transition hover:-translate-y-1">

    <a href="{{ route('product.show', $product->slug) }}">

        <img src="{{ $product->image }}"
             class="h-48 w-full object-cover"
             alt="{{ $product->name }}">

        <div class="p-4">

            <h3 class="font-semibold text-[#4d3028]">
                {{ $product->name }}
            </h3>

            <p class="text-sm text-[#6f5a51]">
                PHP {{ number_format($product->price) }}
            </p>

            <p class="mt-1 text-xs text-[#8d5848]">
                {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
            </p>

        </div>

    </a>

    {{-- FIX #2: UX SAFE ADD TO CART --}}
    <div class="p-4 pt-0">

        @if ($product->stock > 0)
            <form method="POST" action="{{ route('cart.add', $product->slug) }}">
                @csrf

                <button type="submit"
                        class="w-full rounded-full bg-[#5d342b] py-2 text-white transition hover:bg-[#704338]">
                    Add to Cart
                </button>
            </form>
        @else
            <button disabled
                    class="w-full rounded-full bg-gray-300 py-2 text-white cursor-not-allowed">
                Out of Stock
            </button>
        @endif

    </div>

</div>