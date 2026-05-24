@extends('layouts.admin')

@section('content')

@php
    $pageTitle = 'Chatbot FAQs';
@endphp

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-brand-primary">
            Chatbot FAQs
        </h2>
        <p class="text-sm text-brand-ink/70">
            Manage automated chatbot responses
        </p>
    </div>

    <a href="{{ route('admin.chatbot.create') }}"
       class="brand-btn-primary px-4 py-2 text-sm">
        + Add FAQ
    </a>
</div>

{{-- SUCCESS MESSAGE --}}
@if (session('success'))
    <div class="mb-4 flex items-start justify-between gap-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 transition duration-300"
         data-flash-alert
         data-flash-delay="4200">
        <div>{{ session('success') }}</div>
        <button type="button" data-flash-dismiss aria-label="Dismiss notification" class="text-lg leading-none text-green-700 hover:text-green-900">&times;</button>
    </div>
@endif

{{-- TABLE --}}
<div class="overflow-hidden rounded-2xl border border-brand-border bg-white shadow-sm">

    <table class="w-full text-sm">

        <thead class="border-b border-brand-border bg-brand-light text-left text-xs uppercase tracking-wider text-brand-secondary">
            <tr>
                <th class="px-4 py-3">Question</th>
                <th class="px-4 py-3">Answer</th>
                <th class="px-4 py-3">Keywords</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-brand-border">

            @forelse ($faqs as $faq)

                <tr class="hover:bg-brand-light/40 transition">

                    {{-- QUESTION --}}
                    <td class="px-4 py-3 font-medium text-brand-primary max-w-[220px]">
                        {{ $faq->question }}
                    </td>

                    {{-- ANSWER --}}
                    <td class="px-4 py-3 text-brand-ink/70 max-w-[320px]">
                        {{ \Illuminate\Support\Str::limit($faq->answer, 80) }}
                    </td>

                    {{-- KEYWORDS --}}
                    <td class="px-4 py-3 text-brand-ink/60">
                        {{ $faq->keywords ?? '—' }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-4 py-3 text-center">
                        @if ($faq->is_active)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">
                                Disabled
                            </span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-4 py-3">

                        <div class="flex justify-end gap-2">

                            {{-- EDIT --}}
                            <a href="{{ route('admin.chatbot.edit', $faq) }}"
                               class="rounded-lg border border-brand-border px-3 py-1 text-xs text-brand-primary hover:bg-brand-light">
                                Edit
                            </a>

                            {{-- DELETE --}}
                            <form method="POST"
                                  action="{{ route('admin.chatbot.destroy', $faq) }}"
                                  data-confirm-title="Delete Chatbot FAQ?"
                                  data-confirm-message="Are you sure you want to delete this chatbot FAQ?"
                                  data-confirm-final-title="Final Confirmation"
                                  data-confirm-final-message="This chatbot response will be removed. Are you absolutely sure?"
                                  data-confirm-final-action="Yes, Delete">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="rounded-lg border border-red-200 px-3 py-1 text-xs text-red-600 hover:bg-red-50">
                                    Delete
                                </button>

                            </form>

                        </div>

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="5" class="px-4 py-10 text-center text-brand-ink/60">
                        No chatbot FAQs yet. Create your first one.
                    </td>
                </tr>

            @endforelse

        </tbody>

    </table>

</div>

@endsection
