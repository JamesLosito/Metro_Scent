<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Metro Essence</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #333;
        }
        .navbar {
            border-bottom: 1px solid #eee;
        }
        .section-title {
            font-size: 2rem;
            font-weight: bold;
            margin: 40px 0 20px;
            text-align: center;
        }
        .product-img {
            max-width: 100%;
            height: auto;
        }
        .product-card {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand fw-bold" href="#">METRO ESSENCE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link text-dark" href="{{ url('/welcome') }}">HOME</a></li>
                <li class="nav-item"><a class="nav-link text-dark" href="{{ url('/perfumes') }}">PERFUMES</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/bestseller') }}">BEST SELLER</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/aboutus') }}">ABOUT US</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">CONTACT US</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/cart') }}">CART</a></li>
                
            </ul>
        </div>
    </nav>

    <!-- Products Section -->
    <div class="container mt-5">
        <h2 class="section-title">All Perfumes</h2>
        <div class="row">
        @foreach($products as $product)
            @php
                $folder = strtoupper($product->type);
                $imagePath = 'images/' . $folder . '/' . $product->image;
            @endphp
            <div class="col-md-4">
                <div class="product-card">
                    <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
                    <h5 class="mt-3">{{ $product->name }}</h5>
                    <p>{{ $product->description }}</p>
                    <form method="POST" action="{{ url('/cart/add') }}">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-primary mt-3">Add to Cart</button>
                    </form>
                </div>
            </div>
        @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
