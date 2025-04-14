<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weryfikacja emaila - Homeiq</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Weryfikacja emaila</h3>

            <div class="alert alert-info mb-4">
                {{ __('Dziękujemy za rejestrację! Zanim rozpoczniesz, prosimy o weryfikację adresu email poprzez kliknięcie w link, który właśnie wysłaliśmy. Jeśli nie otrzymałeś emaila, chętnie wyślemy Ci kolejny.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4">
                    {{ __('Nowy link weryfikacyjny został wysłany na podany podczas rejestracji adres email.') }}
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Wyślij ponownie email weryfikacyjny
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none">
                        Wyloguj się
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
