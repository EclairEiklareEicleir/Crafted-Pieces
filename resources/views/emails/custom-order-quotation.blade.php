<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Custom Order Quotation</title>
</head>

<body style="font-family: Arial, sans-serif; color: #3b2a24; line-height: 1.6;">

    <h2>
        Your Custom Order Quotation is Ready
    </h2>

    <p>
        Hello {{ $order->name }},
    </p>

    <p>
        Our team has reviewed your custom crochet request and prepared a final quotation.
    </p>

    <hr>

    <h3>Quotation Details</h3>

    <p>
        <strong>Request ID:</strong>
        #{{ $order->id }}
    </p>

    <p>
        <strong>Item Type:</strong>
        {{ $order->item_type }}
    </p>

    <p>
        <strong>Final Price:</strong>
        ₱{{ number_format($order->final_price, 2) }}
    </p>

    <p>
        <strong>Admin Notes:</strong>
    </p>

    <p>
        {{ $order->admin_notes ?? 'No additional notes provided.' }}
    </p>

    <hr>

    <p>
        Please login to your account to review and proceed with payment.
    </p>

    <p>
        Crafted Pieces<br>
        Handmade with care
    </p>

</body>
</html>