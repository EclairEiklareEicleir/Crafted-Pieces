@props([
    'href' => null,
    'label' => 'Back',
    'variant' => 'secondary',
])

@php
    $target = $href ?: url()->previous();
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