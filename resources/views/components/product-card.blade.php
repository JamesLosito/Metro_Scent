@php
    $folder = strtoupper($product->type);
    $imagePath = 'images/' . $folder . '/' . $product->image;
    $isOutOfStock = $product->stock <= 0;
@endphp

<div class="col-md-4">
    <div class="product-card">
        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
        <h5 class="mt-3">{{ $product->name }}</h5>
        <h6 class="text-muted">₱{{ number_format($product->price, 2) }}</h6>
        <p>{{ $product->description }}</p>
        <div class="stock-status {{ $isOutOfStock ? 'text-danger' : 'text-success' }}">
            {{ $isOutOfStock ? 'Out of Stock' : 'In Stock: ' . $product->stock }}
        </div>
        @auth
            <form method="POST" action="{{ url('/cart/add') }}" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                <input type="hidden" name="product_name" value="{{ $product->name }}">
                <input type="hidden" name="product_stock" value="{{ $product->stock }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="btn btn-primary mt-3" {{ $isOutOfStock ? 'disabled' : '' }}>
                    {{ $isOutOfStock ? 'Out of Stock' : 'Add to Cart' }}
                </button>
            </form>
        @else
            <a href="{{ url('/login') }}" class="btn btn-primary mt-3">Add to Cart</a>
        @endauth
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

<!-- CSS for the notification -->
<style>
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

<!-- JavaScript for handling the notification -->
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