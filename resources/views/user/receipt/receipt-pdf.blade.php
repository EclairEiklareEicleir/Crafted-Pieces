<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #4a3b36;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .brand {
            font-size: 18px;
            font-weight: bold;
        }
        .muted {
            color: #7a6a63;
            font-size: 11px;
        }
        .box {
            border: 1px solid #e5d7cf;
            padding: 10px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border-bottom: 1px solid #eee;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f8f4f2;
        }
        .right {
            text-align: right;
        }
        .total {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="header">
    <div class="brand">the_crafted_pieces</div>
    <div class="muted">Official Order Receipt</div>
</div>

<div class="box">
    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y - h:i A') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Customer:</strong> {{ $order->full_name }}</p>
    <p><strong>Email:</strong> {{ $order->email }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Item</th>
            <th class="right">Qty</th>
            <th class="right">Price</th>
            <th class="right">Total</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">PHP {{ number_format($item->price, 2) }}</td>
                <td class="right">
                    PHP {{ number_format($item->quantity * $item->price, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- BREAKDOWN --}}
<div class="box" style="margin-top:15px;">

    <table style="margin-top:0;">
        <tbody>

            <tr>
                <td>Subtotal</td>
                <td class="right">PHP {{ number_format($order->subtotal, 2) }}</td>
            </tr>

            <tr>
                <td>Platform Fee</td>
                <td class="right">PHP {{ number_format($order->platform_fee, 2) }}</td>
            </tr>

            <tr>
                <td>Delivery Fee</td>
                <td class="right">PHP {{ number_format($order->delivery_fee, 2) }}</td>
            </tr>

            <tr>
                <td>VAT</td>
                <td class="right">PHP {{ number_format($order->vat_amount, 2) }}</td>
            </tr>

            <tr>
                <td class="total">Total Paid</td>
                <td class="right total">
                    PHP {{ number_format($order->total_amount, 2) }}
                </td>
            </tr>

        </tbody>
    </table>

</div>

<div class="muted" style="text-align:center; margin-top:20px;">
    Thank you for supporting handmade crochet pieces ❤️
</div>

</body>
</html>