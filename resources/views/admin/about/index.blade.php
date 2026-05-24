@extends('layouts.admin')

@section('content')
<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">About Section</h2>
            <p class="mt-2 text-sm text-brand-ink/70">Manage the About page content displayed on the store.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
            <a href="{{ route('admin.about.create') }}" class="brand-btn-primary px-5 py-2 text-sm whitespace-nowrap">+ Add New Content</a>
        </div>
    </div>

    @if (session('success'))
        <div class="mt-6 flex items-start justify-between gap-4 rounded-3xl border border-brand-border bg-brand-light/75 p-4 text-sm text-brand-primary transition duration-300"
             data-flash-alert
             data-flash-delay="4200">
            <div>{{ session('success') }}</div>
            <button type="button" data-flash-dismiss aria-label="Dismiss notification" class="text-lg leading-none text-brand-primary hover:text-brand-secondary">&times;</button>
        </div>
    @endif

    <div class="mt-6 overflow-x-auto">
        <table class="w-full text-left text-sm text-brand-ink/80">
            <thead>
                <tr class="border-b border-brand-border text-brand-secondary">
                    <th class="py-3 pr-4 font-semibold">Heading</th>
                    <th class="py-3 pr-4 font-semibold">Created</th>
                    <th class="py-3 font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aboutSections as $section)
                    <tr class="border-b border-brand-border hover:bg-brand-light/50">
                        <td class="py-4 pr-4">{{ $section->heading }}</td>
                        <td class="py-4 pr-4">{{ $section->created_at->format('M d, Y') }}</td>
                        <td class="py-4 space-x-2">
                            <a href="{{ route('admin.about.show', $section) }}" class="brand-btn-secondary px-4 py-2 text-sm">View</a>
                            <a href="{{ route('admin.about.edit', $section) }}" class="brand-btn-secondary px-4 py-2 text-sm">Edit</a>
                            <form action="{{ route('admin.about.destroy', $section) }}"
                                  method="POST"
                                  class="inline-block"
                                  data-confirm-title="Delete About Content?"
                                  data-confirm-message="Are you sure you want to delete this About content?"
                                  data-confirm-final-title="Final Confirmation"
                                  data-confirm-final-message="This About content will be removed. Are you absolutely sure?"
                                  data-confirm-final-action="Yes, Delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="brand-btn-danger px-4 py-2 text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-8 text-center text-brand-ink/60">No About section content found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin-pagination :paginator="$aboutSections" label="About section pagination" />
</div>
@endsection
