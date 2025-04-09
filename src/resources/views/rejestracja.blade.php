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
        <div class="card shadow-sm">
          <div class="card-body">
            <h3 class="card-title text-center mb-4">Rejestracja</h3>
            <form action="rejestracja.php" method="post" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="email" class="form-label">Adres email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Wprowadź email" required>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="password" class="form-label">Hasło</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Wprowadź hasło" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="confirm_password" class="form-label">Powtórz hasło</label>
                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Powtórz hasło" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name" class="form-label">Imię</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Opcjonalnie">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="last_name" class="form-label">Nazwisko</label>
                  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Opcjonalnie">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="age" class="form-label">Wiek</label>
                  <input type="number" class="form-control" id="age" name="age" placeholder="Opcjonalnie">
                </div>
                <div class="col-md-8 mb-3">
                  <label for="phone" class="form-label">Numer telefonu</label>
                  <input type="tel" class="form-control" id="phone" name="phone" placeholder="Opcjonalnie">
                </div>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Opis</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Opcjonalnie"></textarea>
              </div>
              <div class="mb-3">
                <label for="photo" class="form-label">Wybierz zdjęcie</label>
                <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Zarejestruj się</button>
              </div>
            </form>
            <hr>
              <div class="text-center">
                  <p class="mb-3">Posiadasz konto?</p>
                  <button class="btn btn-primary" type="button" onclick="location.href='logowanie'">Zaloguj się</button>
              </div>
        </div>
      </div>
    </div>
  </main>
  @include('includes.footer')
</body>
</html>
