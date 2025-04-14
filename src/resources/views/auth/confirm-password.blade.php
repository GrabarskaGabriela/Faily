<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie hasła - Homeiq</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Potwierdzenie hasła</h3>

            <div class="mb-4 text-muted">
                {{ __('To jest zabezpieczona część aplikacji. Potwierdź swoje hasło przed kontynuacją.') }}
            </div>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Hasło</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           required autocomplete="current-password">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Potwierdź') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
