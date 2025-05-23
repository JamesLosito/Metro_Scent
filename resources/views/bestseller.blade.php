<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence - Best Sellers</title>
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
        /* Apply underline only to top-level nav links (not dropdown toggles) */
        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle) {
            position: relative;
            color: #000;
            transition: color 0.3s ease;
        }

        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle)::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 2px;
            width: 0;
            background-color: #5d1d48;
            transition: width 0.3s ease;
            transform-origin: center;
        }

        .navbar-nav > .nav-item > .nav-link:not(.dropdown-toggle):hover::after {
            width: 100%;
        }
        .h2 {
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
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            position: relative;
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
        .out-of-stock-label {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(93, 29, 72, 0.9);
            color: #fff;
            padding: 5px 10px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 5px;
            z-index: 2;
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
    @include('components.navbar')

    <div class="container mt-5">
        <h2 class="h2">BEST SELLER</h2>
        <div class="row">
            @if ($bestSellers->count() > 0)
                @foreach ($bestSellers as $product)
                    @php
                        if ($product->image) {
                            if (Str::contains($product->image, '/')) {
                                // New style: full path
                                $imagePath = 'storage/' . $product->image;
                            } else {
                                // Old style: just filename
                                $imagePath = 'images/products/' . strtolower($product->type) . '/' . $product->image;
                            }
                        } else {
                            $imagePath = 'images/no-image.png';
                        }
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="product-card">
                            @if ($product->stock == 0)
                                <div class="out-of-stock-label">Out of Stock</div>
                            @endif
                            <a href="{{ route('product.show', $product->product_id) }}" style="text-decoration: none; color: inherit;">
                                <img src="{{ asset($imagePath) }}" class="product-img" alt="{{ $product->name }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/no-image.png') }}';">
                                <h5 class="mt-3">{{ $product->name }}</h5>
                                <p class="text-center mt-2">{{ $product->description }}</p>
                                <p class="text-center mt-2">Stock: {{ $product->stock }}</p>
                                <p class="text-center">₱ {{ number_format($product->price, 2) }}</p>
                                @if(isset($product->total_ordered))
                                    <p class="text-center text-success">
                                        <i class="fas fa-shopping-cart"></i> {{ $product->total_ordered }} units sold
                                    </p>
                                @endif
                            </a>
                            @if ($product->stock == 0)
                                <button class="btn btn-secondary" disabled>Sold Out</button>
                            @else
                                <a href="{{ route('product.show', $product->product_id) }}" class="btn btn-primary">View Details</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12 text-center">
                    <p>No best-selling products found yet.</p>
                </div>
            @endif
        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
