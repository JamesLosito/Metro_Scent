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

    <!-- Auth Notice -->
    <div class="container text-end mt-3">
        @auth
            <p class="text-muted">Welcome back, <strong>{{ Auth::user()->name }}</strong>!</p>
        @else
            <div class="text-end">
                <a href="{{ url('/login') }}" class="btn tag-btn m-1">Login</a>
                <a href="{{ url('/register') }}" class="btn tag-btn m-1">Signup</a>
            </div>
        @endauth
    </div>

    <!-- Hero Section -->
    <div class="hero-section">
        <img src="{{ asset('images/perfume.jpg') }}" class="hero-img" alt="Metro Essence Banner">
        <div class="hero-content">
            <h1>ELEGANCE IN EVERY SCENT</h1>
            <p>Discover the essence of luxury with our exclusive perfume collection.</p>
            <a href="{{ url('/perfumes') }}" class="shop-btn">SHOP NOW</a>
        </div>
    </div>

    <!-- Recommended for You - Carousel Section -->
    <div class="container mt-5">
        <h2 class="section-title">Recommended for You</h2>
        <div id="recommendedCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($products->chunk(3) as $index => $chunk)
                    <div class="carousel-item @if($index == 0) active @endif">
                        <div class="row">
                            @foreach($chunk as $product)
                                @include('components.product-card', ['product' => $product])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#recommendedCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#recommendedCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <!-- General Product Display Section -->
    @php
        $sections = [
            'PRODUCTS' => $products,
            'Bestsellers' => $products->shuffle()->take(6),
            'Signature moods' => $products->filter(fn($p) => in_array($p->type, ['Captivating', 'Intense', 'Fresh', 'Floral']))->take(4)
        ];
    @endphp

    @foreach($sections as $sectionTitle => $sectionProducts)
        <div class="container mt-5">
            <h2 class="section-title">{{ $sectionTitle }}</h2>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($sectionProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    @endforeach

    @if ($sectionTitle === 'Signature moods')
    <div class="text-end mb-3">
        <a href="{{ url('/perfumes?type=scent') }}" class="btn btn-primary">View More SCENT Types</a>
    </div>
    @endif

    <!-- Footer -->
    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
