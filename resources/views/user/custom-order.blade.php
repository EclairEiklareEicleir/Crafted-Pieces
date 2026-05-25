@extends('layouts.store')

@section('content')

@php
    $currentUser = auth()->user();
    $canSubmit = $currentUser && $currentUser->role === 'user';

    $processSteps = [
        [
            'title' => 'Share your idea',
            'copy' => 'Tell us what you want made, the size, theme, and any special details you have in mind.',
        ],
        [
            'title' => 'We review and estimate',
            'copy' => 'We look at materials, complexity, and timing to prepare a thoughtful quote for you.',
        ],
        [
            'title' => 'Confirm and discuss',
            'copy' => 'You can continue the conversation in your custom order ticket until everything feels right.',
        ],
    ];
@endphp

<section class="relative min-h-screen overflow-hidden">

    <div class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-[#f79eb8]/35 blur-3xl"></div>
        <div class="absolute right-[-5rem] top-24 h-96 w-96 rounded-full bg-white/55 blur-3xl"></div>
        <div class="absolute bottom-[-8rem] left-1/3 h-80 w-80 rounded-full bg-[#cf4f7a]/15 blur-3xl"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8 lg:py-20">

        <div class="mb-6 flex items-center justify-between gap-3">
            <x-back-button href="{{ route('custom-order.index') }}" label="Back to Custom Orders" />
        </div>

        <div class="max-w-3xl">

            <span class="brand-pill bg-white/80 ring-1 ring-brand-border backdrop-blur">
                Custom Commission
            </span>

            <h1 class="mt-4 font-display text-4xl font-semibold tracking-tight text-brand-primary sm:text-5xl">
                Request a Quote
            </h1>

            <p class="mt-4 max-w-2xl text-base leading-7 text-brand-ink/70 sm:text-lg">
                Share your idea and we will reply with a thoughtful estimate, materials guidance, and a private ticket where you can keep the conversation going.
            </p>

        </div>

        <div class="mt-10 grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">

            {{-- LEFT INFO --}}
            <div class="space-y-6">

                <div class="rounded-[2.25rem] border border-brand-border bg-white/80 p-6 shadow-[0_24px_70px_-45px_rgba(101,12,42,0.35)] backdrop-blur-xl sm:p-8">

                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-secondary">
                                How it works
                            </p>

                            <h2 class="mt-2 font-display text-2xl font-semibold text-brand-primary">
                                A gentle custom order flow
                            </h2>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        @foreach ($processSteps as $index => $step)
                            <div class="flex gap-4 rounded-[1.6rem] border border-brand-border bg-white/80 p-4 shadow-sm">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-brand-light text-sm font-semibold text-brand-primary ring-1 ring-brand-border">
                                    {{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}
                                </div>

                                <div>
                                    <p class="font-semibold text-brand-primary">
                                        {{ $step['title'] }}
                                    </p>

                                    <p class="mt-1 text-sm leading-6 text-brand-ink/68">
                                        {{ $step['copy'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                <div class="rounded-[2.25rem] border border-brand-border bg-brand-surface/90 p-6 shadow-[0_20px_60px_-45px_rgba(101,12,42,0.35)] backdrop-blur-xl sm:p-8">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brand-secondary">
                        What to include
                    </p>

                    <ul class="mt-4 space-y-3 text-sm leading-6 text-brand-ink/70">
                        <li>Tell us the item you want, such as a plushie, bouquet, or keychain.</li>
                        <li>Share your preferred size, theme, color palette, and deadline.</li>
                        <li>Add any reference notes so the quote can match your vision more closely.</li>
                        <li>We will keep everything inside your private custom order ticket.</li>
                    </ul>
                </div>

            </div>

            {{-- FORM --}}
            <div class="rounded-[2.5rem] border border-brand-border bg-white/88 p-5 shadow-[0_30px_90px_-50px_rgba(101,12,42,0.45)] backdrop-blur-xl sm:p-8 lg:p-10">

                @if (! $canSubmit)
                    <div class="mb-6 rounded-[1.5rem] border border-brand-border bg-brand-light/35 px-4 py-3 text-sm text-brand-ink/70">
                        Login as a customer to submit a commission request. The form remains visible so you can review the details first.
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('custom-order.submit') }}"
                    @if (! $canSubmit) onsubmit="return false;" @endif
                    enctype="multipart/form-data"
                    class="grid gap-5 sm:grid-cols-2"
                >

                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Full name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $currentUser?->name) }}"
                            placeholder="Full name"
                            class="brand-input bg-white/95"
                        >

                        @error('name')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Email address</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $currentUser?->email) }}"
                            placeholder="Email address"
                            class="brand-input bg-white/95"
                        >

                        @error('email')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">What would you like made?</label>
                        <input
                            type="text"
                            name="item_type"
                            value="{{ old('item_type') }}"
                            placeholder="Example: plushie, bouquet, keychain"
                            class="brand-input bg-white/95"
                        >

                        @error('item_type')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Theme or aesthetic</label>
                        <input
                            type="text"
                            name="design_theme"
                            value="{{ old('design_theme') }}"
                            placeholder="Soft pink, floral, minimal, etc."
                            class="brand-input bg-white/95"
                        >

                        @error('design_theme')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Preferred size</label>
                        <select
                            name="preferred_size"
                            class="brand-input bg-white/95"
                        >
                            <option value="">Preferred size</option>
                            <option value="10cm" @selected(old('preferred_size') === '10cm')>10 cm</option>
                            <option value="20cm" @selected(old('preferred_size') === '20cm')>20 cm</option>
                            <option value="30cm" @selected(old('preferred_size') === '30cm')>30 cm</option>
                            <option value="40cm" @selected(old('preferred_size') === '40cm')>40 cm</option>
                        </select>

                        @error('preferred_size')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Describe your idea</label>

                        <textarea
                            name="description"
                            rows="7"
                            placeholder="Describe colors, details, size, timeline, or any reference notes."
                            class="brand-input min-h-[12rem] resize-none rounded-[1.5rem] bg-white/95"
                        >{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-brand-primary">Reference image</label>

                        <input
                            type="file"
                            name="reference_image"
                            accept="image/jpeg,image/png,image/webp"
                            class="brand-input bg-white/95"
                        >

                        <p class="mt-2 text-xs text-brand-ink/55">
                            Optional. Accepted formats: JPG, JPEG, PNG, and WEBP. Max size: 4MB.
                        </p>

                        @error('reference_image')
                            <p class="mt-1 text-sm text-brand-secondary">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2 rounded-[1.5rem] border border-brand-border bg-brand-light/35 px-4 py-4 text-sm leading-6 text-brand-ink/68">
                        This creates a quotation request and opens a private commission ticket where you can continue the conversation.
                    </div>

                    <div class="sm:col-span-2">
                        @if ($canSubmit)
                            <button
                                type="submit"
                                class="brand-btn-primary w-full rounded-full py-3.5 text-base shadow-lg shadow-brand-primary/15 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-primary/20"
                            >
                                Submit Commission Request
                            </button>
                        @else
                            <button
                                type="button"
                                data-auth-modal-open
                                class="brand-btn-primary w-full rounded-full py-3.5 text-base shadow-lg shadow-brand-primary/15 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand-primary/20"
                            >
                                Submit Commission Request
                            </button>
                        @endif
                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

@endsection