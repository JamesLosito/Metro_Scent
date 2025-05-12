@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Manage Bestsellers</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Total Orders</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 100px;">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->total_ordered }}</td>
                            <td>
                                <span class="badge {{ $product->is_best_seller ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $product->is_best_seller ? 'Bestseller' : 'Regular' }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm {{ $product->is_best_seller ? 'btn-danger' : 'btn-success' }} toggle-bestseller"
                                        data-product-id="{{ $product->product_id }}"
                                        data-csrf-token="{{ csrf_token() }}">
                                    {{ $product->is_best_seller ? 'Remove from Bestsellers' : 'Mark as Bestseller' }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toast = new bootstrap.Toast(document.getElementById('toast'));
    
    document.querySelectorAll('.toggle-bestseller').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const csrfToken = this.dataset.csrfToken;
            
            fetch(`/admin/bestsellers/${productId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    this.classList.toggle('btn-success');
                    this.classList.toggle('btn-danger');
                    this.textContent = data.is_best_seller ? 'Remove from Bestsellers' : 'Mark as Bestseller';
                    
                    // Update status badge
                    const statusBadge = this.closest('tr').querySelector('.badge');
                    statusBadge.classList.toggle('bg-success');
                    statusBadge.classList.toggle('bg-secondary');
                    statusBadge.textContent = data.is_best_seller ? 'Bestseller' : 'Regular';
                    
                    // Show success message
                    const toastBody = document.querySelector('.toast-body');
                    toastBody.textContent = data.message;
                    toast.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const toastBody = document.querySelector('.toast-body');
                toastBody.textContent = 'An error occurred. Please try again.';
                toast.show();
            });
        });
    });
});
</script>
@endpush 