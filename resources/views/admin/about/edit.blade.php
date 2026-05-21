@extends('layouts.admin')

@section('content')
<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">
    <div>
        <h2 class="font-display text-2xl font-semibold text-brand-primary">About Section Settings</h2>
        <p class="mt-2 text-sm text-brand-ink/70">Manage the About page content that appears on the public website.</p>
    </div>

    <form method="POST" action="{{ route('admin.about.update') }}" class="mt-8 space-y-6">
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
</div>
@endsection
