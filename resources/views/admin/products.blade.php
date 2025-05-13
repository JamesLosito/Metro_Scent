<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - Product Management</title>
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" as="script">
    
    <!-- Load stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        /* Critical CSS */
        body { font-family: 'Times New Roman', serif; background-color: #fff; color: #333; }
        .navbar { background-color: #fff; border-bottom: 1px solid #eee; padding: 15px 0; }
        .navbar-brand { font-family: 'Times New Roman', serif; font-weight: 300; letter-spacing: 2px; color: #5d1d48 !important; }
        .nav-link { color: #5d1d48 !important; font-size: 0.9rem; letter-spacing: 1px; }
        .card { border: none; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        .btn-primary { background-color: #5d1d48; border-color: #5d1d48; }
        .btn-primary:hover { background-color: #4a1739; border-color: #4a1739; }
        .btn-outline-primary { color: #5d1d48; border-color: #5d1d48; }
        .btn-outline-primary:hover { background-color: #5d1d48; border-color: #5d1d48; }
        .table { border-radius: 10px; overflow: hidden; }
        .table thead th { background-color: #f8f9fa; border-bottom: 2px solid #eee; color: #5d1d48; font-weight: 600; }
        .table td { vertical-align: middle; }
        .product-image { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
        .modal-content { border: none; border-radius: 10px; }
        .modal-header { background-color: #f8f9fa; border-bottom: 1px solid #eee; }
        .modal-title { color: #5d1d48; font-weight: 600; }
    </style>
    <!-- Defer non-critical CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}" media="print" onload="this.media='all'">
</head>
<body>
    @include('components.admin_navbar')

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Add New Product</h6>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->product_id }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" width="50" height="50">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="No image" class="product-image" loading="lazy" width="50" height="50">
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ Str::limit($product->description, 50) }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->product_id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.products.delete', $product->product_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="captivating">Captivating</option>
                                <option value="intense">Intense</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modals -->
    @foreach($products as $product)
    <div class="modal fade" id="editProductModal{{ $product->product_id }}" tabindex="-1" aria-labelledby="editProductModalLabel{{ $product->product_id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel{{ $product->product_id }}">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name{{ $product->product_id }}" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_name{{ $product->product_id }}" name="name" value="{{ $product->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price{{ $product->product_id }}" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit_price{{ $product->product_id }}" name="price" step="0.01" value="{{ $product->price }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description{{ $product->product_id }}" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description{{ $product->product_id }}" name="description" rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_type{{ $product->product_id }}" class="form-label">Type</label>
                            <select class="form-select" id="edit_type{{ $product->product_id }}" name="type" required>
                                <option value="captivating" {{ $product->type === 'captivating' ? 'selected' : '' }}>Captivating</option>
                                <option value="intense" {{ $product->type === 'intense' ? 'selected' : '' }}>Intense</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image{{ $product->product_id }}" class="form-label">Product Image</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" width="50" height="50">
                                </div>
                            @endif
                            <input type="file" class="form-control" id="edit_image{{ $product->product_id }}" name="image" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @include('components.footer')

    <!-- Load JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        // Inline critical JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips and popovers if needed
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>
