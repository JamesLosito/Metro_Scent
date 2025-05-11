<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Metro Essence</title>

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
        .product-img {
            max-width: 60px;
            height: auto;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    @include('components.navbar')

    <div class="container mt-5">
        <h2 class="section-title">Checkout</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (isset($selectedItems) && count($selectedItems) > 0)
            <form method="POST" action="{{ url('/process-checkout') }}">
                @csrf

                <ul class="list-group mb-4">
                    @php $grandTotal = 0; @endphp

                    @foreach ($selectedItems as $item)
                        @php
                            $itemTotal = $item->product->price * $item->quantity;
                            $grandTotal += $itemTotal;
                            $folder = strtoupper($item->product->type);
                            $imagePath = 'images/' . $folder . '/' . $item->product->image;
                        @endphp
                        <li class="list-group-item d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ asset($imagePath) }}" alt="{{ $item->product->name }}" class="product-img">
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                ₱{{ number_format($item->product->price, 2) }} x 
                                <input type="number" name="item_quantity[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm w-auto d-inline-block" style="max-width: 70px;" readonly>
                            </div>
                            <span id="itemTotal{{ $item->id }}">₱{{ number_format($itemTotal, 2) }}</span>
                        </li>
                        <!-- Hidden field to track selected items -->
                        <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                        <input type="hidden" name="price[{{ $item->id }}]" value="{{ $item->product->price }}">
                    @endforeach
                </ul>

                <div class="mb-4">
                    <h4>Total Amount: ₱<span id="grandTotal">{{ number_format($grandTotal, 2) }}</span></h4>
                </div>

                <h5>Billing Information</h5>
                <div class="mb-3">
                    <input type="text" name="full_name" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="address" class="form-control" placeholder="Shipping Address" required>
                </div>

                <button type="submit" class="btn btn-primary">Confirm Order</button>
            </form>
        @else
            <div class="alert alert-info">No items selected for checkout.</div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Optional: Script to dynamically update the total as quantities change
        const quantityInputs = document.querySelectorAll('input[name^="item_quantity"]');
        const grandTotalDisplay = document.getElementById('grandTotal');

        quantityInputs.forEach(input => {
            input.addEventListener('change', function () {
                let grandTotal = 0;

                quantityInputs.forEach(input => {
                    const itemId = input.name.match(/\d+/)[0];
                    const quantity = parseInt(input.value, 10);
                    const price = parseFloat(document.querySelector(`input[name="price[${itemId}]"]`).value);
                    const itemTotal = price * quantity;
                    grandTotal += itemTotal;

                    // Update the total amount for this item (optional)
                    const itemTotalSpan = document.querySelector(`#itemTotal${itemId}`);
                    if (itemTotalSpan) {
                        itemTotalSpan.textContent = `₱${itemTotal.toFixed(2)}`;
                    }
                });

                grandTotalDisplay.textContent = grandTotal.toFixed(2);
            });
        });
    </script>
</body>
</html>
