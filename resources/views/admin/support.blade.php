@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Support</h2>
        <div class="mt-6 space-y-4">
            @foreach ($faq as $item)
                <details class="rounded-3xl border border-[#f0e4db] bg-[#fcfaf8] p-4">
                    <summary class="cursor-pointer font-semibold text-[#4d3028]">{{ $item['q'] }}</summary>
                    <p class="mt-3 text-sm leading-6 text-[#6f5a51]">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
@endsection
