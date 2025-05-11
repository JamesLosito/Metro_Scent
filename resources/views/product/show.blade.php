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

            @auth
                <div class="d-flex flex-column mt-4">
                    <form method="POST" action="{{ url('/cart/add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <button type="submit" class="btn btn-primary btn-lg mb-3">Add to Cart</button>
                    </form>
                    <a href="{{ url('/checkout') }}" class="btn btn-success btn-lg">Checkout Now</a>
                </div>
            @else
                <a href="{{ url('/login') }}" class="btn btn-primary btn-lg">Login to Purchase</a>
            @endauth
        </div>
    </div>
</div>
@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>