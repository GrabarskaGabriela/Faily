<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
<div class="page-container">
    @include('includes.navbar')
    <main>
        <section class="hero">
            <div class="container text-center text-white">
                <h1 class="display-4 fw-bold mb-3">Poznawaj ludzi. Twórz wydarzenia.</h1>
                <p class="lead mb-4">Spotkaj się z innymi na wspólnych wyjściach – od kina po rower!</p>
                <a href="{{ route('login') }}" class="btn btn-dark btn-lg">Wchodzę!</a>
            </div>
        </section>

        <section class="py-5 text-white">
            <div class="container text-center">
                <div class="d-flex justify-content-center mb-4">
                    <button class="icon-btn mx-3" onclick="showInfo('people')">
                        <i class="bi bi-people-fill"></i>
                    </button>
                    <button class="icon-btn mx-3" onclick="showInfo('calendar')">
                        <i class="bi bi-calendar-plus-fill"></i>
                    </button>
                    <button class="icon-btn mx-3" onclick="showInfo('car')">
                        <i class="bi bi-car-front-fill"></i>
                    </button>
                </div>
                <div class="info-section">
                    <div id="people" class="info-text active">
                        <h4>Poznawaj ludzi</h4>
                        <p>Dołącz do wydarzeń i poznaj osoby o podobnych zainteresowaniach.</p>
                    </div>
                    <div id="calendar" class="info-text">
                        <h4>Twórz wydarzenia</h4>
                        <p>Organizuj własne spotkania – od wspólnego biegania po wieczory filmowe.</p>
                    </div>
                    <div id="car" class="info-text">
                        <h4>Podwózki</h4>
                        <p>Nie masz jak dotrzeć? Ktoś Cię podrzuci!</p>
                    </div>
                </div>
            </div>
        </section>

 <section class="py-5 bg-dark align-content-lg-center">
     <div class="container align-content-center">
         <h2 class="text-center mb-4 text-white">Zobacz, jak to wygląda!</h2>
         <div class="gallery">
             <img src="{{ asset('images/includes/zdjecie1.png') }}" alt="Wydarzenie 1">
             <img src="{{ asset('images/includes/zdjecie2.png') }}" alt="Wydarzenie 2">
             <img src="{{ asset('images/includes/zdjecie3.png') }}" alt="Wydarzenie 3">
         </div>
     </div>
 </section>
    </main>
</div>

<script>
    function showInfo(id) {
        const texts = document.querySelectorAll('.info-text');
        texts.forEach(text => text.classList.remove('active'));
        document.getElementById(id).classList.add('active');
    }
</script>
@include('includes.footer')
</body>

</html>
