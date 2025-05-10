<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Metro Essence</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            color: #333;
        }
        .section-title {
            font-size: 1.5rem;
            color: #5d1d48;
            font-weight: 500;
            text-align: center;
            margin-bottom: 30px;
        }
        .product-img {
            max-width: 80px;
            height: auto;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container mt-5">
        <h2 class="section-title mb-4">Your Cart</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="alert alert-info">Your cart is empty.</div>
        @else
            <form method="POST" action="{{ url('/checkout') }}">
                @csrf

                <ul class="list-group mb-4">
                    @php $cartTotal = 0; @endphp

                    @foreach ($cartItems as $item)
                        @if ($item->product)
                            @php
                                $itemTotal = $item->product->price * $item->quantity;
                                $cartTotal += $itemTotal;
                                $folder = strtoupper($item->product->type);
                                $imagePath = 'images/' . $folder . '/' . $item->product->image;
                            @endphp
                            <li class="list-group-item d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ asset($imagePath) }}" alt="{{ $item->product->name }}" class="product-img">
                                </div>
                                <div class="flex-grow-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="cartItem{{ $item->id }}">
                                        <label class="form-check-label" for="cartItem{{ $item->id }}">
                                            <strong>{{ $item->product->name }}</strong><br>
                                            ₱{{ number_format($item->product->price, 2) }} x {{ $item->quantity }}
                                        </label>
                                    </div>
                                </div>
                                <span>₱{{ number_format($itemTotal, 2) }}</span>
                            </li>
                        @else
                            <li class="list-group-item">
                                <div class="text-danger">Product not found</div>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Total: ₱{{ number_format($cartTotal, 2) }}</h4>
                    <button type="submit" class="btn btn-success">Checkout Selected</button>
                </div>
            </form>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
