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

                        <a href="{{ route('custom-order') }}"
                           class="brand-btn-secondary px-6 py-3 text-sm shadow-sm hover:-translate-y-0.5">
                            Request Custom Crochet
                        </a>

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

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-primary">
                                Rating
                            </label>

                            <select name="rating" required class="brand-input">
                                <option value="">Select rating</option>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
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

                            <div class="flex gap-1 text-brand-secondary">
                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                    <span>★</span>
                                @endfor
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
    updateReviews();
});
</script>