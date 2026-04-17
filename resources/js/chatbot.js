document.addEventListener('DOMContentLoaded', function () {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotModal = document.getElementById('chatbot-modal');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotSend = document.getElementById('chatbot-send');
    const chatbotMessages = document.getElementById('chatbot-messages');

    if (!chatbotToggle || !chatbotModal) {
        return;
    }

    chatbotToggle.addEventListener('click', function () {
        chatbotModal.classList.toggle('hidden');
        chatbotModal.classList.toggle('flex');
        if (!chatbotModal.classList.contains('hidden')) {
            chatbotInput?.focus();
        }
    });

    chatbotClose?.addEventListener('click', function () {
        chatbotModal.classList.add('hidden');
        chatbotModal.classList.remove('flex');
    });

    function sendMessage() {
        const message = chatbotInput?.value.trim();
        if (!message || !chatbotMessages) {
            return;
        }

        const userMessageEl = document.createElement('div');
        userMessageEl.className = 'flex justify-end gap-2';
        userMessageEl.innerHTML = `
            <div class="max-w-xs rounded-lg bg-[#b8745f] px-4 py-2 text-sm text-white shadow-sm">
                ${escapeHtml(message)}
            </div>
        `;
        chatbotMessages.appendChild(userMessageEl);

        if (chatbotInput) {
            chatbotInput.value = '';
        }

        setTimeout(function () {
            const botMessageEl = document.createElement('div');
            botMessageEl.className = 'flex gap-2';
            botMessageEl.innerHTML = `
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#e7d6cb]">
                    <svg class="h-4 w-4 text-[#5d342b]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="max-w-xs rounded-lg bg-white px-4 py-2 text-sm text-[#3f2a22] shadow-sm">
                    Thanks for your message. For detailed help, visit the About page or use the contact section.
                </div>
            `;
            chatbotMessages.appendChild(botMessageEl);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight || 0;
        }, 500);

        chatbotMessages.scrollTop = chatbotMessages.scrollHeight || 0;
    }

    chatbotSend?.addEventListener('click', sendMessage);

    chatbotInput?.addEventListener('keypress', function (event) {
        if (event.key === 'Enter') {
            sendMessage();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !chatbotModal.classList.contains('hidden')) {
            chatbotModal.classList.add('hidden');
            chatbotModal.classList.remove('flex');
        }
    });

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
});
