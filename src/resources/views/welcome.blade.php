{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ __('messages.title.faily') }}</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero .carousel-item {
        height: 33vh;
        }

        .hero .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        }

        .img-gallery
        {
            height: 25vh;
            width: 45vh;
        }

        .img-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        }
    </style>
</head>
<body class="bg-dark text-color d-flex flex-column min-vh-100">
  @include('includes.navbar')

  <main class="flex-fill">
    {{-- HERO + CAROUSEL --}}
    <section class="hero position-relative overflow-hidden">
      <div id="welcomeCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
          @for($i = 1; $i <= 3; $i++)
            <div class="carousel-item @if($i == 1) active @endif">
              <img src="{{ asset('images/welcome/slide'.$i.'.png') }}" class="d-block w-100" alt="Slide {{ $i }}">
              <div class="carousel-caption d-none d-md-block">
                <h1 class="display-4 fw-bold">
                  {{ __('messages.welcome.heroTitle') }}
                </h1>
                <p class="lead mb-4">{{ __('messages.welcome.heroSubtitle') }}</p>
              </div>
            </div>
          @endfor
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#welcomeCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </section>

    {{-- FEATURES --}}
    <section class="py-5 bg-welcome text-center">
      <div class="container">
        <h2 class="mb-5">{{ __('messages.welcome.mainTitle') }}</h2>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card bg-transparent border-0 text-color fade-in-up delay-1">
              <div class="card-body">
                <i class="bi bi-people-fill fs-1 mb-3"></i>
                <h5 class="card-title">{{ __('messages.welcome.explore') }}</h5>
                <p class="card-text">{{ __('messages.welcome.createEventsDesc') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-transparent border-0 text-color fade-in-up delay-2">
              <div class="card-body">
                <i class="bi bi-calendar-plus-fill fs-1 mb-3"></i>
                <h5 class="card-title">{{ __('messages.welcome.events') }}</h5>
                <p class="card-text">{{ __('messages.welcome.meetPeopleDesc') }}</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-transparent border-0 text-color fade-in-up delay-3">
              <div class="card-body">
                <i class="bi bi-car-front-fill fs-1 mb-3"></i>
                <h5 class="card-title">{{ __('messages.welcome.rides') }}</h5>
                <p class="card-text">{{ __('messages.welcome.needRide') }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- GALLERY --}}
    <section class="py-5 text-center">
      <div class="container">
        <h2 class="mb-4">{{ __('messages.welcome.seeHowItWorks') }}</h2>
        <p class="mb-5">{{ __('messages.welcome.mainSubtitle') }}</p>
        <div class="row g-3">
          @for($i = 1; $i <= 6; $i++)
            <div class="col-6 col-md-4">
              <div class="img-gallery overflow-hidden rounded floating delay-{{ $i % 2 + 1 }}">
                <img src="{{ asset('images/welcome/gallery'.$i.'.png') }}"
                     class="img-fluid" alt="Gallery {{ $i }}">
              </div>
            </div>
          @endfor
        </div>
      </div>
    </section>

    {{-- FINAL CTA --}}
    <section class="py-5 bg-welcome text-center">
      <div class="container">
        <h2 class="mb-4">{{ __('messages.welcome.meetDevelopers') }}</h2>
        <a href="{{ url('/about') }}" class="btn-gradient btn-lg px-5">
          {{ __('messages.welcome.meetDevelopersButton') }}
        </a>
      </div>
    </section>
  </main>

  @include('includes.footer')
</body>
</html>