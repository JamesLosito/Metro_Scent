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
            transform: translateY(-5px);
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
        .out-of-stock-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(93, 29, 72, 0.9);
            color: white;
            padding: 5px 10px;
            font-size: 0.8rem;
            text-transform: uppercase;
            border-radius: 5px;
            z-index: 10;
        }
        .product-card img {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .product-card.out-of-stock img {
            opacity: 0.5;
        }
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
    </style>
</head>
<body>
    @include('components.navbar')

    <!-- Login/Signup Modal -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Welcome</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Please choose an option:</p>
                    <a href="{{ url('/login') }}" class="btn tag-btn m-2">Login</a>
                    <a href="{{ url('/signup') }}" class="btn tag-btn m-2">Signup</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container mt-5">
        <h2 class="section-title">Intense Perfumes</h2>
        <div class="row">
            @foreach($products as $product)
                @php
                    $folder = strtoupper($product->type);
                    $imagePath = 'images/' . $folder . '/' . $product->image;
                @endphp
                <div class="col-md-4">
                    <div class="product-card position-relative {{ $product->stock <= 0 ? 'out-of-stock' : '' }}">
                        <a href="{{ route('product.show', $product->product_id) }}" style="text-decoration: none; color: inherit; display: block;">
                            @if($product->stock <= 0)
                                <span class="out-of-stock-label">Out of Stock</span>
                            @endif

                            <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
                            <h5 class="mt-3">{{ $product->name }}</h5>
                            <h6 class="text-muted">{{ $product->price }} PHP</h6>
                            <div class="stock-status {{ $product->stock <= 0 ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock <= 0 ? 'Out of Stock' : 'In Stock: ' . $product->stock }}
                            </div>
                            <p>{{ Str::limit($product->description, 100) }}</p>
                        </a>

                        @auth
                            <form method="POST" action="{{ url('/cart/add') }}" class="add-to-cart-form">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                <input type="hidden" name="product_name" value="{{ $product->name }}">
                                <input type="hidden" name="product_stock" value="{{ $product->stock }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary mt-2" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $product->stock <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </form>
                        @else
                            <a href="{{ url('/register') }}" class="btn btn-primary mt-2">Add to Cart</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @include('components.footer')

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Create a single instance of notifications
    let successNotification = null;
    let errorNotification = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize notifications only once
        if (!successNotification) {
            successNotification = document.getElementById('success-notification');
            if (successNotification && successNotification.parentNode !== document.body) {
                document.body.appendChild(successNotification);
            }
        }
        
        if (!errorNotification) {
            errorNotification = document.getElementById('error-notification');
            if (errorNotification && errorNotification.parentNode !== document.body) {
                document.body.appendChild(errorNotification);
            }
        }
        
        // Attach handlers to all add-to-cart forms
        const forms = document.querySelectorAll('form[action*="/cart/add"]');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const productName = this.querySelector('input[name="product_name"]').value;
                const stock = parseInt(this.querySelector('input[name="product_stock"]').value);

                if (stock <= 0) {
                    showErrorNotification('This product is out of stock.');
                    return false;
                }

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
                    if (successNotification) {
                        const message = successNotification.querySelector('.notification-message');
                        if (message) {
                            message.textContent = data.message || (productName + ' has been added to your cart.');
                            successNotification.style.display = 'block';
                            setTimeout(() => {
                                successNotification.style.display = 'none';
                            }, 3000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    showErrorNotification(error.message || 'Failed to add item to cart. Please try again.');
                });
            });
        });
    });

    function showErrorNotification(message) {
        if (!errorNotification) return;
        
        const messageElement = errorNotification.querySelector('.notification-message');
        if (!messageElement) return;
        
        messageElement.textContent = message;
        errorNotification.style.display = 'block';
        
        setTimeout(() => {
            errorNotification.style.display = 'none';
        }, 3000);
    }
    </script>
</body>
</html>
