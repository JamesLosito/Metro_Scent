<nav class="navbar navbar-expand-lg navbar-light px-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="{{ asset('images/metE_LOGO.png') }}" alt="Logo" style="height: 60px; width: auto; margin-right: 12px;">
        <span class="fw-bold fs-4">METRO ESSENCE</span>
            </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">HOME</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/perfumes') }}">PERFUMES</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/bestseller') }}">BEST SELLER</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/aboutus') }}">ABOUT US</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('/contact') }}">CONTACT US</a></li>
                    @guest
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#authModal">LOGIN</a>
                    @else
                        <a class="nav-link" href="{{ url('/profile') }}">PROFILE</a>
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           LOGOUT
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>
