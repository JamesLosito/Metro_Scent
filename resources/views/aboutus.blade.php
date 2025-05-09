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
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('components.navbar')
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
