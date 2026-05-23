@extends('layouts.store')

@section('content')
@php
    $availableYarnColors = \App\Models\YarnColor::activeOptionsForProduct($product);
    $selectedYarnColor = $availableYarnColors->first();
    $selectedVariant = $selectedYarnColor ? $product->variantForYarnColor($selectedYarnColor) : $product->defaultVariant;
    $heroImage = $selectedVariant?->floating_image_url ?? $product->floating_image_url;
@endphp
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
    <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr]">

        {{-- PRODUCT IMAGE --}}
        <div class="overflow-hidden rounded-4xl border border-brand-border bg-white/85 p-6 shadow-sm">
            <div class="relative flex aspect-square items-center justify-center p-6">

                <span class="absolute bottom-12 left-1/2 h-8 w-44 -translate-x-1/2 rounded-full bg-brand-primary/10 blur-lg"></span>
                <img src="{{ $heroImage }}"
                     alt="{{ $product->name }}"
                     id="product-hero-image"
                     class="relative z-10 h-full w-full object-contain drop-shadow-[0_28px_30px_rgba(101,12,42,0.20)] transition duration-300">
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

                    <form method="POST" action="{{ route('cart.add', $product->slug) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="product_variant_id" value="{{ $selectedVariant?->id }}" data-selected-variant-field>

                        @if ($availableYarnColors->isNotEmpty())
                            <div>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                    Choose yarn color
                                </p>

                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($availableYarnColors as $color)
                                    @php
                                        $variant = $product->variantForYarnColor($color);
                                        $variantImage = $variant?->floating_image_url;
                                    @endphp

                                    <label class="group/color flex cursor-pointer items-center gap-3 rounded-2xl border border-brand-border bg-brand-light/20 px-3 py-3 text-sm transition hover:border-brand-secondary hover:bg-brand-light/35">
                                        <input type="radio"
                                               name="yarn_color_id"
                                               value="{{ $color->id }}"
                                               data-yarn-color-option
                                               data-variant-id="{{ $variant?->id }}"
                                               data-variant-image="{{ $variantImage }}"
                                               {{ $selectedYarnColor?->id === $color->id ? 'checked' : '' }}
                                               required>

                                        <span class="h-5 w-5 shrink-0 rounded-full border border-brand-border shadow-sm" style="background-color: {{ $color->hex_color ?? '#ffffff' }}"></span>

                                        <span class="min-w-0">
                                            <span class="block font-semibold text-brand-primary">{{ $color->name }}</span>
                                            @if ($color->hex_color)
                                                <span class="mt-1 inline-flex items-center gap-2 text-xs text-brand-ink/60">
                                                    {{ $color->hex_color }}
                                                </span>
                                            @endif
                                        </span>
                                    </label>
                                @endforeach
                                </div>
                            </div>
                        @endif

                        <button type="{{ $canAddToCart ? 'submit' : 'button' }}"
                                @unless ($canAddToCart) onclick="openAuthModal()" @endunless
                                class="brand-btn-primary px-5 py-3 text-sm shadow-md hover:-translate-y-0.5">
                            Add to cart
                        </button>
                    </form>

                    @auth
                        <a href="{{ route('custom-order') }}"
                           class="brand-btn-secondary px-5 py-3 text-sm hover:-translate-y-0.5">
                            Request custom version
                        </a>
                    @endauth

                </div>

            </div>

        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const heroImage = document.getElementById('product-hero-image');
        const variantField = document.querySelector('[data-selected-variant-field]');
        const defaultImage = @json($product->floating_image_url);

        if (!heroImage) return;

        document.querySelectorAll('[data-yarn-color-option]').forEach((input) => {
            input.addEventListener('change', () => {
                if (!input.checked) {
                    return;
                }

                if (variantField) {
                    variantField.value = input.dataset.variantId || '';
                }

                heroImage.src = input.dataset.variantImage || defaultImage;
            });
        });
    });
</script>
@endsection
