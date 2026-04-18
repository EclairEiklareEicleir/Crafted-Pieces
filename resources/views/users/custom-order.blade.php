@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Custom work</p>
                <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">Request a quotation</h1>
                <p class="mt-4 text-[#6f5a51]">Tell us what you want and we will reply with a price, timeline, and material notes.</p>
                <div class="mt-8 rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                    <h2 class="font-display text-2xl font-semibold text-[#4d3028]">What to include</h2>
                    <ul class="mt-4 space-y-3 text-sm leading-6 text-[#6f5a51]">
                        <li>- Item type and size</li>
                        <li>- Preferred colors and theme</li>
                        <li>- Deadline or event date</li>
                        <li>- Reference photos if available</li>
                    </ul>
                </div>
            </div>
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                @php
                    $currentUser = auth()->user();
                    $canSubmitCustomOrder = $currentUser && $currentUser->role === 'customer';
                @endphp

                @if (! $canSubmitCustomOrder)
                    <p class="mb-4 text-sm text-[#6f5a51]">Log in as a customer to submit a quotation request.</p>
                @endif

                <form class="grid gap-4 sm:grid-cols-2" method="POST" action="{{ $canSubmitCustomOrder ? route('custom-order.submit') : '#' }}">
                    @csrf
                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="name" value="{{ old('name', $currentUser?->name) }}" placeholder="Full name">
                    @error('name')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="email" value="{{ old('email', $currentUser?->email) }}" placeholder="Email address">
                    @error('email')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3 sm:col-span-2" name="item_type" value="{{ old('item_type') }}" placeholder="Item type">
                    @error('item_type')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="design_theme" value="{{ old('design_theme') }}" placeholder="Design theme">
                    @error('design_theme')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    <input class="rounded-2xl border border-[#eadfd7] px-4 py-3" name="preferred_size" value="{{ old('preferred_size') }}" placeholder="Preferred size">
                    @error('preferred_size')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    <textarea class="rounded-2xl border border-[#eadfd7] px-4 py-3 sm:col-span-2" name="description" rows="5" placeholder="Describe your idea">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-700 sm:col-span-2">{{ $message }}</p>
                    @enderror

                    @if ($canSubmitCustomOrder)
                        <button type="submit" class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 sm:col-span-2">Send quotation request</button>
                    @else
                        <button type="button" data-auth-modal-open class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 sm:col-span-2">Send quotation request</button>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
