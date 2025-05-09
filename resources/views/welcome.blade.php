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
        .hero-img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
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
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }
        .product-name {
            font-size: 1rem;
            color: #5d1d48;
            margin-top: 10px;
            font-weight: 400;
        }
        .product-price {
            font-size: 0.9rem;
            color: #666;
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
        .featured-product {
            margin-bottom: 30px;
        }
        .featured-product p {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.6;
        }
        .bestseller-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }
        .bestseller-product {
            width: 180px;
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
    </style>
</head>
<body>
  
    @include('components.navbar')

    <!-- Hero Section -->
    <div class="hero-section">
        <img src="{{ asset('images/perfume.jpg') }}" class="hero-img" alt="Metro Essence Banner">
        <div class="hero-content">
            <h1>ELEGANCE IN EVERY SCENTS</h1>
            <p>Discover the essence of luxury with our exclusive perfume collection.</p>
            <a href="{{ url('/perfumes') }}" class="shop-btn">SHOP NOW</a>
        </div>
    </div>
    
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

    <!-- Favorites Section -->
    <div class="container">
        <h2 class="section-title">SHOP OUR FAVORITES</h2>
        <div class="row mb-5">
            <div class="col-md-6 featured-product">
                <div class="text-center">
                    <img src="{{ asset('images/CAPTIVATING/p8.png') }}" class="product-img" alt="Blush Enchanté Perfume">
                    <h3 class="product-name">Blush Enchanté</h3>
                    <p class="product-price">$85.00</p>
                    <p class="mx-4">
                    Blush Enchanté is a mesmerizing blend of sweet vanilla and luscious berries, creating a scent that feels both playful and sophisticated. Its delicate yet captivating aroma lingers beautifully, making it perfect for those who love a touch of elegance with a hint of sweetness. Whether worn for a casual day out or a special evening, this fragrance adds a charming and radiant aura.                    </p>
                    <button class="tag-btn">SHOP NOW</button>
                </div>
            </div>
            <div class="col-md-6 featured-product">
                <div class="text-center">
                    <img src="{{ asset('images/INTENSE/p11.png') }}" class="product-img" alt="Shadow Crest Perfume">
                    <h3 class="product-name">Vortex Edge</h3>
                    <p class="product-price">$95.00</p>
                    <p class="mx-4">
                    Vortex Edge is a bold and mysterious fragrance that exudes confidence and sophistication. The deep, smoky richness of oud blends seamlessly with the warm sweetness of vanilla, creating an irresistible balance of intensity and allure. This scent lingers with a magnetic presence, making it perfect for those who embrace both power and elegance.                    </p>
                    <button class="tag-btn">SHOP NOW</button>
                </div>
            </div>
        </div>

        <!-- Best Seller Section -->
        <h2 class="section-title">BEST SELLER</h2>
        <div class="bestseller-container mb-5">
            @foreach(['Midnight Bloom', 'Golden Aura', 'Azure Dream', 'Velvet Rose'] as $index => $name)
            <div class="bestseller-product">
                <img src="{{ asset('images/INTENSE/p'.($index+2).'.png') }}" class="product-img" alt="{{ $name }}">
                <h3 class="product-name">{{ $name }}</h3>
                <p class="product-price">${{ 75 + ($index * 5) }}.00</p>
                <button class="tag-btn">SHOP</button>
            </div>
            @endforeach
        </div>
    </div>

    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>