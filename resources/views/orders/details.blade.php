<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - Order Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            color: #333;
        }
        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Times New Roman', serif;
            font-weight: 300;
            letter-spacing: 2px;
            color: #5d1d48 !important;
        }
        .nav-link {
            color: #5d1d48 !important;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle) {
            position: relative;
            color: #000;
            transition: color 0.3s ease;
        }
        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle)::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 2px;
            width: 0;
            background-color: #5d1d48;
            transition: width 0.3s ease;
        }
        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle):hover::after {
            width: 100%;
        }
        footer {
            background-color: #f8f8f8;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid #eee;
            text-align: center;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links a {
            color: #5d1d48;
            text-decoration: none;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
@include('components.navbar')

{{-- Order Details Content --}}
<div class="container mt-5">
    <h3>Order Details</h3>

    <div class="card mt-4">
        <div class="card-header">
            Order #{{ $order->id }} — <strong>{{ ucfirst($order->status) }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Full Name:</strong> {{ $order->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->email }}</p>
            <p><strong>Address:</strong> {{ $order->address }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($order->total, 2) }}</p>

            {{-- Cancel Order Button --}}
            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                    @csrf
                    <button type="submit" class="btn btn-danger mt-3">Cancel Order</button>
                </form>
            @elseif($order->status === 'cancelled')
                <div class="alert alert-warning mt-3">This order has been cancelled.</div>
            @endif

        </div>
    </div>

    <h5 class="mt-5">Items</h5>
    <div class="table-responsive">
        <table class="table table-bordered mt-2">
            <thead class="table-light">
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price (₱)</th>
                    <th>Subtotal (₱)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td>{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a href="{{ route('orders.my') }}" class="btn btn-secondary mt-3">← Back</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
