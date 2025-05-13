<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 shadow-sm">
    <div class="container">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/metE_LOGO.png') }}" alt="Logo" style="height: 50px; width: auto; margin-right: 12px;">
            <span class="fw-bold fs-4 text-white">METRO ESSENCE ADMIN</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ route('admin.users') }}">
                        <i class="fas fa-users me-1"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ route('admin.products') }}">
                        <i class="fas fa-box me-1"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3 fw-semibold" href="{{ route('admin.orders') }}">
                        <i class="fas fa-shopping-cart me-1"></i> Orders
                    </a>
                </li>
                <li class="nav-item dropdown ms-3">
                    <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        <span>Account</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.profile.show') }}">
                                <i class="fas fa-id-card me-2"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Log Out
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    background: linear-gradient(to right, #1a1a1a, #2d2d2d) !important;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.navbar-brand {
    transition: transform 0.2s;
}

.navbar-brand:hover {
    transform: scale(1.02);
}

.nav-link {
    color: rgba(255,255,255,0.9) !important;
    transition: all 0.2s;
    position: relative;
}

.nav-link:hover {
    color: #fff !important;
    transform: translateY(-1px);
}

.nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 50%;
    background-color: #fff;
    transition: all 0.3s;
    transform: translateX(-50%);
}

.nav-link:hover::after {
    width: 80%;
}

.dropdown-menu {
    border: none;
    border-radius: 8px;
    background: #fff;
}

.dropdown-item {
    transition: all 0.2s;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.btn-outline-light {
    border-width: 2px;
    font-weight: 500;
}

.btn-outline-light:hover {
    background-color: rgba(255,255,255,0.1);
}

@media (max-width: 991.98px) {
    .navbar-nav {
        padding: 1rem 0;
    }
    .nav-link {
        padding: 0.5rem 0 !important;
    }
    .nav-link::after {
        display: none;
    }
}
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 