<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - Metro Essence</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
        .tag-btn {
            font-size: 0.7rem;
            padding: 5px 15px;
            margin-top: 10px;
            border: none;
            background-color: #5d1d48;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        footer {
            background-color: #f8f8f8;
            padding: 30px 0;
            margin-top: 50px;
            border-top: 1px solid #eee;
        }
        .footer-links {
            list-style: none;
            padding: 0;
        }
        .footer-links li {
            margin-bottom: 10px;
        }
        .footer-links a {
            color: #5d1d48;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .social-icons {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .social-icons li {
            margin: 0 10px;
        }
        .social-icons a {
            color: #5d1d48;
            font-size: 1.2rem;
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
        .notification-popup {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            width: 100%;
            max-width: 400px;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .notification-content {
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-popup.success .notification-content {
            background-color: #5d1d48;
            color: white;
            border-left: 4px solid #4a1839;
        }

        .notification-popup.error .notification-content {
            background-color: #dc3545;
            color: white;
            border-left: 4px solid #c82333;
        }

        .notification-icon {
            margin-right: 12px;
            font-size: 20px;
        }

        .notification-message {
            flex-grow: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .notification-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.7;
            cursor: pointer;
            padding: 0;
            margin-left: 10px;
            font-size: 18px;
        }

        .notification-close:hover {
            opacity: 1;
        }

        .delete-item {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
            transition: all 0.3s ease;
        }
        
        .delete-item:hover {
            background-color: #dc3545;
            color: white;
            transform: scale(1.05);
        }

        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }

        .confirmation-content {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .confirmation-title {
            color: #5d1d48;
            font-size: 1.2rem;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .confirmation-message {
            color: #666;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .confirmation-button {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .confirm-delete {
            background-color: #dc3545;
            color: white;
        }

        .confirm-delete:hover {
            background-color: #c82333;
        }

        .cancel-delete {
            background-color: #f8f9fa;
            color: #333;
            border: 1px solid #ddd;
        }

        .cancel-delete:hover {
            background-color: #e9ecef;
        }

        .cart-item-link {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            flex-grow: 1;
            transition: all 0.3s ease;
        }

        .cart-item-link:hover {
            opacity: 0.8;
        }

        .cart-item-content {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }

        .cart-item-details {
            margin-left: 15px;
        }

        .cart-item-name {
            color: #5d1d48;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .cart-item-price {
            color: #666;
            font-size: 0.9rem;
        }

        .cart-item-stock {
            font-size: 0.85rem;
            margin-top: 3px;
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
            <form method="POST" action="{{ route('checkout') }}" id="checkoutForm">
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
                                $isOutOfStock = $item->product->stock <= 0;
                            @endphp
                            <li class="list-group-item d-flex align-items-center">
                                <div class="form-check me-3">
                                    <input class="form-check-input item-checkbox" type="checkbox" name="selected_items[]" value="{{ $item->id }}" id="cartItem{{ $item->id }}" {{ $isOutOfStock ? 'disabled' : '' }}>
                                </div>
                                <a href="{{ route('product.show', $item->product->product_id) }}" class="cart-item-link">
                                    <div class="cart-item-content">
                                        <img src="{{ asset($imagePath) }}" alt="{{ $item->product->name }}" class="product-img">
                                        <div class="cart-item-details">
                                            <div class="cart-item-name">{{ $item->product->name }}</div>
                                            <div class="cart-item-price">₱{{ number_format($item->product->price, 2) }} x <span class="quantity-display" data-id="{{ $item->id }}">{{ $item->quantity }}</span></div>
                                            @if($isOutOfStock)
                                                <div class="cart-item-stock text-danger">(Out of Stock)</div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <div class="quantity-controls ms-3">
                                    <button type="button" class="btn btn-sm btn-outline-secondary decrement" data-id="{{ $item->id }}" {{ $isOutOfStock ? 'disabled' : '' }}>-</button>
                                    <input type="number" value="{{ $item->quantity }}" class="form-control form-control-sm quantity" data-id="{{ $item->id }}" min="1" max="{{ $item->product->stock }}" {{ $isOutOfStock ? 'disabled' : '' }}>
                                    <button type="button" class="btn btn-sm btn-outline-secondary increment" data-id="{{ $item->id }}" {{ $isOutOfStock ? 'disabled' : '' }}>+</button>
                                    <button type="button" class="btn btn-sm btn-danger delete-item ms-2" data-id="{{ $item->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                                <span class="item-total ms-3" data-id="{{ $item->id }}">₱{{ number_format($itemTotal, 2) }}</span>
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
                    <div>
                        <h6>Subtotal: ₱<span id="subtotal">0.00</span></h6>
                        <h6>Shipping Fee: ₱<span id="shippingFee">0.00</span></h6>
                        <h4>Total: ₱<span id="cartTotal">0.00</span></h4>
                    </div>
                    <button type="submit" class="btn btn-success" id="checkoutButton">Checkout Selected</button>
                </div>
            </form>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const cartTotalDisplay = document.getElementById('cartTotal');
            const subtotalDisplay = document.getElementById('subtotal');
            const shippingFeeDisplay = document.getElementById('shippingFee');
            const checkoutForm = document.getElementById('checkoutForm');
            const checkoutButton = document.getElementById('checkoutButton');
            let shippingFee = 50;

            // Store item prices for calculations
            let itemPrices = @json($cartItems->mapWithKeys(function ($item) {
                return [$item->id => $item->product ? $item->product->price : 0];
            }));

            function updateItemTotal(itemId, quantity) {
                const price = itemPrices[itemId] || 0;
                const total = price * quantity;
                
                // Update the item total display
                const itemTotalSpan = document.querySelector(`.item-total[data-id="${itemId}"]`);
                if (itemTotalSpan) {
                    itemTotalSpan.textContent = `₱${total.toFixed(2)}`;
                }

                // Update the quantity display
                const quantityDisplay = document.querySelector(`.quantity-display[data-id="${itemId}"]`);
                if (quantityDisplay) {
                    quantityDisplay.textContent = quantity;
                }

                return total;
            }

            function updateTotal() {
                let subtotal = 0;
                let hasSelected = false;
                let hasOutOfStock = false;

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const itemId = checkbox.value;
                        const itemElement = checkbox.closest('.list-group-item');
                        const isOutOfStock = itemElement.querySelector('.text-danger') !== null;
                        const quantityInput = itemElement.querySelector('.quantity');
                        
                        if (isOutOfStock) {
                            hasOutOfStock = true;
                        } else {
                            const quantity = parseInt(quantityInput.value) || 0;
                            subtotal += updateItemTotal(itemId, quantity);
                            hasSelected = true;
                        }
                    }
                });

                let total = subtotal;
                if (hasSelected) {
                    total += shippingFee;
                }

                subtotalDisplay.textContent = subtotal.toFixed(2);
                shippingFeeDisplay.textContent = hasSelected ? shippingFee.toFixed(2) : "0.00";
                cartTotalDisplay.textContent = total.toFixed(2);
                
                checkoutButton.disabled = !hasSelected || hasOutOfStock;
                
                if (hasOutOfStock) {
                    showErrorNotification('Cannot checkout: Some selected items are out of stock.');
                }
            }

            function showNotification(type, message) {
                const notification = document.getElementById(`${type}-notification`);
                const messageElement = notification.querySelector('.notification-message');
                const closeButton = notification.querySelector('.notification-close');
                
                // Set message
                messageElement.textContent = message;
                
                // Show notification
                notification.style.display = 'block';
                
                // Add close button functionality
                closeButton.onclick = function() {
                    notification.style.display = 'none';
                };
                
                // Auto hide after 5 seconds
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 5000);
            }

            function showSuccessNotification(message) {
                showNotification('success', message);
            }

            function showErrorNotification(message) {
                showNotification('error', message);
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
                        updateTotal();
                        showSuccessNotification('Quantity updated successfully');
                    } else {
                        showErrorNotification(data.message || 'Failed to update quantity');
                    }
                })
                .catch(error => {
                    console.error("Error updating quantity:", error);
                    showErrorNotification('Failed to update quantity. Please try again.');
                });
            }

            // Quantity increment button
            document.querySelectorAll('.increment').forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.querySelector(`.quantity[data-id="${button.dataset.id}"]`);
                    const maxStock = parseInt(input.getAttribute('max'));
                    let value = parseInt(input.value, 10) + 1;
                    
                    if (value > maxStock) {
                        showErrorNotification('Cannot exceed available stock');
                        return;
                    }
                    
                    input.value = value;
                    // Update display immediately
                    updateItemTotal(button.dataset.id, value);
                    // Then update server
                    ajaxUpdateQuantity(button.dataset.id, value);
                });
            });

            // Quantity decrement button
            document.querySelectorAll('.decrement').forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.querySelector(`.quantity[data-id="${button.dataset.id}"]`);
                    let value = Math.max(1, parseInt(input.value, 10) - 1);
                    input.value = value;
                    // Update display immediately
                    updateItemTotal(button.dataset.id, value);
                    // Then update server
                    ajaxUpdateQuantity(button.dataset.id, value);
                });
            });

            // Quantity input change
            document.querySelectorAll('.quantity').forEach(input => {
                input.addEventListener('change', function () {
                    const maxStock = parseInt(input.getAttribute('max'));
                    let value = Math.max(1, parseInt(input.value, 10));
                    
                    if (value > maxStock) {
                        showErrorNotification('Cannot exceed available stock');
                        value = maxStock;
                        input.value = value;
                    }
                    
                    // Update display immediately
                    updateItemTotal(input.dataset.id, value);
                    // Then update server
                    ajaxUpdateQuantity(input.dataset.id, value);
                });
            });

            // Checkbox change event
            checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));

            // Initial total calculation
            updateTotal();

            let itemToDelete = null;
            const modal = document.getElementById('confirmationModal');
            const confirmButton = modal.querySelector('.confirm-delete');
            const cancelButton = modal.querySelector('.cancel-delete');

            function showConfirmationModal(itemId) {
                itemToDelete = itemId;
                modal.style.display = 'flex';
            }

            function hideConfirmationModal() {
                modal.style.display = 'none';
                itemToDelete = null;
            }

            confirmButton.addEventListener('click', function() {
                if (itemToDelete) {
                    deleteCartItem(itemToDelete);
                    hideConfirmationModal();
                }
            });

            cancelButton.addEventListener('click', hideConfirmationModal);

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideConfirmationModal();
                }
            });

            function deleteCartItem(itemId) {
                fetch("{{ route('cart.remove') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const itemElement = document.querySelector(`.list-group-item:has([data-id="${itemId}"])`);
                        if (itemElement) {
                            itemElement.style.transition = 'all 0.3s ease';
                            itemElement.style.opacity = '0';
                            itemElement.style.transform = 'translateX(20px)';
                            setTimeout(() => {
                                itemElement.remove();
                                updateTotal();
                                showSuccessNotification('Item removed from cart successfully');
                                
                                if (document.querySelectorAll('.list-group-item').length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        }
                    } else {
                        showErrorNotification(data.message || 'Failed to remove item from cart');
                    }
                })
                .catch(error => {
                    console.error("Error removing item:", error);
                    showErrorNotification('Failed to remove item from cart. Please try again.');
                });
            }

            // Update delete button event listeners
            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', function() {
                    showConfirmationModal(this.dataset.id);
                });
            });
        });
    </script>

    <!-- Success Notification Template -->
    <div id="success-notification" class="notification-popup success">
        <div class="notification-content">
            <span class="notification-icon">✓</span>
            <span class="notification-message"></span>
            <button class="notification-close">&times;</button>
        </div>
    </div>

    <!-- Error Notification Template -->
    <div id="error-notification" class="notification-popup error">
        <div class="notification-content">
            <span class="notification-icon">!</span>
            <span class="notification-message"></span>
            <button class="notification-close">&times;</button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="confirmation-modal">
        <div class="confirmation-content">
            <h3 class="confirmation-title">Remove Item</h3>
            <p class="confirmation-message">Are you sure you want to remove this item from your cart?</p>
            <div class="confirmation-buttons">
                <button class="confirmation-button cancel-delete">Cancel</button>
                <button class="confirmation-button confirm-delete">Remove</button>
            </div>
        </div>
    </div>
</body>
</html>
