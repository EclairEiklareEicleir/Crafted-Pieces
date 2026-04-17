@extends('admin.layouts.admin')

@section('content')
    <div class="grid gap-6 xl:grid-cols-[0.95fr_1.05fr]">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Store settings</h2>
            <form class="mt-6 space-y-4" method="POST" action="#">
                @csrf
                <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" placeholder="Store name" value="the_crafted_pieces">
                <input class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3" placeholder="Support email" value="hello@thecraftedpieces.com">
                <select class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3">
                    <option>GCash</option>
                    <option>Maya</option>
                    <option>Bank Transfer</option>
                </select>
                <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white">Save settings</button>
            </form>
        </div>
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h3 class="font-display text-2xl font-semibold text-[#4d3028]">Operational notes</h3>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-[#6f5a51]">
                <li>- This dashboard is powered by Laravel Blade.</li>
                <li>- Data currently comes from local PHP arrays.</li>
                <li>- You can swap to Eloquent models without changing layout structure.</li>
            </ul>
        </div>
    </div>
@endsection
