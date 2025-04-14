<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - lista wydarzeń</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
@include('includes.navbar')
  <main class="container mt-5">
    <div class="card shadow-sm centered-form">
      <div class="card-body">
        <h3 class="card-title text-center mb-4">Przypomnij hasło</h3>
        <form id="reminderForm" action="przypomnij_haslo.php" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Adres email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Wprowadź email" required>
          </div>
          <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary" id="sendCodeBtn">Otrzymaj kod</button>
          </div>
          <div id="codeSection" class="mt-4" style="display: none;">
            <div class="mb-3">
              <label for="code" class="form-label">Wpisz kod</label>
              <input type="text" class="form-control" id="code" name="code" placeholder="6-znakowy kod" pattern="[a-z0-9]{6}" title="Kod musi zawierać 6 małych liter lub cyfr" required>
            </div>
            <p>Pozostały czas: <span id="timer">100</span> sekund</p>
          </div>
        </form>
      </div>
    </div>
  </main>
@include('includes.footer')
  <script>
    const sendCodeBtn = document.getElementById('sendCodeBtn');
    const codeSection = document.getElementById('codeSection');
    const timerDisplay = document.getElementById('timer');
    let countdown = 100;
    let timerInterval;

    sendCodeBtn.addEventListener('click', function() {
      sendCodeBtn.disabled = true;
      codeSection.style.display = 'block';
      timerInterval = setInterval(function() {
        countdown--;
        timerDisplay.textContent = countdown;
        if (countdown <= 0) {
          clearInterval(timerInterval);
        }
      }, 1000);
    });
  </script>
</body>
</html>
