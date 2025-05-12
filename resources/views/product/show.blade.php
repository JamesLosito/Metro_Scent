<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence</title>
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
        .section-title {
            font-family: 'Times New Roman', serif;
            font-size: 1.5rem;
            font-weight: 400;
            color: #5d1d48;
            margin: 40px 0 30px;
            text-align: center;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .product-img {
            max-width: 100%;
            height: auto;
        }
        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        .tag-btn, .btn-primary {
            font-size: 0.7rem;
            padding: 8px 15px;
            margin-top: 10px;
            border: none;
            background-color: #5d1d48;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-radius: 3px;
        }
        .btn-primary:hover {
            background-color: #4a1839;
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
        .hero-img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 10%;
            transform: translateY(-50%);
            color: white;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        .hero-content h1 {
            font-size: 2.5rem;
            font-weight: 300;
            letter-spacing: 2px;
        }
        .shop-btn {
            background-color: #5d1d48;
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 0.9rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            text-decoration: none;
        }
        .hero-section {
            position: relative;
        }
        .carousel-item {
            transition: transform 0.3s ease-in-out;
        }
        .carousel-inner {
            display: flex;
        }
        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
        }
        @media (max-width: 768px) {
            .row-cols-md-3 {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 576px) {
            .row-cols-md-3 {
                grid-template-columns: 1fr;
            }
        }

        /* Notification Styles */
        .notification-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            width: 100%;
            max-width: 600px;
        }

        .notification-content {
            background-color: #5d1d48;
            color: white;
            padding: 15px 20px;
            text-align: left;
            border-radius: 4px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .notification-popup.error .notification-content {
            background-color: #dc3545;
        }

        .checkmark {
            margin-right: 10px;
            font-size: 18px;
        }

        .error-mark {
            margin-right: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .notification-message {
            font-size: 16px;
        }

        .stock-status {
            font-weight: bold;
            margin: 10px 0;
        }

        /* Add to existing styles */
        .quantity-controls {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .quantity-controls button {
            width: 40px;
            height: 40px;
            border-radius: 4px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .quantity-controls button:hover {
            background-color: #e0e0e0;
        }

        .quantity-controls input {
            width: 60px;
            height: 40px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 10px;
            font-size: 16px;
            border-radius: 4px;
        }

        .quantity-controls input:focus {
            outline: none;
            border-color: #5d1d48;
        }

        .quantity-label {
            margin-right: 15px;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
<body>
    @include('components.navbar')
<div class="container mt-5">
    <div class="row">
        <!-- Product Image Column -->
        <div class="col-md-6">
            @php
                $folder = strtoupper($product->type);
                $imagePath = 'images/' . $folder . '/' . $product->image;
                $isOutOfStock = $product->stock <= 0;
            @endphp
            <div class="product-image mb-4">
                <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="img-fluid rounded shadow">
            </div>
        </div>

        <!-- Product Details Column -->
        <div class="col-md-6">
            <h2 class="product-name">{{ $product->name }}</h2>
            <h4 class="text-muted">{{ $product->price }} PHP</h4>
            <p class="product-description">{{ $product->description }}</p>
            <div class="stock-status {{ $isOutOfStock ? 'text-danger' : 'text-success' }}">
                {{ $isOutOfStock ? 'Out of Stock' : 'In Stock: ' . $product->stock }}
            </div>

            @auth
                <div class="d-flex flex-column mt-4">
                    <form method="POST" action="{{ url('/cart/add') }}" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="product_name" value="{{ $product->name }}">
                        <input type="hidden" name="product_stock" value="{{ $product->stock }}">
                        
                        <div class="quantity-controls">
                            <span class="quantity-label">Quantity:</span>
                            <button type="button" class="decrement" {{ $isOutOfStock ? 'disabled' : '' }}>-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" {{ $isOutOfStock ? 'disabled' : '' }}>
                            <button type="button" class="increment" {{ $isOutOfStock ? 'disabled' : '' }}>+</button>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg mb-3 w-100" {{ $isOutOfStock ? 'disabled' : '' }}>
                            {{ $isOutOfStock ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Login to Purchase</a>
            @endauth
        </div>
    </div>
</div>

<!-- Success Notification Popup -->
<div id="success-notification" class="notification-popup">
    <div class="notification-content">
        <span class="checkmark">✓</span>
        <span class="notification-message"></span>
    </div>
</div>

<!-- Error Notification Popup -->
<div id="error-notification" class="notification-popup error">
    <div class="notification-content">
        <span class="error-mark">!</span>
        <span class="notification-message"></span>
    </div>
</div>

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Move the notification elements to the body to ensure they're available globally
    const successNotification = document.getElementById('success-notification');
    const errorNotification = document.getElementById('error-notification');
    if (successNotification && successNotification.parentNode) {
        document.body.appendChild(successNotification);
    }
    if (errorNotification && errorNotification.parentNode) {
        document.body.appendChild(errorNotification);
    }
    
    // Quantity controls
    const quantityInput = document.querySelector('input[name="quantity"]');
    const decrementBtn = document.querySelector('.decrement');
    const incrementBtn = document.querySelector('.increment');
    const maxStock = parseInt(quantityInput.getAttribute('max'));

    function updateQuantity(value) {
        value = Math.max(1, Math.min(value, maxStock));
        quantityInput.value = value;
    }

    decrementBtn.addEventListener('click', function() {
        updateQuantity(parseInt(quantityInput.value) - 1);
    });

    incrementBtn.addEventListener('click', function() {
        updateQuantity(parseInt(quantityInput.value) + 1);
    });

    quantityInput.addEventListener('change', function() {
        updateQuantity(parseInt(this.value));
    });

    // Update form submission
    const forms = document.querySelectorAll('form[action*="/cart/add"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const productName = this.querySelector('input[name="product_name"]').value;
            const stock = parseInt(this.querySelector('input[name="product_stock"]').value);
            const quantity = parseInt(this.querySelector('input[name="quantity"]').value);

            if (stock <= 0) {
                showErrorNotification('This product is out of stock.');
                return false;
            }

            if (quantity > stock) {
                showErrorNotification('Quantity cannot exceed available stock.');
                return false;
            }

            // Show success notification
            const message = successNotification.querySelector('.notification-message');
            message.textContent = `${quantity} ${productName}${quantity > 1 ? 's' : ''} added to your cart.`;
            successNotification.style.display = 'block';

            // Submit via AJAX
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'Failed to add item to cart');
                    });
                }
                return response.json();
            })
            .then(data => {
                const message = successNotification.querySelector('.notification-message');
                message.textContent = data.message || `${quantity} ${productName}${quantity > 1 ? 's' : ''} added to your cart.`;
                successNotification.style.display = 'block';
                setTimeout(() => {
                    successNotification.style.display = 'none';
                }, 3000);
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showErrorNotification(error.message || 'Failed to add item to cart. Please try again.');
                successNotification.style.display = 'none';
            });
        });
    });

    function showErrorNotification(message) {
        const notification = document.getElementById('error-notification');
        const messageElement = notification.querySelector('.notification-message');
        messageElement.textContent = message;
        notification.style.display = 'block';
        
        setTimeout(() => {
            notification.style.display = 'none';
        }, 3000);
    }
});
</script>
</body>
</html>