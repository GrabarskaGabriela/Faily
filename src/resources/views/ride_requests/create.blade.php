@extends('layouts.app')

@section('content')
    <div class="container mt-5 text-white">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Zgłoś się na przejazd</h2>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('events.show', $ride->event) }}" class="btn btn-secondary">Powrót do wydarzenia</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card custom-card-bg text-white">
                    <div class="card-header">
                        <h4>Formularz zgłoszenia</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('ride-requests.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ride_id" value="{{ $ride->id }}">

                            <div class="mb-3">
                                <label for="message" class="form-label">Wiadomość dla kierowcy (opcjonalnie)</label>
                                <textarea class="form-control" id="message" name="message" rows="3">{{ old('message') }}</textarea>
                                <small class="text-muted">Możesz dodać dodatkową informację dla kierowcy</small>
                                @error('message')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Wyślij zgłoszenie</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card-bg text-white">
                    <div class="card-header">
                        <h4>Informacje o przejeździe</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $ride->driver->avatar }}" class="rounded-circle me-3" width="40" alt="{{ $ride->driver->name }}">
                            <div>
                                <h5 class="mb-0">{{ $ride->driver->name }}</h5>
                                <small>Kierowca</small>
                            </div>
                        </div>

                        <p><strong>Wydarzenie:</strong> {{ $ride->event->title }}</p>
                        <p><strong>Data wydarzenia:</strong> {{ \Carbon\Carbon::parse($ride->event->date)->format('d.m.Y H:i') }}</p>
                        <p><strong>Pojazd:</strong> {{ $ride->vehicle_description }}</p>

                        @php
                            $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                            $availableSeats = max(0, $ride->available_seats - $takenSeats);
                        @endphp
                        <p><strong>Dostępne miejsca:</strong> {{ $availableSeats }} / {{ $ride->available_seats }}</p>

                        <p><strong>Miejsce spotkania:</strong> {{ $ride->meeting_location_name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
