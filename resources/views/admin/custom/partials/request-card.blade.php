@php
    $amount = $customRequest->final_price ?? $customRequest->estimated_price ?? 0;
@endphp

<div class="rounded-3xl border border-brand-border bg-brand-light/35 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="font-semibold text-brand-primary">
                #{{ $customRequest->id }} - {{ $customRequest->item_type }}
            </p>

            <p class="mt-1 text-sm text-brand-ink/70">
                {{ $customRequest->name }}
            </p>

            <p class="mt-2 text-xs text-brand-ink/55">
                {{ $customRequest->created_at?->format('M d, Y h:i A') ?? 'No date' }}
            </p>
        </div>

        <div class="lg:text-right">
            <p class="font-semibold text-brand-secondary">
                PHP {{ number_format($amount, 2) }}
            </p>

            <div class="mt-2 flex flex-wrap gap-2 lg:justify-end">
                <x-status-badge :status="$customRequest->status" context="custom" />
                <x-status-badge :status="$customRequest->payment_status ?? 'unpaid'" context="payment" />
            </div>
        </div>
    </div>

    <div class="mt-4 flex flex-wrap gap-2">
        <a href="{{ route('admin.custom.show', $customRequest->id) }}"
           class="inline-flex items-center gap-2 rounded-full border border-brand-border bg-white px-3 py-2 text-xs font-semibold text-brand-secondary transition hover:border-brand-secondary hover:bg-brand-light/40">
            View
        </a>

        <form method="POST"
              action="{{ route('admin.custom.destroy', $customRequest->id) }}"
              data-confirm-title="Delete Custom Order Request?"
              data-confirm-message="Are you sure you want to delete this custom order request?"
              data-confirm-final-title="Final Confirmation"
              data-confirm-final-message="This action may remove this custom order request. Are you absolutely sure?"
              data-confirm-final-action="Yes, Delete">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-full border border-[#f0c5cf] bg-white px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                Delete
            </button>
        </form>
    </div>
</div>