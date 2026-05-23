@props([
    'status' => null,
    'label' => null,
    'context' => 'generic',
])

@php
    $normalized = is_string($status) ? strtolower(trim($status)) : null;

    $maps = [
        'order' => [
            'pending' => ['label' => 'Pending', 'classes' => 'bg-amber-100 text-amber-800 ring-1 ring-amber-200'],
            'processing' => ['label' => 'Processing', 'classes' => 'bg-sky-100 text-sky-800 ring-1 ring-sky-200'],
            'shipped' => ['label' => 'Shipped', 'classes' => 'bg-cyan-100 text-cyan-800 ring-1 ring-cyan-200'],
            'out_for_delivery' => ['label' => 'Out for Delivery', 'classes' => 'bg-indigo-100 text-indigo-800 ring-1 ring-indigo-200'],
            'delivered' => ['label' => 'Delivered', 'classes' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200'],
            'received' => ['label' => 'Received', 'classes' => 'bg-teal-100 text-teal-800 ring-1 ring-teal-200'],
            'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
        ],
        'payment' => [
            'paid' => ['label' => 'Paid', 'classes' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200'],
            'unpaid' => ['label' => 'Unpaid', 'classes' => 'bg-rose-100 text-rose-800 ring-1 ring-rose-200'],
            'pending' => ['label' => 'Pending', 'classes' => 'bg-amber-100 text-amber-800 ring-1 ring-amber-200'],
            'failed' => ['label' => 'Failed', 'classes' => 'bg-red-100 text-red-800 ring-1 ring-red-200'],
            'refunded' => ['label' => 'Refunded', 'classes' => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200'],
            'expired' => ['label' => 'Expired', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
            'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
        ],
        'toggle' => [
            'active' => ['label' => 'Active', 'classes' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200'],
            'inactive' => ['label' => 'Inactive', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
            'enabled' => ['label' => 'Enabled', 'classes' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200'],
            'disabled' => ['label' => 'Disabled', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
        ],
        'custom' => [
            'pending' => ['label' => 'Discussion', 'classes' => 'bg-fuchsia-100 text-fuchsia-800 ring-1 ring-fuchsia-200'],
            'quoted' => ['label' => 'Quoted', 'classes' => 'bg-pink-100 text-pink-800 ring-1 ring-pink-200'],
            'awaiting_payment' => ['label' => 'Awaiting Payment', 'classes' => 'bg-amber-100 text-amber-800 ring-1 ring-amber-200'],
            'paid' => ['label' => 'Paid', 'classes' => 'bg-emerald-100 text-emerald-800 ring-1 ring-emerald-200'],
            'in_progress' => ['label' => 'In Progress', 'classes' => 'bg-indigo-100 text-indigo-800 ring-1 ring-indigo-200'],
            'completed' => ['label' => 'Completed', 'classes' => 'bg-teal-100 text-teal-800 ring-1 ring-teal-200'],
            'rejected' => ['label' => 'Rejected', 'classes' => 'bg-stone-100 text-stone-700 ring-1 ring-stone-200'],
        ],
        'generic' => [],
    ];

    $map = $maps[$context] ?? $maps['generic'];
    $fallbackLabel = $label ?: ($normalized ? ucwords(str_replace('_', ' ', $normalized)) : 'Unknown');

    $resolved = $normalized && isset($map[$normalized])
        ? $map[$normalized]
        : [
            'label' => $fallbackLabel,
            'classes' => 'bg-brand-light text-brand-primary ring-1 ring-brand-border',
        ];

    if ($label !== null) {
        $resolved['label'] = $label;
    }
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap ' . $resolved['classes']]) }}>
    {{ $resolved['label'] }}
</span>