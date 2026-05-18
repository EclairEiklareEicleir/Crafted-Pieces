@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-brand-primary">
                Create Product
            </h1>

            <p class="mt-2 text-sm text-brand-ink/55">
                Add a new crochet product to your catalog.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="brand-btn-secondary px-5 py-3 text-sm">
            ← Back
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="rounded-4xl border border-brand-border bg-white p-8 shadow-sm">

        <form method="POST"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf

            {{-- NAME --}}
            <div>
                  <label class="text-sm font-medium text-brand-ink/70">Product Name</label>
                <input type="text" name="name"
                      class="mt-2 brand-input"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Description</label>
                <textarea name="description" rows="4"
                          class="mt-2 brand-input"></textarea>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">

                {{-- PRICE --}}
                <div>
                          <label class="text-sm font-medium text-brand-ink/70">Price</label>
                    <input type="number" step="0.01" name="price"
                              class="mt-2 brand-input"
                           required>
                </div>

                {{-- STOCK --}}
                <div>
                          <label class="text-sm font-medium text-brand-ink/70">Stock</label>
                    <input type="number" name="stock"
                              class="mt-2 brand-input"
                           required>
                </div>

                {{-- TYPE --}}
                <div>
                        <label class="text-sm font-medium text-brand-ink/70">Product Type</label>
                    <select name="product_type"
                            class="mt-2 brand-input"
                            required>

                        <option value="standard">Standard</option>
                        <option value="custom">Custom</option>

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
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- IMAGE UPLOAD --}}
            <div>
                <label class="text-sm font-medium text-brand-ink/70">Product Image</label>

                <input type="file"
                       name="image"
                       accept="image/*"
                       class="mt-2 brand-input bg-white px-4 py-3">

                <p class="mt-2 text-xs text-brand-ink/55">
                    Upload JPG, PNG or WEBP (max 2MB)
                </p>
            </div>

            {{-- SUBMIT --}}
            <button class="brand-btn-primary w-full py-4 text-sm">
                Create Product
            </button>

        </form>

    </div>

</div>

@endsection