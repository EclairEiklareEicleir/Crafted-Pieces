@extends('layouts.admin')

@section('content')

<div class="rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-secondary">
                Custom Requests
            </p>

            <h1 class="mt-2 font-display text-3xl font-semibold text-brand-primary">
                Commission Inbox
            </h1>

            <p class="mt-2 text-sm text-brand-ink/60">
                Separate active requests from completed or resolved work without changing the custom order workflow.
            </p>
        </div>

        <x-back-button href="{{ route('admin.dashboard') }}" label="Back to Dashboard" />
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
        <a href="#active-custom-orders" class="brand-btn-primary px-5 py-2 text-sm">
            Active ({{ $activeRequests->count() }})
        </a>
        <a href="#resolved-custom-orders" class="brand-btn-secondary px-5 py-2 text-sm">
            Completed / Resolved ({{ $resolvedRequests->count() }})
        </a>
    </div>
</div>

<div id="active-custom-orders" class="mt-6 scroll-mt-28 rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Active Custom Orders
            </h2>
            <p class="mt-1 text-sm text-brand-ink/60">
                Requests still waiting for admin action, customer action, payment, or completion.
            </p>
        </div>
        <span class="brand-pill">{{ $activeRequests->count() }} active</span>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($activeRequests as $request)
            @include('admin.custom.partials.request-card', ['customRequest' => $request])
        @empty
            <p class="rounded-3xl border border-dashed border-brand-border bg-brand-light/30 p-6 text-center text-sm text-brand-ink/70">
                No active custom orders right now.
            </p>
        @endforelse
    </div>
</div>

<div id="resolved-custom-orders" class="mt-6 scroll-mt-28 rounded-4xl border border-brand-border bg-white p-6 shadow-sm">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-display text-2xl font-semibold text-brand-primary">
                Completed / Resolved Custom Orders
            </h2>
            <p class="mt-1 text-sm text-brand-ink/60">
                Finished, rejected, cancelled, refunded, or otherwise closed requests.
            </p>
        </div>
        <span class="brand-pill">{{ $resolvedRequests->count() }} resolved</span>
    </div>

    <div class="mt-6 space-y-4">
        @forelse ($resolvedRequests as $request)
            @include('admin.custom.partials.request-card', ['customRequest' => $request])
        @empty
            <p class="rounded-3xl border border-dashed border-brand-border bg-brand-light/30 p-6 text-center text-sm text-brand-ink/70">
                No completed or resolved custom orders yet.
            </p>
        @endforelse
    </div>
</div>

@endsection
