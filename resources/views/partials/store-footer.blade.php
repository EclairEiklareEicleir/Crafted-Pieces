<footer class="mt-16 border-t border-[#eadfd7] bg-white/70">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div>
            <h3 class="font-display text-lg font-semibold text-[#5d342b]">the_crafted_pieces</h3>
            <p class="mt-3 text-sm leading-6 text-[#6f5a51]">Handmade crochet pieces crafted with care.</p>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">Quick Links</h4>
            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('shop') }}">
                    <x-icon name="shop" size="w-4 h-4" />
                    Shop All
                </a>
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('custom-order') }}">
                    <x-icon name="custom" size="w-4 h-4" />
                    Custom Orders
                </a>
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}">
                    <x-icon name="about" size="w-4 h-4" />
                    About Us
                </a>
            </div>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">Help</h4>
            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}#faq">
                    <x-icon name="chat" size="w-4 h-4" />
                    FAQs
                </a>
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="{{ route('about') }}#contact">
                    <x-icon name="email" size="w-4 h-4" />
                    Contact Us
                </a>
            </div>
        </div>
        <div>
            <h4 class="text-sm font-semibold uppercase tracking-[0.18em] text-[#8f6a5d]">Connect</h4>
            <div class="mt-4 space-y-2 text-sm text-[#6f5a51]">
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="mailto:hello@thecraftedpieces.com">
                    <x-icon name="email" size="w-4 h-4" />
                    hello@thecraftedpieces.com
                </a>
                <a class="flex items-center gap-2 transition hover:text-[#a86b57]" href="https://www.instagram.com/the_crafted_pieces" target="_blank" rel="noreferrer">
                    <x-icon name="instagram" size="w-4 h-4" />
                    Instagram
                </a>
            </div>
        </div>
    </div>
    <div class="border-t border-[#eadfd7] px-4 py-4 text-center text-xs text-[#8f7a70] sm:px-6 lg:px-8">Made with care by the_crafted_pieces.</div>
</footer>
