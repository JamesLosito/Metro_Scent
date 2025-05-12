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
            <form method="POST" action="{{ route('checkout.processCheckout') }}" id="payment-form">
                @csrf

                <ul class="list-group mb-4">
                    @php $grandTotal = 0; @endphp

                    @foreach ($selectedItems as $item)
                        @php
                            $itemTotal = $item->product->price * $item->quantity;
                            $grandTotal += $itemTotal;
                            $folder = strtoupper($item->product->type);
                            $imagePath = 'images/' . $folder . '/' . $item->product->image;
                            $isOutOfStock = $item->product->stock <= 0;
                        @endphp
                        <li class="list-group-item d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ asset($imagePath) }}" alt="{{ $item->product->name }}" class="product-img">
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong>
                                @if($isOutOfStock)
                                    <span class="text-danger">(Out of Stock)</span>
                                @endif
                                <br>
                                ₱{{ number_format($item->product->price, 2) }} x 
                                <input 
                                    type="number" 
                                    name="item_quantity[{{ $item->id }}]" 
                                    value="{{ $item->quantity }}" 
                                    min="1" 
                                    max="{{ $item->product->stock }}"
                                    class="form-control form-control-sm w-auto d-inline-block" 
                                    style="max-width: 70px;" 
                                    readonly
                                >
                            </div>
                            <span id="itemTotal{{ $item->id }}">₱{{ number_format($itemTotal, 2) }}</span>
                        </li>
                        <input type="hidden" name="selected_items[]" value="{{ $item->id }}">
                        <input type="hidden" name="price[{{ $item->id }}]" value="{{ $item->product->price }}">
                    @endforeach
                </ul>

                @php 
                    $shippingFee = 50; 
                    $grandTotalWithShipping = $grandTotal + $shippingFee;
                    $hasOutOfStock = collect($selectedItems)->contains(function($item) {
                        return $item->product->stock <= 0;
                    });
                @endphp
                <div class="mb-4">
                    <h4>Total Amount (with shipping): ₱<span id="grandTotal">{{ number_format($grandTotalWithShipping, 2) }}</span></h4>
                    @if($hasOutOfStock)
                        <div class="alert alert-danger">
                            Some items in your cart are out of stock. Please remove them before proceeding with checkout.
                        </div>
                    @endif
                </div>

                @if(!$hasOutOfStock)
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

                    <div class="mb-3">
                        <label for="card-element" class="form-label">Credit or Debit Card</label>
                        <div id="card-element" class="form-control p-3"></div>
                        <div id="card-errors" class="text-danger mt-2"></div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="submit-button">Confirm Order & Pay</button>
                @else
                    <a href="{{ route('cart.index') }}" class="btn btn-primary">Return to Cart</a>
                @endif
            </form>
        @else
            <div class="alert alert-info">No items selected for checkout.</div>
        @endif
    </div>

    <!-- Stripe JS -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ config('services.stripe.key') }}");
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const cardErrors = document.getElementById('card-errors');
        const submitButton = document.getElementById('submit-button');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitButton.disabled = true;

            const response = await fetch('{{ route("checkout.createPaymentIntent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    full_name: form.full_name.value,
                    email: form.email.value,
                    address: form.address.value,
                    amount: {{ intval(($grandTotal + 50) * 100) }} // includes shipping
                })
            });

            const data = await response.json();

            if (!data.client_secret) {
                cardErrors.textContent = "Failed to initiate payment.";
                submitButton.disabled = false;
                return;
            }

            const result = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: form.full_name.value,
                        email: form.email.value,
                        address: {
                            line1: form.address.value
                        }
                    }
                }
            });

            if (result.error) {
                cardErrors.textContent = result.error.message;
                submitButton.disabled = false;
            } else if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
                const paymentInput = document.createElement('input');
                paymentInput.type = 'hidden';
                paymentInput.name = 'payment_intent_id';
                paymentInput.value = result.paymentIntent.id;
                form.appendChild(paymentInput);

                form.submit();
            }
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
