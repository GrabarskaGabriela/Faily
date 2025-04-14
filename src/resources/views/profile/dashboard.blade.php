<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel użytkownika - Homeiq</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        <!-- Lewa kolumna - menu boczne -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i>Moje konto</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.dashboard') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Podsumowanie
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-edit me-2"></i>Profil
                    </a>
                    <a href="{{ route('password.confirm') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i>Zmiana hasła
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-bell me-2"></i>Powiadomienia
                    </a>
                </div>
            </div>
        </div>

        <!-- Główna zawartość -->
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Podsumowanie</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Jesteś zalogowany jako <strong>{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</strong>
                    </div>

                    <!-- Statystyki -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><i class="fas fa-calendar-check text-primary me-2"></i>Wydarzenia</h5>
                                    <p class="h2">5</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Zobacz</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><i class="fas fa-tasks text-success me-2"></i>Zadania</h5>
                                    <p class="h2">3</p>
                                    <a href="#" class="btn btn-sm btn-outline-success">Zobacz</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><i class="fas fa-envelope text-info me-2"></i>Wiadomości</h5>
                                    <p class="h2">2</p>
                                    <a href="#" class="btn btn-sm btn-outline-info">Zobacz</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ostatnie aktywności -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Ostatnie aktywności</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-check-circle text-success me-2"></i>Zalogowano pomyślnie</span>
                                    <small class="text-muted">5 minut temu</small>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-edit text-primary me-2"></i>Zaktualizowano profil</span>
                                    <small class="text-muted">2 dni temu</small>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
