@extends('layouts.store')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[#a86b57]">History</p>
            <h1 class="mt-2 font-display text-4xl font-semibold text-[#4d3028]">My Orders</h1>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-2">
            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Orders</h2>
                <div class="mt-6 space-y-4">
                    @foreach ($orders as $order)
                        <div class="rounded-3xl border border-[#f0e4db] bg-[#fcfaf8] p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-[#4d3028]">{{ $order['id'] }}</p>
                                    <p class="text-sm text-[#6f5a51]">{{ $order['customer_name'] }} | {{ $order['created_at'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-[#8d5848]">PHP {{ number_format($order['total']) }}</p>
                                    <p class="text-xs text-[#8f6a5d]">{{ str_replace('-', ' ', $order['status']) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
                <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Quotation requests</h2>
                <div class="mt-6 space-y-4">
                    @foreach ($quotations as $quotation)
                        <div class="rounded-3xl border border-[#f0e4db] bg-[#fcfaf8] p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-[#4d3028]">{{ $quotation['id'] }}</p>
                                    <p class="text-sm text-[#6f5a51]">{{ $quotation['customer_name'] }} | {{ $quotation['item_type'] }}</p>
                                </div>
                                <span class="rounded-full bg-[#f4e4d9] px-3 py-1 text-xs font-semibold uppercase tracking-[0.16em] text-[#8d5848]">{{ $quotation['status'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
