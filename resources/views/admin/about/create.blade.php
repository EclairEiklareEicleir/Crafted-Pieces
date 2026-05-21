@extends('layouts.admin')

@section('content')
<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">Create About Content</h2>
            <p class="mt-2 text-sm text-brand-ink/70">Add new content for the public About page.</p>
        </div>
        <a href="{{ route('admin.about.index') }}" class="brand-btn-secondary px-5 py-2 text-sm">Back to list</a>
    </div>

    <form method="POST" action="{{ route('admin.about.store') }}" class="mt-8 space-y-6">
        @csrf

        <div>
            <label class="mb-2 block text-sm font-semibold text-brand-primary">Heading</label>
            <input type="text" name="heading" value="{{ old('heading') }}" required class="brand-input w-full">
            @error('heading')
                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-brand-primary">Content</label>
            <textarea name="content" rows="8" required class="brand-input w-full">{{ old('content') }}</textarea>
            @error('content')
                <p class="mt-2 text-sm text-brand-secondary">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="brand-btn-primary px-6 py-3">Save Content</button>
    </form>
</div>
@endsection
