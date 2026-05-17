<footer class="mt-16 border-t border-[#eadfd7] bg-white/70">

    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">

        {{-- BRAND --}}
        <div>
            <h3 class="font-display text-lg font-semibold text-[#5d342b]">
                the_crafted_pieces
            </h3>

            <p class="mt-3 text-sm leading-6 text-[#6f5a51]">
                Handmade crochet pieces crafted with care.
            </p>
        </div>

        {{-- QUICK LINKS --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">
                Quick Links
            </h4>

            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('shop') }}">
                    Shop All
                </a>

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('custom-order') }}">
                    Custom Orders
                </a>

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}">
                    About Us
                </a>

            </div>
        </div>

        {{-- HELP --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">
                Help
            </h4>

            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}#faq">
                    FAQs
                </a>

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}#contact">
                    Contact Us
                </a>

            </div>
        </div>

        {{-- CONNECT --}}
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">
                Connect
            </h4>

            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="mailto:hello@craftedpieces.com">
                    hello@craftedpieces.com
                </a>

                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="https://instagram.com/the_crafted_pieces" target="_blank" rel="noreferrer">
                    Instagram
                </a>

            </div>
        </div>

    </div>

    <div class="border-t border-[#eadfd7] px-4 py-4 text-center text-xs text-[#8f7a70] sm:px-6 lg:px-8">
        Made with care by the_crafted_pieces.
    </div>

</footer>