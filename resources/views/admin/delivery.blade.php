@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Delivery</h2>
        <p class="mt-2 text-sm text-[#6f5a51]">Admin overview for delivery management.</p>
        <div class="mt-6 grid gap-4 lg:grid-cols-2">
            @foreach ($orders as $order)
                <div class="rounded-3xl bg-[#fcfaf8] p-4">
                    <p class="font-semibold text-[#4d3028]">{{ $order['id'] }}</p>
                    <p class="mt-1 text-sm text-[#6f5a51]">{{ $order['customer_name'] }} | {{ $order['shipping_address'] }}</p>
                    <p class="mt-1 text-sm text-[#8f7a70]">{{ str_replace('-', ' ', $order['status']) }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
