<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resetowanie hasła - Homeiq</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100">
@include('includes.navbar')

<main class="container flex-grow-1 my-5">
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Resetowanie hasła</h3>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" name="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="email">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Nowe hasło</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password"
                           required autocomplete="new-password">
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Potwierdź nowe hasło</label>
                    <input type="password" class="form-control"
                           id="password_confirmation" name="password_confirmation"
                           required autocomplete="new-password">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        Zresetuj hasło
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
