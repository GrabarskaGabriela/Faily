<header>
    <nav class="navbar navbar-expand-lg bg-dark">
        <div class="container-fluid">
            <!-- Logo + napis -->
            <a class="navbar-brand fs-3 text-white" href="{{ url('/') }}" style="text-decoration: none;">
                <img src="{{ asset('images/includes/logo.png') }}" alt="Logo" width="50" height="50"
                     class="d-inline-block align-text-top">
                Faily
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                    aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            @auth
                <div class="collapse navbar-collapse  align-content-center" id="navbarTogglerDemo02">
                    <ul class="navbar-nav justify-content-center align-items-center fs-4 flex-grow-1 pe-3">
                        <li class="nav-item mx-2">
                            <a class="btn btn-outline-light me-2" href="{{ url('/mapa') }}">Mapa eventów</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-outline-light me-2" href="{{ url('/event_list') }}">Posty</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-outline-light me-2" href="#">Guziczek bez nazwy</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="btn btn-outline-light me-2" href="#">Guziczek bez nazwy</a>
                        </li>
                    </ul>

                    <a class="btn btn-outline-light me-2" href="{{ url('/add_event') }}">Dodaj ogłoszenie</a>
                    <div class="dropdown">
                        <a class="btn btn-outline-light dropdown-toggle" href="#" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            Moje konto
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('/account') }}">Przejdź do konta</a></li>
                            <li><a class="dropdown-item" href="{{ url('/my_events') }}">Moje ogłoszenia</a></li>
                            <li><a class="dropdown-item" href="{{ url('/help') }}">Pomoc</a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.dashboard') }}">Ustawienia</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Wyloguj się</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                        <!-- Dla niezalogowanego użytkownika - tylko przycisk logowania -->
                        <a href="{{ route('login') }}" class="btn btn-outline-light">
                            Zaloguj się
                        </a>
                    @endauth
                </div>
        </div>
        </div>
    </nav>
</header>
