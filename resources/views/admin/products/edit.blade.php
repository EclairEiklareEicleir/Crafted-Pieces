@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-[#4d3028]">
                Edit Product
            </h1>

            <p class="mt-2 text-sm text-[#8f7a70]">
                Update product details.
            </p>
        </div>

        <a href="{{ route('admin.products.index') }}"
           class="rounded-2xl border border-[#eadfd7] bg-[#fcfaf8] px-5 py-3 text-sm font-semibold text-[#5d342b]">
            ← Back
        </a>

    </div>

    {{-- FORM CARD --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-8 shadow-sm">

        <form method="POST"
              action="{{ route('admin.products.update', $product->id) }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="text-sm font-medium text-[#6f5a51]">Product Name</label>
                <input type="text" name="name"
                       value="{{ $product->name }}"
                       class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="text-sm font-medium text-[#6f5a51]">Description</label>
                <textarea name="description" rows="4"
                          class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm">{{ $product->description }}</textarea>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">

                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Price</label>
                    <input type="number" step="0.01" name="price"
                           value="{{ $product->price }}"
                           class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                           required>
                </div>

                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Stock</label>
                    <input type="number" name="stock"
                           value="{{ $product->stock }}"
                           class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                           required>
                </div>

                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Product Type</label>
                    <select name="product_type"
                            class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm">

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
                <label class="text-sm font-medium text-[#6f5a51]">Category</label>

                <select name="category_id"
                        class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm">

                    <option value="">-- None --</option>

                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach

                </select>
            </div>

            {{-- IMAGE UPLOAD --}}
            <div>
                <label class="text-sm font-medium text-[#6f5a51]">Product Image</label>

                <input type="file"
                       name="image"
                       accept="image/*"
                       class="mt-2 w-full rounded-2xl border border-[#eadfd7] bg-white px-4 py-3 text-sm">

                @if ($product->image)
                    <div class="mt-3">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="h-24 w-24 rounded-2xl object-cover border border-[#eadfd7]">
                    </div>
                @endif
            </div>

            {{-- STATUS --}}
            <div class="flex items-center gap-3">

                <input type="checkbox"
                       name="is_active"
                       {{ $product->is_active ? 'checked' : '' }}>

                <label class="text-sm text-[#6f5a51]">
                    Active Product
                </label>

            </div>

            {{-- SUBMIT --}}
            <button class="w-full rounded-2xl bg-[#5d342b] py-4 text-sm font-semibold text-white">
                Update Product
            </button>

        </form>

    </div>

</div>

@endsection