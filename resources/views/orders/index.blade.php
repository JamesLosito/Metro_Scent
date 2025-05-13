<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* ...your CSS styles (same as before)... */
    </style>
</head>
<style>
    body {
        font-family: 'Times New Roman', serif;
        background-color: #fefefe;
        color: #333;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 500;
        color: #5d1d48;
        margin-bottom: 30px;
        text-align: center;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 10px;
    }

    .card-header {
        background-color: #f8f8f8;
        font-size: 1rem;
        font-weight: 500;
        color: #5d1d48;
    }

    .card-body p {
        margin-bottom: 10px;
        font-size: 0.95rem;
    }

    .btn-primary {
        background-color: #5d1d48;
        border-color: #5d1d48;
        font-size: 0.85rem;
        padding: 6px 16px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .btn-primary:hover {
        background-color: #451536;
        border-color: #451536;
    }

    .alert-info {
        background-color: #f2f2f2;
        color: #5d1d48;
        border-left: 4px solid #5d1d48;
    }
</style>

<body>
    @include('components.navbar')

    <div class="container mt-5">
        <h3 class="section-title">My Orders</h3>

        @forelse($orders as $order)
            <div class="card mt-3 shadow-sm">
                <div class="card-header bg-light">
                    <strong>Order #{{ $order->id }}</strong> — <span class="text-muted">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    <p><strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
                    <p><strong>Total:</strong> ₱{{ number_format($order->total, 2) }}</p>
                    <a href="{{ route('orders.details', $order->id) }}" class="btn btn-sm btn-primary">View Details</a>
                </div>
            </div>
        @empty
            <div class="alert alert-info mt-3">You have no orders.</div>
        @endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
