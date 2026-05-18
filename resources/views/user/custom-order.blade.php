@extends('layouts.store')

@section('content')

<section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">

    <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">

        {{-- LEFT INFO --}}
        <div>

            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Custom Commission
            </p>

            <h1 class="mt-2 font-display text-4xl font-semibold text-brand-primary">
                Request a Quote
            </h1>

            <p class="mt-4 text-brand-ink/70">
                Submit your idea and we will reply with a
                <strong>price estimate, materials breakdown, and timeline</strong>.
                This is not an order yet — it becomes a commission only after confirmation.
            </p>

            <div class="mt-8 rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">

                <h2 class="font-display text-2xl font-semibold text-brand-primary">
                    How it works
                </h2>

                <ul class="mt-4 space-y-3 text-sm leading-6 text-brand-ink/70">
                    <li>1. Submit your commission request</li>
                    <li>2. Owner reviews your request</li>
                    <li>3. Discuss details through the commission chatroom</li>
                    <li>4. Receive a final quotation</li>
                    <li>5. Accept quotation to begin production</li>
                </ul>

            </div>

            <div class="mt-6 rounded-[2rem] border border-brand-border bg-brand-light/35 p-6 text-sm text-brand-ink/70">

                <p class="font-semibold text-brand-primary">
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
        <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">

            @php
                $currentUser = auth()->user();
                $canSubmit = $currentUser && $currentUser->role === 'user';
            @endphp

            @if (! $canSubmit)

                <p class="mb-4 text-sm text-brand-ink/70">
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
                        class="brand-input"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $currentUser?->email) }}"
                        placeholder="Email address"
                        class="brand-input"
                    >

                    @error('email')
                        <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ITEM TYPE --}}
                <div class="sm:col-span-2">
                    <input
                        type="text"
                        name="item_type"
                        value="{{ old('item_type') }}"
                        placeholder="What do you want? (e.g. plushie, bouquet, keychain)"
                        class="brand-input"
                    >

                    @error('item_type')
                        <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                    @enderror
                </div>

                {{-- THEME --}}
                <div>
                    <input
                        type="text"
                        name="design_theme"
                        value="{{ old('design_theme') }}"
                        placeholder="Theme / aesthetic"
                        class="brand-input"
                    >

                    @error('design_theme')
                        <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SIZE --}}
                <div>
                    <select
                        name="preferred_size"
                        class="brand-input"
                    >
                        <option value="">Preferred size</option>
                        <option value="10cm">10 cm</option>
                        <option value="20cm">20 cm</option>
                        <option value="30cm">30 cm</option>
                        <option value="40cm">40 cm</option>
                    </select>

                    @error('preferred_size')
                        <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
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
                <p class="sm:col-span-2 text-xs text-brand-secondary">
                    This creates a quotation request and opens a commission discussion ticket.
                </p>

                {{-- BUTTON --}}
                @if ($canSubmit)

                    <button
                        type="submit"
                        class="brand-btn-primary sm:col-span-2"
                    >
                        Submit Commission Request
                    </button>

                @else

                    <button
                        type="button"
                        data-auth-modal-open
                        class="brand-btn-primary sm:col-span-2"
                    >
                        Submit Commission Request
                    </button>

                @endif

            </form>

        </div>

    </div>

</section>

@endsection