@extends('admin.layouts.admin')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">{{ $mode === 'edit' ? 'Edit Product' : 'Add Product' }}</h2>
            <form class="mt-6 space-y-4" method="POST" action="#">
                @csrf
                <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" placeholder="Product name" value="{{ $product['name'] ?? '' }}">
                <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" placeholder="Slug" value="{{ $product['slug'] ?? '' }}">
                <textarea class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" rows="5" placeholder="Description">{{ $product['description'] ?? '' }}</textarea>
                <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white">Save product</button>
            </form>
        </div>
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
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
