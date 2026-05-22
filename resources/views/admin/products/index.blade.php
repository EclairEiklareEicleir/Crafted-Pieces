@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between">

        <div>

            <h1 class="font-display text-4xl font-semibold text-brand-primary">
                Product Management
            </h1>

            <p class="mt-2 text-sm text-brand-ink/55">
                Manage categories and crochet products.
            </p>

        </div>

    </div>

    {{-- ========================= --}}
    {{-- CATEGORY MANAGEMENT --}}
    {{-- ========================= --}}
    <div class="rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            <div>

                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    Categories
                </h2>

                <p class="mt-1 text-sm text-brand-ink/55">
                    Organize products into categories.
                </p>

            </div>

            {{-- ADD CATEGORY --}}
            <form method="POST"
                action="{{ route('admin.categories.store') }}"
                enctype="multipart/form-data"
                class="grid gap-3 sm:grid-cols-2 lg:min-w-184 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)_auto] lg:items-start">

                @csrf

                <div>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="New category..."
                        class="brand-input"
                    >

                    @error('name')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <input
                        type="file"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="brand-input bg-white px-4 py-3"
                    >

                    <p class="mt-2 text-xs text-brand-ink/55">
                        For best results, upload PNG or WebP images with transparent background (max 10MB).
                    </p>

                    @error('image')
                        <p class="mt-2 text-xs font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    class="brand-btn-primary self-start px-5 py-3 text-sm sm:h-12 lg:mt-0">

                    Add Category

                </button>

            </form>

        </div>

        {{-- CATEGORY TABLE --}}
        <div class="mt-6 space-y-4 md:hidden">

            @forelse ($categories as $category)

                <div class="rounded-[1.75rem] border border-brand-border bg-brand-surface p-4 shadow-sm">

                    <div class="flex items-start gap-4">

                        <div class="brand-checkerboard flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-3xl border border-brand-border p-1">
                            <img src="{{ $category->image_url }}"
                                 alt="{{ $category->name }}"
                                 class="h-full w-full object-contain">
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-base font-semibold text-brand-primary">
                                {{ $category->name }}
                            </p>
                            <p class="mt-1 break-all text-sm text-brand-ink/55">
                                {{ $category->slug }}
                            </p>
                            <p class="mt-2 text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                ID {{ $category->id }}
                            </p>
                        </div>

                    </div>

                    <div class="mt-4 flex flex-wrap gap-2">

                        <button type="button"
                                data-category-modal-open="edit-category-modal-{{ $category->id }}"
                                class="inline-flex items-center justify-center rounded-full border border-brand-secondary/30 bg-white px-4 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/60">
                            Edit
                        </button>

                        <form method="POST"
                              action="{{ route('admin.categories.destroy', $category->id) }}">

                            @csrf
                            @method('DELETE')

                            <button class="inline-flex items-center justify-center rounded-full border border-[#f3b4c7] bg-[#fff7fa] px-4 py-2 text-xs font-semibold text-[#a43d61] transition hover:border-brand-accent hover:bg-white">
                                Delete
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <div class="rounded-[1.75rem] border border-brand-border bg-white p-6 text-center text-brand-ink/55">
                    No categories found.
                </div>

            @endforelse

        </div>

        <div class="mt-6 hidden overflow-x-auto md:block">

            <table class="w-full table-fixed text-left text-sm">

                <colgroup>

                    <col class="w-16">
                    <col class="w-24">
                    <col class="w-[28%]">
                    <col class="w-[32%]">
                    <col>

                </colgroup>

                <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">

                    <tr>

                        <th class="py-3 px-3">
                            ID
                        </th>

                        <th class="py-3 px-3">
                            Preview
                        </th>

                        <th class="py-3 px-3">
                            Name
                        </th>

                        <th class="py-3 px-3">
                            Slug
                        </th>

                        <th class="py-3 px-3 text-right">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-brand-border">

                    @forelse ($categories as $category)

                        <tr>

                            <td class="py-4 px-3 align-middle text-brand-ink/70">
                                {{ $category->id }}
                            </td>

                            <td class="py-4 px-3 align-middle">
                                <div class="brand-checkerboard mx-auto flex h-16 w-16 items-center justify-center overflow-hidden rounded-3xl border border-brand-border p-1">
                                    <img src="{{ $category->image_url }}"
                                         alt="{{ $category->name }}"
                                         class="h-full w-full object-contain">
                                </div>
                            </td>

                            <td class="py-4 px-3 align-middle font-medium text-brand-primary">
                                {{ $category->name }}
                            </td>

                            <td class="py-4 px-3 align-middle text-brand-ink/55">
                                {{ $category->slug }}
                            </td>

                            <td class="py-4 px-3 align-middle">

                                <div class="flex justify-end gap-2">

                                    <button type="button"
                                            data-category-modal-open="edit-category-modal-{{ $category->id }}"
                                            class="inline-flex items-center justify-center rounded-full border border-brand-secondary/30 bg-white px-4 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/60">
                                        Edit
                                    </button>

                                    <form method="POST"
                                          action="{{ route('admin.categories.destroy', $category->id) }}">

                                        @csrf
                                        @method('DELETE')

                                        <button class="inline-flex items-center justify-center rounded-full border border-[#f3b4c7] bg-[#fff7fa] px-4 py-2 text-xs font-semibold text-[#a43d61] transition hover:border-brand-accent hover:bg-white">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5"
                                class="py-6 text-center text-brand-ink/55">

                                No categories found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

        @foreach ($categories as $category)

            <div id="edit-category-modal-{{ $category->id }}"
                 data-category-modal
                 class="fixed inset-0 z-50 hidden items-center justify-center bg-brand-primary/30 px-4 py-6 backdrop-blur-[2px]">

                <div class="absolute inset-0" data-category-modal-close="edit-category-modal-{{ $category->id }}"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-4xl border border-brand-border bg-white shadow-[0_30px_100px_rgba(101,12,42,0.22)]">

                    <div class="flex items-center justify-between border-b border-brand-border bg-brand-surface px-6 py-5 sm:px-8">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Edit category</p>
                            <h3 class="mt-1 text-2xl font-semibold text-brand-primary">{{ $category->name }}</h3>
                        </div>

                        <button type="button"
                                data-category-modal-close="edit-category-modal-{{ $category->id }}"
                                class="brand-icon-button h-10 w-10 shrink-0">
                            <span class="sr-only">Close</span>
                            <span aria-hidden="true" class="text-lg leading-none">&times;</span>
                        </button>
                    </div>

                    <form method="POST"
                          action="{{ route('admin.categories.update', $category) }}"
                          enctype="multipart/form-data"
                          class="grid gap-6 px-6 py-6 sm:px-8">

                        @csrf
                        @method('PUT')

                        <div class="grid gap-5 md:grid-cols-[minmax(0,1fr)_16rem] md:items-start">

                            <div class="space-y-4">

                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">
                                        Name
                                    </label>

                                    <input type="text"
                                           name="name"
                                           value="{{ $category->name }}"
                                           class="mt-2 brand-input">
                                </div>

                                <div>
                                    <label class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">
                                        Image
                                    </label>

                                    <input type="file"
                                           name="image"
                                           accept=".jpg,.jpeg,.png,.webp"
                                           class="mt-2 brand-input bg-white px-4 py-3">

                                    <p class="mt-2 text-xs text-brand-ink/55">
                                        Upload a PNG or WebP cut-out for the cleanest floating preview (max 10MB).
                                    </p>
                                </div>

                            </div>

                            <div class="space-y-3">

                                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">
                                    Current preview
                                </p>

                                <div class="brand-checkerboard flex h-44 items-center justify-center overflow-hidden rounded-[1.75rem] border border-brand-border p-4">
                                    <img src="{{ $category->image_url }}"
                                         alt="{{ $category->name }}"
                                         class="h-full w-full object-contain">
                                </div>

                            </div>

                        </div>

                        <div class="flex flex-wrap justify-end gap-3 border-t border-brand-border pt-5">

                            <button type="button"
                                    data-category-modal-close="edit-category-modal-{{ $category->id }}"
                                    class="brand-btn-secondary px-5 py-3 text-sm">
                                Cancel
                            </button>

                            <button class="brand-btn-primary px-5 py-3 text-sm">
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        @endforeach

    {{-- ========================= --}}
    {{-- YARN COLOR MANAGEMENT --}}
    {{-- ========================= --}}
    <div class="rounded-4xl border border-brand-border bg-white p-5 shadow-sm sm:p-6">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    Yarn Colors
                </h2>

                <p class="mt-1 text-sm text-brand-ink/55">
                    Global colors are available to every product unless a product has its own allowed color list.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.yarn-colors.store') }}"
                  enctype="multipart/form-data"
                  class="grid gap-3 lg:min-w-184 lg:grid-cols-[minmax(0,1fr)_8rem_7rem_auto] lg:items-start">
                @csrf

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Color name"
                       class="brand-input"
                       required>

                <input type="text"
                       name="hex_color"
                       value="{{ old('hex_color') }}"
                       placeholder="#f79eb8"
                       class="brand-input"
                       pattern="^#[0-9A-Fa-f]{6}$">

                <input type="number"
                       name="sort_order"
                       value="{{ old('sort_order', 0) }}"
                       min="0"
                       class="brand-input">

                <button class="brand-btn-primary px-5 py-3 text-sm">
                    Add Color
                </button>

                <div class="lg:col-span-4">
                    <input type="file"
                           name="preview_image"
                           accept=".jpg,.jpeg,.png,.webp"
                           class="brand-input bg-white px-4 py-3">
                    <p class="mt-2 text-xs text-brand-ink/55">
                        Optional preview image for specialty yarns.
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-6 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($yarnColors as $color)
                <div class="rounded-[1.75rem] border border-brand-border bg-brand-surface p-4">
                    <form method="POST"
                          action="{{ route('admin.yarn-colors.update', $color) }}"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="flex items-center gap-3">
                            <span class="h-10 w-10 shrink-0 rounded-full border border-brand-border shadow-sm" style="background-color: {{ $color->hex_color ?? '#ffffff' }}"></span>

                            <div class="grid min-w-0 flex-1 gap-2">
                                <input type="text" name="name" value="{{ $color->name }}" class="brand-input py-2" required>
                                <div class="grid gap-2 sm:grid-cols-2">
                                    <input type="text" name="hex_color" value="{{ $color->hex_color }}" class="brand-input py-2" pattern="^#[0-9A-Fa-f]{6}$">
                                    <input type="number" name="sort_order" value="{{ $color->sort_order }}" min="0" class="brand-input py-2">
                                </div>
                                <input type="file"
                                       name="preview_image"
                                       accept=".jpg,.jpeg,.png,.webp"
                                       class="brand-input bg-white px-3 py-2 text-xs">
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                            <label class="flex items-center gap-2 text-sm text-brand-ink/70">
                                <input type="checkbox" name="is_active" value="1" @checked($color->is_active)>
                                Active
                            </label>

                            <button class="inline-flex items-center justify-center rounded-full border border-brand-secondary/30 bg-white px-4 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/60">
                                Save
                            </button>
                        </div>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.yarn-colors.destroy', $color) }}"
                          class="mt-3 border-t border-brand-border pt-3">
                        @csrf
                        @method('DELETE')

                        <button class="text-xs font-semibold text-red-600">
                            Delete {{ $color->name }}
                        </button>
                    </form>
                </div>
            @empty
                <div class="rounded-[1.75rem] border border-brand-border bg-brand-surface p-6 text-sm text-brand-ink/55">
                    No yarn colors yet.
                </div>
            @endforelse
        </div>
    </div>

    {{-- ========================= --}}
    {{-- PRODUCT MANAGEMENT --}}
    {{-- ========================= --}}
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    Products
                </h2>

                <p class="mt-1 text-sm text-brand-ink/55">
                    Manage active crochet products and custom items.
                </p>

                <div class="mt-4">
                    <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
                </div>

            </div>

                <a href="{{ route('admin.products.create') }}"
                    class="brand-btn-primary px-5 py-3 text-sm">

                + Add Product

            </a>

        </div>

        {{-- PRODUCT TABLE --}}
        <div class="mt-6 overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="text-xs uppercase tracking-[0.16em] text-brand-ink/55">

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

                <tbody class="divide-y divide-brand-border">

                    @forelse ($products as $product)

                        <tr>

                            {{-- PRODUCT --}}
                            <td class="py-4 pr-4">

                                <div class="flex items-center gap-4">

                                    <img src="{{ $product->floating_image_url }}"
                                        alt="{{ $product->name }}"
                                        class="h-14 w-14 rounded-2xl object-contain border border-[#eadfd7] bg-brand-surface p-1">

                                    <div>

                                        <p class="font-semibold text-[#4d3028]">
                                            {{ $product->name }}
                                        </p>

                                        <p class="text-xs text-[#8f7a70]">
                                            {{ $product->slug }}
                                        </p>

                                        <p class="mt-1 text-xs text-brand-ink/55">
                                            {{ $product->yarnColors->isNotEmpty() ? $product->yarnColors->pluck('name')->join(', ') : 'All active yarn colors' }}
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

                                <x-status-badge :status="$product->is_active ? 'active' : 'inactive'" context="toggle" />

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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openButtons = document.querySelectorAll('[data-category-modal-open]');
        const closeTargets = document.querySelectorAll('[data-category-modal-close]');

        const openModal = (modalId) => {
            const modal = document.getElementById(modalId);

            if (!modal) {
                return;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = (modalId) => {
            const modal = document.getElementById(modalId);

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        };

        openButtons.forEach((button) => {
            button.addEventListener('click', () => openModal(button.dataset.categoryModalOpen));
        });

        closeTargets.forEach((button) => {
            button.addEventListener('click', () => closeModal(button.dataset.categoryModalClose));
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') {
                return;
            }

            document.querySelectorAll('[data-category-modal]:not(.hidden)').forEach((modal) => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            document.body.classList.remove('overflow-hidden');
        });
    });
</script>

@endsection
