@extends('layouts.admin')

@section('content')

<div class="space-y-8">

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

            {{-- YARN COLORS --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Allowed Yarn Colors</label>
                <p class="mt-1 text-xs text-brand-ink/55">
                    Leave all unchecked to allow every active global yarn color.
                </p>

                @php
                    $selectedColorIds = old('yarn_color_ids', $product->yarnColors->pluck('id')->all());
                @endphp

                <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($yarnColors as $color)
                        <label class="flex cursor-pointer items-center gap-3 rounded-2xl border border-brand-border bg-brand-surface px-4 py-3 text-sm transition hover:border-brand-secondary">
                            <input type="checkbox"
                                   name="yarn_color_ids[]"
                                   value="{{ $color->id }}"
                                   @checked(in_array($color->id, $selectedColorIds))>
                            <span class="h-5 w-5 rounded-full border border-brand-border" style="background-color: {{ $color->hex_color ?? '#ffffff' }}"></span>
                            <span class="font-semibold text-brand-primary">{{ $color->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- IMAGE UPLOAD --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Product Image</label>

                <input type="file"
                       name="image"
                       accept="image/*"
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
