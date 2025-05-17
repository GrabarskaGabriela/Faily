<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.faily') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-dark text-color">
<main>
<div class="page-container">
    @include('includes.navbar')

        <section class="hero py-5 text-color_2 text-center">
            <div class="container">
                <h1 class="display-3 fw-bold mb-3">{{ __('messages.welcome.mainTitle') }}</h1>
                <p class="lead mb-4">{{ __('messages.welcome.mainSubtitle') }}</p>
                <a href="{{ url('/about') }}" class="btn-gradient btn-outline-dark btn-lg px-5 py-2">{{ __('messages.welcome.meetDevelopers') }}</a>
            </div>
        </section>
        <section class="py-5 bg-welcome">
            <div class="container text-center">
                <div class="row justify-content-center mb-4">
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient w-100 py-3" onclick="showInfo('people')">
                            <i class="bi bi-people-fill fs-3"></i>
                            <div class="small mt-2">{{ __('messages.welcome.explore') }}</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient w-100 py-3" onclick="showInfo('calendar')">
                            <i class="bi bi-calendar-plus-fill fs-3"></i>
                            <div class="small mt-2">{{ __('messages.welcome.events') }}</div>
                        </button>
                    </div>
                    <div class="col-4 col-md-2">
                        <button class="btn btn-gradient w-100 py-3" onclick="showInfo('car')">
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

</div>

<script>
    function showInfo(id) {
        const texts = document.querySelectorAll('.info-text');
        texts.forEach(text => text.classList.add('d-none'));
        document.getElementById(id).classList.remove('d-none');
    }
</script>
</main>
@include('includes.footer')
</body>
</html>
