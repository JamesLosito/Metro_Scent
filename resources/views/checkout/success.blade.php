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
            
            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 