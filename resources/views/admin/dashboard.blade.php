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

    <!-- Content Section -->
    <div class="container mt-5">
        <h1>Admin Dashboard</h1>
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card p-3" style="background-color: #ffe6eb;">
                    <h5>Total Users</h5>
                    <p class="display-4">{{ $usersCount }}</p>
                    <a href="{{ route('admin.users') }}">Manage Users</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3" style="background-color: #e6f3ff;">
                    <h5>Total Products</h5>
                    <p class="display-4">{{ $productsCount }}</p>
                    <a href="{{ route('admin.products') }}">Manage Products</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card p-3" style="background-color: #fff9e6;">
                    <h5>Pending Orders</h5>
                    <p class="display-4">{{ $ordersPending }}</p>
                    <a href="{{ route('admin.orders') }}">Manage Orders</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
