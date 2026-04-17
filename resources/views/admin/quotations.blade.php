@extends('admin.layouts.admin')

@section('content')
    <div class="rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">
        <h2 class="font-display text-2xl font-semibold text-[#4d3028]">Quotations</h2>
        <div class="mt-6 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.16em] text-[#8f7a70]">
                    <tr>
                        <th class="py-3 pr-4">Request</th>
                        <th class="py-3 pr-4">Theme</th>
                        <th class="py-3 pr-4">Status</th>
                        <th class="py-3 pr-4">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#efe3da]">
                    @foreach ($quotations as $quotation)
                        <tr>
                            <td class="py-4 pr-4 font-medium text-[#4d3028]">{{ $quotation['id'] }} | {{ $quotation['customer_name'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $quotation['design_theme'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ $quotation['status'] }}</td>
                            <td class="py-4 pr-4 text-[#6f5a51]">{{ isset($quotation['quoted_price']) ? 'PHP ' . number_format($quotation['quoted_price']) : 'TBD' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
