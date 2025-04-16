<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-dark text-white">
<div class="page-container">
    @include('includes.navbar')
    <main>
        <section class="hero py-5 text-white text-center">
            <div class="container">
                <h1 class="display-3 fw-bold mb-3">Poznawaj ludzi. Twórz wydarzenia.</h1>
                <p class="lead mb-4">Spotkaj się z innymi na wspólnych wyjściach – od kina po rower!</p>
                <a href="{{ route('login') }}" class="btn btn-outline-light bg-dark btn-lg px-5 py-2">Wchodzę!</a>
            </div>
        </section>

        <!-- IKONY / INFO -->
        <section class="py-5 bg-transparent text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <div class="container text-center">
                <div class="row justify-content-center mb-4">
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('people')">
                            <i class="bi bi-people-fill fs-3"></i>
                            <div class="small mt-2">Poznawaj</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('calendar')">
                            <i class="bi bi-calendar-plus-fill fs-3"></i>
                            <div class="small mt-2">Wydarzenia</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('car')">
                            <i class="bi bi-car-front-fill fs-3"></i>
                            <div class="small mt-2">Podwózki</div>
                        </button>
                    </div>
                </div>

                <div class="info-section mt-4">
                    <div id="people" class="info-text active">
                        <h4>Poznawaj ludzi</h4>
                        <p>Dołącz do wydarzeń i poznaj osoby o podobnych zainteresowaniach.</p>
                    </div>
                    <div id="calendar" class="info-text d-none">
                        <h4>Twórz wydarzenia</h4>
                        <p>Organizuj własne spotkania – od wspólnego biegania po wieczory filmowe.</p>
                    </div>
                    <div id="car" class="info-text d-none">
                        <h4>Podwózki</h4>
                        <p>Nie masz jak dotrzeć? Ktoś Cię podrzuci!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- GALERIA -->
        <section class="py-5 bg-transparent">
            <div class="container">
                <h2 class="text-center mb-5">Zobacz, jak to wygląda!</h2>
                <div class="row justify-content-center g-4">
                    <div class="col-10 col-sm-6 col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('images/includes/zdjecie1.png') }}" class="img-fluid rounded shadow" alt="Wydarzenie 1">
                    </div>
                    <div class="col-10 col-sm-6 col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('images/includes/zdjecie2.png') }}" class="img-fluid rounded shadow" alt="Wydarzenie 2">
                    </div>
                    <div class="col-10 col-sm-6 col-md-4 d-flex justify-content-center">
                        <img src="{{ asset('images/includes/zdjecie3.png') }}" class="img-fluid rounded shadow" alt="Wydarzenie 3">
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- SKRYPT -->
<script>
    function showInfo(id) {
        const texts = document.querySelectorAll('.info-text');
        texts.forEach(text => text.classList.add('d-none'));
        document.getElementById(id).classList.remove('d-none');
    }
</script>

@include('includes.footer')
</body>
</html>
