<header>
    <style>
        .btn-gradient {
            background: linear-gradient(45deg, #4e4376 0%, #2b5876 100%);
            border: none;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .navbar-nav {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        .dropdown-menu {
            min-width: 200px;
        }

        .nav-wrapper {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .navbar-toggler {
            border-color: white;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='white' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
        }
    </style>

    <nav class="navbar navbar-expand-lg" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
        <div class="container-fluid nav-wrapper">

            <a class="navbar-brand fs-3 text-white me-3" href="{{ url('/') }}" style="text-decoration: none;">
                <img src="{{ asset('images/includes/logo.png') }}" alt="Logo" width="50" height="50"
                     class="d-inline-block align-text-top"> Faily
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                @auth
                    <ul class="navbar-nav mb-2 mb-lg-0 fs-5">
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient" href="{{ url('/map') }}">Mapa eventów</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient" href="{{ url('/event_list') }}">Posty</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient" href="#">Guziczek bez nazwy</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-gradient" href="{{ url('/about') }}">Twórcy</a>
                        </li>
                    </ul>

                    <div class="mx-3">
                        <a class="btn btn-gradient" href="{{ url('/add_event') }}">Nowe wydarzenie</a>
                    </div>

                    <div class="dropdown ms-auto">
                        <a class="btn btn-gradient dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Konto
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ url('/account') }}">Przejdź do konta</a></li>
                            <li><a class="dropdown-item" href="{{ url('/my_events') }}">Moje ogłoszenia</a></li>
                            <li><a class="dropdown-item" href="{{ url('/help') }}">Pomoc</a></li>
                            <li><a class="dropdown-item" href="{{ url('/profile/dashboard') }}">Ustawienia</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Wyloguj się</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-gradient ms-auto">
                        Zaloguj się
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</header>
