@extends('layouts.store')

@section('content')

    {{-- HERO --}}
    <section class="mx-auto max-w-7xl px-4 pb-4 pt-14 sm:px-6 lg:px-8">

        <div class="overflow-hidden rounded-[2rem] border border-white/70 bg-white/90 shadow-[0_24px_80px_rgba(101,12,42,0.10)]">

            <div class="grid gap-10 px-6 py-14 lg:grid-cols-[1.15fr_0.85fr] lg:px-12">

                <div>

                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-brand-secondary">
                        Handmade Crochet Art
                    </p>

                    <h1 class="mt-4 max-w-2xl font-display text-4xl font-semibold leading-tight text-brand-primary sm:text-5xl lg:text-6xl">
                        Every Stitch, Crafted with Love
                    </h1>

                    <p class="mt-6 max-w-2xl text-base leading-7 text-brand-ink/75">
                        Welcome to Crafted Pieces, your destination for handmade crochet creations and custom pieces.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">

                        <a href="{{ route('shop') }}"
                           class="brand-btn-primary px-6 py-3 text-sm shadow-md shadow-brand-primary/20 hover:-translate-y-0.5">
                            Shop Crochet
                        </a>

                        @auth
                            <a href="{{ route('custom-order') }}"
                               class="brand-btn-secondary px-6 py-3 text-sm shadow-sm hover:-translate-y-0.5">
                                Request Custom Crochet
                            </a>
                        @endauth

                    </div>

                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">

                    <div class="rounded-[1.75rem] bg-gradient-to-br from-brand-light to-white p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">Bestsellers</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-brand-primary">
                            Handmade pieces with strong character.
                        </p>
                    </div>

                    <div class="rounded-[1.75rem] bg-gradient-to-br from-white to-brand-light p-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-primary">Custom Work</p>
                        <p class="mt-3 font-display text-2xl font-semibold text-brand-primary">
                            One-of-one crochet gifts made to order.
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- SHOP ENTRY (CATEGORIES) --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between gap-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Start exploring
                </p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    Browse categories
                </h2>
            </div>

            <a href="{{ route('shop') }}"
               class="text-sm font-semibold text-brand-secondary transition hover:text-brand-primary">
                View all
            </a>

        </div>

        <div class="mt-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">

            @foreach ($categories as $category)

                <a href="{{ route('shop', ['category' => $category['slug']]) }}"
                   class="group relative overflow-visible rounded-[2rem] border border-[#f3c5d6] bg-gradient-to-br from-[#fff7fb] via-[#fde4ee] to-[#f9d4e0] p-6 pt-16 text-left shadow-[0_18px_50px_rgba(101,12,42,0.10)] transition-all duration-300 hover:-translate-y-2 hover:shadow-[0_28px_70px_rgba(101,12,42,0.18)]">

                    <div class="relative z-10 max-w-[12rem] pr-20 sm:pr-24">

                        <p class="text-lg font-semibold leading-tight text-brand-primary">
                            {{ $category['name'] }}
                        </p>

                        <p class="mt-2 text-sm text-brand-ink/60">
                            {{ $category['count'] }} items
                        </p>

                    </div>

                    <div class="pointer-events-none absolute right-[-0.5rem] top-[-2rem] z-20 w-28 transition-transform duration-300 group-hover:-rotate-6 group-hover:scale-105 sm:right-[-0.75rem] sm:top-[-2.25rem] sm:w-32">

                        <img src="{{ $category['image_url'] }}"
                             alt="{{ $category['name'] }}"
                             class="h-full w-full object-contain drop-shadow-[0_20px_25px_rgba(101,12,42,0.18)]">

                    </div>

                </a>

            @endforeach

        </div>

    </section>

    {{-- FEATURED PRODUCTS --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

        <div class="flex items-end justify-between gap-4">

            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    Featured
                </p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    Bestsellers
                </h2>
            </div>

            <a href="{{ route('shop') }}"
               class="text-sm font-semibold text-brand-secondary transition hover:text-brand-primary">
                Shop all
            </a>

        </div>

        <div class="mt-6 grid gap-5 md:grid-cols-2 xl:grid-cols-4">

            @foreach ($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach

        </div>

    </section>

    {{-- TRUST SECTION (COMBINED: STEPS + REVIEWS) --}}
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                How it works
            </p>

            <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Ordering & Reviews
            </h2>

            {{-- STEPS --}}
            <div class="mt-8 grid gap-5 md:grid-cols-4">
                @foreach ($steps as $step)
                    <div class="rounded-3xl bg-brand-light/60 p-5">
                        <h3 class="font-semibold text-brand-primary">
                            {{ $step['title'] ?? '' }}
                        </h3>

                        <p class="mt-2 text-sm leading-6 text-brand-ink/70">
                            {{ $step['desc'] ?? '' }}
                        </p>
                    </div>
                @endforeach
            </div>

            {{-- REVIEW FORM --}}
            @auth
                <div class="mt-10 rounded-3xl border border-brand-border bg-brand-light/30 p-6">
                    <h3 class="text-xl font-semibold text-brand-primary">
                        Leave a Review
                    </h3>

                    <p class="mt-2 text-sm text-brand-ink/70">
                        Share your experience with Crafted Pieces.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('reviews.store') }}"
                        class="mt-6 space-y-5"
                    >
                        @csrf

                        @php
                            $oldRating = old('rating');
                            $ratingValue = in_array((string) $oldRating, ['1', '2', '3', '4', '5'], true)
                                ? (int) $oldRating
                                : null;
                        @endphp

                        <div data-rating-control>
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between sm:gap-4">
                                <label id="review-rating-label" class="block text-sm font-semibold text-brand-primary">
                                    Your Rating
                                </label>

                                <p id="review-rating-help" class="text-xs font-medium text-brand-ink/60">
                                    Select your rating: 1 yarn = lowest, 5 yarns = highest
                                </p>
                            </div>

                            <input
                                type="hidden"
                                name="rating"
                                value="{{ $ratingValue ?? '' }}"
                                data-rating-input
                            >

                            <div
                                class="mt-3 flex flex-wrap gap-2 sm:flex-nowrap"
                                role="group"
                                aria-labelledby="review-rating-label"
                                aria-describedby="review-rating-help review-rating-selected"
                            >
                                @foreach ([1, 2, 3, 4, 5] as $rating)
                                    @php
                                        $isActive = $ratingValue !== null && $rating <= $ratingValue;
                                        $isSelected = $ratingValue === $rating;
                                    @endphp

                                    <button
                                        type="button"
                                        class="group inline-flex h-12 w-12 cursor-pointer items-center justify-center rounded-2xl border transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-accent/70 sm:h-14 sm:w-14 {{ $isActive ? 'border-brand-primary bg-brand-light shadow-sm' : 'border-brand-border bg-white/80 opacity-55 hover:border-brand-secondary hover:bg-brand-light/40 hover:opacity-100' }}"
                                        data-rating-button
                                        data-rating-value="{{ $rating }}"
                                        aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                        aria-label="Rate {{ $rating }} out of 5"
                                        title="Rate {{ $rating }} out of 5"
                                    >
                                        <span
                                            class="inline-flex h-8 w-8 items-center justify-center transition group-hover:scale-105 sm:h-9 sm:w-9 {{ $isActive ? 'opacity-100' : 'opacity-35' }}"
                                            data-rating-icon
                                        >
                                            <img
                                                src="{{ asset('images/yarn.png') }}"
                                                alt=""
                                                class="h-full w-full object-contain"
                                            >
                                        </span>
                                    </button>
                                @endforeach
                            </div>

                            <p
                                id="review-rating-selected"
                                class="mt-3 text-sm font-semibold text-brand-primary"
                                data-rating-selected-text
                            >
                                Selected: {{ $ratingValue ? $ratingValue . ' out of 5' : 'none yet' }}
                            </p>

                            <p class="mt-2 hidden text-sm text-brand-secondary" data-rating-client-error>
                                Please select a rating from 1 to 5 yarns.
                            </p>

                            @error('rating')
                                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-primary">
                                Review
                            </label>

                            <textarea
                                name="comment"
                                rows="4"
                                required
                                maxlength="1000"
                                class="brand-input resize-none"
                            ></textarea>
                        </div>

                        <label class="flex items-center gap-2 text-sm text-brand-ink/70">
                            <input
                                type="checkbox"
                                name="is_anonymous"
                                class="h-4 w-4"
                            >

                            Submit anonymously
                        </label>

                        <button type="submit" class="brand-btn-primary">
                            Submit Review
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-10 rounded-3xl border border-brand-border bg-brand-light/30 p-6 text-center">
                    <p class="text-sm text-brand-ink/70">
                        Login to leave a review.
                    </p>

                    <button
                        type="button"
                        onclick="openAuthModal()"
                        class="mt-4 brand-btn-primary"
                    >
                        Login / Register
                    </button>
                </div>
            @endauth

            {{-- REVIEWS --}}
            <div class="mt-10">

                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                        Customer Reviews
                    </p>

                    <div class="flex gap-2">
                        <button type="button" onclick="prevReviews()" class="text-brand-primary px-3 py-1 border rounded-lg">
                            ‹
                        </button>

                        <button type="button" onclick="nextReviews()" class="text-brand-primary px-3 py-1 border rounded-lg">
                            ›
                        </button>
                    </div>
                </div>

                <div id="reviews-container" class="grid gap-5 md:grid-cols-3">

                    @forelse ($testimonials as $testimonial)

                        <div class="review-card hidden rounded-3xl border border-brand-border bg-white p-5">

                            @php
                                $displayRating = (int) $testimonial->rating;
                            @endphp

                            <div
                                class="flex items-center gap-2"
                                aria-label="{{ $displayRating }} out of 5 yarn rating"
                            >
                                <div class="flex gap-1" aria-hidden="true">
                                    @for ($rating = 1; $rating <= 5; $rating++)
                                        <img
                                            src="{{ asset('images/yarn.png') }}"
                                            alt=""
                                            class="h-5 w-5 object-contain {{ $rating <= $displayRating ? 'opacity-100' : 'opacity-25' }}"
                                        >
                                    @endfor
                                </div>

                                <span class="text-xs font-semibold text-brand-primary">
                                    {{ $displayRating }} out of 5
                                </span>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-brand-ink/70">
                                {{ $testimonial->comment }}
                            </p>

                            <p class="mt-4 text-xs font-semibold uppercase tracking-[0.16em] text-brand-secondary">
                                {{ $testimonial->is_anonymous ? 'Anonymous Customer' : $testimonial->user->name }}
                            </p>

                        </div>

                    @empty

                        <div class="md:col-span-3 rounded-3xl border border-dashed border-brand-border bg-brand-light/20 p-8 text-center">
                            <p class="text-sm text-brand-ink/70">
                                No reviews yet.
                            </p>
                        </div>

                    @endforelse

                </div>
            </div>

        </div>
    </section>
@endsection

<script>
let reviewIndex = 0;

function updateReviews() {
    const cards = document.querySelectorAll('.review-card');

    cards.forEach((card, i) => {
        card.classList.add('hidden');
    });

    for (let i = 0; i < 3; i++) {
        const idx = (reviewIndex + i) % cards.length;
        if (cards[idx]) {
            cards[idx].classList.remove('hidden');
        }
    }
}

function nextReviews() {
    const cards = document.querySelectorAll('.review-card');
    reviewIndex = (reviewIndex + 3) % cards.length;
    updateReviews();
}

function prevReviews() {
    const cards = document.querySelectorAll('.review-card');
    reviewIndex = (reviewIndex - 3 + cards.length) % cards.length;
    updateReviews();
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-rating-control]').forEach((ratingControl) => {
        const input = ratingControl.querySelector('[data-rating-input]');
        const buttons = Array.from(ratingControl.querySelectorAll('[data-rating-button]'));
        const selectedText = ratingControl.querySelector('[data-rating-selected-text]');
        const clientError = ratingControl.querySelector('[data-rating-client-error]');
        const form = ratingControl.closest('form');

        const setPreview = (value) => {
            buttons.forEach((button) => {
                const rating = Number(button.dataset.ratingValue);
                const icon = button.querySelector('[data-rating-icon]');
                const isActive = rating <= value;

                button.classList.toggle('border-brand-primary', isActive);
                button.classList.toggle('bg-brand-light', isActive);
                button.classList.toggle('shadow-sm', isActive);
                button.classList.toggle('border-brand-border', ! isActive);
                button.classList.toggle('bg-white/80', ! isActive);
                button.classList.toggle('opacity-55', ! isActive);
                button.classList.toggle('hover:border-brand-secondary', ! isActive);
                button.classList.toggle('hover:bg-brand-light/40', ! isActive);
                button.classList.toggle('hover:opacity-100', ! isActive);

                if (icon) {
                    icon.classList.toggle('opacity-100', isActive);
                    icon.classList.toggle('opacity-35', ! isActive);
                }
            });
        };

        const selectedValue = () => {
            const value = Number(input?.value || 0);

            return Number.isInteger(value) && value >= 1 && value <= 5 ? value : 0;
        };

        const syncSelection = () => {
            const value = selectedValue();

            setPreview(value);

            buttons.forEach((button) => {
                button.setAttribute('aria-pressed', Number(button.dataset.ratingValue) === value ? 'true' : 'false');
            });

            if (selectedText) {
                selectedText.textContent = value ? `Selected: ${value} out of 5` : 'Selected: none yet';
            }
        };

        buttons.forEach((button) => {
            const value = Number(button.dataset.ratingValue);

            button.addEventListener('click', () => {
                input.value = String(value);
                clientError?.classList.add('hidden');
                syncSelection();
            });

            button.addEventListener('mouseenter', () => setPreview(value));
            button.addEventListener('focus', () => setPreview(value));
            button.addEventListener('blur', syncSelection);
        });

        ratingControl.addEventListener('mouseleave', syncSelection);

        form?.addEventListener('submit', (event) => {
            if (selectedValue()) {
                return;
            }

            event.preventDefault();
            clientError?.classList.remove('hidden');
            buttons[0]?.focus();
        });

        syncSelection();
    });

    updateReviews();
});
</script>
