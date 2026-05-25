@props([
    'type',
])

@php
    $definitions = [
        'privacy' => [
            'id' => 'privacy-policy-modal',
            'title' => 'Privacy Policy',
            'subtitle' => 'How Crafted Pieces handles your information',
            'content' => [
                'At Crafted Pieces, we respect your privacy and only collect the information needed to process orders and communicate with you.',
                'We do not sell or share your personal data with third parties for marketing purposes. Your information is used to fulfill orders, respond to inquiries, and improve our shopping experience.',
                'If you have questions about how we handle your information, please contact us at hello@craftedpieces.com.',
            ],
        ],
        'terms' => [
            'id' => 'terms-service-modal',
            'title' => 'Terms of Service',
            'subtitle' => 'The basic rules for using Crafted Pieces',
            'content' => [
                'By using Crafted Pieces, you agree to our terms for placing orders, custom requests, and account activity.',
                'Orders are subject to availability, payment confirmation, and our standard shipping policies. We reserve the right to update our terms as needed to ensure a safe experience for our customers.',
                'If you have questions about these terms, please reach out to hello@craftedpieces.com.',
            ],
        ],
    ];

    $definition = $definitions[$type] ?? $definitions['privacy'];
@endphp

<div id="{{ $definition['id'] }}"
    data-legal-modal="{{ $type }}"
    class="hidden fixed inset-0 z-60 items-center justify-center px-4 py-6">
    <div class="absolute inset-0 bg-black/55 backdrop-blur-sm" data-legal-modal-close="{{ $type }}"></div>

    <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-4xl border border-brand-border bg-white shadow-[0_30px_100px_rgba(101,12,42,0.22)]">
        <div class="flex items-start justify-between gap-4 border-b border-brand-border bg-brand-surface px-6 py-5 sm:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">{{ $definition['subtitle'] }}</p>
                <h3 class="mt-1 text-2xl font-semibold text-brand-primary">{{ $definition['title'] }}</h3>
            </div>

            <button type="button"
                    data-legal-modal-close="{{ $type }}"
                    class="brand-icon-button h-10 w-10 shrink-0"
                    aria-label="Close {{ $definition['title'] }} modal">
                <span aria-hidden="true" class="text-lg leading-none">&times;</span>
            </button>
        </div>

        <div class="max-h-[70vh] overflow-y-auto px-6 py-6 sm:px-8">
            <div class="space-y-4 text-sm leading-7 text-brand-ink/75">
                @foreach ($definition['content'] as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="mt-6 rounded-3xl border border-brand-border bg-brand-light/30 p-4 text-sm text-brand-ink/65">
                These terms are shown inside the register flow so you can review them without leaving the page.
            </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3 border-t border-brand-border px-6 py-5 sm:px-8">
            <button type="button"
                    data-legal-modal-close="{{ $type }}"
                    class="brand-btn-secondary px-5 py-3 text-sm">
                Close
            </button>

            <button type="button"
                    data-legal-modal-close="{{ $type }}"
                    class="brand-btn-primary px-5 py-3 text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>