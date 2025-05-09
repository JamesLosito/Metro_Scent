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
        .hero-img {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 8px;
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
            flex: 0 0 auto;
            width: 250px;
        }
        .tag-btn {
            font-size: 0.8rem;
            padding: 5px 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
        }
        .btn-captivating { background-color: #6f42c1; color: white; }
        .btn-intense { background-color: #343a40; color: white; }
        .bestseller-scroll-container {
            overflow-x: auto;
            white-space: nowrap;
            padding: 0 15px;
        }
        .bestseller-scroll {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand fw-bold" href="#">METRO ESSENCE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ url('/welcome') }}">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ url('/perfumes') }}">PERFUMES</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{ url('/bestseller') }}">BEST SELLER</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{ url('/aboutus') }}">ABOUT US</a>
                </li>
                <li class="nav-item mx-2">
                    <a class="nav-link" href="{{ url('/contact') }}">CONTACT US</a>
                </li>
                
                @guest
                    <!-- For Guest Users -->
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">LOGIN</a>
                    </li>
                @else
                    <!-- For Authenticated Users -->
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ url('/profile') }}">Profile</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endguest
            </ul>
        </div>
    </nav>

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
                    <a href="{{ url('/login') }}" class="btn btn-primary m-2">Login</a>
                    <a href="{{ url('/signup') }}" class="btn btn-secondary m-2">Signup</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="container mt-4">
        <img src="{{ asset('images/perfume.jpg') }}" class="hero-img" alt="Metro Essence Banner">

        <!-- Favorites Section -->
        <h2 class="section-title">SHOP OUR FAVORITES</h2>
        <div class="row mb-5">
            <div class="col-md-6 d-flex flex-column align-items-center">
                <img src="{{ asset('images/CAPTIVATING/p1.png') }}" class="product-img" alt="Blush Enchanté Perfume">
                <p class="text-center mt-3">
                    Blush Enchanté is a mesmerizing blend of sweet vanilla and luscious berries, creating a scent that feels both playful and sophisticated...
                </p>
                <button class="tag-btn btn-captivating">CAPTIVATING</button>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-center">
                <img src="{{ asset('images/INTENSE/p1.png') }}" class="product-img" alt="Shadow Crest Perfume">
                <p class="text-center mt-3">
                    Shadow Crest is a bold and mysterious fragrance that exudes confidence and sophistication...
                </p>
                <button class="tag-btn btn-intense">INTENSE</button>
            </div>
        </div>

        <!-- Best Seller Section -->
        <h2 class="section-title">BEST SELLER</h2>
        <div class="bestseller-scroll-container mb-5">
            <div class="bestseller-scroll d-flex flex-nowrap">
                @for ($i = 2; $i <= 5; $i++)
                    <div class="product-card mx-3">
                        <img src="{{ asset("images/INTENSE/p$i.png") }}" class="product-img" alt="Shadow Crest Variant {{ $i }}">
                        <p class="text-center mt-3">Shadow Crest is a bold and mysterious fragrance</p>
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
