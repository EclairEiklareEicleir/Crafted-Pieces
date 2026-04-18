@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-4xl border border-[#eadfd7] bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Products</h2>
                <p class="mt-2 text-sm text-[#6f5a51]">Product catalog from local showcase data.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="rounded-full bg-[#5d342b] px-4 py-2 text-sm font-semibold text-white">Add product</a>
        </div>
        @if (session('status'))
            <p class="mt-4 rounded-2xl border border-[#e7d6cb] bg-[#fcfaf8] px-4 py-3 text-sm text-[#5d342b]">{{ session('status') }}</p>
        @endif

        <form method="GET" action="{{ route('admin.products') }}" class="mt-5 grid gap-3 rounded-3xl border border-[#efe3da] bg-[#fcfaf8] p-4 md:grid-cols-[1.4fr_1fr_1fr_auto] md:items-end">
            <div>
                <label for="q" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-[#8f7a70]">Search</label>
                <input id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search by name or slug" class="w-full rounded-2xl border border-[#eadfd7] bg-white px-4 py-2.5 text-sm">
            </div>
            <div>
                <label for="category" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-[#8f7a70]">Category</label>
                <select id="category" name="category" class="w-full rounded-2xl border border-[#eadfd7] bg-white px-4 py-2.5 text-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['slug'] }}" {{ ($filters['category'] ?? '') === $category['slug'] ? 'selected' : '' }}>{{ $category['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-[#8f7a70]">Status</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-[#eadfd7] bg-white px-4 py-2.5 text-sm">
                    <option value="">All status</option>
                    @foreach (['active', 'inactive', 'discontinued'] as $status)
                        <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-full bg-[#5d342b] px-4 py-2 text-sm font-semibold text-white">Apply</button>
                @if (!empty($filters['q']) || !empty($filters['category']) || !empty($filters['status']))
                    <a href="{{ route('admin.products') }}" class="rounded-full border border-[#eadfd7] bg-white px-4 py-2 text-sm font-semibold text-[#5d342b]">Reset</a>
                @endif
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">
                    <tr>
                        <th class="py-3 pr-4">Image</th>
                        <th class="py-3 pr-4">Name</th>
                        <th class="py-3 pr-4">Category</th>
                        <th class="py-3 pr-4">Price</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe3da]">
                    @forelse ($products as $product)
                        <tr>
                            <td class="py-4 pr-4">
                                <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-14 w-14 rounded-2xl object-cover">
                            </td>
                            <td class="py-4 pr-4 font-medium text-[#4d3028]">{{ $product['name'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $product['category'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">PHP {{ number_format($product['price']) }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $product['status'] ?? 'active' }}</td>
                            <td class="py-4 pr-4">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('admin.products.edit', $product['slug']) }}" class="font-semibold text-[#a86b57]">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product['slug']) }}" data-confirm-delete="Delete this product? This will remove the uploaded image file too.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-[#8d5848]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-sm text-[#6f5a51]">No products match the current search/filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
