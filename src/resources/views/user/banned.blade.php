<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Konto zablokowane</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0"><i class="fas fa-ban me-2"></i>Konto zablokowane</h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                    </div>

                    <h3 class="mb-4">Twoje konto zostało zablokowane</h3>

                    <div class="alert alert-danger mx-auto" style="max-width: 500px;">
                        <p class="mb-0">
                            Przykro nam, ale Twoje konto zostało zablokowane z powodu naruszenia regulaminu serwisu.
                            Obecnie nie masz dostępu do żadnych funkcji serwisu.
                        </p>
                    </div>

                    <p class="text-muted mt-4">
                        Jeśli uważasz, że to pomyłka, skontaktuj się z administratorem:
                        <a href="https://www.youtube.com/watch?v=U2wtIIT9hMU">Proszę kliknąć</a>
                    </p>

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt me-2"></i>Wyloguj się
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
