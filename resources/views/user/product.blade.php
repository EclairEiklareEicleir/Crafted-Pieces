@extends('layouts.store')

@section('content')
@php
    $availableVariants = $product->availableVariants();
    $unavailableVariants = $product->variants->filter(fn ($variant) => ! (($variant->status ?? 'active') === 'active' && (int) ($variant->stock ?? 0) > 0))->values();
    $selectedVariant = $availableVariants->firstWhere('is_default', true) ?? $availableVariants->first() ?? $product->defaultVariant;
    $heroImage = $selectedVariant?->floating_image_url ?? $product->floating_image_url;
@endphp
<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8" data-product-default-image="{{ $product->floating_image_url }}">
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
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">Variant Price</p>
                    <p class="mt-2 text-2xl font-semibold text-brand-primary" data-selected-variant-price>
                        PHP {{ number_format((float) ($selectedVariant?->price ?? $product->price), 2) }}
                    </p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-white p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">Variant Stock</p>
                    <p class="mt-2 text-2xl font-semibold text-brand-primary" data-selected-variant-stock>
                        {{ $selectedVariant ? (int) $selectedVariant->stock : (int) $product->stock }}
                    </p>
                </div>

                <div class="rounded-3xl border border-brand-border bg-white p-4">
                    <p class="text-xs uppercase tracking-[0.18em] text-brand-secondary">
                        Variant Status
                    </p>

                    <p class="mt-2 text-lg font-semibold capitalize text-brand-primary" data-selected-variant-status>
                        {{ $selectedVariant?->availability_label ?? 'No available variants' }}
                    </p>
                </div>

            </div>

            <div class="mt-4 rounded-3xl border border-brand-border bg-white p-4 text-sm text-brand-ink/70">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">Selected Variant</p>
                        <p class="mt-1 text-base font-semibold text-brand-primary" id="selected-variant-name">{{ $selectedVariant?->name ?? $product->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">SKU</p>
                        <p class="mt-1 font-semibold text-brand-primary" id="selected-variant-sku">{{ $selectedVariant?->sku ?? 'Auto-generated' }}</p>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-8 rounded-[1.75rem] border border-brand-border bg-white p-6">

                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    Order notes
                </h2>

                <p class="mt-3 text-sm leading-6 text-brand-ink/70">
                    Need adjustments? Request a custom version for this design and we can tailor size and colors.
                </p>

                <div class="mt-5 flex flex-wrap gap-3">

                    <form method="POST" action="{{ route('cart.add', $product->slug) }}" class="space-y-4" data-product-form>
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" value="{{ $selectedVariant?->id ?? '' }}" data-selected-variant-field>

                        @if ($availableVariants->isNotEmpty())
                            <div>
                                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                                    Choose variant
                                </p>

                                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                                    @foreach ($availableVariants as $variant)
                                        <label data-variant-card class="group/color flex cursor-pointer items-center gap-3 rounded-2xl border border-brand-border bg-brand-light/20 px-3 py-3 text-sm transition hover:border-brand-secondary hover:bg-brand-light/35">
                                            <input type="radio"
                                                   name="product_variant_id"
                                                   id="product-{{ $product->id }}-variant-{{ $variant->id }}"
                                                   value="{{ $variant->id }}"
                                                   data-variant-option
                                                   data-variant-id="{{ $variant->id }}"
                                                   data-variant-image="{{ $variant?->floating_image_url }}"
                                                   data-variant-name="{{ $variant->name ?: $variant->yarn_color }}"
                                                   data-variant-price="{{ number_format((float) $variant->price, 2, '.', '') }}"
                                                   data-variant-stock="{{ (int) $variant->stock }}"
                                                   data-variant-status="{{ $variant->availability_label }}"
                                                   data-variant-sku="{{ $variant->sku ?? 'Auto-generated' }}"
                                                   {{ $selectedVariant?->id === $variant->id ? 'checked' : '' }}
                                                   required>

                                            <span class="h-5 w-5 shrink-0 rounded-full border border-brand-border shadow-sm" @style(['background-color: ' . ($variant->hex_color ?: '#ffffff')])></span>

                                            <span class="min-w-0">
                                                <span class="block font-semibold text-brand-primary">{{ $variant->name ?: $variant->yarn_color }}</span>
                                                <span class="mt-1 block text-xs text-brand-ink/60">PHP {{ number_format((float) $variant->price, 2) }} • Stock {{ (int) $variant->stock }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @elseif ($unavailableVariants->isNotEmpty())
                            <p class="rounded-2xl border border-brand-border bg-brand-surface px-4 py-3 text-sm text-brand-ink/70">
                                No available variants.
                            </p>
                        @endif

                        <button type="submit" class="brand-btn-primary px-5 py-3 text-sm shadow-md hover:-translate-y-0.5" @disabled($availableVariants->isEmpty())>
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
        const productRoot = document.querySelector('[data-product-default-image]');
        const productForm = productRoot?.querySelector('[data-product-form]');
        const heroImage = document.getElementById('product-hero-image');
        const defaultImage = productRoot?.dataset.productDefaultImage || '';
        const selectedVariantName = productRoot?.querySelector('#selected-variant-name');
        const selectedVariantSku = productRoot?.querySelector('#selected-variant-sku');
        const selectedVariantPrice = productRoot?.querySelector('[data-selected-variant-price]');
        const selectedVariantStock = productRoot?.querySelector('[data-selected-variant-stock]');
        const selectedVariantStatus = productRoot?.querySelector('[data-selected-variant-status]');
        const selectedVariantField = productForm?.querySelector('[data-selected-variant-field]');
        const selectedCardClasses = ['border-brand-primary', 'bg-brand-primary/5', 'ring-1', 'ring-brand-primary/15'];

        if (!productRoot || !productForm || !heroImage) return;

        const updateSelectedCard = (input) => {
            productForm.querySelectorAll('[data-variant-card]').forEach((card) => {
                card.classList.remove(...selectedCardClasses);
            });

            const activeCard = input?.closest('[data-variant-card]');

            if (activeCard) {
                activeCard.classList.add(...selectedCardClasses);
            }
        };

        const syncVariant = (input) => {
            if (selectedVariantField) {
                selectedVariantField.value = input.dataset.variantId || '';
            }

            if (selectedVariantName) {
                selectedVariantName.textContent = input.dataset.variantName || '';
            }

            if (selectedVariantSku) {
                selectedVariantSku.textContent = input.dataset.variantSku || '';
            }

            if (selectedVariantPrice) {
                selectedVariantPrice.textContent = `PHP ${Number(input.dataset.variantPrice || 0).toFixed(2)}`;
            }

            if (selectedVariantStock) {
                selectedVariantStock.textContent = input.dataset.variantStock || '';
            }

            if (selectedVariantStatus) {
                selectedVariantStatus.textContent = input.dataset.variantStatus || '';
            }

            heroImage.src = input.dataset.variantImage || defaultImage;
            updateSelectedCard(input);
        };

        productForm.querySelectorAll('[data-variant-option]').forEach((input) => {
            input.addEventListener('change', () => {
                if (!input.checked) {
                    return;
                }

                syncVariant(input);
            });
        });

        const checkedVariant = productForm.querySelector('[data-variant-option]:checked');

        if (checkedVariant) {
            syncVariant(checkedVariant);
        }
    });
</script>
@endsection
