<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edycja profilu - Homeiq</title>
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
                    <h5 class="mb-0"><i class="fas fa-user-cog me-2"></i>Ustawienia konta</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('profile.dashboard') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Podsumowanie
                    </a>
                    <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user-edit me-2"></i>Edycja profilu
                    </a>
                    <a href="{{ route('password.confirm') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i>Zmiana hasła
                    </a>
                </div>
            </div>
        </div>

        <!-- Główna zawartość -->
        <div class="col-md-9">
            <!-- Sekcja danych osobowych -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Dane osobowe</h4>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form', [
                        'user' => $user,
                        'mustVerifyEmail' => $mustVerifyEmail ?? false,
                        'verified' => $verified ?? false,
                    ])
                </div>
            </div>

            <!-- Sekcja zmiany hasła -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>Zmiana hasła</h4>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Sekcja usuwania konta -->
            <div class="card shadow-sm border-danger">
                <div class="card-header bg-white text-danger">
                    <h4 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Usuń konto</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Po usunięciu konta wszystkie jego dane zostaną trwale usunięte. Tej operacji nie można cofnąć.
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
