@props(['item'])

@php
    $image = $item->image;

    $imageUrl = $image
        ? (Str::startsWith($image, 'http')
            ? $image
            : asset('storage/' . $image))
        : 'https://placehold.co/600x600/png';
@endphp

<div class="flex items-center justify-between gap-4 py-5">

    {{-- LEFT SIDE --}}
    <div class="flex items-center gap-4">
        <img
            src="{{ $imageUrl }}"
            class="h-20 w-20 rounded-2xl object-cover border border-[#eadfd7]"
        >

        <div>

            <p class="font-semibold text-[#4d3028]">
                {{ $item->name }}
            </p>

            <p class="mt-1 text-sm text-[#8d5848]">
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
                       class="w-20 rounded-xl border px-3 py-1.5 text-sm">

                <button class="text-xs font-semibold text-[#5d342b]">
                    Update
                </button>

            </form>

            {{-- REMOVE --}}
            <form method="POST"
                  action="{{ route('cart.items.remove', $item->id) }}"
                  class="mt-2">

                @csrf
                @method('DELETE')

                <button class="text-xs text-red-600 hover:text-red-800">
                    Remove
                </button>

            </form>

        </div>

    </div>

    {{-- RIGHT SIDE --}}
    <p class="font-semibold text-[#8d5848]">
        PHP {{ number_format($item->quantity * $item->price) }}
    </p>

</div>