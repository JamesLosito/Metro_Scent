<nav class="navbar navbar-expand-lg navbar-light px-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center">
        <img src="{{ asset('images/metE_LOGO.png') }}" alt="Logo" style="height: 60px; width: auto; margin-right: 12px;">
        <span class="fw-bold fs-4">METRO ESSENCE</span>
            </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/home') }}">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/bestseller') }}">BEST SELLER</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/aboutus') }}">ABOUT US</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">CONTACT US</a></li>
        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="perfumeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            PERFUMES
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="perfumeDropdown">
                            <li><a class="dropdown-item" href="{{ url('/perfumes') }}">All Products</a></li>
                            <li><a class="dropdown-item" href="{{ url('/perfumes/captivating') }}">Captivating</a></li>
                            <li><a class="dropdown-item" href="{{ url('/perfumes/intense') }}">Intense</a></li>
                        </ul>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">LOGIN</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                ACCOUNT
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Profile</a></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Log Out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/cart') }}"><i class="fas fa-shopping-cart"></i></a>
                        </li>
                    @endauth
                </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
