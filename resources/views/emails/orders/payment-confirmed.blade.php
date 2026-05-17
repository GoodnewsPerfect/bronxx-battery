<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Payment Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.5;">
    <h1 style="font-size: 22px; margin-bottom: 16px;">Payment confirmed</h1>

    <p>Hello {{ $order->user->name }},</p>

    <p>Thank you for your purchase. We have received your Espees payment for order #{{ $order->id }}.</p>

    <table cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin: 20px 0; width: 100%; max-width: 560px;">
        <tr>
            <td style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">Order ID</td>
            <td style="border-bottom: 1px solid #e5e7eb; font-weight: bold;">#{{ $order->id }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">Payment Method</td>
            <td style="border-bottom: 1px solid #e5e7eb; font-weight: bold;">ESPEES</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #e5e7eb; color: #6b7280;">Amount Paid</td>
            <td style="border-bottom: 1px solid #e5e7eb; font-weight: bold;">{{ $amountPaid }}</td>
        </tr>
    </table>

    <h2 style="font-size: 18px; margin-top: 24px;">Order items</h2>

    <table cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 560px;">
        <thead>
            <tr>
                <th align="left" style="border-bottom: 1px solid #e5e7eb;">Item</th>
                <th align="left" style="border-bottom: 1px solid #e5e7eb;">Quantity</th>
                <th align="right" style="border-bottom: 1px solid #e5e7eb;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td style="border-bottom: 1px solid #f3f4f6;">{{ $item->product_name }}</td>
                    <td style="border-bottom: 1px solid #f3f4f6;">{{ $item->quantity }}</td>
                    <td align="right" style="border-bottom: 1px solid #f3f4f6;">{{ $item->subtotal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 24px;">We will process your order and keep you updated.</p>

    <p>Thank you for choosing {{ $appName }}.</p>
</body>
</html>
