@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">

        <div>
            <h1 class="font-display text-4xl font-semibold text-[#4d3028]">
                Create Product
            </h1>

            <p class="mt-2 text-sm text-[#8f7a70]">
                Add a new crochet product to your catalog.
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
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">

            @csrf

            {{-- NAME --}}
            <div>
                <label class="text-sm font-medium text-[#6f5a51]">Product Name</label>
                <input type="text" name="name"
                       class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm focus:outline-none"
                       required>
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <label class="text-sm font-medium text-[#6f5a51]">Description</label>
                <textarea name="description" rows="4"
                          class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm focus:outline-none"></textarea>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">

                {{-- PRICE --}}
                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Price</label>
                    <input type="number" step="0.01" name="price"
                           class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                           required>
                </div>

                {{-- STOCK --}}
                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Stock</label>
                    <input type="number" name="stock"
                           class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                           required>
                </div>

                {{-- TYPE --}}
                <div>
                    <label class="text-sm font-medium text-[#6f5a51]">Product Type</label>
                    <select name="product_type"
                            class="mt-2 w-full rounded-2xl border border-[#eadfd7] px-4 py-3 text-sm"
                            required>

                        <option value="standard">Standard</option>
                        <option value="custom">Custom</option>

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
                        <option value="{{ $category->id }}">
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

                <p class="mt-2 text-xs text-[#8f7a70]">
                    Upload JPG, PNG or WEBP (max 2MB)
                </p>
            </div>

            {{-- SUBMIT --}}
            <button class="w-full rounded-2xl bg-[#5d342b] py-4 text-sm font-semibold text-white">
                Create Product
            </button>

        </form>

    </div>

</div>

@endsection