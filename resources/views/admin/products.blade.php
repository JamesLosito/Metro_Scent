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
        
        /* Type Filter Styles */
        .type-filter {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .type-filter .btn-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .type-filter .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .type-filter .btn.active {
            background-color: #5d1d48;
            color: white;
            border-color: #5d1d48;
        }
        
        .type-filter .btn:not(.active):hover {
            background-color: #f0f0f0;
        }
        
        /* Product Type Badge */
        .type-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .type-badge.captivating {
            background-color: #e3f2fd;
            color: #1976d2;
        }
        
        .type-badge.intense {
            background-color: #fce4ec;
            color: #c2185b;
        }
        
        /* Product Row Animation */
        .product-row {
            transition: all 0.3s ease;
        }
        
        .product-row.hidden {
            display: none;
        }
        
        /* Product Image Styles */
        .product-image-container {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .product-image-container:hover {
            transform: scale(1.05);
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }
        
        .product-image:hover {
            transform: scale(1.1);
        }
        
        /* Image Preview Modal */
        .image-preview-modal .modal-dialog {
            max-width: 90%;
            margin: 1.75rem auto;
        }
        
        .image-preview-modal .modal-content {
            background-color: transparent;
            border: none;
        }
        
        .image-preview-modal .modal-body {
            padding: 0;
            text-align: center;
        }
        
        .image-preview-modal img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        .image-preview-modal .modal-header {
            background-color: transparent;
            border: none;
            position: absolute;
            right: 0;
            z-index: 1;
        }
        
        .image-preview-modal .btn-close {
            background-color: rgba(255,255,255,0.8);
            border-radius: 50%;
            padding: 0.5rem;
        }

        /* Pagination Styles */
        .pagination-container {
            margin: 1rem 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-wrapper {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            background: #fff;
            padding: 0.5rem;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .page-item {
            display: inline-block;
        }

        .page-link {
            color: #5d1d48;
            border: 1px solid #e0e0e0;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: all 0.2s ease;
            font-weight: 500;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 1.75rem;
            height: 1.75rem;
            font-size: 0.875rem;
            background: #fff;
        }

        /* Fix icon size in pagination */
        .page-link i {
            font-size: 1em !important;
            width: auto !important;
            height: auto !important;
            line-height: 1;
            vertical-align: middle;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            padding: 0.25rem;
            min-width: 1.5rem;
            height: 1.5rem;
        }

        .page-link:hover {
            background-color: #f8f9fa;
            border-color: #5d1d48;
            color: #5d1d48;
            text-decoration: none;
        }

        .page-item.active .page-link {
            background-color: #5d1d48;
            border-color: #5d1d48;
            color: white;
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            background-color: #f8f9fa;
            border-color: #e0e0e0;
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
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
                <h6 class="m-0 font-weight-bold text-primary">Product Management</h6>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus"></i> Add Product
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Type Filter -->
                <div class="type-filter">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Filter by Type:</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.products', ['type' => 'all']) }}" 
                               class="btn btn-outline-primary {{ $type === 'all' ? 'active' : '' }}">
                                <i class="fas fa-th-large me-1"></i> All Products
                                <span class="badge bg-secondary ms-1">{{ $typeCounts['all'] }}</span>
                            </a>
                            <a href="{{ route('admin.products', ['type' => 'captivating']) }}" 
                               class="btn btn-outline-primary {{ $type === 'captivating' ? 'active' : '' }}">
                                <i class="fas fa-star me-1"></i> Captivating
                                <span class="badge bg-secondary ms-1">{{ $typeCounts['captivating'] }}</span>
                            </a>
                            <a href="{{ route('admin.products', ['type' => 'intense']) }}" 
                               class="btn btn-outline-primary {{ $type === 'intense' ? 'active' : '' }}">
                                <i class="fas fa-fire me-1"></i> Intense
                                <span class="badge bg-secondary ms-1">{{ $typeCounts['intense'] }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr class="product-row">
                                <td>{{ $product->product_id }}</td>
                                <td>
                                    <div class="product-image-container" data-bs-toggle="modal" data-bs-target="#imagePreviewModal{{ $product->product_id }}">
                                        @php
                                            $type = strtolower($product->type);
                                            $imagePath = $product->image ? 'storage/products/' . $type . '/' . $product->image : 'images/no-image.png';
                                        @endphp
                                        <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-image" loading="lazy" onerror="handleImageError(this)">
                                    </div>
                                    
                                    <!-- Image Preview Modal -->
                                    <div class="modal fade image-preview-modal" id="imagePreviewModal{{ $product->product_id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="{{ asset($imagePath) }}" 
                                                         alt="{{ $product->name }}"
                                                         loading="lazy"
                                                         onerror="handleImageError(this)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <span class="type-badge {{ $product->type }}">
                                        {{ ucfirst($product->type) }}
                                    </span>
                                </td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
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
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No products found for this type.
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex flex-column align-items-center mt-4">
                    {{ $products->appends(['type' => $type])->links('vendor.pagination.bootstrap-5') }}
                    <div class="pagination-info">
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
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
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" name="stock" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="captivating" {{ old('type') === 'captivating' ? 'selected' : '' }}>Captivating</option>
                                <option value="intense" {{ old('type') === 'intense' ? 'selected' : '' }}>Intense</option>
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
                            <label for="edit_stock{{ $product->product_id }}" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock{{ $product->product_id }}" name="stock" min="0" value="{{ $product->stock }}" required>
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
                                    @php
                                        $type = strtolower($product->type);
                                        $imagePath = $product->image ? 'storage/products/' . $type . '/' . $product->image : 'images/no-image.png';
                                    @endphp
                                    <img src="{{ asset($imagePath) }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-image" 
                                         loading="lazy"
                                         onerror="handleImageError(this)">
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

            // Handle image loading errors
            function handleImageError(img) {
                img.onerror = null;
                img.src = "{{ asset('images/no-image.png') }}";
            }

            // Add error handlers to all product images
            document.querySelectorAll('.product-image').forEach(img => {
                img.addEventListener('error', function() {
                    handleImageError(this);
                });
            });
        });

        // Add JavaScript for type filtering
        document.addEventListener('DOMContentLoaded', function() {
            const typeButtons = document.querySelectorAll('[data-type]');
            const productRows = document.querySelectorAll('.product-row');
            let currentType = 'all';

            function updateFilter(type) {
                // Update active button
                typeButtons.forEach(btn => {
                    if (btn.dataset.type === type) {
                        btn.classList.add('active');
                    } else {
                        btn.classList.remove('active');
                    }
                });

                // Update product rows - remove animation
                productRows.forEach(row => {
                    if (type === 'all' || row.dataset.type === type) {
                        row.classList.remove('hidden');
                        // Remove the visible class and animation
                        // row.classList.add('visible');
                    } else {
                        row.classList.add('hidden');
                        // row.classList.remove('visible');
                    }
                });

                // Update current type
                currentType = type;
            }

            // Add click handlers to filter buttons
            typeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    updateFilter(this.dataset.type);
                });
            });

            // Initialize image preview modals
            const imageModals = document.querySelectorAll('.image-preview-modal');
            imageModals.forEach(modal => {
                modal.addEventListener('show.bs.modal', function() {
                    const img = this.querySelector('img');
                    if (img) {
                        img.style.opacity = '0';
                        setTimeout(() => {
                            img.style.opacity = '1';
                        }, 50);
                    }
                });
            });
        });
    </script>
</body>
</html>
