@props(['item'])

@php
    $product = $item->product;
    $variant = $item->productVariant;
    $selectedColorName = $item->yarnColor?->name ?? $item->variant_name;
    $selectedColorHex = $item->yarnColor?->hex_color ?? $item->variant_hex_color;
    $imageUrl = $variant?->floating_image_url
        ?: \App\Support\ProductImage::floatingUrl($item->variant_image_path)
        ?: $product?->floating_image_url;
    $imageUrl = $imageUrl ?: 'https://placehold.co/600x600/png';
@endphp

<div class="flex items-center justify-between gap-4 py-5">

    {{-- LEFT SIDE --}}
    <div class="flex items-center gap-4">
        <span class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl border border-brand-border bg-brand-surface p-2">
            <img src="{{ $imageUrl }}" class="h-full w-full object-contain drop-shadow-[0_10px_14px_rgba(101,12,42,0.14)]" alt="{{ $product?->name ?? 'Product' }}">
        </span>

        <div>

            <p class="font-semibold text-brand-primary">
                {{ $product?->name ?? 'Deleted Product' }}
            </p>

            @if ($selectedColorName)
                <p class="mt-1 inline-flex items-center gap-2 rounded-full border border-brand-border bg-brand-light/40 px-3 py-1 text-xs font-semibold text-brand-primary">
                    Yarn color: {{ $selectedColorName }}
                    @if ($selectedColorHex)
                        <span class="h-3 w-3 rounded-full border border-brand-border" style="background-color: {{ $selectedColorHex }}"></span>
                        <span class="text-[10px] font-medium text-brand-ink/55">{{ $selectedColorHex }}</span>
                    @endif
                </p>
            @endif

            <p class="mt-1 text-sm text-brand-secondary">
                PHP {{ number_format($item->price) }}
            </p>

            {{-- UPDATE --}}
            <form method="POST"
                  action="{{ route('cart.items.update', $item->id) }}"
                  class="mt-3 flex items-center gap-2">

                @csrf
                @method('PATCH')

                <input type="number"
                       name="quantity"
                       value="{{ $item->quantity }}"
                       min="1"
                       max="99"
                      class="w-20 rounded-xl border border-brand-border px-3 py-1.5 text-sm focus:border-brand-secondary focus:outline-none">

                  <button class="text-xs font-semibold text-brand-primary hover:text-brand-secondary">
                    Update
                </button>

            </form>

            {{-- REMOVE --}}
            <form method="POST"
                  action="{{ route('cart.items.remove', $item->id) }}"
                  class="mt-2">

                @csrf
                @method('DELETE')

                <button class="text-xs text-brand-secondary hover:text-brand-primary">
                    Remove
                </button>

            </form>

        </div>

    </div>

    {{-- RIGHT SIDE --}}
    <p class="font-semibold text-brand-secondary">
        PHP {{ number_format($item->quantity * $item->price) }}
    </p>

</div>
