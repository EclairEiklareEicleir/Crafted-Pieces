@props(['product'])

<a href="{{ route('product.show', $product['slug']) }}" class="group overflow-hidden rounded-[1.75rem] border border-[#eadfd7] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    <div class="aspect-[4/3] bg-gradient-to-br from-[#f7ece5] to-[#f0d7cb] p-6">
        <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" class="h-full w-full rounded-2xl object-cover transition duration-300 group-hover:scale-[1.03]">
    </div>
    <div class="p-5">
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#a86b57]">{{ $product['category'] }}</p>
        <h3 class="mt-2 font-semibold text-[#4d3028]">{{ $product['name'] }}</h3>
        <p class="mt-2 text-sm text-[#6f5a51]">{{ $product['short_description'] }}</p>
        <div class="mt-4 flex items-center justify-between text-sm font-semibold">
            <span class="text-[#8d5848]">PHP {{ number_format($product['price']) }}</span>
            <span class="text-[#8a7a70]">{{ $product['fulfillment'] === 'ready-stock' ? 'Ready stock' : 'Made to order' }}</span>
        </div>
    </div>
</a>