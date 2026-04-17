@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Categories</h2>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
            @foreach ($categories as $category)
                <div class="rounded-3xl bg-[#fcfaf8] p-5">
                    <div class="text-3xl">{{ $category['emoji'] ?? '#' }}</div>
                    <p class="mt-3 font-semibold text-[#4d3028]">{{ $category['name'] }}</p>
                    <p class="mt-1 text-sm text-[#6f5a51]">{{ $category['count'] }} products</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
