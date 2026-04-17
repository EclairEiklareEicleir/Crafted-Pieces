@extends('admin.layouts.admin')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1fr_0.8fr]">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">{{ $order['id'] }}</h2>
            <p class="mt-2 text-sm text-[#6f5a51]">{{ $order['customer_name'] }} | {{ $order['customer_email'] }}</p>
            <div class="mt-6 space-y-4">
                @foreach ($order['items'] as $item)
                    <div class="rounded-3xl bg-[#fcfaf8] p-4">
                        <p class="font-semibold text-[#4d3028]">{{ $item['product_name'] }}</p>
                        <p class="mt-1 text-sm text-[#6f5a51]">Qty: {{ $item['quantity'] }} | PHP {{ number_format($item['price']) }}</p>
                        @if (! empty($item['variant']))
                            <p class="mt-1 text-sm text-[#8f7a70]">Variant: {{ $item['variant'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h3 class="font-display text-2xl font-semibold text-[#4d3028]">Timeline</h3>
            <div class="mt-5 space-y-3 text-sm text-[#6f5a51]">
                <p>Payment: {{ $order['payment_status'] }}</p>
                <p>Method: {{ $order['payment_method'] }}</p>
                <p>Status: {{ str_replace('-', ' ', $order['status']) }}</p>
                <p>Address: {{ $order['shipping_address'] }}</p>
                <p>Updated: {{ $order['updated_at'] }}</p>
            </div>
        </div>
    </div>
@endsection
