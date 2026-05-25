<div class="fixed bottom-6 right-6 z-50" id="chatbot-container">

    {{-- TOGGLE BUTTON --}}
    <button
        id="chatbot-toggle"
        class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-primary text-white shadow-xl transition hover:scale-105"
        aria-label="Open chatbot"
    >
        <svg viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="1.8"
             class="h-6 w-6">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M8 10h8M8 14h5" />

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M21 12c0 4.97-4.03 9-9 9a9.77 9.77 0 0 1-4-.8L3 21l.8-5A8.96 8.96 0 0 1 3 12c0-4.97 4.03-9 9-9s9 4.03 9 9Z" />
        </svg>
    </button>

    {{-- CHAT MODAL --}}
    <div
        id="chatbot-modal"
        class="absolute bottom-20 right-0 hidden flex-col h-[32rem] w-80 overflow-hidden rounded-3xl border border-brand-border bg-white shadow-2xl"
    >

        {{-- HEADER --}}
        <div class="flex items-center justify-between border-b border-brand-border bg-brand-primary px-5 py-4 text-white">

            <div>
                <h3 class="text-sm font-semibold">
                    Crafted Pieces Assistant
                </h3>

                <p class="text-xs text-white/70">
                    Ask us anything
                </p>
            </div>

            <button
                id="chatbot-close"
                class="rounded-lg p-1 transition hover:bg-white/10"
            >
                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8"
                     class="h-5 w-5">

                    <path stroke-linecap="round"
                          d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>

        {{-- MESSAGES --}}
        <div
            id="chatbot-messages"
            class="flex h-[22rem] flex-col gap-3 overflow-y-auto bg-brand-light/30 p-4"
        >

            {{-- BOT MESSAGE --}}
            <div class="flex gap-2">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-brand-primary text-white">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         class="h-4 w-4">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M8 10h8M8 14h5" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M21 12c0 4.97-4.03 9-9 9a9.77 9.77 0 0 1-4-.8L3 21l.8-5A8.96 8.96 0 0 1 3 12c0-4.97 4.03-9 9-9s9 4.03 9 9Z" />
                    </svg>
                </div>

                <div class="max-w-[85%] rounded-2xl rounded-tl-md bg-white px-4 py-3 text-sm shadow-sm">
                    <p class="font-medium text-brand-primary">
                        Hi! How can we help?
                    </p>

                    <div class="mt-2 space-y-1 text-xs text-brand-ink/70">
                        <p>• Track orders</p>
                        <p>• Custom orders</p>
                        <p>• Payments</p>
                        <p>• Shipping</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- INPUT --}}
        <div class="border-t border-brand-border bg-white p-4">

            <div class="flex gap-2">

                <input
                    id="chatbot-input"
                    type="text"
                    placeholder="Type your message..."
                    class="flex-1 rounded-2xl border border-brand-border bg-brand-surface px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                >

                <button
                    id="chatbot-send"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-primary text-white transition hover:scale-105"
                >
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         class="h-5 w-5">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M22 2 11 13" />

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="m22 2-7 20-4-9-9-4Z" />
                    </svg>
                </button>

            </div>
        </div>
    </div>
</div>

<script>
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotModal = document.getElementById('chatbot-modal');
    const chatbotClose = document.getElementById('chatbot-close');

    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessages = document.getElementById('chatbot-messages');

    chatbotToggle?.addEventListener('click', () => {
        chatbotModal.classList.toggle('hidden');
        chatbotModal.classList.toggle('flex');
    });

    chatbotClose?.addEventListener('click', () => {
        chatbotModal.classList.add('hidden');
        chatbotModal.classList.remove('flex');
    });

    chatbotSend?.addEventListener('click', sendMessage);

    chatbotInput?.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    async function sendMessage()
    {
        const message = chatbotInput.value.trim();

        if (!message) return;

        appendUserMessage(message);

        chatbotInput.value = '';

        try {

            const response = await fetch('/chatbot/message', {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },

body: JSON.stringify({
    message: message,
    context: window.chatbotContext ?? null
})
            });

            const data = await response.json();

            appendBotMessage(data.reply);

        } catch (error) {

            appendBotMessage('Server error. Please try again.');
        }
    }

    function appendUserMessage(message)
    {
        chatbotMessages.innerHTML += `
            <div class="flex justify-end">
                <div class="max-w-[80%] rounded-lg bg-[#b8745f] px-4 py-2 text-sm text-white shadow">
                    ${message}
                </div>
            </div>
        `;

        scrollChatToBottom();
    }

    function appendBotMessage(message)
    {
        chatbotMessages.innerHTML += `
            <div class="flex gap-2">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-[#e7d6cb]">
                    💬
                </div>

                <div class="max-w-[80%] rounded-lg bg-white px-4 py-2 text-sm text-[#3f2a22] shadow-sm">
                    ${message}
                </div>
            </div>
        `;

        scrollChatToBottom();
    }

    function scrollChatToBottom()
    {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
</script>