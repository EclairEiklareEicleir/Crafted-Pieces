@extends('layouts.admin')

@section('content')
<div class="rounded-[2rem] border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">FAQ Management</h2>
            <p class="mt-2 text-sm text-brand-ink/70">Create, edit, and manage frequently asked questions on the public website.</p>
        </div>
        <a href="{{ route('admin.faq.create') }}" class="brand-btn-primary px-5 py-2 text-sm">+ New FAQ</a>
    </div>

    @if ($faqs->count())
        <div class="mt-8 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-brand-border">
                    <tr>
                        <th class="px-3 py-3 font-semibold text-brand-primary">Order</th>
                        <th class="px-3 py-3 font-semibold text-brand-primary">Question</th>
                        <th class="px-3 py-3 font-semibold text-brand-primary">Status</th>
                        <th class="px-3 py-3 font-semibold text-brand-primary">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($faqs as $faq)
                        <tr class="border-b border-brand-border/50 hover:bg-brand-light/30 transition">
                            <td class="px-3 py-3 text-brand-ink/70">{{ $faq->order }}</td>
                            <td class="px-3 py-3">
                                <p class="font-medium text-brand-primary">{{ $faq->question }}</p>
                            </td>
                            <td class="px-3 py-3">
                                @if ($faq->active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">Inactive</span>
                                @endif
                            </td>
                            <td class="px-3 py-3">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.faq.edit', $faq) }}" class="font-semibold text-brand-secondary hover:text-brand-primary transition">Edit</a>
                                    <form action="{{ route('admin.faq.destroy', $faq) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-red-600 hover:text-red-700 transition">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $faqs->links() }}
        </div>
    @else
        <div class="mt-8 rounded-3xl border-2 border-dashed border-brand-border bg-brand-light/50 p-8 text-center">
            <p class="text-brand-ink/70">No FAQs yet. <a href="{{ route('admin.faq.create') }}" class="font-semibold text-brand-secondary hover:text-brand-primary">Create the first one</a>.</p>
        </div>
    @endif
</div>
@endsection
