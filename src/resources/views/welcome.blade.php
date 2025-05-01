<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.faily') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-white">
<div class="page-container">
    @include('includes.navbar')
    <main>
        <section class="hero py-5 text-white text-center">
            <div class="container">
                <h1 class="display-3 fw-bold mb-3">{{ __('messages.welcome.mainTitle') }}</h1>
                <p class="lead mb-4">{{ __('messages.welcome.mainSubtitle') }}</p>
                <a href="{{ url('/about') }}" class="btn text-white btn-outline-dark btn-lg px-5 py-2" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.welcome.meetDevelopers') }}</a>
            </div>
        </section>
        <section class="py-5 bg-transparent text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <div class="container text-center">
                <div class="row justify-content-center mb-4">
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('people')">
                            <i class="bi bi-people-fill fs-3"></i>
                            <div class="small mt-2">{{ __('messages.welcome.explore') }}</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('calendar')">
                            <i class="bi bi-calendar-plus-fill fs-3"></i>
                            <div class="small mt-2">{{ __('messages.welcome.events') }}</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient btn-outline-light w-100 py-3" onclick="showInfo('car')">
                            <i class="bi bi-car-front-fill fs-3"></i>
                            <div class="small mt-2">{{ __('messages.welcome.rides') }}</div>
                        </button>
                    </div>
                </div>

                <div class="info-section mt-4">
                    <div id="people" class="info-text active">
                        <h4>{{ __('messages.welcome.meetPeople') }}</h4>
                        <p>{{ __('messages.welcome.meetPeopleDesc') }}</p>
                    </div>
                    <div id="calendar" class="info-text d-none">
                        <h4>{{ __('messages.welcome.createEvents') }}</h4>
                        <p>{{ __('messages.welcome.createEventsDesc') }}</p>
                    </div>
                    <div id="car" class="info-text d-none">
                        <h4>{{ __('messages.welcome.rides') }}</h4>
                        <p>{{ __('messages.welcome.needRide') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 bg-transparent">
            <div class="container">
                <h2 class="text-center mb-5">{{ __('messages.welcome.seeHowItWorks') }}</h2>
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
