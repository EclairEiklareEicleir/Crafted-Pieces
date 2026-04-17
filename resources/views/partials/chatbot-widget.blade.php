<div class="fixed bottom-6 right-6 z-50" id="chatbot-container">
    <button
        id="chatbot-toggle"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-[#b8745f] p-3 text-white shadow-lg shadow-[#b8745f]/25 transition hover:-translate-y-0.5 hover:bg-[#a96550]"
        aria-label="Open help chat"
    >
        <x-icon name="chat" size="w-6 h-6" />
    </button>

    <div
        id="chatbot-modal"
        class="absolute bottom-20 right-0 hidden max-h-96 w-80 flex-col rounded-2xl border border-[#e7d6cb] bg-white shadow-2xl"
    >
        <div class="flex items-center justify-between rounded-t-2xl bg-[#b8745f] px-6 py-4 text-white">
            <div>
                <h3 class="font-semibold">How can we help?</h3>
                <p class="text-xs text-white/70">We are here to assist</p>
            </div>
            <button id="chatbot-close" class="rounded p-1 text-white transition hover:bg-[#a96550]" aria-label="Close chat">
                <x-icon name="close" size="w-5 h-5" />
            </button>
        </div>

        <div id="chatbot-messages" class="flex-1 space-y-3 overflow-y-auto bg-[#faf8f6] p-4">
            <div class="flex gap-2">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-[#e7d6cb]">
                    <x-icon name="chat" size="w-4 h-4" class="text-[#5d342b]" />
                </div>
                <div class="rounded-lg bg-white px-4 py-2 text-sm text-[#3f2a22] shadow-sm">
                    <p>Hi! How can we help you today?</p>
                    <p class="mt-2 text-xs text-[#8f6a5d]">- Track your order</p>
                    <p class="text-xs text-[#8f6a5d]">- Custom orders</p>
                    <p class="text-xs text-[#8f6a5d]">- Contact us</p>
                </div>
            </div>
        </div>

        <div class="rounded-b-2xl border-t border-[#e7d6cb] bg-white p-4">
            <div class="flex gap-2">
                <input
                    id="chatbot-input"
                    type="text"
                    placeholder="Type a message..."
                    class="flex-1 rounded-lg border border-[#e7d6cb] px-3 py-2 text-sm focus:border-[#b8745f] focus:outline-none focus:ring-1 focus:ring-[#b8745f]/20"
                />
                <button id="chatbot-send" class="rounded-lg bg-[#b8745f] px-3 py-2 text-white transition hover:bg-[#a96550]" aria-label="Send message">
                    <x-icon name="checkout" size="w-4 h-4" />
                </button>
            </div>
        </div>
    </div>
</div>
