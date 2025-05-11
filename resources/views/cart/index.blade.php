<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Metro Essence</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        .quantity-controls {
            display: flex;
            align-items: center;
        }
        .quantity-controls button {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            font-size: 18px;
        }
        .quantity-controls input {
            width: 40px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 5px;
            margin: 0 5px;
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
                                            ₱{{ number_format($item->product->price, 2) }} x <span class="quantity-display">{{ $item->quantity }}</span>
                                        </label>
                                    </div>
                                    <div class="quantity-controls">
                                        <button type="button" class="btn btn-sm btn-outline-secondary decrement" data-id="{{ $item->id }}">-</button>
                                        <input type="number" value="{{ $item->quantity }}" class="form-control form-control-sm quantity" data-id="{{ $item->id }}" min="1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary increment" data-id="{{ $item->id }}">+</button>
                                    </div>
                                </div>
                                <span class="item-total" data-id="{{ $item->id }}">₱{{ number_format($itemTotal, 2) }}</span>
                                <input type="hidden" name="item_quantity[{{ $item->id }}]" class="item-quantity" value="{{ $item->quantity }}">
                            </li>
                        @else
                            <li class="list-group-item">
                                <div class="text-danger">Product not found</div>
                            </li>
                        @endif
                    @endforeach
                </ul>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Total: ₱<span id="cartTotal">0.00</span></h4>
                    <button type="submit" class="btn btn-success">Checkout Selected</button>
                </div>
            </form>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            const cartTotalDisplay = document.getElementById('cartTotal');

            let itemTotals = @json($cartItems->mapWithKeys(function ($item) {
                return [$item->id => $item->product ? $item->product->price * $item->quantity : 0];
            }));

            function updateTotal() {
                let total = 0;
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const itemId = checkbox.value;
                        total += itemTotals[itemId] || 0;
                    }
                });
                cartTotalDisplay.textContent = total.toFixed(2);
            }

            function ajaxUpdateQuantity(itemId, newQty) {
                fetch("{{ route('cart.updateQuantity') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        quantity: newQty
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const itemTotalSpan = document.querySelector(`.item-total[data-id="${itemId}"]`);
                        itemTotalSpan.textContent = `₱${data.new_total.toFixed(2)}`;
                        itemTotals[itemId] = data.new_total;
                        updateTotal();
                    }
                })
                .catch(error => console.error("Error updating quantity:", error));
            }

            const incrementBtns = document.querySelectorAll('.increment');
            const decrementBtns = document.querySelectorAll('.decrement');
            const quantityInputs = document.querySelectorAll('.quantity');

            incrementBtns.forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.querySelector(`.quantity[data-id="${button.dataset.id}"]`);
                    let value = parseInt(input.value, 10) + 1;
                    input.value = value;
                    ajaxUpdateQuantity(button.dataset.id, value);
                });
            });

            decrementBtns.forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.querySelector(`.quantity[data-id="${button.dataset.id}"]`);
                    let value = Math.max(1, parseInt(input.value, 10) - 1);
                    input.value = value;
                    ajaxUpdateQuantity(button.dataset.id, value);
                });
            });

            quantityInputs.forEach(input => {
                input.addEventListener('change', function () {
                    let value = Math.max(1, parseInt(input.value, 10));
                    input.value = value;
                    ajaxUpdateQuantity(input.dataset.id, value);
                });
            });

            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
        });
    </script>
</body>
</html>
