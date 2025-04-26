<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - rejestracja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('includes.navbar')

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-dark">
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);" >
                    <h3 class="card-title text-center mb-4 text-white">Rejestracja</h3>

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label text-white">Nazwa użytkownika</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Wprowadź nazwę użytkownika" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-white">Adres email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Wprowadź email" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label text-white">Hasło</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Wprowadź hasło" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label text-white">Powtórz hasło</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Powtórz hasło" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label text-white">Imię</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Opcjonalnie">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label text-white">Nazwisko</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Opcjonalnie">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="age" class="form-label text-white">Wiek</label>
                                <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" placeholder="Opcjonalnie">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="phone" class="form-label text-white">Numer telefonu</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Opcjonalnie">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label text-white">Opis</label>
                            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Opcjonalnie">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label text-white">Wybierz zdjęcie</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Zarejestruj się</button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-3 text-white">Posiadasz konto?</p>
                        <a href="{{ route('login') }}" class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Zaloguj się</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
