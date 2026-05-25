@extends('layouts.admin')

@section('content')
<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Edit FAQ</h2>
            <p class="mt-2 text-sm text-brand-ink/70">Update the frequently asked question and answer.</p>
        </div>
        <x-back-button href="{{ route('admin.faq.index') }}" label="Back to FAQ" />
    </div>

    <form method="POST" action="{{ route('admin.faq.update', $faq) }}" class="mt-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-2 block text-sm font-semibold text-brand-primary">Question</label>
            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required
                   class="brand-input w-full" placeholder="What is your question?">
            @error('question')
                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-brand-primary">Answer</label>
            <textarea name="answer" rows="10" required
                      class="brand-input w-full" placeholder="Provide a detailed answer...">{{ old('answer', $faq->answer) }}</textarea>
            @error('answer')
                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Display Order</label>
                <input type="number" name="order" value="{{ old('order', $faq->order) }}" min="0" required
                       class="brand-input w-full" placeholder="0">
                <p class="mt-1 text-xs text-brand-ink/70">Lower numbers appear first</p>
                @error('order')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-end">
                <label class="flex items-center gap-2 text-sm text-brand-ink/70 cursor-pointer">
                    <input type="checkbox" name="active" value="1" {{ old('active', $faq->active) ? 'checked' : '' }} class="h-4 w-4">
                    <span class="font-semibold">Display on public site</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-4">
            <button type="submit" class="brand-btn-primary px-6 py-3">Save Changes</button>
            <a href="{{ route('admin.faq.index') }}" class="brand-btn-secondary px-6 py-3">Cancel</a>
        </div>
    </form>
</div>
@endsection
