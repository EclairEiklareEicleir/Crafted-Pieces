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

            <a href="{{ route('admin.custom.show', $request->id) }}"
               class="block rounded-3xl border border-brand-border bg-brand-light/35 p-5 transition hover:-translate-y-0.5 hover:shadow-md">

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

            </a>

        @empty

            <p class="text-sm text-brand-ink/70">
                No custom requests yet.
            </p>

        @endforelse

    </div>

</div>

@endsection