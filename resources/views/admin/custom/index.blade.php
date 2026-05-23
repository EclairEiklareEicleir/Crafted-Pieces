@extends('layouts.admin')

@section('content')

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">

    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Custom Requests
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Commission Inbox
            </h1>
        </div>

        <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
    </div>

    <div class="mt-8 space-y-4">

        @forelse ($requests as $request)

            <div class="rounded-3xl border border-brand-border bg-brand-light/35 p-5 transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="font-semibold text-brand-primary">
                            #{{ $request->id }} — {{ $request->item_type }}
                        </p>

                        <p class="mt-1 text-sm text-brand-ink/70">
                            {{ $request->name }}
                        </p>
                    </div>

                    <div class="text-right">

                        <p class="font-semibold text-brand-secondary">
                            PHP {{ number_format($request->estimated_price, 2) }}
                        </p>

                        <div class="mt-2 flex flex-wrap justify-end gap-2">
                            <x-status-badge :status="$request->status" context="custom" />
                            <x-status-badge :status="$request->payment_status ?? 'unpaid'" context="payment" />
                        </div>

                    </div>

                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('admin.custom.show', $request->id) }}"
                       class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
                        View
                    </a>

                    <form method="POST"
                          action="{{ route('admin.custom.destroy', $request->id) }}"
                          onsubmit="return confirm('Are you sure you want to delete this custom order request?');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-full border border-[#f0c5cf] bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                            Delete
                        </button>
                    </form>
                </div>

            </div>

        @empty

            <p class="text-sm text-brand-ink/70">
                No custom requests yet.
            </p>

        @endforelse

    </div>

</div>

@endsection