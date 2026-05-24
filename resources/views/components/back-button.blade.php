@props([
    'href' => null,
    'label' => 'Back',
    'variant' => 'secondary',
])

@php
    $target = $href;

    if (! $target) {
        $previous = url()->previous();
        $previousHost = parse_url($previous, PHP_URL_HOST);
        $previousPath = parse_url($previous, PHP_URL_PATH) ?: '';
        $appHost = parse_url(config('app.url') ?: url('/'), PHP_URL_HOST) ?: request()->getHost();

        $target = $previousHost && strcasecmp($previousHost, $appHost) === 0 && ! str_starts_with($previousPath, '/admin')
            ? $previous
            : route('home');
    }

    $classes = $variant === 'primary'
        ? 'brand-btn-primary'
        : 'brand-btn-secondary';
@endphp

<a href="{{ $target }}" {{ $attributes->merge(['class' => $classes . ' gap-2']) }}>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4 shrink-0" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
    </svg>

    <span>{{ $label }}</span>
</a>