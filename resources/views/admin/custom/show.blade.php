@extends('layouts.admin')

@section('content')

@php
    $status = strtolower($request->status);
@endphp

<div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">

    {{-- LEFT INFO --}}
    <div class="space-y-6">

        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Request Details
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-[#4d3028]">
                {{ $request->item_type }}
            </h1>

            <div class="mt-6 space-y-4 text-sm text-[#6f5a51]">

                <p><strong>Customer:</strong> {{ $request->name }}</p>
                <p><strong>Email:</strong> {{ $request->email }}</p>
                <p><strong>Theme:</strong> {{ $request->design_theme }}</p>
                <p><strong>Preferred Size:</strong> {{ $request->preferred_size }}</p>

                <p>
                    <strong>Status:</strong>
                    {{ ucfirst($request->status) }}
                </p>

                <p>
                    <strong>Estimated Price:</strong>
                    PHP {{ number_format($request->estimated_price, 2) }}
                </p>

                @if ($request->final_price)
                    <p>
                        <strong>Final Price:</strong>
                        PHP {{ number_format($request->final_price, 2) }}
                    </p>
                @endif

            </div>

            <div class="mt-6 rounded-3xl bg-[#fcfaf8] p-4 text-sm text-[#6f5a51]">
                {{ $request->description }}
            </div>

            {{-- FLOW ACTIONS --}}
            @if (in_array($status, ['pending', 'awaiting_confirmation']))

                <div class="mt-6 rounded-2xl border border-[#f0e4db] bg-[#fcfaf8] p-4">

                    <p class="text-sm font-semibold text-[#4d3028]">
                        Seller Decision Required
                    </p>

                    <p class="mt-1 text-xs text-[#6f5a51]">
                        After discussion and quotation, choose whether to proceed.
                    </p>

                    <div class="mt-4 flex gap-3">

                        {{-- ACCEPT --}}
                        <form method="POST"
                              action="{{ route('admin.custom.accept', $request->id) }}">
                            @csrf
                            <button class="rounded-full bg-green-600 px-5 py-2 text-white">
                                Accept & Proceed
                            </button>
                            
                        </form>

                        {{-- REJECT --}}
                        <form method="POST"
                              action="{{ route('admin.custom.reject', $request->id) }}">
                            @csrf
                            <button class="rounded-full bg-red-600 px-5 py-2 text-white">
                                Decline
                            </button>
                        </form>

                    </div>

                </div>

            @endif

        </div>

        {{-- QUOTE FORM --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                Send Quotation
            </h2>

            <form method="POST"
                  action="{{ route('admin.custom.quote', $request->id) }}"
                  class="mt-6 space-y-4">

                @csrf

                <input
                    type="number"
                    step="0.01"
                    name="final_price"
                    value="{{ old('final_price', $request->final_price) }}"
                    placeholder="Final quotation price"
                    class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    required
                >

                <textarea
                    name="admin_notes"
                    rows="4"
                    placeholder="Notes for customer"
                    class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                >{{ old('admin_notes', $request->admin_notes) }}</textarea>

                <button class="w-full rounded-full bg-[#5d342b] py-3 text-white">
                    Send Quotation
                </button>

            </form>

        </div>

    </div>

    {{-- RIGHT CHAT --}}
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
            Conversation
        </h2>

        <div class="mt-6 space-y-4">

            @forelse ($request->messages as $message)

                <div class="rounded-3xl bg-[#fcfaf8] p-4">

                    <div class="flex items-center justify-between">

                        <p class="font-semibold text-[#4d3028]">
                            {{ $message->user->name }}
                        </p>

                        <p class="text-xs text-[#8f6a5d]">
                            {{ $message->created_at->diffForHumans() }}
                        </p>

                    </div>

                    <p class="mt-3 text-sm leading-6 text-[#6f5a51]">
                        {{ $message->message }}
                    </p>

                </div>

            @empty

                <p class="text-sm text-[#6f5a51]">
                    No messages yet.
                </p>

            @endforelse

        </div>

        {{-- MESSAGE FORM --}}
        <form method="POST"
              action="{{ route('admin.custom.message', $request->id) }}"
              class="mt-6">

            @csrf

            <textarea
                name="message"
                rows="4"
                placeholder="Reply to customer..."
                class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                required></textarea>

            <button class="mt-4 rounded-full bg-[#5d342b] px-6 py-3 text-white">
                Send Reply
            </button>

        </form>

    </div>

</div>

@endsection