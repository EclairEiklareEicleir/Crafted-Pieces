@props(['product'])

@php
    $availableYarnColors = \App\Models\YarnColor::activeOptionsForProduct($product);
    $defaultYarnColor = $availableYarnColors->first();
    $defaultVariant = $defaultYarnColor ? $product->variantForYarnColor($defaultYarnColor) : $product->defaultVariant;
    $cardImage = $defaultVariant?->floating_image_url ?? $product->floating_image_url;
    $modalId = 'yarn-color-modal-' . $product->id;
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
                PHP {{ number_format($product->price) }}
            </p>

            <p class="mt-1 text-xs text-brand-secondary">
                {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
            </p>

        </div>

    </a>

    {{-- ADD TO CART --}}
    <div class="mt-auto p-4 pt-0">

        @if ($product->stock > 0)
            <button type="button"
                    data-yarn-modal-open="{{ $modalId }}"
                    class="brand-btn-primary w-full py-2">
                Add to Cart
            </button>
        @else
            <button disabled
                    class="w-full rounded-full bg-brand-ink/20 py-2 text-white cursor-not-allowed">
                Out of Stock
            </button>
        @endif

    </div>

</div>

@if ($product->stock > 0)
    <div id="{{ $modalId }}"
         data-yarn-modal
         class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6">
        <button type="button"
                class="absolute inset-0 cursor-default bg-brand-primary/35 backdrop-blur-sm"
                data-yarn-modal-close="{{ $modalId }}"
                aria-label="Close yarn color selector"></button>

        <form method="POST"
              action="{{ route('cart.add', $product->slug) }}"
              data-yarn-form
              data-default-image="{{ $product->floating_image_url }}"
              class="relative z-10 w-full max-w-lg overflow-hidden rounded-[1.75rem] border border-brand-border bg-white shadow-2xl shadow-brand-primary/15">
            @csrf

            <input type="hidden" name="quantity" value="1">
            <input type="hidden" name="product_variant_id" value="{{ $defaultVariant?->id }}" data-selected-variant-field>

            <div class="flex items-start justify-between gap-4 border-b border-brand-border bg-brand-surface px-5 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                        Yarn color
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
                    <div class="grid max-h-72 gap-2 overflow-y-auto pr-1">
                        @foreach ($availableYarnColors as $color)
                            @php
                                $variant = $product->variantForYarnColor($color);
                                $variantImage = $variant?->floating_image_url;
                                $colorHex = $color->hex_color ?: '#ffffff';
                            @endphp

                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-brand-border bg-brand-light/20 px-3 py-2.5 text-sm transition hover:border-brand-secondary hover:bg-brand-light/35">
                                <input type="radio"
                                       name="yarn_color_id"
                                       value="{{ $color->id }}"
                                       data-yarn-color-option
                                       data-variant-id="{{ $variant?->id }}"
                                       data-variant-image="{{ $variantImage }}"
                                       @checked($defaultYarnColor?->id === $color->id)
                                       required>
                                <span class="h-5 w-5 shrink-0 rounded-full border border-brand-border shadow-sm" @style(['background-color: ' . $colorHex])></span>
                                <span class="font-semibold text-brand-primary">{{ $color->name }}</span>
                            </label>
                        @endforeach
                    </div>

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
            const syncYarnSelection = (form, option) => {
                const preview = form.querySelector('[data-yarn-preview]');
                const variantField = form.querySelector('[data-selected-variant-field]');
                const fallbackImage = form.dataset.defaultImage;
                const variantImage = option?.dataset.variantImage || '';

                if (variantField) {
                    variantField.value = option?.dataset.variantId || '';
                }

                if (preview) {
                    preview.src = variantImage || fallbackImage;
                }
            };

            document.querySelectorAll('[data-yarn-modal-open]').forEach((button) => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.dataset.yarnModalOpen);

                    if (!modal) return;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.classList.add('overflow-hidden');

                    const checked = modal.querySelector('[data-yarn-color-option]:checked');
                    const form = modal.querySelector('[data-yarn-form]');

                    if (form && checked) {
                        syncYarnSelection(form, checked);
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

            document.querySelectorAll('[data-yarn-color-option]').forEach((option) => {
                option.addEventListener('change', () => {
                    const form = option.closest('[data-yarn-form]');

                    if (form) {
                        syncYarnSelection(form, option);
                    }
                });
            });
        });
    </script>
@endonce
