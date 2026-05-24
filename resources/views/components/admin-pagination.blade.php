@props([
    'paginator',
    'label' => 'Pagination',
    'summary' => true,
])

@if ($paginator->hasPages())
    <nav class="mt-6 flex flex-col gap-3 rounded-3xl border border-brand-border bg-brand-light/20 p-3 sm:flex-row sm:items-center sm:justify-between" aria-label="{{ $label }}">
        @if ($summary)
            <p class="text-sm text-brand-ink/60">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} records
            </p>
        @else
            <span></span>
        @endif

        <div class="flex flex-wrap items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center rounded-full border border-brand-border bg-white/60 px-4 py-2 text-sm font-semibold text-brand-ink/35">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center rounded-full border border-brand-border bg-white px-4 py-2 text-sm font-semibold text-brand-primary transition hover:border-brand-secondary hover:bg-brand-light/60">
                    Previous
                </a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="inline-flex min-w-10 items-center justify-center rounded-full bg-brand-primary px-4 py-2 text-sm font-semibold text-white">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="inline-flex min-w-10 items-center justify-center rounded-full border border-brand-border bg-white px-4 py-2 text-sm font-semibold text-brand-primary transition hover:border-brand-secondary hover:bg-brand-light/60">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center rounded-full border border-brand-border bg-white px-4 py-2 text-sm font-semibold text-brand-primary transition hover:border-brand-secondary hover:bg-brand-light/60">
                    Next
                </a>
            @else
                <span class="inline-flex items-center rounded-full border border-brand-border bg-white/60 px-4 py-2 text-sm font-semibold text-brand-ink/35">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif