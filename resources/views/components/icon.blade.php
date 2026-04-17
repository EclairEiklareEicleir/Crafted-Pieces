@props(['name', 'size' => 'w-5 h-5'])

@php
    $icons = [
        'home' => '<path d="M3 12a9 9 0 1 0 18 0A9 9 0 0 0 3 12Z M9 8h6v8H9z"/><path d="M12 3v6m0 6v3"/>',
        'shop' => '<rect x="2" y="4" width="20" height="3"/><path d="M4 7v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7M8 11v4M12 11v4M16 11v4"/>',
        'custom' => '<path d="M11 4H7a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>',
        'orders' => '<path d="M3 9h18M3 9v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9M3 9l1.5-4.5A2 2 0 0 1 6.5 3h11a2 2 0 0 1 2 1.5L21 9"/><circle cx="8" cy="14" r="1"/><circle cx="16" cy="14" r="1"/>',
        'about' => '<circle cx="12" cy="8" r="4"/><path d="M6 20c0-3.314 2.686-6 6-6s6 2.686 6 6"/>',
        'cart' => '<path d="M9 2L7 6H3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-4l-2-4h-2z M8 10a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>',
        'checkout' => '<path d="M5 9h14M5 9l1.35-6.75A2 2 0 0 1 8.36 1h7.28a2 2 0 0 1 1.97 1.25L19 9M8 13v4M12 13v4M16 13v4"/>',
        'chat' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>',
        'close' => '<path d="M18 6L6 18M6 6l12 12"/>',
        'dashboard' => '<rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>',
        'products' => '<path d="M6 2L2 6v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6l-4-4H6z M6 2v6h12V2"/><circle cx="12" cy="12" r="3"/>',
        'categories' => '<rect x="3" y="3" width="8" height="8"/><rect x="13" y="3" width="8" height="8"/><rect x="3" y="13" width="8" height="8"/><rect x="13" y="13" width="8" height="8"/>',
        'quotations' => '<path d="M3 12h18M3 6h18M3 18h18M9 3v3M12 3v3M15 3v3"/>',
        'payments' => '<rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/><circle cx="8" cy="15" r="1"/>',
        'delivery' => '<path d="M5 18a3 3 0 0 0 0 6h12a3 3 0 0 0 0-6M5 18V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v11M2 8h16v6M9 3v5h6V3"/>',
        'support' => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m4.24 4.24l4.24 4.24M1 12h6m6 0h6M4.22 19.78l4.24-4.24m4.24-4.24l4.24-4.24"/>',
        'back' => '<path d="M19 12H5M12 19l-7-7 7-7"/>',
        'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" fill="currentColor"/><circle cx="17.5" cy="6.5" r="1.5" fill="currentColor"/>',
        'email' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 6l10 7 10-7"/>',
    ];

    $iconSvg = $icons[$name] ?? null;
@endphp

@if ($iconSvg)
    <svg class="{{ $size }} inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes }}>
        {!! $iconSvg !!}
    </svg>
@endif
