@extends('admin.layouts.admin')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <div class="rounded-[1.75rem] border border-[#eadfd7] bg-white p-5 shadow-sm">
                <p class="text-sm text-[#8f7a70]">{{ $stat['label'] }}</p>
                <p class="mt-3 text-3xl font-semibold text-[#4d3028]">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Recent Orders</h2>
            <div class="mt-5 space-y-4">
                @foreach ($recentOrders as $order)
                    <div class="flex items-center justify-between rounded-3xl bg-[#fcfaf8] px-4 py-3">
                        <div>
                            <p class="font-semibold text-[#4d3028]">{{ $order['id'] }}</p>
                            <p class="text-sm text-[#6f5a51]">{{ $order['customer_name'] }} | {{ $order['created_at'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-[#8d5848]">PHP {{ number_format($order['total']) }}</p>
                            <p class="text-xs text-[#8f6a5d]">{{ str_replace('-', ' ', $order['status']) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
            <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Pending Quotations</h2>
            <div class="mt-5 space-y-4">
                @foreach ($quotations as $quotation)
                    <div class="flex items-center justify-between rounded-3xl bg-[#fcfaf8] px-4 py-3">
                        <div>
                            <p class="font-semibold text-[#4d3028]">{{ $quotation['id'] }}</p>
                            <p class="text-sm text-[#6f5a51]">{{ $quotation['customer_name'] }} | {{ $quotation['item_type'] }}</p>
                        </div>
                        <span class="rounded-full bg-[#f4e4d9] px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#8d5848]">{{ $quotation['status'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
