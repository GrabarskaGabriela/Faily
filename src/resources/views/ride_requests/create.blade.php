<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.title.rideApply') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">
@include('includes.navbar')
<main>
<div class="container mt-5 text-color_2">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ __('messages.riderequestscreate.applyEvent') }}</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('events.show', $ride->event) }}" class="btn btn-gradient text-color_2">{{ __('messages.riderequestscreate.backToEvent') }}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card custom-card-bg shadow-sm text-color_2">
                <div class="card-header text-color_2 text-center">
                    <h4>{{ __('messages.riderequestscreate.applyForm') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('ride-requests.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">

                        <div class="mb-3">
                            <label for="message" class="form-label text-color">{{ __('messages.riderequestscreate.driverMessage') }}</label>
                            <textarea class="form-control" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                            <small class="text-color">{{ __('messages.riderequestscreate.addInfo') }}</small>
                            @error('message')
                            <div class="text-danger">{{ __('messages.riderequestscreate.driverMessageError') }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn text-color_2 btn-gradient">{{ __('messages.riderequestscreate.sendApl') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card-bg text-color shadow-sm">
                <div class="card-header text-color_2 text-center">
                    <h4>{{ __('messages.riderequestscreate.rideInfo') }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($ride->driver->photo_path)
                            <img src="{{ asset('storage/' . $ride->driver->photo_path) }}"
                                 class="rounded-circle border border-2 border-light"
                                 alt="{{ __('messages.eventlist.profilePhotoLabel') }}" width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/includes/default-avatar.png') }}"
                                 class="rounded-circle border border-2 border-light"
                                 alt="{{ __('messages.eventlist.profilePhotoLabel') }}" width="80" height="80" style="object-fit: cover;">
                        @endif
                        <div>
                            <h5 class="mb-0">{{ $ride->driver->name }}</h5>
                            <small>{{ __('messages.riderequestscreate.driver') }}</small>
                        </div>
                    </div>

                    <p><strong>{{ __('messages.riderequestscreate.eventName') }}</strong> {{ $ride->event->title }}</p>
                    <p><strong>{{ __('messages.riderequestscreate.eventDate') }}</strong> {{ \Carbon\Carbon::parse($ride->event->date)->format('d.m.Y H:i') }}</p>
                    <p><strong>{{ __('messages.riderequestscreate.carInfo') }}</strong> {{ $ride->vehicle_description }}</p>

                    @php
                        $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                        $availableSeats = max(0, $ride->available_seats - $takenSeats);
                    @endphp
                    <p><strong>{{ __('messages.riderequestscreate.availableSeats') }}</strong> {{ $availableSeats }} / {{ $ride->available_seats }}</p>

                    <p><strong>{{ __('messages.riderequestscreate.eventLocation') }}</strong> {{ $ride->meeting_location_name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@include('includes.footer')
</body>
</html>
