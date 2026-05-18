<footer class="mt-16 border-t border-brand-border bg-white/80">

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">

        {{-- BRAND --}}
        <div>
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/crafted_pieces_logo.png') }}" alt="Crafted Pieces" class="h-14 w-auto rounded-xl object-contain">
                <h3 class="text-sm font-semibold uppercase tracking-[0.22em] text-brand-primary">
                    Crafted Pieces
                </h3>
            </div>

            <p class="mt-3 text-sm leading-6 text-brand-ink/70">
                Handmade crochet pieces crafted with care.
            </p>
        </div>

        {{-- QUICK LINKS --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                Quick Links
            </h4>

            <div class="mt-4 space-y-2 text-sm text-brand-ink/70">

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="{{ route('shop') }}">
                    Shop All
                </a>

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="{{ route('custom-order') }}">
                    Custom Orders
                </a>

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="{{ route('about') }}">
                    About Us
                </a>

            </div>
        </div>

        {{-- HELP --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                Help
            </h4>

            <div class="mt-4 space-y-2 text-sm text-brand-ink/70">

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="{{ route('about') }}#faq">
                    FAQs
                </a>

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="{{ route('about') }}#contact">
                    Contact Us
                </a>

            </div>
        </div>

        {{-- CONNECT --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-brand-secondary">
                Connect
            </h4>

            <div class="mt-4 space-y-2 text-sm text-brand-ink/70">

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="mailto:hello@craftedpieces.com">
                    hello@craftedpieces.com
                </a>

                <a class="flex items-center gap-2 transition hover:text-brand-primary" href="https://instagram.com/the_crafted_pieces" target="_blank" rel="noreferrer">
                    Instagram
                </a>

            </div>
        </div>

    </div>

    <div class="border-t border-brand-border px-4 py-4 text-center text-xs text-brand-ink/55 sm:px-6 lg:px-8">
        Made with care by the_crafted_pieces.
    </div>

</footer>