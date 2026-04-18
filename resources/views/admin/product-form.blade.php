@extends('admin.layouts.admin')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-4xl border border-[#eadfd7] bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">{{ $mode === 'edit' ? 'Edit Product' : 'Add Product' }}</h2>
                <a href="{{ route('admin.products') }}" class="rounded-full border border-[#eadfd7] bg-white px-4 py-2 text-sm font-semibold text-[#5d342b]">Back</a>
            </div>
            @if (session('status'))
                <p class="mt-4 rounded-2xl border border-[#e7d6cb] bg-[#fcfaf8] px-4 py-3 text-sm text-[#5d342b]">{{ session('status') }}</p>
            @endif

            <form class="mt-6 space-y-4" method="POST" action="{{ $mode === 'edit' ? route('admin.products.update', $product['slug']) : route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                @if ($mode === 'edit')
                    @method('PATCH')
                @endif

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="name">Product name</label>
                    <input id="name" name="name" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" value="{{ old('name', $product['name'] ?? '') }}">
                    @error('name')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="slug">Slug</label>
                    <input id="slug" name="slug" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" value="{{ old('slug', $product['slug'] ?? '') }}">
                    @error('slug')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="short_description">Short description</label>
                    <textarea id="short_description" name="short_description" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" rows="3">{{ old('short_description', $product['short_description'] ?? '') }}</textarea>
                    @error('short_description')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="description">Description</label>
                    <textarea id="description" name="description" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" rows="5">{{ old('description', $product['description'] ?? '') }}</textarea>
                    @error('description')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="category_id">Category</label>
                        <select id="category_id" name="category_id" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3">
                            <option value="">Select category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category['id'] }}" {{ (string) old('category_id', $product['category_id'] ?? '') === (string) $category['id'] ? 'selected' : '' }}>{{ $category['name'] }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="price">Price</label>
                        <input id="price" name="price" type="number" min="0" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" value="{{ old('price', $product['price'] ?? '') }}">
                        @error('price')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="stock">Stock</label>
                        <input id="stock" name="stock" type="number" min="0" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" value="{{ old('stock', $product['stock'] ?? 0) }}">
                        @error('stock')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="status">Status</label>
                        <select id="status" name="status" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3">
                            @foreach (['active', 'inactive', 'discontinued'] as $status)
                                <option value="{{ $status }}" {{ old('status', $product['status'] ?? 'active') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="fulfillment">Fulfillment</label>
                        <select id="fulfillment" name="fulfillment" class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3">
                            <option value="ready-stock" {{ old('fulfillment', $product['fulfillment'] ?? 'ready-stock') === 'ready-stock' ? 'selected' : '' }}>Ready stock</option>
                            <option value="made-to-order" {{ old('fulfillment', $product['fulfillment'] ?? '') === 'made-to-order' ? 'selected' : '' }}>Made to order</option>
                        </select>
                        @error('fulfillment')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-[#5d342b]" for="image">Product image {{ $mode === 'edit' ? '(optional)' : '' }}</label>
                        <input id="image" name="image" type="file" accept="image/*" class="w-full rounded-2xl border border-[#eadfd7] bg-white px-4 py-3 text-sm">
                        @error('image')<p class="mt-2 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>

                @if (! empty($product['image']))
                    <div class="rounded-3xl border border-[#eadfd7] bg-[#fcfaf8] p-4">
                        <p class="text-sm font-semibold text-[#5d342b]">Current image</p>
                        <img src="{{ $product['image'] }}" alt="{{ $product['name'] ?? 'Product image' }}" class="mt-3 h-48 w-full rounded-2xl object-cover">
                    </div>
                @endif

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white">Save product</button>
                    <a href="{{ route('admin.products') }}" class="rounded-full border border-[#eadfd7] bg-white px-5 py-3 text-sm font-semibold text-[#5d342b]">Back</a>
                </div>
            </form>

            @if ($mode === 'edit')
                <form method="POST" action="{{ route('admin.products.destroy', $product['slug']) }}" class="mt-4" data-confirm-delete="Delete this product? This will remove the uploaded image file too.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-full border border-[#e7d6cb] bg-white px-5 py-3 text-sm font-semibold text-[#8d5848]">Delete product</button>
                </form>
            @endif
        </div>
        <div class="rounded-4xl border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h3 class="font-display text-2xl font-semibold text-[#4d3028]">Supported categories</h3>
            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach ($categories as $category)
                    <div class="rounded-3xl bg-[#fcfaf8] p-4">
                        <p class="font-semibold text-[#4d3028]">{{ $category['name'] }}</p>
                        <p class="mt-1 text-sm text-[#6f5a51]">{{ $category['count'] }} items</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
