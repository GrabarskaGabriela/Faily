<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - index</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('includes.navbar')
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title text-center mb-4">Logowanie</h3>
                    <form action="logowanie.php" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adres email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Wprowadź email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Hasło</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Wprowadź hasło" required>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary" type="button" onclick="location.href='przypomnij_haslo'">Przypomnij hasło</button>
                            <button class="btn btn-primary" type="button" onclick="location.href='rejestracja'">Zaloguj się</button>
                        </div>
                    </form>
                    <hr>
                    <div class="d-grid gap-2 text-center">
                        <p class="mb-3">Nie posiadasz konta?</p>
                        <button class="btn btn-primary" type="button" onclick="location.href='rejestracja'">Zarejsetruj się</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('includes.footer')
</body>
</html>
