@extends('layouts.admin')

@section('content')

@php
    $pageTitle = 'Edit FAQ';
@endphp

<div class="mb-6">
    <h2 class="text-xl font-semibold text-brand-primary">
        Edit Chatbot FAQ
    </h2>
    <p class="text-sm text-brand-ink/70">
        Update the question and answer for this chatbot entry
    </p>
</div>

{{-- BACK BUTTON --}}
<div class="mb-4">
    <a href="{{ route('admin.chatbot.index') }}"
       class="text-sm text-brand-primary hover:underline">
        ← Back to FAQs
    </a>
</div>

{{-- FORM --}}
<div class="rounded-2xl border border-brand-border bg-white p-6 shadow-sm">

    <form method="POST"
          action="{{ route('admin.chatbot.update', $faq) }}"
          class="space-y-5">

        @csrf
        @method('PUT')

        {{-- QUESTION --}}
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

        {{-- ANSWER --}}
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

        {{-- KEYWORDS --}}
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

        {{-- ACTIONS --}}
        <div class="flex items-center justify-between pt-4">

            {{-- DELETE --}}
            <form method="POST"
                  action="{{ route('admin.chatbot.destroy', $faq) }}"
                  onsubmit="return confirm('Delete this FAQ?')">

                @csrf
                @method('DELETE')

                <button type="submit"
                        class="text-sm text-red-600 hover:underline">
                    Delete FAQ
                </button>

            </form>

            {{-- UPDATE --}}
            <button type="submit"
                    class="brand-btn-primary px-6 py-3 text-sm">
                Update FAQ
            </button>

        </div>

    </form>

</div>

@endsection