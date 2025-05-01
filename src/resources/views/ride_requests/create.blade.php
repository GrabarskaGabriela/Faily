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

<div class="container mt-5 text-white">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ __('messages.riderequestcreate.applyEvent') }}</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('events.show', $ride->event) }}" class="btn btn-secondary">{{ __('messages.riderequestcreate.backToEvent') }}</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card custom-card-bg text-white">
                <div class="card-header">
                    <h4>{{ __('messages.riderequestcreate.applyForm') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('ride-requests.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ride_id" value="{{ $ride->id }}">

                        <div class="mb-3">
                            <label for="message" class="form-label">{{ __('messages.riderequestcreate.driverMessage') }}</label>
                            <textarea class="form-control" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                            <small class="text-white">{{ __('messages.riderequestcreate.addInfo') }}</small>
                            @error('message')
                            <div class="text-danger">{{ __('messages.riderequestcreate.driverMessageError') }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">{{ __('messages.riderequestcreate.sendApl') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card custom-card-bg text-white">
                <div class="card-header">
                    <h4>{{ __('messages.riderequestcreate.rideInfo') }}</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $ride->driver->avatar }}" class="rounded-circle me-3" width="40" alt="{{ $ride->driver->name }}">
                        <div>
                            <h5 class="mb-0">{{ $ride->driver->name }}</h5>
                            <small>{{ __('messages.riderequestcreate.driver') }}</small>
                        </div>
                    </div>

                    <p><strong>{{ __('messages.riderequestcreate.eventName') }}</strong> {{ $ride->event->title }}</p>
                    <p><strong>{{ __('messages.riderequestcreate.eventDate') }}</strong> {{ \Carbon\Carbon::parse($ride->event->date)->format('d.m.Y H:i') }}</p>
                    <p><strong>Pojazd:</strong> {{ $ride->vehicle_description }}</p>

                    @php
                        $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                        $availableSeats = max(0, $ride->available_seats - $takenSeats);
                    @endphp
                    <p><strong>{{ __('messages.riderequestcreate.availableSeats') }}</strong> {{ $availableSeats }} / {{ $ride->available_seats }}</p>

                    <p><strong>{{ __('messages.riderequestcreate.eventLocation') }}</strong> {{ $ride->meeting_location_name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes.footer')
</body>
</html>
