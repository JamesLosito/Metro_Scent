@php
    $folder = strtoupper($product->type);
    $imagePath = 'images/' . $folder . '/' . $product->image;
@endphp

<div class="col-md-4">
    <div class="product-card">
        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
        <h5 class="mt-3">{{ $product->name }}</h5>
        <h6 class="text-muted">{{ $product->price }} PHP</h6>
        <p>{{ $product->description }}</p>
        @auth
        <form method="POST" action="{{ url('/cart/add') }}" class="add-to-cart-form" onsubmit="return addToCartWithNotification(this, '{{ $product->name }}')">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <button type="submit" class="btn btn-primary mt-3">Add to Cart</button>
        </form>
        @else
        <a href="{{ url('/register') }}" class="btn btn-primary mt-3">Add to Cart</a>
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

.checkmark {
    margin-right: 10px;
    font-size: 18px;
}

.notification-message {
    font-size: 16px;
}
</style>

<!-- JavaScript for handling the notification -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Move the notification element to the body to ensure it's available globally
    const notificationElement = document.getElementById('success-notification');
    if (notificationElement && notificationElement.parentNode) {
        document.body.appendChild(notificationElement);
    }
    
    // Add a global function to handle the cart addition
    window.addToCartWithNotification = function(form, productName) {
        // Prevent default form behavior
        event.preventDefault();
        
        // Get the product name if not provided
        if (!productName) {
            const productCard = form.closest('.product-card');
            if (productCard) {
                productName = productCard.querySelector('h5').textContent.trim();
            } else {
                productName = "Product"; // Fallback
            }
        }
        
        // Show notification
        const notification = document.getElementById('success-notification');
        const message = notification.querySelector('.notification-message');
        message.textContent = '"' + productName + '" has been added to your cart.';
        notification.style.display = 'block';
        
        // Store the form for later submission
        const formToSubmit = form;
        
        // Hide notification after 3 seconds and submit the form
        setTimeout(() => {
            notification.style.display = 'none';

            // Submit via AJAX to prevent page reload
            const formData = new FormData(formToSubmit);

            fetch(formToSubmit.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok.');
                return response.json(); // Or response.text() depending on your backend
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
            });

        }, 3000);

        // Prevent immediate form submission
        return false;
    };
    
    // Attach handlers to all add-to-cart forms
    const forms = document.querySelectorAll('form[action*="/cart/add"]');
    forms.forEach(form => {
        form.classList.add('add-to-cart-form'); // Ensure class is present
        form.onsubmit = function(e) {
            return addToCartWithNotification(this);
        };
    });
});
</script>