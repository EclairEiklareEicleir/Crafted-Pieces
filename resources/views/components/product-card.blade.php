@props(['product'])

@php
    $availableVariants = $product->availableVariants();
    $unavailableVariants = $product->variants->filter(fn ($variant) => ! (($variant->status ?? 'active') === 'active' && (int) ($variant->stock ?? 0) > 0))->values();
    $defaultVariant = $availableVariants->firstWhere('is_default', true) ?? $availableVariants->first() ?? $product->defaultVariant;
    $cardImage = $defaultVariant?->floating_image_url ?? $product->floating_image_url;
    $modalId = 'variant-modal-' . $product->id;
@endphp

<div class="group flex h-full min-h-108 flex-col overflow-hidden rounded-[1.75rem] border border-brand-border bg-white/85 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

    <a href="{{ route('product.show', $product->slug) }}" class="flex flex-1 flex-col">

        <div class="relative flex h-60 items-center justify-center overflow-hidden p-6">
            <span class="absolute bottom-8 left-1/2 h-5 w-28 -translate-x-1/2 rounded-full bg-brand-primary/10 blur-md transition group-hover:w-32"></span>
            <img src="{{ $cardImage }}"
                 class="relative z-10 h-full w-full object-contain drop-shadow-[0_22px_24px_rgba(101,12,42,0.20)] transition duration-300 group-hover:-translate-y-1 group-hover:scale-105"
                 alt="{{ $product->name }}">
        </div>

        <div class="flex flex-1 flex-col p-4 pt-0">

            <h3 class="font-semibold text-brand-primary">
                {{ $product->name }}
            </h3>

            <p class="text-sm text-brand-ink/70">
                PHP {{ number_format((float) ($defaultVariant?->price ?? $product->price)) }}
            </p>

            <p class="mt-1 text-xs text-brand-secondary">
                {{ $defaultVariant ? $defaultVariant->availability_label : ($product->stock > 0 ? 'In stock' : 'Out of stock') }}
            </p>

        </div>

    </a>

    {{-- ADD TO CART --}}
    <div class="mt-auto p-4 pt-0">

        @if ($availableVariants->isNotEmpty())
            <button type="button"
                    data-yarn-modal-open="{{ $modalId }}"
                    class="brand-btn-primary w-full py-2">
                Add to Cart
            </button>
        @else
            <button disabled
                    class="w-full rounded-full bg-brand-ink/20 py-2 text-white cursor-not-allowed">
                No available variants
            </button>
        @endif

    </div>

</div>

@if ($availableVariants->isNotEmpty())
    <div id="{{ $modalId }}"
         data-yarn-modal
         class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6">
        <button type="button"
                class="absolute inset-0 cursor-default bg-brand-primary/35 backdrop-blur-sm"
                data-yarn-modal-close="{{ $modalId }}"
                aria-label="Close variant selector"></button>

        <form method="POST"
              action="{{ route('cart.add', $product->slug) }}"
              data-yarn-form
              data-default-image="{{ $product->floating_image_url }}"
              class="relative z-10 w-full max-w-lg overflow-hidden rounded-[1.75rem] border border-brand-border bg-white shadow-2xl shadow-brand-primary/15">
            @csrf

            <input type="hidden" name="quantity" value="1">
            <input type="hidden"
                   value="{{ $defaultVariant?->id ?? '' }}"
                   data-selected-variant-field>

            <div class="flex items-start justify-between gap-4 border-b border-brand-border bg-brand-surface px-5 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                        Select variant
                    </p>
                    <h3 class="mt-1 text-xl font-semibold text-brand-primary">
                        {{ $product->name }}
                    </h3>
                </div>

                <button type="button"
                        data-yarn-modal-close="{{ $modalId }}"
                        class="brand-icon-button h-10 w-10 shrink-0">
                    <span class="sr-only">Close</span>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="grid gap-5 p-5 sm:grid-cols-[9rem_minmax(0,1fr)]">
                <div class="relative flex aspect-square items-center justify-center">
                    <span class="absolute bottom-5 left-1/2 h-4 w-20 -translate-x-1/2 rounded-full bg-brand-primary/10 blur-md"></span>
                    <img src="{{ $cardImage }}"
                         alt="{{ $product->name }}"
                         class="relative z-10 h-full w-full object-contain drop-shadow-[0_16px_18px_rgba(101,12,42,0.18)]"
                         data-yarn-preview>
                </div>

                <div class="min-w-0">
                    <div class="grid gap-2 rounded-2xl border border-brand-border bg-brand-surface/60 p-4 text-sm text-brand-ink/70">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant</span>
                            <span class="font-semibold text-brand-primary" data-selected-variant-name>{{ $defaultVariant?->name ?? $product->name }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Price</span>
                            <span class="font-semibold text-brand-primary" data-selected-variant-price>PHP {{ number_format((float) ($defaultVariant?->price ?? $product->price), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Stock</span>
                            <span class="font-semibold text-brand-primary" data-selected-variant-stock>{{ $defaultVariant ? (int) $defaultVariant->stock : (int) $product->stock }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Status</span>
                            <span class="font-semibold text-brand-primary" data-selected-variant-status>{{ $defaultVariant?->availability_label ?? ($product->stock > 0 ? 'Available' : 'Out of Stock') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">SKU</span>
                            <span class="font-semibold text-brand-primary" data-selected-variant-sku>{{ $defaultVariant?->sku ?? 'Auto-generated' }}</span>
                        </div>
                    </div>

                    <div class="grid max-h-72 gap-2 overflow-y-auto pr-1">
                        @foreach ($availableVariants as $variant)
                            @php
                                $variantImage = $variant?->floating_image_url;
                                $colorHex = $variant->hex_color ?: '#ffffff';
                            @endphp

                            <label data-variant-card class="flex cursor-pointer items-center gap-3 rounded-2xl border border-brand-border bg-brand-light/20 px-3 py-2.5 text-sm transition hover:border-brand-secondary hover:bg-brand-light/35">
                                <input type="radio"
                                       name="product_variant_id"
                                       id="product-{{ $product->id }}-variant-{{ $variant->id }}"
                                       value="{{ $variant->id }}"
                                       data-variant-option
                                       data-variant-id="{{ $variant?->id }}"
                                       data-variant-image="{{ $variantImage }}"
                                       data-variant-name="{{ $variant->name ?: $variant->yarn_color }}"
                                       data-variant-price="{{ number_format((float) $variant->price, 2, '.', '') }}"
                                       data-variant-stock="{{ (int) $variant->stock }}"
                                       data-variant-status="{{ $variant->availability_label }}"
                                       data-variant-sku="{{ $variant->sku ?? 'Auto-generated' }}"
                                       data-variant-color="{{ $variant->yarn_color }}"
                                       @checked(($defaultVariant?->id ?? null) === $variant->id)
                                       required>
                                <span class="h-5 w-5 shrink-0 rounded-full border border-brand-border shadow-sm" @style(['background-color: ' . $colorHex])></span>
                                <span class="font-semibold text-brand-primary">{{ $variant->name ?: $variant->yarn_color }}</span>
                            </label>
                        @endforeach
                    </div>

                    @if ($unavailableVariants->isNotEmpty())
                        <div class="mt-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Unavailable variants</p>
                            <div class="mt-2 grid gap-2">
                                @foreach ($unavailableVariants as $variant)
                                    <label class="flex cursor-not-allowed items-center gap-3 rounded-2xl border border-brand-border bg-brand-light/10 px-3 py-2.5 text-sm opacity-60">
                                        <input type="radio" disabled>
                                        <span class="h-5 w-5 shrink-0 rounded-full border border-brand-border shadow-sm" @style(['background-color: ' . ($variant->hex_color ?: '#ffffff')])></span>
                                        <span class="font-semibold text-brand-primary">{{ $variant->name ?: $variant->yarn_color }}</span>
                                        <span class="ml-auto text-xs text-brand-ink/55">{{ $variant->availability_label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <button class="brand-btn-primary mt-4 w-full py-3 text-sm">
                        Add to Cart
                    </button>
                </div>
            </div>
        </form>
    </div>
@endif

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const syncVariantSelection = (form, option) => {
                const preview = form.querySelector('[data-yarn-preview]');
                const variantField = form.querySelector('[data-selected-variant-field]');
                const fallbackImage = form.dataset.defaultImage;
                const variantImage = option?.dataset.variantImage || '';
                const variantName = option?.dataset.variantName || '';
                const variantPrice = option?.dataset.variantPrice || '';
                const variantStock = option?.dataset.variantStock || '';
                const variantStatus = option?.dataset.variantStatus || '';
                const variantSku = option?.dataset.variantSku || '';

                const textTargets = {
                    name: form.querySelector('[data-selected-variant-name]'),
                    price: form.querySelector('[data-selected-variant-price]'),
                    stock: form.querySelector('[data-selected-variant-stock]'),
                    status: form.querySelector('[data-selected-variant-status]'),
                    sku: form.querySelector('[data-selected-variant-sku]'),
                };
                const selectedCardClasses = ['border-brand-primary', 'bg-brand-primary/5', 'ring-1', 'ring-brand-primary/15'];

                if (variantField) {
                    variantField.value = option?.dataset.variantId || '';
                }

                form.querySelectorAll('[data-variant-card]').forEach((card) => {
                    card.classList.remove(...selectedCardClasses);
                });

                const activeCard = option?.closest('[data-variant-card]');

                if (activeCard) {
                    activeCard.classList.add(...selectedCardClasses);
                }

                if (preview) {
                    preview.src = variantImage || fallbackImage;
                }

                if (textTargets.name) textTargets.name.textContent = variantName;
                if (textTargets.price && variantPrice !== '') textTargets.price.textContent = `PHP ${Number(variantPrice).toFixed(2)}`;
                if (textTargets.stock && variantStock !== '') textTargets.stock.textContent = variantStock;
                if (textTargets.status) textTargets.status.textContent = variantStatus;
                if (textTargets.sku) textTargets.sku.textContent = variantSku;
            };

            document.querySelectorAll('[data-yarn-modal-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.yarnModalOpen);

                    if (!modal) return;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');

                    const checked = modal.querySelector('[data-variant-option]:checked');
                    const form = modal.querySelector('[data-yarn-form]');

                    if (form && checked) {
                        syncVariantSelection(form, checked);
                    }
                });
            });

            document.querySelectorAll('[data-yarn-modal-close]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.yarnModalClose);

                    if (!modal) return;

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                });
            });

            document.querySelectorAll('[data-variant-option]').forEach((option) => {
                option.addEventListener('change', () => {
                    const form = option.closest('[data-yarn-form]');

                    if (form) {
                        syncVariantSelection(form, option);
                    }
                });
            });
        });
    </script>
@endonce
