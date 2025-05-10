<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel Administratora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Menu Administratora</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Podsumowanie
                    </a>
                    <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>Użytkownicy
                    </a>
                    <a href="{{ route('admin.reports') }}" class="list-group-item list-group-item-action {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <i class="fas fa-flag me-2"></i>Zgłoszenia
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Podsumowanie systemu</h4>
                    <span class="badge">Ostatnia aktualizacja: {{ now()->format('d.m.Y H:i') }}</span>
                </div>

                <div class="card-body">
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-check-circle me-3 fs-4"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Witaj w panelu administratora!</h5>
                            <p class="mb-0">Masz dostęp do zarządzania użytkownikami i zgłoszeniami.</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Użytkownicy</h6>
                                            <h2 class="mb-0">{{ $stats['users_count'] }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Zablokowani</h6>
                                            <h2 class="mb-0">{{ $stats['banned_users'] }}</h2>
                                        </div>
                                        <i class="fas fa-user-slash fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="card-title">Zgłoszenia</h6>
                                            <h2 class="mb-0">{{ $stats['pending_reports'] }}</h2>
                                        </div>
                                        <i class="fas fa-flag fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Zarządzanie użytkownikami</h5>
                                </div>
                                <div class="card-body">
                                    <p>Przeglądaj, banuj i zarządzaj użytkownikami systemu.</p>
                                    <a href="{{ route('admin.users') }}" class="btn btn-gradient-nav w-100">Przejdź do użytkowników</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-flag me-2"></i>Zgłoszenia</h5>
                                </div>
                                <div class="card-body">
                                    <p>Przeglądaj i moderuj zgłoszenia od użytkowników.</p>
                                    <a href="{{ route('admin.reports') }}" class="btn btn-gradient-nav w-100">Przejdź do zgłoszeń</a>
                                </div>
                            </div>
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
