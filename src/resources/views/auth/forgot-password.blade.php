<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Przypominanie hasła - Homeiq</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto border-dark" style="max-width: 400px;">
        <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <h3 class="card-title text-center mb-4 text-white">Przypominanie hasła</h3>

            <div class="mb-4 text-white">
                {{ __('Zapomniałeś hasła? Podaj swój adres email, a wyślemy Ci link do resetowania hasła.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ __('Na podany adres email został wysłany link do resetowania hasła.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label text-white">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="Wpisz swój email"
                           required autofocus>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">
                        {{ __('Wyślij link resetujący hasło') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
