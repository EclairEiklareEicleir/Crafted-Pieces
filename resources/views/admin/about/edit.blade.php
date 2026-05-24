@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                    About Management
                </p>
                <h2 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                    About & FAQ
                </h2>
                <p class="mt-2 text-sm text-brand-ink/70">
                    Manage the public About page content and frequently asked questions from one place.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="#about-content" class="brand-btn-secondary px-5 py-2 text-sm">About Content</a>
                <a href="#faqs" class="brand-btn-secondary px-5 py-2 text-sm">FAQs</a>
                <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
            </div>
        </div>
    </div>

    <section id="about-content" class="scroll-mt-28 rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="font-display text-2xl font-semibold text-brand-primary">About Content</h3>
                <p class="mt-2 text-sm text-brand-ink/70">Edit the content displayed on the public About page.</p>
            </div>
        </div>

        @unless ($aboutSection->exists)
            <div class="mt-6 rounded-3xl border border-brand-border bg-brand-light/40 p-4 text-sm text-brand-primary">
                No About content exists yet. Add the heading and content below to create it.
            </div>
        @endunless

        <form method="POST" action="{{ route('admin.about.update') }}" class="mt-8 space-y-6" data-preserve-scroll>
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Heading</label>
                <input type="text" name="heading" value="{{ old('heading', $aboutSection->heading) }}" required class="brand-input w-full">
                @error('heading')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-brand-primary">Content</label>
                <textarea name="content" rows="8" required class="brand-input w-full">{{ old('content', $aboutSection->content) }}</textarea>
                @error('content')
                    <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="brand-btn-primary px-6 py-3">Save Changes</button>
        </form>
    </section>

    <section id="faqs" class="scroll-mt-28 rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h3 class="font-display text-2xl font-semibold text-brand-primary">FAQ Management</h3>
                <p class="mt-2 text-sm text-brand-ink/70">Add, edit, publish, and remove public FAQs.</p>
            </div>

            <a href="#faq-create" class="brand-btn-primary px-5 py-2 text-sm">New FAQ</a>
        </div>

        <div id="faq-create" class="mt-6 scroll-mt-28 rounded-3xl border border-brand-border bg-brand-light/25 p-5">
            <p class="text-sm font-semibold text-brand-primary">Add FAQ</p>

            <form method="POST" action="{{ route('admin.faq.store') }}" class="mt-4 grid gap-4 lg:grid-cols-[1fr_1fr_8rem_auto]" data-preserve-scroll>
                @csrf

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Question</label>
                    <input type="text" name="question" value="{{ old('question') }}" required class="brand-input bg-white" placeholder="What is your question?">
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Answer</label>
                    <textarea name="answer" rows="1" required class="brand-input min-h-12 bg-white" placeholder="Provide a clear answer">{{ old('answer') }}</textarea>
                </div>

                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.16em] text-brand-ink/55">Order</label>
                    <input type="number" name="order" value="{{ old('order', ($faqs->max('order') ?? 0) + 1) }}" min="0" required class="brand-input bg-white">
                </div>

                <div class="flex flex-wrap items-end gap-4">
                    <label class="mb-3 inline-flex items-center gap-2 text-sm font-semibold text-brand-primary">
                        <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="h-4 w-4 rounded border-brand-border text-brand-primary">
                        Active
                    </label>

                    <button type="submit" class="brand-btn-primary px-5 py-3 text-sm">Add FAQ</button>
                </div>
            </form>
        </div>

        <div class="mt-6 space-y-4">
            @forelse ($faqs as $faq)
                <details class="rounded-3xl border border-brand-border bg-white p-5 shadow-sm" id="faq-{{ $faq->id }}">
                    <summary class="flex cursor-pointer list-none flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-brand-primary">
                                {{ $faq->question }}
                            </p>
                            <p class="mt-1 text-xs text-brand-ink/55">
                                Display order {{ $faq->order }}
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <x-status-badge :status="$faq->active ? 'active' : 'inactive'" context="toggle" />
                            <span class="rounded-full border border-brand-border px-3 py-1 text-xs font-semibold text-brand-secondary">Edit</span>
                        </div>
                    </summary>

                    <form method="POST" action="{{ route('admin.faq.update', $faq) }}" class="mt-5 grid gap-4" data-preserve-scroll>
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-primary">Question</label>
                            <input type="text" name="question" value="{{ old('question', $faq->question) }}" required class="brand-input w-full">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-brand-primary">Answer</label>
                            <textarea name="answer" rows="5" required class="brand-input w-full">{{ old('answer', $faq->answer) }}</textarea>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-[12rem_1fr]">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-brand-primary">Display Order</label>
                                <input type="number" name="order" value="{{ old('order', $faq->order) }}" min="0" required class="brand-input w-full">
                            </div>

                            <label class="flex items-end gap-2 pb-3 text-sm font-semibold text-brand-primary">
                                <input type="checkbox" name="active" value="1" {{ old('active', $faq->active) ? 'checked' : '' }} class="h-4 w-4 rounded border-brand-border text-brand-primary">
                                Display on public site
                            </label>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button type="submit" class="brand-btn-primary px-5 py-2 text-sm">Save FAQ</button>
                        </div>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.faq.destroy', $faq) }}"
                          class="mt-3"
                          data-preserve-scroll
                          data-confirm-title="Delete FAQ?"
                          data-confirm-message="Are you sure you want to delete this FAQ?"
                          data-confirm-final-title="Final Confirmation"
                          data-confirm-final-message="This FAQ will be removed from the public About page. Are you absolutely sure?"
                          data-confirm-final-action="Yes, Delete">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="inline-flex items-center rounded-full border border-rose-200 bg-white px-4 py-2 text-sm font-semibold text-rose-600 transition hover:border-rose-300 hover:bg-rose-50">
                            Delete FAQ
                        </button>
                    </form>
                </details>
            @empty
                <div class="rounded-3xl border-2 border-dashed border-brand-border bg-brand-light/50 p-8 text-center">
                    <p class="text-brand-ink/70">No FAQs yet. Add the first one above.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
