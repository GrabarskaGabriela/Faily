<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - index</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
  <main class="container py-5">
    <h2 class="mb-4">Ustawienia</h2>
    <div class="row g-4">

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Zmień hasło</h5>
            <form action="zmien_haslo.php" method="post">
              <div class="mb-3">
                <label for="currentPassword" class="form-label">Aktualne hasło</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
              </div>
              <div class="mb-3">
                <label for="newPassword" class="form-label">Nowe hasło</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
              </div>
              <div class="mb-3">
                <label for="confirmNewPassword" class="form-label">Powtórz nowe hasło</label>
                <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" required>
              </div>
              <button type="submit" class="btn btn-primary">Zmień hasło</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Ustawienia prywatności</h5>
            <form action="ustawienia_prywatnosci.php" method="post">
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="profileVisibility" name="profileVisibility" checked>
                <label class="form-check-label" for="profileVisibility">Widoczność profilu</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="dataSharing" name="dataSharing">
                <label class="form-check-label" for="dataSharing">Udostępnianie danych osobowych</label>
              </div>
              <button type="submit" class="btn btn-primary">Zapisz ustawienia</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Powiadomienia</h5>
            <form action="ustawienia_powiadomien.php" method="post">
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="emailNotifications" name="emailNotifications" checked>
                <label class="form-check-label" for="emailNotifications">Powiadomienia email</label>
              </div>
              <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="smsNotifications" name="smsNotifications">
                <label class="form-check-label" for="smsNotifications">Powiadomienia SMS</label>
              </div>
              <button type="submit" class="btn btn-primary">Zapisz ustawienia</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Personalizacja</h5>
            <form action="ustawienia_personalizacja.php" method="post">
              <div class="mb-3">
                <label for="theme" class="form-label">Wybierz motyw</label>
                <select class="form-select" id="theme" name="theme">
                  <option value="light" selected>Jasny</option>
                  <option value="dark">Ciemny</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="language" class="form-label">Język</label>
                <select class="form-select" id="language" name="language">
                  <option value="pl" selected>Polski</option>
                  <option value="en">Angielski</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Zapisz ustawienia</button>
            </form>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-danger">Usuń konto</h5>
            <p class="text-muted">Operacja jest nieodwracalna. Upewnij się, że chcesz usunąć konto.</p>
            <button class="btn btn-danger" onclick="if(confirm('Czy na pewno chcesz usunąć konto?')) { /* php */ }">Usuń konto</button>
          </div>
        </div>
      </div>

    </div>
  </main>
  @include('includes.footer')
</body>
</html>
