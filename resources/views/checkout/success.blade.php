<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - Metro Essence</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
        }
        .section-title {
            color: #5d1d48;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        .success-icon {
            color: #28a745;
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        .order-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }
        .order-item {
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container mt-5">
        <div class="text-center">
            <div class="success-icon">✓</div>
            <h2 class="section-title">Order Placed Successfully!</h2>
            
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <p class="lead">Thank you for your purchase. Your order has been received and is being processed.</p>
            
            <div class="order-details">
                <h3 class="mb-4">Order Details</h3>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                        <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total, 2) }}</p>
                    </div>
                </div>

                <h4 class="mb-3">Items</h4>
                @foreach($order->orderItems as $item)
                    <div class="order-item">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-0">{{ $item->product->name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p class="mb-0">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="col-md-3 text-end">
                                <p class="mb-0">₱{{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    <h4>Shipping Information</h4>
                    <p><strong>Name:</strong> {{ $order->full_name }}</p>
                    <p><strong>Email:</strong> {{ $order->email }}</p>
                    <p><strong>Address:</strong> {{ $order->address }}</p>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 