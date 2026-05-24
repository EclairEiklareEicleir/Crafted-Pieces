@extends('layouts.admin')

@section('content')

<div class="space-y-8">

@php
    $variantRows = old('variants', $product->variants->map(function ($variant) {
        return [
            'id' => $variant->id,
            'name' => $variant->name,
            'yarn_color' => $variant->yarn_color,
            'hex_color' => $variant->hex_color,
            'size' => $variant->size,
            'material' => $variant->material,
            'design_style' => $variant->design_style,
            'set_quantity' => $variant->set_quantity,
            'packaging_option' => $variant->packaging_option,
            'price' => $variant->price,
            'stock' => $variant->stock,
            'status' => $variant->status,
            'sku' => $variant->sku,
        ];
    })->values()->all() ?: [[
        'name' => '',
        'yarn_color' => '',
        'hex_color' => '#f79eb8',
        'size' => '',
        'material' => '',
        'design_style' => '',
        'set_quantity' => '',
        'packaging_option' => '',
        'price' => $product->price,
        'stock' => $product->stock,
        'status' => 'active',
        'sku' => '',
    ]]);
@endphp

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-brand-primary">
                Edit Product
            </h1>

            <p class="mt-2 text-sm text-brand-ink/55">
                Update product details.
            </p>
        </div>

        <x-back-button href="{{ route('admin.products.index') }}" label="Back to Products" />

    </div>

    {{-- FORM CARD --}}
    <div class="rounded-4xl border border-brand-border bg-white p-8 shadow-sm">

        <form method="POST"
              action="{{ route('admin.products.update', $product->id) }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                  <label class="text-sm font-medium text-brand-ink/70">Product Name</label>
                <input type="text" name="name"
                       value="{{ $product->name }}"
                      class="mt-2 brand-input"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Description</label>
                <textarea name="description" rows="4"
                          class="mt-2 brand-input">{{ $product->description }}</textarea>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">

                <div>
                          <label class="text-sm font-medium text-brand-ink/70">Price</label>
                    <input type="number" step="0.01" name="price"
                           value="{{ $product->price }}"
                              class="mt-2 brand-input"
                           required>
                </div>

                <div>
                          <label class="text-sm font-medium text-brand-ink/70">Stock</label>
                    <input type="number" name="stock"
                           value="{{ $product->stock }}"
                              class="mt-2 brand-input"
                           required>
                </div>

                <div>
                        <label class="text-sm font-medium text-brand-ink/70">Product Type</label>
                    <select name="product_type"
                            class="mt-2 brand-input">

                        <option value="standard" {{ $product->product_type === 'standard' ? 'selected' : '' }}>
                            Standard
                        </option>

                        <option value="custom" {{ $product->product_type === 'custom' ? 'selected' : '' }}>
                            Custom
                        </option>

                    </select>
                </div>

            </div>

            {{-- CATEGORY --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Category</label>

                <select name="category_id"
                    class="mt-2 brand-input">

                    <option value="">-- None --</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- VARIANTS --}}
            <div class="rounded-4xl border border-brand-border bg-brand-surface p-5 shadow-sm">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <label class="text-sm font-medium text-brand-ink/70">Product Variants</label>
                        <p class="mt-1 text-xs text-brand-ink/55">
                            Update variant name, color, stock, price, and other options for each sellable SKU.
                        </p>
                    </div>

                    <button type="button" id="addVariantRow" class="brand-btn-secondary px-5 py-2 text-sm">
                        Add Variant
                    </button>
                </div>

                <div id="variantRows" class="mt-5 space-y-4" data-next-index="{{ count($variantRows) }}">
                    @foreach ($variantRows as $index => $variant)
                        <div class="variant-row rounded-[1.75rem] border border-brand-border bg-white p-4 shadow-sm">
                            <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">

                            <div class="flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-brand-primary">Variant {{ $index + 1 }}</p>
                                <button type="button" class="remove-variant-row text-xs font-semibold text-red-600">
                                    Remove
                                </button>
                            </div>

                            <div class="mt-4 grid gap-4 lg:grid-cols-3">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Name</label>
                                    <input type="text" name="variants[{{ $index }}][name]" value="{{ $variant['name'] ?? '' }}" class="brand-input" placeholder="Red Tulip" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Color</label>
                                    <input type="text" name="variants[{{ $index }}][yarn_color]" value="{{ $variant['yarn_color'] ?? '' }}" class="brand-input" placeholder="Pink" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Color Hex</label>
                                    <input type="text" name="variants[{{ $index }}][hex_color]" value="{{ $variant['hex_color'] ?? '' }}" class="brand-input" placeholder="#f79eb8" pattern="^#[0-9A-Fa-f]{6}$">
                                </div>
                            </div>

                            <div class="mt-4 grid gap-4 lg:grid-cols-4">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Size</label>
                                    <input type="text" name="variants[{{ $index }}][size]" value="{{ $variant['size'] ?? '' }}" class="brand-input" placeholder="Mini / Small / Medium">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Material</label>
                                    <input type="text" name="variants[{{ $index }}][material]" value="{{ $variant['material'] ?? '' }}" class="brand-input" placeholder="Milk Cotton Yarn">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Style</label>
                                    <input type="text" name="variants[{{ $index }}][design_style]" value="{{ $variant['design_style'] ?? '' }}" class="brand-input" placeholder="With Ribbon">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Set Quantity</label>
                                    <input type="text" name="variants[{{ $index }}][set_quantity]" value="{{ $variant['set_quantity'] ?? '' }}" class="brand-input" placeholder="Single Piece / Set of 3">
                                </div>
                            </div>

                            <div class="mt-4 grid gap-4 lg:grid-cols-[1fr_1fr_1fr_1fr_1fr]">
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Packaging</label>
                                    <input type="text" name="variants[{{ $index }}][packaging_option]" value="{{ $variant['packaging_option'] ?? '' }}" class="brand-input" placeholder="Gift Box">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Price</label>
                                    <input type="number" step="0.01" min="0" name="variants[{{ $index }}][price]" value="{{ $variant['price'] ?? '' }}" class="brand-input" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Stock</label>
                                    <input type="number" min="0" name="variants[{{ $index }}][stock]" value="{{ $variant['stock'] ?? 0 }}" class="brand-input" required>
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">SKU</label>
                                    <input type="text" name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] ?? '' }}" class="brand-input" placeholder="CP-RED-001">
                                </div>
                                <div>
                                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Status</label>
                                    <select name="variants[{{ $index }}][status]" class="brand-input" required>
                                        <option value="active" @selected(($variant['status'] ?? 'active') === 'active')>Active</option>
                                        <option value="inactive" @selected(($variant['status'] ?? '') === 'inactive')>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Image</label>
                                <input type="file" name="variants[{{ $index }}][image]" accept=".jpg,.jpeg,.png,.webp" class="brand-input bg-white px-4 py-3">
                                <p class="mt-2 text-xs text-brand-ink/55">JPG, JPEG, PNG, or WEBP up to 5 MB.</p>

                                @if (! empty($variant['image_path']))
                                    <div class="mt-3">
                                        <img src="{{ \App\Support\ProductImage::url($variant['image_path']) }}"
                                             alt="{{ $variant['name'] ?? 'Variant image' }}"
                                             class="h-24 w-24 rounded-2xl border border-brand-border bg-brand-surface object-contain p-2">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <template id="variantRowTemplate">
                    <div class="variant-row rounded-[1.75rem] border border-brand-border bg-white p-4 shadow-sm">
                        <input type="hidden" name="variants[__INDEX__][id]" value="">
                        <div class="flex items-center justify-between gap-3">
                            <p class="text-sm font-semibold text-brand-primary">Variant __NUMBER__</p>
                            <button type="button" class="remove-variant-row text-xs font-semibold text-red-600">Remove</button>
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-3">
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Name</label><input type="text" name="variants[__INDEX__][name]" class="brand-input" placeholder="Red Tulip" required></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Color</label><input type="text" name="variants[__INDEX__][yarn_color]" class="brand-input" placeholder="Pink" required></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Color Hex</label><input type="text" name="variants[__INDEX__][hex_color]" class="brand-input" placeholder="#f79eb8" pattern="^#[0-9A-Fa-f]{6}$"></div>
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-4">
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Size</label><input type="text" name="variants[__INDEX__][size]" class="brand-input" placeholder="Mini / Small / Medium"></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Material</label><input type="text" name="variants[__INDEX__][material]" class="brand-input" placeholder="Milk Cotton Yarn"></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Style</label><input type="text" name="variants[__INDEX__][design_style]" class="brand-input" placeholder="With Ribbon"></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Set Quantity</label><input type="text" name="variants[__INDEX__][set_quantity]" class="brand-input" placeholder="Single Piece / Set of 3"></div>
                        </div>
                        <div class="mt-4 grid gap-4 lg:grid-cols-[1fr_1fr_1fr_1fr_1fr]">
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Packaging</label><input type="text" name="variants[__INDEX__][packaging_option]" class="brand-input" placeholder="Gift Box"></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Price</label><input type="number" step="0.01" min="0" name="variants[__INDEX__][price]" class="brand-input" required></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Stock</label><input type="number" min="0" name="variants[__INDEX__][stock]" class="brand-input" required></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">SKU</label><input type="text" name="variants[__INDEX__][sku]" class="brand-input" placeholder="CP-RED-001"></div>
                            <div><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Status</label><select name="variants[__INDEX__][status]" class="brand-input" required><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
                        </div>
                        <div class="mt-4"><label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">Variant Image</label><input type="file" name="variants[__INDEX__][image]" accept=".jpg,.jpeg,.png,.webp" class="brand-input bg-white px-4 py-3"><p class="mt-2 text-xs text-brand-ink/55">JPG, JPEG, PNG, or WEBP up to 5 MB.</p></div>
                    </div>
                </template>

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const variantRows = document.getElementById('variantRows');
                        const addVariantButton = document.getElementById('addVariantRow');
                        const template = document.getElementById('variantRowTemplate');

                        const bindRemoveButtons = () => {
                            variantRows.querySelectorAll('.remove-variant-row').forEach((button) => {
                                button.onclick = () => {
                                    const rows = variantRows.querySelectorAll('.variant-row');

                                    if (rows.length <= 1) {
                                        const inputs = button.closest('.variant-row').querySelectorAll('input, select');
                                        inputs.forEach((input) => {
                                            if (input.type === 'hidden') return;
                                            if (input.type === 'checkbox' || input.type === 'radio') {
                                                input.checked = false;
                                                return;
                                            }

                                            input.value = input.tagName === 'SELECT' ? 'active' : '';
                                        });

                                        return;
                                    }

                                    button.closest('.variant-row').remove();
                                };
                            });
                        };

                        addVariantButton?.addEventListener('click', () => {
                            if (!template || !variantRows) return;

                            const nextIndex = parseInt(variantRows.dataset.nextIndex || '0', 10);
                            const markup = template.innerHTML
                                .replaceAll('__INDEX__', String(nextIndex))
                                .replaceAll('__NUMBER__', String(nextIndex + 1));

                            variantRows.insertAdjacentHTML('beforeend', markup);
                            variantRows.dataset.nextIndex = String(nextIndex + 1);
                            bindRemoveButtons();
                        });

                        bindRemoveButtons();
                    });
                </script>
            </div>

            {{-- IMAGE UPLOAD --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Product Image</label>

                <input type="file"
                       name="image"
                      accept=".jpg,.jpeg,.png,.webp"
                       class="mt-2 brand-input bg-white px-4 py-3">

                @if ($product->image)
                    <div class="mt-3">
                        <img src="{{ $product->floating_image_url }}"
                             class="h-24 w-24 rounded-2xl border border-brand-border bg-brand-surface object-contain p-2">
                    </div>
                @endif
            </div>

            {{-- STATUS --}}
            <div class="flex items-center gap-3">

                <input type="checkbox"
                       name="is_active"
                       {{ $product->is_active ? 'checked' : '' }}>

                <label class="text-sm text-brand-ink/70">
                    Active Product
                </label>

            </div>

            {{-- SUBMIT --}}
            <button class="brand-btn-primary w-full py-4 text-sm">
                Update Product
            </button>

        </form>

    </div>

</div>

@endsection
