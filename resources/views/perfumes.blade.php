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
    <h2 class="section-title">All Perfumes</h2>
    <div class="row">
    @foreach($products as $product)
    @php
        $folder = strtoupper($product->type);
        $imagePath = 'images/' . $folder . '/' . $product->image;
    @endphp
    <div class="col-md-4">
        <div class="product-card position-relative">
            <a href="{{ route('product.show', $product->product_id) }}" style="text-decoration: none; color: inherit; display: block;">
                <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" class="product-img">
                <h5 class="mt-3">{{ $product->name }}</h5>
                <h6 class="text-muted">{{ $product->price }} PHP</h6>
                <p>{{ Str::limit($product->description, 100) }}</p>
            </a>
            @auth
                <form method="POST" action="{{ url('/cart/add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <button type="submit" class="btn btn-primary mt-2">Add to Cart</button>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>