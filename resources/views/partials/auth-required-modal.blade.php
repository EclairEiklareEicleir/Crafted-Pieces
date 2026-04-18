<div id="auth-required-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-md rounded-2xl border border-[#eadfd7] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">Account Required</p>
                <h3 class="mt-2 font-display text-2xl font-semibold text-[#4d3028]">Please log in to continue</h3>
                <p class="mt-3 text-sm text-[#6f5a51]">This action needs a customer account so we can save your order details securely.</p>
            </div>
            <button type="button" id="auth-required-close" class="rounded p-1 text-[#8f6a5d] transition hover:bg-[#f6efe8]" aria-label="Close login prompt">
                <x-icon name="close" size="w-5 h-5" />
            </button>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('login') }}" class="rounded-full bg-[#b8745f] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#a96550]">Log In</a>
            <a href="{{ route('register') }}" class="rounded-full border border-[#eadfd7] bg-white px-5 py-2.5 text-sm font-semibold text-[#5d342b] transition hover:-translate-y-0.5">Create Account</a>
            <button type="button" data-auth-modal-close class="rounded-full border border-transparent px-2 py-2 text-sm font-medium text-[#8f6a5d]">Maybe later</button>
        </div>
    </div>
</div>
