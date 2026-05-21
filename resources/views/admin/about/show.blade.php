@extends('layouts.admin')

@section('content')
<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">View About Content</h2>
            <p class="mt-2 text-sm text-brand-ink/70">Details for the selected About section entry.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.about.edit', $aboutSection) }}" class="brand-btn-secondary px-5 py-2 text-sm">Edit</a>
            <a href="{{ route('admin.about.index') }}" class="brand-btn-secondary px-5 py-2 text-sm">Back to list</a>
        </div>
    </div>

    <div class="mt-8 space-y-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Heading</p>
            <h1 class="mt-2 text-3xl font-semibold text-brand-primary">{{ $aboutSection->heading }}</h1>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brand-secondary">Content</p>
            <div class="mt-3 rounded-3xl border border-brand-border bg-brand-light/40 p-5 text-brand-ink/70 whitespace-pre-line">
                {{ $aboutSection->content }}
            </div>
        </div>
    </div>
</div>
@endsection
