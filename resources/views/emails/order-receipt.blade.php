<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Receipt</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f7f3f0; padding:20px; color:#3b2a24;">

    <div style="max-width:600px; margin:0 auto; background:#ffffff; border-radius:16px; padding:24px; border:1px solid #eadfd7;">

        <h2 style="margin-bottom:10px;">
            Thank you for your order!
        </h2>

        <p style="margin-bottom:20px;">
            Hi, we’ve received your order and are currently processing it.
        </p>

        <hr style="border:none; border-top:1px solid #eadfd7; margin:20px 0;">

        <p><strong>Order ID:</strong> {{ $order->id }}</p>

        <p><strong>Status:</strong> {{ $order->status ?? 'Pending' }}</p>

        <p><strong>Total:</strong> ₱{{ number_format($order->total_amount ?? 0, 2) }}</p>

        <hr style="border:none; border-top:1px solid #eadfd7; margin:20px 0;">

        <p style="font-size:14px; color:#6f5a51;">
            We will notify you once your order has been processed and shipped.
        </p>

        <p style="font-size:12px; color:#a08a82; margin-top:30px;">
            Crafted Pieces • Handmade with care
        </p>

    </div>

</body>
</html>