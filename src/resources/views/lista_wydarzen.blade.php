<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - lista wydarzeń</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
  <div class="container mt-5">
    <section class="mb-5">
      <h3 class="mb-4">Na te wydarzenia się zapisałeś:</h3>
      <div class="row">

        <div class="col-md-4 mb-4">
          <div class="card h-100 custom-card-bg">
            <a href="wydarzenie.blade.php">
              <img src="zdjecie1.png" class="card-img-top event-img" alt="Tytuł wydarzenia">
            </a>
            <div class="card-body">
              <h5 class="card-title">Tytuł wydarzenia</h5>
              <p class="card-text">Miasto, ulica, nr.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section>
      <h3 class="mb-4">Te wydarzenia dodałeś do listy:</h3>
      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="card h-100 custom-card-bg">
            <a href="wydarzenie.blade.php">
              <img src="zdjecie3.png" class="card-img-top event-img" alt="Tytuł wydarzenia">
            </a>
            <div class="card-body">
              <h5 class="card-title">Kolejne wydarzenie</h5>
              <p class="card-text">Miasto, ulica, nr.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="card h-100 custom-card-bg">
            <a href="wydarzenie.blade.php">
              <img src="zdjecie1.png" class="card-img-top event-img" alt="Tytuł wydarzenia">
            </a>
            <div class="card-body">
              <h5 class="card-title">Jeszcze jedno wydarzenie</h5>
              <p class="card-text">Miasto, ulica, nr.</p>
            </div>
          </div>
        </div>

      </div>
    </section>
  </div>
@include('includes.footer')
</body>
</html>
