@extends('layouts.admin')

@section('content')

@php
    $pageTitle = 'Edit FAQ';
@endphp

<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h2 class="text-xl font-semibold text-brand-primary">
            Edit Chatbot FAQ
        </h2>
        <p class="text-sm text-brand-ink/70">
            Update the question and answer for this chatbot entry
        </p>
    </div>

    <x-back-button href="{{ route('admin.chatbot.index') }}" label="Back to Chatbot" />
</div>

<div class="rounded-2xl border border-brand-border bg-white p-6 shadow-sm">

    <form id="chatbot-faq-update-form"
          method="POST"
          action="{{ route('admin.chatbot.update', $faq) }}"
          class="space-y-5">

        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium text-brand-primary">
                Question
            </label>

            <input
                type="text"
                name="question"
                value="{{ old('question', $faq->question) }}"
                class="mt-1 w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                required
            >

            @error('question')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-brand-primary">
                Answer
            </label>

            <textarea
                name="answer"
                rows="5"
                class="mt-1 w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                required
            >{{ old('answer', $faq->answer) }}</textarea>

            @error('answer')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-sm font-medium text-brand-primary">
                Keywords (optional)
            </label>

            <input
                type="text"
                name="keywords"
                value="{{ old('keywords', $faq->keywords) }}"
                placeholder="e.g. track, order, shipping"
                class="mt-1 w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
            >

            <p class="mt-1 text-xs text-brand-ink/60">
                Used to improve chatbot matching (comma separated)
            </p>

            @error('keywords')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </form>

    <div class="mt-5 flex items-center justify-between gap-3">
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
                    class="text-sm text-red-600 hover:underline">
                Delete FAQ
            </button>
        </form>

        <button type="submit"
                form="chatbot-faq-update-form"
                class="brand-btn-primary px-6 py-3 text-sm">
            Update FAQ
        </button>
    </div>

</div>

@endsection
