@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">

        {{-- LEFT INFO --}}
        <div>

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">
                Custom Commission
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">
                Request a Quote
            </h1>

            <p class="mt-4 text-[#6f5a51]">
                Submit your idea and we will reply with a
                <strong>price estimate, materials breakdown, and timeline</strong>.
                This is not an order yet — it becomes a commission only after confirmation.
            </p>

            <div class="mt-8 rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
                    How it works
                </h2>

                <ul class="mt-4 space-y-3 text-sm leading-6 text-[#6f5a51]">
                    <li>1. Submit your commission request</li>
                    <li>2. Owner reviews your request</li>
                    <li>3. Discuss details through the commission chatroom</li>
                    <li>4. Receive a final quotation</li>
                    <li>5. Accept quotation to begin production</li>
                </ul>

            </div>

            <div class="mt-6 rounded-[2rem] border border-[#f0e4db] bg-[#fcfaf8] p-6 text-sm text-[#6f5a51]">

                <p class="font-semibold text-[#4d3028]">
                    Estimated pricing includes:
                </p>

                <ul class="mt-2 list-disc pl-5">
                    <li>Material cost</li>
                    <li>Labor complexity</li>
                    <li>Preferred size</li>
                    <li>Design difficulty</li>
                    <li>Shipping fee (final stage)</li>
                </ul>

            </div>

        </div>

        {{-- FORM --}}
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

            @php
                $currentUser = auth()->user();
                $canSubmit = $currentUser && $currentUser->role === 'user';
            @endphp

            @if (! $canSubmit)

                <p class="mb-4 text-sm text-[#6f5a51]">
                    Login as a customer to submit a commission request.
                </p>

            @endif

            <form
                method="POST"
                action="{{ $canSubmit ? route('custom-order.submit') : '#' }}"
                class="grid gap-4 sm:grid-cols-2"
            >

                @csrf

                {{-- NAME --}}
                <div>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $currentUser?->name) }}"
                        placeholder="Full name"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $currentUser?->email) }}"
                        placeholder="Email address"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ITEM TYPE --}}
                <div class="sm:col-span-2">
                    <input
                        type="text"
                        name="item_type"
                        value="{{ old('item_type') }}"
                        placeholder="What do you want? (e.g. plushie, bouquet, keychain)"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >

                    @error('item_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- THEME --}}
                <div>
                    <input
                        type="text"
                        name="design_theme"
                        value="{{ old('design_theme') }}"
                        placeholder="Theme / aesthetic"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >

                    @error('design_theme')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SIZE --}}
                <div>
                    <select
                        name="preferred_size"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >
                        <option value="">Preferred size</option>
                        <option value="10cm">10 cm</option>
                        <option value="20cm">20 cm</option>
                        <option value="30cm">30 cm</option>
                        <option value="40cm">40 cm</option>
                    </select>

                    @error('preferred_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="sm:col-span-2">

                    <textarea
                        name="description"
                        rows="5"
                        placeholder="Describe your idea in detail"
                        class="w-full rounded-2xl border border-[#eadfd7] px-4 py-3"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                </div>

                {{-- NOTICE --}}
                <p class="sm:col-span-2 text-xs text-[#8f6a5d]">
                    This creates a quotation request and opens a commission discussion ticket.
                </p>

                {{-- BUTTON --}}
                @if ($canSubmit)

                    <button
                        type="submit"
                        class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 sm:col-span-2"
                    >
                        Submit Commission Request
                    </button>

                @else

                    <button
                        type="button"
                        data-auth-modal-open
                        class="rounded-full bg-[#5d342b] px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 sm:col-span-2"
                    >
                        Submit Commission Request
                    </button>

                @endif

            </form>

        </div>

    </div>

</section>

@endsection