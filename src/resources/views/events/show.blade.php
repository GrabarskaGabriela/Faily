<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faily - Szczegóły wydarzenia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-main">

@include('includes.navbar')

<main class="container mt-5 mb-5 text-white">
    <div class="row">
        <div class="col-md-8">
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
                <img src="{{ asset('images/includes/brak_zdjecia.jpg') }}"
                     alt="Brak zdjęcia"
                     class="card-img-top w-100"
                     style="height: 250px; object-fit: cover;">
            @endif

            <div class="card text-white border-dark mb-4" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <div class="card-header flex-column">
                    <h4>Opis wydarzenia</h4>
                </div>
                <div class="card-body border-black" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <p class="text-white">Tytuł wydarzenia:  {{ $event->title }}</p>
                    <p class="text-white">Lokalizacja: {{ $event->location_name }}</p>
                    <p class="text-white">Opis wydarzenia: {{ $event->description}}</p>
                </div>
            </div>

            <div class="card text-white border-black mb-4" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <div class="card-header">
                    <h4>Szczegóły</h4>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
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
                    <div class="card shadow-lg mb-4 border-black" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%); border-radius: 12px; border-color: black">
                        <div class="card-header py-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                            <h4 class="text-white m-0 font-weight-bold"><i class="fas fa-car me-2"></i>Dostępne przejazdy</h4>
                        </div>
                        <div class="card-body p-0  border-dark">
                            <div class="table-responsive border-dark ">
                                <table class="table table-dark table-hover mb-0">
                                    <thead>
                                    <tr class="border-dark">
                                        <th class="py-3 px-4 border-0">Kierowca</th>
                                        <th class="py-3 px-4 border-0">Pojazd</th>
                                        <th class="py-3 px-4 border-0">Miejsca</th>
                                        <th class="py-3 px-4 border-0">Lokalizacja</th>
                                        <th class="py-3 px-4 border-0">Akcje</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($event->rides as $ride)
                                        <tr class="border-top border-secondary">
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $ride->driver->avatar }}"
                                                         class="rounded-circle me-3 border border-2 border-light"
                                                         width="40" height="40"
                                                         style="object-fit: cover;"
                                                         alt="{{ $ride->driver->name }}">
                                                    <span class="fw-bold">{{ $ride->driver->name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-car-side me-2 text-info"></i>
                                                    <span>{{ $ride->vehicle_description }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @php
                                                    $takenSeats = $ride->requests()->where('status', 'accepted')->count();
                                                    $availableSeats = max(0, $ride->available_seats - $takenSeats);
                                                @endphp
                                                <div class="d-flex align-items-center">
                                                    <span class="badge {{ $availableSeats > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                        {{ $availableSeats }} / {{ $ride->available_seats }}
                                    </span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                                    <span>{{ $ride->meeting_location_name }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                @auth
                                                    @if(Auth::id() !== $ride->driver_id)
                                                        @php
                                                            $userRequest = $ride->requests()->where('passenger_id', Auth::id())->first();
                                                        @endphp

                                                        @if(!$userRequest)
                                                            @if($availableSeats > 0)
                                                                <a href="{{ route('ride-requests.create', ['ride_id' => $ride->id]) }}"
                                                                   class="btn btn-sm fw-bold text-white"
                                                                   style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%); border-radius: 20px; padding: 5px 15px;">
                                                                    <i class="fas fa-hand-point-up me-1"></i> Zgłoś się
                                                                </a>
                                                            @else
                                                                <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%);">
                                                    <i class="fas fa-times-circle me-1"></i> Brak miejsc
                                                </span>
                                                            @endif
                                                        @elseif($userRequest->status == 'pending')
                                                            <div class="d-flex flex-column align-items-start gap-2">
                                                <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #d47700 0%, #ffae00 100%);">
                                                    <i class="fas fa-clock me-1"></i> Oczekuje
                                                </span>
                                                                <form action="{{ route('ride-requests.destroy', $userRequest) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="btn btn-sm text-white"
                                                                            style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%); border-radius: 20px; padding: 5px 15px;">
                                                                        <i class="fas fa-times me-1"></i> Anuluj
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif($userRequest->status == 'accepted')
                                                            <div class="d-flex flex-column align-items-start gap-2">
                                                <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #00a01d 0%, #00d429 100%);">
                                                    <i class="fas fa-check-circle me-1"></i> Zaakceptowane
                                                </span>
                                                                <form action="{{ route('ride-requests.destroy', $userRequest) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="btn btn-sm text-white"
                                                                            style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%); border-radius: 20px; padding: 5px 15px;">
                                                                        <i class="fas fa-sign-out-alt me-1"></i> Zrezygnuj
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif($userRequest->status == 'rejected')
                                                            <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%);">
                                                <i class="fas fa-ban me-1"></i> Odrzucone
                                            </span>
                                                        @endif
                                                    @else
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('ride-requests.index', ['ride_id' => $ride->id]) }}"
                                                               class="btn btn-sm text-white"
                                                               style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); border-radius: 20px 0 0 20px; padding: 5px 10px;">
                                                                <i class="fas fa-users me-1"></i>Zgłoszenia
                                                            </a>
                                                            <a href="{{ route('rides.edit', $ride) }}"
                                                               class="btn btn-sm text-white"
                                                               style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%); padding: 5px 10px;">
                                                                <i class="fas fa-edit me-1"></i>Edytuj
                                                            </a>
                                                            <form action="{{ route('rides.destroy', $ride) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="btn btn-sm text-white"
                                                                        style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%); border-radius: 0 20px 20px 0; padding: 5px 10px;"
                                                                        onclick="return confirm('Czy na pewno chcesz usunąć ten przejazd?')">
                                                                    <i class="fas fa-trash-alt me-1"></i>Usuń
                                                                </button>
                                                            </form>
                                                        </div>
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
            <div class="card text-white border-black mb-4" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <div class="card-header">
                    <h4>Akcje</h4>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    @auth
                        @if(Auth::id() === $event->user_id)
                            {{-- Akcje dla organizatora wydarzenia --}}
                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('events.edit', $event) }}"  class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Edytuj wydarzenie</a>
                            </div>
                            <div class="d-grid gap-2 mb-3">
                                <a href="{{ route('events.attendees.index', $event) }}"  class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Zarządzaj uczestnikami</a>
                            </div>
                            @if($event->has_ride_sharing)
                                <div class="d-grid gap-2 mb-3">
                                    <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="btn text-white border-dark" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Dodaj przejazd</a>
                                </div>
                            @endif
                            <div class="d-grid gap-2">
                                <form action="{{ route('events.destroy', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn w-100 btn-danger btn-sm rounded-pill mt-2" onclick="return confirm('Czy na pewno chcesz usunąć to wydarzenie?')">Usuń wydarzenie</button>
                                </form>
                            </div>
                        @else
                            {{-- Akcje dla zwykłego użytkownika --}}
                            @php
                                $attendee = $event->attendees()->where('user_id', Auth::id())->first();
                            @endphp

                            @if(!$attendee)
                                <div class="d-grid gap-2 mb-3">
                                    <a href="{{ route('events.attendees.create', $event) }}" class="btn text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%);">Zapisz się na wydarzenie</a>
                                </div>
                            @else
                                <div class="alert text-white" style="
                                    @if($attendee->status == 'pending') background: linear-gradient(135deg, #d47700 0%, #ffae00 100%);
                                    @elseif($attendee->status == 'accepted') background: linear-gradient(135deg, #00a01d 0%, #00d429 100%);
                                    @elseif($attendee->status == 'rejected') background: linear-gradient(135deg, #d40000 0%, #ff0000 100%);
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
                                        <button type="submit" class="btn text-white" style="background: linear-gradient(135deg, #d40000 0%, #ff0000 100%); border: none;">
                                            @if($attendee->status == 'accepted') Wypisz się
                                            @else Anuluj zgłoszenie
                                            @endif
                                        </button>
                                    </form>
                                </div>

                                @if($attendee->status == 'accepted' && $event->has_ride_sharing)
                                    <div class="card mb-3 text-white" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border: none;">
                                        <div class="card-header" style="background: rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                            <h5>Opcje przejazdu</h5>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $userRide = $event->rides()->where('driver_id', Auth::id())->first();
                                            @endphp

                                            @if(!$userRide)
                                                <div class="mb-3">
                                                    <a href="{{ route('rides.create', ['event_id' => $event->id]) }}" class="btn w-100 text-white" style="background: linear-gradient(135deg, #00a01d 0%, #00d429 100%);">Zaoferuj przejazd</a>
                                                </div>
                                            @else
                                                <div class="alert text-white" style="background: linear-gradient(135deg, #00a01d 0%, #00d429 100%);">
                                                    <p><strong>Oferujesz przejazd!</strong></p>
                                                    <p>Pojazd: {{ $userRide->vehicle_description }}</p>
                                                    <p>Miejsca: {{ $userRide->available_seats }}</p>
                                                    <div class="mt-2">
                                                        <a href="{{ route('ride-requests.index', ['ride_id' => $userRide->id]) }}" class="btn btn-sm text-white" style="background: linear-gradient(135deg, #007ad4 0%, #00a0ff 100%); border: none;">Zarządzaj zgłoszeniami</a>
                                                        <a href="{{ route('rides.edit', $userRide) }}" class="btn btn-sm text-white" style="background: linear-gradient(135deg, #5a00a0 0%, #7f00d4 100%); border: none;">Edytuj</a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endif
                    @else
                    @endauth
                </div>
            </div>

            <div class="card text-white border-black" style="background: linear-gradient(45deg, #5a4e82 0%, #3a6b8a 100%);">
                <div class="card-header">
                    <h4>Organizator</h4>
                </div>
                <div class="card-body" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
                    <div class="d-flex align-items-center">
                        @if($event->user->photo_path)
                            <img src="{{ asset('storage/' . $event->user->photo_path) }}"
                                 class="rounded-circle me-3" alt="Zdjęcie profilowe" width="80" height="80">
                        @else
                            <img src="{{ asset('images/includes/default_avatar.png') }}"
                                 class="rounded-circle me-3" alt="Zdjęcie profilowe" width="80" height="80">
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $event->user->name }}</h5>
                            @if($event->user->description)
                                <p class="mb-0 small text-white">{{ $event->user->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

@include('includes.footer')
</body>
</html>
