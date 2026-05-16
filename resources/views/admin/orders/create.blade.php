@extends('layouts.admin')

@section('content')

<div class="max-w-3xl mx-auto rounded-[2rem] border border-[#eadfd7] bg-white p-6 shadow-sm">

    <h2 class="font-display text-2xl font-semibold text-[#4d3028]">
        Create Manual Order
    </h2>

    <p class="mt-2 text-sm text-[#6f5a51]">
        Admin-only order creation (bypasses cart system)
    </p>

    <form method="POST" action="{{ route('admin.orders.store') }}" class="mt-6 space-y-4">

        @csrf

        {{-- CUSTOMER INFO --}}
        <div class="grid gap-3 md:grid-cols-2">

            <input type="text"
                   name="full_name"
                   placeholder="Full Name"
                   class="rounded-2xl border px-4 py-3"
                   required>

            <input type="email"
                   name="email"
                   placeholder="Email"
                   class="rounded-2xl border px-4 py-3"
                   required>

        </div>

        {{-- SHIPPING --}}
        <textarea name="shipping_address"
                  rows="3"
                  placeholder="Shipping Address"
                  class="w-full rounded-2xl border px-4 py-3"
                  required></textarea>

        {{-- PAYMENT METHOD --}}
        <input type="text"
               name="payment_method"
               placeholder="Payment Method (e.g. COD, GCASH)"
               class="w-full rounded-2xl border px-4 py-3"
               required>

        {{-- TOTAL AMOUNT --}}
        <input type="number"
               step="0.01"
               name="total_amount"
               placeholder="Total Amount"
               class="w-full rounded-2xl border px-4 py-3"
               required>

        {{-- STATUS --}}
        <select name="status" class="w-full rounded-2xl border px-4 py-3">

            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="out_for_delivery">Out for Delivery</option>
            <option value="delivered">Delivered</option>
            <option value="received">Received</option>
            <option value="cancelled">Cancelled</option>

        </select>

        {{-- BUTTON --}}
        <button class="w-full rounded-full bg-[#5d342b] py-3 text-white">
            Create Order
        </button>

    </form>

</div>
@endsection