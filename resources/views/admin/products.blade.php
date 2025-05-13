<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>

    @include('components.admin_navbar')

    <div class="container mt-4">
        <h1 class="mb-4">Product Management</h1>

        {{-- Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add New Product Form --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5>Add New Product</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input 
                          type="text" 
                          name="name" 
                          id="name" 
                          class="form-control" 
                          placeholder="Enter product name" 
                          required
                        >
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input 
                          type="number" 
                          name="price" 
                          id="price" 
                          class="form-control" 
                          placeholder="0.00" 
                          step="0.01" 
                          required
                        >
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea 
                          name="description" 
                          id="description" 
                          class="form-control" 
                          rows="3" 
                          placeholder="Enter a short description"
                        ></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        {{-- Existing Products Table --}}
        <div class="card">
            <div class="card-header">
                <h5>Existing Products</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            @if ($product->id)
                            <tr>
                                <form 
                                  method="POST" 
                                  action="{{ route('admin.products.update', ['id' => $product->id]) }}"
                                  class="d-flex"
                                >
                                    @csrf
                                    @method('PUT')
                                    <td style="width: 25%">
                                        <input 
                                          type="text" 
                                          name="name" 
                                          class="form-control" 
                                          value="{{ $product->name }}"
                                        >
                                    </td>
                                    <td style="width: 15%">
                                        <input 
                                          type="number" 
                                          name="price" 
                                          class="form-control" 
                                          value="{{ $product->price }}" 
                                          step="0.01"
                                        >
                                    </td>
                                    <td style="width: 40%">
                                        <textarea 
                                          name="description" 
                                          class="form-control" 
                                          rows="2"
                                        >{{ $product->description }}</textarea>
                                    </td>
                                    <td class="d-flex justify-content-between align-items-center">
                                        <button 
                                          type="submit" 
                                          class="btn btn-sm btn-success me-2"
                                        >
                                          Update
                                        </button>
                                </form>
                                <form 
                                  method="POST" 
                                  action="{{ route('admin.products.delete', ['id' => $product->id]) }}"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                      type="submit" 
                                      class="btn btn-sm btn-danger"
                                      onclick="return confirm('Delete this product?')"
                                    >
                                      Delete
                                    </button>
                                </form>
                                    </td>
                            </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    No products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('components.footer')

    <!-- Bootstrap Bundle JS (optional, for navbars/modals) -->
    <script 
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    ></script>
</body>
</html>
