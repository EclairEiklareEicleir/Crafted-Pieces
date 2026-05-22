<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Custom Order Receipt</title>
</head>

<body style="font-family: Arial, sans-serif; color: #3b2a24; line-height: 1.6;">

    <h2 style="margin-bottom: 0;">
        Payment Confirmed
    </h2>

    <p style="margin-top: 6px;">
        Thank you for your payment. Your custom order has now been officially confirmed.
    </p>

    <hr>

    <h3>Order Information</h3>

    <p>
        <strong>Request ID:</strong>
        #{{ $order->id }}
    </p>

    <p>
        <strong>Status:</strong>
        {{ ucfirst($order->status) }}
    </p>

    <p>
        <strong>Paid At:</strong>
        {{ optional($order->paid_at)->format('F d, Y h:i A') }}
    </p>

    <hr>

    <h3>Custom Order Details</h3>

    <p>
        <strong>Item Type:</strong>
        {{ $order->item_type }}
    </p>

    <p>
        <strong>Theme:</strong>
        {{ $order->design_theme ?? 'N/A' }}
    </p>

    <p>
        <strong>Preferred Size:</strong>
        {{ $order->preferred_size ?? 'N/A' }}
    </p>

    <p>
        <strong>Description:</strong>
    </p>

    <p>
        {{ $order->description }}
    </p>

    <hr>

    <h3>Payment Summary</h3>

    <p>
        <strong>Final Price:</strong>
        ₱{{ number_format($order->final_price ?? $order->estimated_price, 2) }}
    </p>

    <hr>

    <p>
        Our team will now begin processing and crafting your order.
    </p>

    <p>
        Thank you for supporting Crafted Pieces.
    </p>

    <p style="margin-top: 30px;">
        Crafted Pieces<br>
        Handmade with care
    </p>

</body>
</html>