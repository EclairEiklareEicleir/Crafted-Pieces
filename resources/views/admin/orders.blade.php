@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Orders</h2>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">
                    <tr>
                        <th class="py-3 pr-4">Order</th>
                        <th class="py-3 pr-4">Customer</th>
                        <th class="py-3 pr-4">Total</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe3da]">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="py-4 pr-4 font-medium text-[#4d3028]">{{ $order['id'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $order['customer_name'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">PHP {{ number_format($order['total']) }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ str_replace('-', ' ', $order['status']) }}</td>
                            <td class="py-4 pr-4"><a href="{{ route('admin.orders.show', $order['id']) }}" class="font-semibold text-[#a86b57]">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
