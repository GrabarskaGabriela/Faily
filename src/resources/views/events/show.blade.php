<x-app-layout>
    <div class="container mt-5 text-white">
        <div class="row">
            <div class="col-md-8">
                <h2>{{ $event->title }}</h2>
                <p class="text-muted">{{ $event->location_name }}</p>

                @if($event->photos->count() > 0)
                    <div id="eventCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            @foreach($event->photos as $key => $photo)
                                <button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                        <div class="carousel-inner">
                            @foreach($event->photos as $key => $photo)
                                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $photo->path) }}" class="d-block w-100" alt="{{ $event->title }}">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                @else
                    <img src="{{ asset('images/includes/zdjecie1.png') }}" class="img-fluid mb-4" alt="{{ $event->title }}">
                @endif

                <div class="card custom-card-bg mb-4">
                    <div class="card-header">
                        <h4>Opis wydarzenia</h4>
                    </div>
                    <div class="card-body">
                        {!! nl2br(e($event->description)) !!}
                    </div>
                </div>

                <div class="card custom-card-bg mb-4">
                    <div class="card-header">
                        <h4>Szczegóły</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d.m.Y H:i') }}</p>
                                <p><strong>Lokalizacja:</strong> {{ $event->location_name }}</p>
                                <p><strong>Organizator:</strong> {{ $event->user->name }}</p>
                            </div>
                            <div class="col-md-6">
                                @php
                                    $totalAttendees = $event->acceptedAttendees()->sum('attendees_count');
                                    $availableSpots = max(0, $event->people_count - $totalAttendees);
                                @endphp
                                <p><strong>Limit uczestników:</strong> {{ $event->people_count }}</p>
                                <p><strong>Zapisanych osób:</strong> {{ $totalAttendees }}</p>
                                <p><strong>Wolne miejsca:</strong> {{ $availableSpots }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($event->has_ride_sharing && $event->rides->count() > 0)
                    <div class="card custom-card-bg mb-4">
                        <div class="card-header">
                            <h4>Dostępne przejazdy</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-striped">
                                    <thead>
                                    <tr>
                                        <th>Kierowca</th>
                                        <th>Pojazd</th>
                                        <th>Miejsca</th>
                                        <th>Miejsce spotkania</th>
                                        <th>Akcje</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($event->rides as $ride)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $ride->driver->avatar }}" class="rounded-circle me-2" width="30" alt="{{ $ride->driver->name }}">
                                                    <span>{{ $ride->driver->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $ride->vehicle_description }}</td>
                                            <td>
                                                @php
                                                    $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                                                    $availableSeats = max(0, $ride->available_seats - $takenSeats);
                                                @endphp
                                                {{ $availableSeats }} / {{ $ride->available_seats }}
                                            </td>
                                            <td>{{ $ride->meeting_location_name }}</td>
                                            <td>
                                                @auth
                                                    @if(Auth::id() !== $ride->driver_id)
                                                        @php
                                                            $userRequest = $ride->requests()->where('passenger_id', Auth::id())->first();
                                                        @endphp

                                                        @if(!$userRequest)
                                                            @if($availableSeats > 0)
                                                                <a href="{{ route('ride-requests.create', ['ride_id' => $ride->id]) }}" class="btn btn-sm btn-primary">Zgłoś się na przejazd</a>
                                                            @else
                                                                <span class="badge bg-danger">Brak miejsc</span>
                                                            @endif
                                                        @elseif($userRequest->status == 'pending')
                                                            <span class="badge bg-warning">Oczekuje na potwierdzenie</span>
                                                            <form action="{{ route('ride-requests.destroy', $userRequest) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Anuluj</button>
                                                            </form>
                                                        @elseif($userRequest->status == 'accepted')
                                                            <span class="badge bg-success">Zaakceptowane</span>
                                                            <form action="{{ route('ride-requests.destroy', $userRequest) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">Zrezygnuj</button>
                                                            </form>
                                                        @elseif($userRequest->status == 'rejected')
                                                            <span class="badge bg-danger">Odrzucone</span>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('ride-requests.index', ['ride_id' => $ride->id]) }}" class="btn btn-sm btn-info">Zarządzaj zgłoszeniami</a>
                                                        <a href="{{ route('rides.edit', $ride) }}" class="btn btn-sm btn-primary">Edytuj</a>
                                                        <form action="{{ route('rides.destroy', $ride) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Czy na pewno chcesz usunąć ten przejazd?')">Usuń</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card custom-card-bg mb-4">
                    <div class="card-header">
                        <h4>Akcje</h4>
                    </div>
                    <div class="card-body">
                        @auth
                            @if(Auth::id() === $event->user_id)
                                {{-- Akcje dla organizatora wydarzenia --}}
                                <div class="d-grid gap-2 mb-3">
                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-primary">Edytuj wydarzenie</a>
                                </div>
                                <div class="d-grid gap-2 mb-3">
                                    <a href="{{ route('events.attendees.index', $event) }}" class="btn btn-info">Zarządzaj uczestnikami</a>
                                </div>
                                @if($event->has_ride_sharing)
                                    <div class="d-grid gap-2 mb-3">
                                        <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="btn btn-success">Dodaj przejazd</a>
                                    </div>
                                @endif
                                <div class="d-grid gap-2">
                                    <form action="{{ route('events.destroy', $event) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Czy na pewno chcesz usunąć to wydarzenie?')">Usuń wydarzenie</button>
                                    </form>
                                </div>
                            @else
                                {{-- Akcje dla zwykłego użytkownika --}}
                                @php
                                    $attendee = $event->attendees()->where('user_id', Auth::id())->first();
                                @endphp

                                @if(!$attendee)
                                    <div class="d-grid gap-2 mb-3">
                                        <a href="{{ route('events.attendees.create', $event) }}" class="btn btn-primary">Zapisz się na wydarzenie</a>
                                    </div>
                                @else
                                    <div class="alert
                                    @if($attendee->status == 'pending') alert-warning
                                    @elseif($attendee->status == 'accepted') alert-success
                                    @elseif($attendee->status == 'rejected') alert-danger
                                    @endif">
                                        <p>
                                            <strong>
                                                @if($attendee->status == 'pending') Twoje zgłoszenie oczekuje na akceptację
                                                @elseif($attendee->status == 'accepted') Jesteś zapisany na to wydarzenie
                                                @elseif($attendee->status == 'rejected') Twoje zgłoszenie zostało odrzucone
                                                @endif
                                            </strong>
                                        </p>
                                        <p>Liczba osób: {{ $attendee->attendees_count }}</p>
                                        @if($attendee->message)
                                            <p>Twoja wiadomość: {{ $attendee->message }}</p>
                                        @endif

                                        <form action="{{ route('events.attendees.destroy', ['event' => $event, 'attendee' => $attendee]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                @if($attendee->status == 'accepted') Wypisz się
                                                @else Anuluj zgłoszenie
                                                @endif
                                            </button>
                                        </form>
                                    </div>

                                    @if($attendee->status == 'accepted' && $event->has_ride_sharing)
                                        <div class="card mb-3">
                                            <div class="card-header bg-light text-dark">
                                                <h5>Opcje przejazdu</h5>
                                            </div>
                                            <div class="card-body">
                                                @php
                                                    $userRide = $event->rides()->where('driver_id', Auth::id())->first();
                                                @endphp

                                                @if(!$userRide)
                                                    <div class="mb-3">
                                                        <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="btn btn-success w-100">Zaoferuj przejazd</a>
                                                    </div>
                                                @else
                                                    <div class="alert alert-success">
                                                        <p><strong>Oferujesz przejazd!</strong></p>
                                                        <p>Pojazd: {{ $userRide->vehicle_description }}</p>
                                                        <p>Miejsca: {{ $userRide->available_seats }}</p>
                                                        <div class="mt-2">
                                                            <a href="{{ route('ride-requests.index', ['ride_id' => $userRide->id]) }}" class="btn btn-sm btn-info">Zarządzaj zgłoszeniami</a>
                                                            <a href="{{ route('rides.edit', $userRide) }}" class="btn btn-sm btn-primary">Edytuj</a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @else
                            <div class="alert alert-info">
                                <p>Zaloguj się, aby zapisać się na to wydarzenie lub zobaczyć więcej opcji.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Zaloguj się</a>
                            </div>
                        @endauth
                    </div>
                </div>

                <div class="card custom-card-bg">
                    <div class="card-header">
                        <h4>Organizator</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $event->user->avatar }}" class="rounded-circle me-3" width="60" alt="{{ $event->user->name }}">
                            <div>
                                <h5 class="mb-0">{{ $event->user->name }}</h5>
                                @if($event->user->description)
                                    <p class="mt-2">{{ $event->user->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
