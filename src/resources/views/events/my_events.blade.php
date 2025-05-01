<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.myEvents') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5">
    <h1 class="fw-bold mb-4 text-white">{{ __('messages.myevents.myEvents') }}</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row">
        @forelse ($events as $event)
            <div class="col-md-4 mb-4">
                <div class="card text-black h-100 shadow text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
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
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('events.show', $event->id) }}"  class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">{{ __('messages.myevents.check') }}</a>
                        <a href="{{ route('events.edit', $event->id) }}" class="btn btn-secondary">{{ __('messages.myevents.edit') }}</a>
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
