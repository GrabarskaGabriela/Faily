<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.eventlist.allEventsButton') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5">
    <h1 class="fw-bold mb-4 text-color_2">{{ __('messages.eventlist.allEventsButton') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        @forelse ($events as $event)
            <div class="col-md-4 mb-4 fade-in-up">
                <div class="card h-100 shadow-sm text-color lift-card">
                    <a href="{{ route('events.show', $event->id) }}">
                        @if(isset($event->photos) && count($event->photos) > 0)
                            <img src="{{ asset('storage/' . $event->photos[0]->path) }}"
                                 alt="{{ $event->title }}"
                                 class="card-img-top"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/includes/brak_zdjecia.jpg') }}"
                                 alt="Brak zdjęcia"
                                 class="card-img-top w-100"
                                 style="height: 250px; object-fit: cover;">
                        @endif
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">{{ $event->title }}</h5>
                        <p><strong>{{ __('messages.myevents.date') }}</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                        <p><strong>{{ __('messages.myevents.location') }}</strong> {{ $event->location_name }}</p>

                        @if($event->has_ride_sharing)
                            <p>
                                    <span class="badge bg-success">
                                        <i class="bi bi-car-front"></i> {{ __('messages.myevents.ridesAvailable') }}
                                    </span>
                            </p>
                        @endif

                        <p class="text-truncate">{{ Str::limit($event->description, 100) }}</p>
                    </div>
                    <div class="card-body d-grid gap-2 text-color">
                        <a href="{{ route('events.show', $event->id) }}"  class="btn btn-gradient text-color_2">{{ __('messages.myevents.check') }}</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    {{ __('messages.myevents.noEvent') }}
                </div>
            </div>
        @endforelse
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $events->links('pagination::bootstrap-5') }}
    </div>
</main>
</div>
@include('includes.footer')
</body>
</html>
