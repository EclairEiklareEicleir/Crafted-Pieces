@props([
    'active' => true,
])

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" {{ $attributes->merge(['aria-hidden' => 'true']) }}>
    <path stroke-linecap="round" stroke-linejoin="round" d="M8.5 4.75A6.75 6.75 0 1 0 19.2 9.9c0-3.72-2.94-5.15-5.95-4.65-2.1.35-3.7 1.54-4.75 3.2Z" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 14.25c1.5-2.2 3.9-3.25 6.75-3.25 2.5 0 4.38.76 5.75 2.25" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M7.25 18.5c1.55-1.55 3.55-2.35 6-2.35 2.75 0 4.98 1.03 6.75 3.1" />
    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 9.75c1.05-.95 2.55-1.5 4.5-1.5" />
</svg>