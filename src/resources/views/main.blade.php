<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - lista wydarzeń</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
  <div class="page-wrapper">
      @include('includes.navbar')
    <main class="container mt-5 mb-5">
      <h2>Wszystkie wydarzenia:</h2>

      <div class="row">
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie1.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie2.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie3.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>


    <div class="row">
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie1.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie2.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie3.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie1.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie2.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie3.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie1.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie2.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie3.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie1.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie2.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-black" style="background-color: rgb(0, 140, 255);">
          <a href="event.blade.php">
            <img src="zdjecie3.png" class="card-img-top" style="height: 250px; object-fit: cover;" alt="Tytuł wydarzenia">
          </a>
          <div class="card-body">
            <h5 class="card-title">Tytuł wydarzenia</h5>
            <p class="card-text">Miasto, ulica, nr.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

  <nav aria-label="Page navigation">
    <ul class="pagination pagination-floating justify-content-center">
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Previous"><span aria-hidden="true">«</span></a>
      </li>
      <li class="page-item"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">4</a></li>
      <li class="page-item"><a class="page-link" href="#">5</a></li>
      <li class="page-item">
        <a class="page-link" href="#" aria-label="Next"><span aria-hidden="true">»</span></a>
      </li>
    </ul>
  </nav>

  <div class="container mt-5 py-5 newsletter-section rounded-3 shadow">
    <div class="row align-items-center">
      <div class="col-md-6 text-center text-md-start">
        <h2 class="fw-bold">Chcesz być informowany o wydarzeniach?</h2>
        <p class="text-muted">Dodaj swój email i ciesz się stałym kontaktem z nowymi wydarzeniami!</p>
      </div>
      <div class="col-md-6">
        <form class="d-flex">
          <input type="email" class="form-control me-2 rounded-pill shadow-sm" placeholder="Tutaj podaj email" required>
          <button class="btn btn-primary px-4 rounded-pill shadow-sm" type="submit">Dodaj</button>
        </form>
        <small class="d-block mt-2 text-muted">Szanujemy Twoją prywatność. Wyłącz powiadomienia w każdym momencie.</small>
      </div>
    </div>
  </div>

  @include('includes.footer')
</body>
</html>
