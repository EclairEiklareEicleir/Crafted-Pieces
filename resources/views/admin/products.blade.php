@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Products</h2>
                <p class="mt-2 text-sm text-[#6f5a51]">Product catalog from local showcase data.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="rounded-full bg-[#5d342b] px-4 py-2 text-sm font-semibold text-white">Add product</a>
        </div>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">
                    <tr>
                        <th class="py-3 pr-4">Name</th>
                        <th class="py-3 pr-4">Category</th>
                        <th class="py-3 pr-4">Price</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe3da]">
                    @foreach ($products as $product)
                        <tr>
                            <td class="py-4 pr-4 font-medium text-[#4d3028]">{{ $product['name'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $product['category'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">PHP {{ number_format($product['price']) }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $product['status'] ?? 'active' }}</td>
                            <td class="py-4 pr-4"><a href="{{ route('admin.products.edit', $product['slug']) }}" class="font-semibold text-[#a86b57]">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
