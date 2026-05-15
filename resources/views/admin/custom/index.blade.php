@extends('layouts.admin')

@section('content')

<div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Custom Requests
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">
                Commission Inbox
            </h1>
        </div>
    </div>

    <div class="mt-8 space-y-4">

        @forelse ($requests as $request)

            <a href="{{ route('admin.custom.show', $request->id) }}"
               class="block rounded-3xl border border-[#efe3da] bg-[#fcfaf8] p-5 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="font-semibold text-[#4d3028]">
                            #{{ $request->id }} — {{ $request->item_type }}
                        </p>

                        <p class="mt-1 text-sm text-[#6f5a51]">
                            {{ $request->name }}
                        </p>
                    </div>

                    <div class="text-right">

                        <p class="font-semibold text-[#8d5848]">
                            PHP {{ number_format($request->estimated_price, 2) }}
                        </p>

                        <p class="mt-1 text-xs uppercase tracking-[0.18em] text-[#8f6a5d]">
                            {{ str_replace('_', ' ', $request->status) }}
                        </p>

                    </div>

                </div>

            </a>

        @empty

            <p class="text-sm text-[#6f5a51]">
                No custom requests yet.
            </p>

        @endforelse

    </div>

</div>

@endsection