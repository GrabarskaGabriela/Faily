<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - wydarzenie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
  <div class="container mt-5">
    <div class="row">
      <div class="col-12">
        <!-- Carousel -->
        <div id="demo" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <img src="zdjecie1.png" alt="Zdjęcie 1" class="d-block img-fluid">
            </div>
            <div class="carousel-item">
              <img src="zdjecie2.png" alt="Zdjęcie 2" class="d-block img-fluid">
            </div>
            <div class="carousel-item">
              <img src="zdjecie3.png" alt="Zdjęcie 3" class="d-block img-fluid">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-12">
        <h2 class="mb-3">Nazwa wydarzenia</h2>
        <p class="text-muted mb-4">Lokalizacja: Miasto, ulica, nr.</p>
        <div class="mb-3">
          <span class="ms-2">Ilość zapisanych osób</span>
        </div>
        <p class="mb-4">Opis wydarzenia:</p>
        <div class="mb-4">
          <label for="quantity" class="form-label">Ile osób:</label>
          <input type="number" class="form-control" id="quantity" value="1" min="1" style="width: 80px;">
        </div>

        <div class="mt-5 border p-3 bg-white rounded shadow-sm">
          <h4>Dodatkowe opcje</h4>
          <div class="mb-3">
            <label class="form-label">Masz auto?</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="hasAuto" id="autoYes" value="tak">
              <label class="form-check-label" for="autoYes">Tak</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="hasAuto" id="autoNo" value="nie">
              <label class="form-check-label" for="autoNo">Nie</label>
            </div>
          </div>
          <div id="podwiezSection" class="mb-3" style="display: none;">
            <label class="form-label">Czy chcesz kogoś podwieźć?</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="podwiez" id="podwiezYes" value="tak">
              <label class="form-check-label" for="podwiezYes">Tak</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="podwiez" id="podwiezNo" value="nie">
              <label class="form-check-label" for="podwiezNo">Nie</label>
            </div>
          </div>
          <div id="podwozkaSection" class="mb-3" style="display: none;">
            <label class="form-label">Szukasz podwózki?</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="podwozka" id="podwozkaYes" value="tak">
              <label class="form-check-label" for="podwozkaYes">Tak</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="podwozka" id="podwozkaNo" value="nie">
              <label class="form-check-label" for="podwozkaNo">Nie</label>
            </div>
          </div>
        </div>

        <button class="btn btn-primary btn-lg mb-3 me-2">
          <i class="bi bi-cart-plus"></i> Zapisz się na wydarzenie
        </button>
        <button class="btn btn-outline-secondary btn-lg mb-3">
          <i class="bi bi-heart"></i> Dodaj do listy wydarzeń
        </button>
      </div>
    </div>
  </div>
  @include('includes.footer')
  <script>
    const autoYes = document.getElementById('autoYes');
    const autoNo = document.getElementById('autoNo');
    const podwiezSection = document.getElementById('podwiezSection');
    const podwozkaSection = document.getElementById('podwozkaSection');

    function handleAutoChange() {
      if (autoYes.checked) {
        podwiezSection.style.display = 'block';
        podwozkaSection.style.display = 'none';
      } else if (autoNo.checked) {
        podwiezSection.style.display = 'none';
        podwozkaSection.style.display = 'block';
      }
    }

    autoYes.addEventListener('change', handleAutoChange);
    autoNo.addEventListener('change', handleAutoChange);
  </script>
</body>
</html>
