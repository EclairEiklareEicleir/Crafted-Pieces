@php
    $logoPath = public_path('images/crafted_pieces_logo.png');
    $logoData = file_exists($logoPath)
        ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
        : null;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #4d3028;
            background: #fff7fb;
        }

        .card {
            border: 1px solid #f1d4de;
            border-radius: 24px;
            padding: 28px;
            background: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 22px;
        }

        .logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            display: inline-block;
            margin-bottom: 10px;
        }

        .brand {
            font-size: 18px;
            font-weight: bold;
        }

        .muted {
            color: #7a6a63;
            font-size: 11px;
        }

        .panel {
            border: 1px solid #f1d4de;
            border-radius: 18px;
            padding: 14px;
            margin-bottom: 16px;
            background: #fff8fb;
        }

        .grid {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .grid th,
        .grid td {
            border-bottom: 1px solid #eee2e7;
            padding: 10px 8px;
            text-align: left;
        }

        .grid th {
            background: #fdf3f7;
            color: #8f3153;
            font-size: 10px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            background: #fce6ed;
            color: #8f3153;
        }

        .total {
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="card">
    <div class="header">
        @if ($logoData)
            <img src="{{ $logoData }}" class="logo" alt="Crafted Pieces">
        @endif

        <div class="brand">Crafted Pieces</div>
        <div class="muted">Official Order Receipt</div>
    </div>

    <div class="panel">
        <table class="grid" style="margin-top:0;">
            <tbody>
                <tr>
                    <td><strong>Order ID:</strong> #{{ $order->id }}</td>
                    <td class="right"><strong>Date:</strong> {{ $order->created_at->format('M d, Y - h:i A') }}</td>
                </tr>
                <tr>
                    <td><strong>Customer:</strong> {{ $order->full_name }}</td>
                    <td class="right"><strong>Email:</strong> {{ $order->email }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong> {{ $order->payment_method }}</td>
                    <td class="right"><span class="badge">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                </tr>
                @if ($order->shipping_address)
                    <tr>
                        <td colspan="2"><strong>Shipping:</strong> {{ $order->shipping_address }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <table class="grid">
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
                    <td>
                        {{ $item->product->name ?? 'Deleted Product' }}
                        @if ($item->yarnColor?->name || $item->variant_name)
                            <div class="muted">Yarn color: {{ $item->yarnColor?->name ?? $item->variant_name }}</div>
                        @endif
                    </td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">PHP {{ number_format($item->price, 2) }}</td>
                    <td class="right">PHP {{ number_format($item->quantity * $item->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="panel" style="margin-top:16px;">
        <table class="grid" style="margin-top:0;">
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
                    <td class="right total">PHP {{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="muted" style="text-align:center; margin-top:18px;">
        Thank you for supporting handmade crochet pieces.
    </div>
</div>

</body>
</html>
