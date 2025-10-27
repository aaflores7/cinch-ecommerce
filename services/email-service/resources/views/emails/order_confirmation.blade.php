<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation</title>
</head>
<body>
    <h1>Thank you for your order, {{ $order['customer_name'] }}!</h1>
    <p>We've received your order and will process it shortly.</p>
    <h2>Order Summary</h2>
    <p><strong>Order ID:</strong> {{ $order['id'] }}</p>
    <p><strong>Total Amount:</strong> ${{ number_format($order['total_amount'], 2) }}</p>
    <h3>Items:</h3>
    <ul>
        @foreach ($order['items'] as $item)
            <li>
                {{ $item['product_name'] }} -
                Quantity: {{ $item['quantity'] }} -
                Price: ${{ number_format($item['price'], 2) }}
            </li>
        @endforeach
    </ul>
    <p>Thank you for shopping with us!</p>
</body>
</html>