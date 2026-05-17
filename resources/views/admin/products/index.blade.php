@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="font-display text-4xl font-semibold text-[#4d3028]">
                Product Management
            </h1>

            <p class="mt-2 text-sm text-[#8f7a70]">
                Manage categories and crochet products.
            </p>

        </div>

    </div>

    {{-- ========================= --}}
    {{-- CATEGORY MANAGEMENT --}}
    {{-- ========================= --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                    Categories
                </h2>

                <p class="mt-1 text-sm text-[#8f7a70]">
                    Organize products into categories.
                </p>

            </div>

            {{-- ADD CATEGORY --}}
            <form method="POST"
                  action="{{ route('admin.categories.store') }}"
                  class="flex gap-3">

                @csrf

                <input
                    type="text"
                    name="name"
                    placeholder="New category..."
                    class="rounded-2xl border border-[#eadfd7] px-4 py-2 text-sm focus:outline-none"
                >

                <button
                    class="rounded-2xl bg-[#5d342b] px-5 py-2 text-sm font-semibold text-white">

                    Add Category

                </button>

            </form>

        </div>

        {{-- CATEGORY TABLE --}}
        <div class="mt-6 overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">

                    <tr>

                        <th class="py-3 pr-4">
                            ID
                        </th>

                        <th class="py-3 pr-4">
                            Name
                        </th>

                        <th class="py-3 pr-4">
                            Slug
                        </th>

                        <th class="py-3 pr-4">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-[#efe3da]">

                    @forelse ($categories as $category)

                        <tr>

                            <td class="py-4 pr-4 text-[#6f5a51]">
                                {{ $category->id }}
                            </td>

                            <td class="py-4 pr-4 font-medium text-[#4d3028]">
                                {{ $category->name }}
                            </td>

                            <td class="py-4 pr-4 text-[#8f7a70]">
                                {{ $category->slug }}
                            </td>

                            <td class="py-4 pr-4">

                                <form method="POST"
                                      action="{{ route('admin.categories.destroy', $category->id) }}">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="text-sm font-semibold text-red-600">

                                        Delete

                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="py-6 text-center text-[#8f7a70]">

                                No categories found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- ========================= --}}
    {{-- PRODUCT MANAGEMENT --}}
    {{-- ========================= --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                    Products
                </h2>

                <p class="mt-1 text-sm text-[#8f7a70]">
                    Manage active crochet products and custom items.
                </p>

            </div>

            <a href="{{ route('admin.products.create') }}"
               class="rounded-2xl bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white">

                + Add Product

            </a>

        </div>

        {{-- PRODUCT TABLE --}}
        <div class="mt-6 overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">

                    <tr>

                        <th class="py-3 pr-4">
                            Product
                        </th>

                        <th class="py-3 pr-4">
                            Category
                        </th>

                        <th class="py-3 pr-4">
                            Type
                        </th>

                        <th class="py-3 pr-4">
                            Price
                        </th>

                        <th class="py-3 pr-4">
                            Stock
                        </th>

                        <th class="py-3 pr-4">
                            Status
                        </th>

                        <th class="py-3 pr-4">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-[#efe3da]">

                    @forelse ($products as $product)

                        <tr>

                            {{-- PRODUCT --}}
                            <td class="py-4 pr-4">

                                <div class="flex items-center gap-4">

                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        alt="{{ $product->name }}"
                                        class="h-14 w-14 rounded-2xl object-cover border border-[#eadfd7]">

                                    <div>

                                        <p class="font-semibold text-[#4d3028]">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-xs text-[#8f7a70]">
                                            {{ $product->slug }}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            {{-- CATEGORY --}}
                            <td class="py-4 pr-4 text-[#6f5a51]">
                                {{ $product->category?->name ?? 'N/A' }}
                            </td>

                            {{-- TYPE --}}
                            <td class="py-4 pr-4 text-[#6f5a51]">
                                {{ ucfirst($product->product_type) }}
                            </td>

                            {{-- PRICE --}}
                            <td class="py-4 pr-4 text-[#6f5a51]">
                                ₱ {{ number_format($product->price, 2) }}
                            </td>

                            {{-- STOCK --}}
                            <td class="py-4 pr-4 text-[#6f5a51]">
                                {{ $product->stock }}
                            </td>

                            {{-- STATUS --}}
                            <td class="py-4 pr-4">

                                @if ($product->is_active)

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Active
                                    </span>

                                @else

                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Inactive
                                    </span>

                                @endif

                            </td>

                            {{-- ACTIONS --}}
                            <td class="py-4 pr-4">

                                <div class="flex items-center gap-4">

                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="text-sm font-semibold text-[#a86b57]">

                                        Edit

                                    </a>

                                    <form method="POST"
                                          action="{{ route('admin.products.destroy', $product->id) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="text-sm font-semibold text-red-600">

                                            Delete

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7"
                                class="py-6 text-center text-[#8f7a70]">

                                No products found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection