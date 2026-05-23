@extends('layouts.admin')

@section('content')

@php
    $pageTitle = 'Create FAQ';
@endphp

<div class="mb-6">
    <h2 class="text-xl font-semibold text-brand-primary">
        Create Chatbot FAQ
    </h2>
    <p class="text-sm text-brand-ink/70">
        Add a new question and answer for the chatbot assistant
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

    <form method="POST" action="{{ route('admin.chatbot.store') }}" class="space-y-5">

        @csrf

        {{-- QUESTION --}}
        <div>
            <label class="text-sm font-medium text-brand-primary">
                Question
            </label>

            <input
                type="text"
                name="question"
                value="{{ old('question') }}"
                placeholder="e.g. How do I track my order?"
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
                placeholder="Write the chatbot response..."
                class="mt-1 w-full rounded-xl border border-brand-border px-4 py-3 text-sm focus:border-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary/20"
                required
            >{{ old('answer') }}</textarea>

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
                value="{{ old('keywords') }}"
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

        {{-- SUBMIT --}}
        <div class="flex justify-end pt-4">
            <button
                type="submit"
                class="brand-btn-primary px-6 py-3 text-sm"
            >
                Save FAQ
            </button>
        </div>

    </form>

</div>

@endsection